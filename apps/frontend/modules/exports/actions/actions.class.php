<?php

/**
 * exports actions.
 *
 * @package tracking.sol-violette.info
 * @subpackage exports
 * @author Your name here
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class exportsActions extends sfActions {
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request)
    {
        // sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url'));
        // $this->redirectUnless($this->getUser()->hasCredential('exports'), url_for('@login'));
        $this->form = new ticketDetailsForm();
        $this->form2 = new exportForm();
        $this->datas = array();
        if ($request->isMethod('post')) {
            //region coupons
            if ($request->hasParameter('coupons')) {
                $this->form->bind($request->getParameter($this->form->getName()));
                if ($this->form->isValid()) {
                    $values = $this->form->getValues();
                    $date = $values['date'];
                    // $ids =/*(Doctrine_Query)*/ Doctrine_Query::create()->select('MAX(id),e.ticket_id')->from('TicketTrackingEntry e')->leftJoin('e.TicketTracking tt')->where("date_format(e.created_at, '%Y-%m-%d') = ?", $date)->groupBy('e.ticket_id')->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

                    // $inIds = array();
                    // foreach ($ids as $details) {
                    // $inIds[] = $details['MAX'];
                    // }

                    // $lasts =/*(Doctrine_Query)*/ Doctrine_Query::create()->select('MAX(id),e.ticket_id,e.created_at')->from('TicketTrackingEntry e')->where("date_format(e.created_at, '%Y-%m-%d') = ?", $date)->whereNotIn('e.id', $inIds)->groupBy('e.ticket_id')->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

                    // $lastSaisie = array();
                    // foreach ($lasts as $last) {
                    // $lastSaisie[$last['ticket_id']] = $last['created_at'];
                    // }

                    // $q2 =/*(Doctrine_Query)*/ Doctrine_Query::create()->from('TicketTrackingEntry e')->leftJoin('e.Ticket t')->leftJoin('e.TicketTracking tt')->whereIn('e.id', $inIds)->andWhere("date_format(e.created_at, '%Y-%m-%d') = ?", $date) ;

                    // if (!empty($values['actor'])) { // un acteur en particulier
                    // $q2->andWhere('tt.actor_id = ?', $values['actor']['id']);
                    // }
                    // $q2->addOrderBy('e.created_at DESC');

                    // $rr = $q2->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
                    if (!empty($values['actor'])) { // un acteur en particulier
                        $data = Application::getTicketStats($date, $values['actor']['id']);
                    } else {
                        $data = Application::getTicketStats($date);
                    }
                    // return sfView::SUCCESS;
                    // var_dump($data);exit;
                    $this->getUser()->setFlash('csv', Application::createCSV($data));
                    $this->getUser()->setFlash('title', 'etat-des-coupons_' . date('Ymd', strtotime($date)));
                    $this->redirect('@exports_csv');
                }
            }
            //endregion
            //region transactions
            elseif ($request->hasParameter('transactions')) {
                $this->form2->bind($request->getParameter($this->form2->getName()));
                if ($this->form2->isValid()) {
                    $values = $this->form2->getValues();
                    $from = $values['from'];
                    $to = $values['to'];

                    $query =/*(Doctrine_Query)*/ Doctrine_Query::create()->from('TicketTrackingEntry e')->leftJoin('e.Ticket t')->leftJoin('e.TicketTracking tt')->where("date_format(e.created_at, '%Y-%m-%d') >= ? AND date_format(e.created_at, '%Y-%m-%d') <= ?", array($from, $to))->addOrderBy('e.created_at DESC');

                    if ($values['operator']) {
                        $query->andWhereIn("tt.operator_id", $values['operator']['id']);
                    }
                    if ($values['actor']) {
                        $query->andWhereIn("tt.actor_id", $values['actor']['id']);
                    }

                    $results = $query->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
                    $userInfos = CoreConnector::getAllUsersInfos();

                    $return = array();
                    foreach ($results as $key => $TicketTrackingEntry) {
                        $Ticket = $TicketTrackingEntry['Ticket'];
                        $return[$key][utf8_decode('date de création du coupon')] = date('d/m/Y', strtotime($Ticket['created_at']));
                        $return[$key][utf8_decode('code à bulle')] = $Ticket['bubble_tag'];
                        $return[$key][utf8_decode('valeur du coupon')] = $Ticket['amount'];

                        $operator = $userInfos[$TicketTrackingEntry['TicketTracking']['operator_id']];
                        $actor = $userInfos[$TicketTrackingEntry['TicketTracking']['actor_id']];

                        $return[$key][utf8_decode('acteur ayant effectué la saisie (tel)')] = $operator['phone'];
                        $return[$key][utf8_decode('acteur ayant effectué la saisie (nom)')] = $operator['name'];
                        $return[$key][utf8_decode('acteur chez qui est le coupon (tel)')] = $actor['phone'];
                        $return[$key][utf8_decode('acteur chez qui est le coupon (nom)')] = $actor['name'];
                        $return[$key][utf8_decode('date de transaction')] = date('d/m/Y', strtotime($TicketTrackingEntry['created_at']));
                        switch ($TicketTrackingEntry['status']) {
                            case TicketTrackingEntry::STATUS_UPDATED :
                                $state = utf8_decode('Mis à jour');
                                break;
                            case TicketTrackingEntry::STATUS_CONFIRMED:
                                $state = utf8_decode('Confirmé');
                                break;
                            case TicketTrackingEntry::STATUS_NEW:
                                $state = utf8_decode('Mis en circulation');
                                break;
                            default:// STATUS_EXPIRED
                                $state = utf8_decode('Expiré');
                        } // switch
                        $return[$key][utf8_decode('état de la transaction')] = $state;
                    }

                    $csv = Application::createCSV($return);
                    $this->getUser()->setFlash('csv', $csv);
                    $title = sprintf('etat-des-transactions_%s_%s', date('Ymd', strtotime($from)), date('Ymd', strtotime($to)));
                    $this->getUser()->setFlash('title', $title);
                    $this->redirect('@exports_csv');
                }
            }
            //endregion
        }

        return sfView::SUCCESS;
    }

    public function executeExport(sfWebRequest $request)
    {
        $csv = $this->getUser()->getFlash('csv');
        $title = $this->getUser()->getFlash('title');

        $response = $this->getResponse();
        $response->setContentType('text/csv');
        $response->setHttpHeader('Content-disposition', 'attachment; filename="' . $title . '.csv"');
        $response->setHttpHeader('Content-Length', strlen($csv));
        $response->setHttpHeader('Pragma', 'public');
        $response->setHttpHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0');
        $response->setHttpHeader('Expires', '0');
        $response->setContent($csv);
        return sfView::NONE;
    }
}