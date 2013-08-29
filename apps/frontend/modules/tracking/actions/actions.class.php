<?php

/**
 * tracking actions.
 *
 * @package tracking.sol-violette.info
 * @subpackage tracking
 * @author Your name here
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class trackingActions extends sfActions {
    public function executeIndex(sfWebRequest $request)
    {
        $this->form = new trackingActorForm();
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter($this->form->getName()));
            if ($this->form->isValid()) {
                $values = $this->form->getValues();
                $this->getUser()->setAttribute('actor', $values['login']);
                $this->redirect('@tracking_record');
            } else {
                $this->form->getWidget('login')->setAttribute('class', 'inp-form-error');
            }
        }
    }
    public function executeRecord(sfWebRequest $request)
    {
        $user =/*(myUser)*/ $this->getUser();

        $this->TicketTracking = new TicketTracking();
        $this->TicketTracking->setOperatorId($user->getAttribute('operator.id'));
        $actor = $user->getAttribute('actor');
        $this->TicketTracking->setActorId($actor['id']);
        $this->TicketTracking->save();

        $this->Actor = $user->getAttribute('actor');

		$user->setAttribute('datas', array());

        // $this->Actor->name=$user->getAttribute('actor')->name;
    }
    public function executeReset(sfWebRequest $request)
    {
        $user =/*(myUser)*/ $this->getUser();

        $this->TicketTracking = new TicketTracking();
        $this->TicketTracking->setOperatorId($user->getAttribute('operator.id'));
        $this->TicketTracking->setActorId(0);
        $this->TicketTracking->save();
    }
    public function executeAjax(sfWebRequest $request)
    {
        $user =/*(myUser)*/ $this->getUser();
        $ticket = /*(Ticket)*/Doctrine::getTable('ticket')->findOneByBubbleTag($request->getParameter('code'));

    	$stats = $user->getAttribute('scan_stats', array());
    	if(!isset($stats[$ticket->getAmount()])){
    		$stats[$ticket->getAmount()] = array();
		}
    	if(isset($stats[$ticket->getAmount()][$ticket->getBubbleTag()])){
    		$result = array(
    			'code' => $ticket->getBubbleTag(),
    			'status' => 'IGNORE',
    			'message' => 'Ce coupon a déjà été scanné',
			'overview' => $this->getPartial('tracking/overview', array('datas' => $user->getAttribute('datas'))));
    	}else{
    		$stats[$ticket->getAmount()][$ticket->getBubbleTag()] = true;
    		$user->setAttribute('scan_stats', $stats);


    		$TicketTrackingEntry = new TicketTrackingEntry();

    		$result = array();
    		if ($ticket) {
    			if ('reset' == $request->getParameter('mode') && $user->hasCredential('tracking_advanced')) {
    				$result = TicketTrackingEntryTable::getInstance()->createTicketTrackingEntryAsReset($request->getParameter('session'), $ticket);
    			} else {
    				$actor = $user->getAttribute('actor');
    				$result = TicketTrackingEntryTable::getInstance()->createTicketTrackingEntry($request->getParameter('session'), $ticket, $actor['id']);
    			}
    			$result['amount'] = $ticket->amount;
    		}else{
    			$result['amount'] = 0;

    		}
    		$result['code'] = $request->getParameter('code');
    		$result['stats'] = $stats;

    		$datas = $user->getAttribute('datas');
    		if(!isset($datas[(int)$ticket->amount])){
    			foreach(array('delivered', 'updated', 'confirmed', 'expired') as $status){
    				$datas[(int)$ticket->amount][$status] = 0;
    			}
    		}
    		switch ($result['status']) {
    			case 'UPDATED':
    				$datas[(int)$result['amount']]['updated']++;
    				break;
    			case 'CONFIRMED':
    				$datas[(int)$result['amount']]['confirmed']++;
    				break;
    			case 'UNKNOWN':
    				$datas[(int)$result['amount']]['delivered']++;
    				break;
    			case 'EXPIRED':
    			$datas[(int)$result['amount']]['expired']++;
    			break;
    			case 'ERROR':
    			case 'IGNORE':
    			//$datas[(int)$result['amount']]['confirmed']++;
    			break;

    			default:
    			;
    		} // switch
    		$result['overview'] = $this->getPartial('tracking/overview', array('datas' => $datas));
    		$user->setAttribute('datas', $datas);
    	}


        $this->setLayout(null);
        $this->setTemplate(false);
        $this->getResponse()->setContent(json_encode($result));
        sfConfig::set('sf_web_debug', false);
        return sfView::NONE;
    }
}
