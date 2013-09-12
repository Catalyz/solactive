<?php

class Application {
    static public function getTicketStats($when = null, $restrictToActorId = null)
    {
        if (null == $when) {
            $when = date('Y-m-d');
        }

        $file_cache = new sfFileCache(array('cache_dir' => sfConfig::get('sf_cache_dir') . '/Application_getTicketStats'));
        $key = sprintf('%s_%s', $when, $restrictToActorId);
        if ($file_cache->has($key)) {
            $cached = $file_cache->get($key);
            if (!empty($cached)) {
                $result = unserialize($cached);
            }
        }
        $result = null;
        if (empty($result)) {
            $timer = sfTimerManager::getTimer('Application::getTicketStats');

            $q =/*(Doctrine_Query)*/ Doctrine_Query::create()->from('TicketTrackingEntry e')->leftJoin('e.TicketTracking tt')->leftJoin('e.Ticket t');
            $q->where("date_format(e.created_at, '%Y-%m-%d') <= ?", $when);

            $q->addOrderBy('e.created_at DESC');
            // var_dump($q->__toString());
            $results = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

            $result = array();
            $temp = array();

            $allUsers = CoreConnector::getAllUsersInfos();
        	$allUsers[0] = array('name' => 'Inconnu', 'phone' => '');

            foreach ($results as $res) {
                if (empty($temp[$res['ticket_id']])) {
                	$temp[$res['ticket_id']] = true;

                    $Ticket = $res['Ticket'];
                    $Actor = $allUsers[$res['TicketTracking']['actor_id']];

                    if (!$restrictToActorId || ($restrictToActorId == $res['TicketTracking']['actor_id'])) {
                        $item = array();

                        $item['date de creation du coupon'] = date('d/m/Y', strtotime($Ticket['created_at']));
                        $item['code a bulle'] = $Ticket['bubble_tag'];
                        $item['valeur du coupon'] = (int)$Ticket['amount'];
                       // $item['date d\'echeance'] = date('d/m/Y', strtotime($Ticket['expire_at']));
                        $item['date de derniere saisie'] = date('d/m/Y', strtotime($res['created_at']));
                    	$item['adhérent chez qui il est (nom)'] = $Actor['name'];
                    	$item['adhérent chez qui il est (tel)'] = $Actor['phone'];
                        if ($Ticket['status'] == Ticket::STATUS_DISABLED) {
                            $item['etat du coupon'] = 'En banque';
                        } else {
                            $item['etat du coupon'] = 'En circulation';
                        }
                        $result[] = $item;

                    }
                }
            }
            unset($temp);

            $timer->addTime();

            $file_cache->set($key, serialize($result));
        }

        return $result;
    }

    static public function getTicketLocation($when, $restrictToActorId = null)
    {
        $file_cache = new sfFileCache(array('cache_dir' => sfConfig::get('sf_cache_dir') . '/Application_getTicketLocation'));
        $key = sprintf('%s_%s', $when, $restrictToActorId);
        if ($file_cache->has($key)) {
            $cached = $file_cache->get($key);
            if (!empty($cached)) {
                $result = unserialize($cached);
            }
        }
        if (empty($result)) {
            $timer = sfTimerManager::getTimer('Application::getTicketLocation');
            $q =/*(Doctrine_Query)*/ Doctrine_Query::create()->from('TicketTrackingEntry e')->leftJoin('e.TicketTracking tt');
            $q->where("date_format(e.created_at, '%Y-%m-%d') <= ? ", $when)->addOrderBy('e.created_at DESC');
            $results = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

            $result = array();
            $temp = array();

            foreach ($results as $res) {
                if (empty($temp[$res['ticket_id']])) {
                    $temp[$res['ticket_id']] = strtotime($res['created_at']);

                    $actorId = $res['TicketTracking']['actor_id'];
                    $result[$actorId][$res['ticket_id']] = $res['ticket_id'];
                }
            }
            unset($temp);

            if ($restrictToActorId) {
                $result = array($restrictToActorId => $result[$restrictToActorId]);
            }
            $timer->addTime();

            $file_cache->set($key, serialize($result));
        }

        return $result;
    }

    static public function computeStats($from, $to , $restrictToActorId)
    {
        $dataFrom = Application::getTicketLocation($from, $restrictToActorId);
        $totalTransaction = 0;
        $result = array();
        $map = array();

        $timer = sfTimerManager::getTimer('Application::computeStats#1');
        if ($to != null) {
            $dataTo = Application::getTicketLocation($to, $restrictToActorId);
            if (!empty($dataTo)) {
                foreach($dataFrom as $actorId => $ticketDetails) {
                    foreach ($ticketDetails as $ticketId) {
                        if (!empty($dataTo[$actorId][$ticketId])) {
                            // chez le même acteur
                            $map[$actorId]['still-here'][] = $ticketId;
                        } else {
                            // acteur différent
                            $map[$actorId]['lost'][] = $ticketId;
                        }
                    }
                }

                foreach($dataTo as $actorId => $ticketDetails) {
                    foreach ($ticketDetails as $ticketId) {
                        if (empty($dataFrom[$actorId][$ticketId])) {
                            $map[$actorId]['gained'][] = $ticketId;
                        }
                    }
                }
            }
        } else {
            foreach ($dataFrom as $actorId => $ticketDetails) {
                $map[$actorId]['gained'] = $ticketDetails;
            }
        }
        $timer->addTime();

        $timer = sfTimerManager::getTimer('Application::computeStats#2');
        foreach ($map as $actorId => $data) {
            foreach ($data as $details) {
                if (empty($map[$actorId]['count'])) {
                    $map[$actorId]['count'] = 0;
                }
                $map[$actorId]['count'] += count($details);
            }
        }
        $timer->addTime();
        // var_dump($map[311]);exit;
        $timer = sfTimerManager::getTimer('Application::computeStats#3');
        foreach($map as $actorId => $data) {
            if (!isset($result[$actorId])) {
                $result[$actorId] = array(
                    'transaction-count' => 0);
            }
            if ($to != null && isset($dataTo)) {
                $gained = isset($data['gained'])?count($data['gained']):0;
                $lost = isset($data['lost'])?count($data['lost']):0;

                $result[$actorId]['transaction-count'] = $lost + $gained;
                $totalTransaction += $result[$actorId]['transaction-count'];
            }
            $result[$actorId]['nombre'] = $data['count'];
            $result[$actorId]['transaction-count'] = $result[$actorId]['transaction-count'];

            $result[$actorId]['transaction-value'] = 0;
            if (!empty($data['lost'])) {
                foreach($data['lost'] as $ticketId) {
                    $result[$actorId]['transaction-value'] += self::getTicketValue($ticketId);
                }
            }
            if (!empty($data['gained'])) {
                foreach($data['gained'] as $ticketId) {
                    $result[$actorId]['transaction-value'] += self::getTicketValue($ticketId);
                }
            }


            if ($to != null && isset($dataTo)) {
                $average = 0;
                if (isset($result[$actorId]['transaction-value']) && !empty($result[$actorId]['transaction-count'])) {
                    $average = $result[$actorId]['transaction-value'] / $result[$actorId]['transaction-count'] ;
                }
                $result[$actorId]['average-transaction-value'] = number_format($average, 2, ',', ' ');
            }
        	//$result[$actorId]['transaction-value'] = number_format($result[$actorId]['transaction-value'], 0, ',', ' ');
        }
        $timer->addTime();

        return array('datas' => $result, 'totalTransaction' => $totalTransaction);
    }

    static public function getTicketValue($ticketId)
    {
        static $values = null;

        if (null == $values) {
            $timer = sfTimerManager::getTimer('Application::getTicketValue');
            $values = array();

            $query = Doctrine_Query::create()->select('t.id, t.amount')->from('Ticket t');
            foreach($query->execute(null, Doctrine::HYDRATE_NONE) as $row) {
                $values[(int)$row[0]] = (float)$row[1];
            }

            $timer->addTime();
        }

        return $values[$ticketId];
    }

    static public function createCSV($arrays)
    {
        $string = '';
        $c = 0;
        foreach($arrays AS $array) {
            $val_array = array();
            $key_array = array();
            foreach($array AS $key => $val) {
                $key_array[] = $key;
                $val = str_replace('"', '""', $val);
                $val_array[] = "\"$val\"";
            }
            if ($c == 0) {
                $string .= implode(",", $key_array) . "\n";
            }
            $string .= implode(",", $val_array) . "\n";
            $c++;
        }
        return $string;
    }

    static public function getActorCoord($actorId)
    {
        static $coords = null;

        if (null == $coords) {
            // $cache = new sfFileCache(array('cache_dir' => sfConfig::get('sf_cache_dir')));
            // $coords = unserialize($cache->get('actors_coord'));
            if (!$coords) {
                $coords = CoreConnector::getCoordsForAllActors();
                // $cache->set('actors_coord', serialize($coords));
            }
        }

        return isset($coords[$actorId])?$coords[$actorId]:null;
    }

    public static function getMapValues($datas, $type)
    {
        $return = array();
        if (empty($datas)) {
            return $return;
        }

        foreach ($datas as $id => $details) {
            if (isset($details[$type])) {
                $return[$id] = $details[$type];
            }
        }

        if (empty($return)) {
            return $return;
        }

        arsort($return);
        $max = max($return);

        if ($max != 0) {
            foreach ($return as $key => $value) {
                $newVal = ($value * 100) / $max;
                $return[$key] = $newVal;
            }
        }

        return $return;
    }

    public static function keepPrivacy($role)
    {
        return !in_array($role, array('admin', 'delegate', 'referer'));
    }

    public static function getActorName($actorInfos, $actorId)
    {
        if ($actorInfos) {
            if (self::keepPrivacy($actorInfos['role'])) {
                $actorName = 'Anonyme';
            } else {
                $actorName = $actorInfos['name'];
            }
        } else {
            $actorName = sprintf('Inconnu (id: %d)', $actorId);
        }
        //$actorName .= sprintf('<small>%d</small>', $actorId);

        return $actorName;
    }
}

?>
