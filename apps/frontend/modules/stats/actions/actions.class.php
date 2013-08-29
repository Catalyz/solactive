<?php

/**
 * stats actions.
 *
 * @package tracking.sol-violette.info
 * @subpackage stats
 * @author Your name here
 * @version SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class statsActions extends sfActions {
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
	public function executeIndex(sfWebRequest $request)
	{
		$this->form = new statistiquesForm();
		if ($request->isMethod('post')) {
			$this->form->bind($request->getParameter($this->form->getName()));
			if ($this->form->isValid()) {
				$values = $this->form->getValues();
				$this->to = $values['reference_date'];
				$this->from = $values['date'];
				$this->actor = $values['actor'];
				$this->datas = Application::computeStats($values['date'], $values['reference_date'], $this->actor);
				$this->mapDetails = Application::getMapValues($this->datas['datas'], $values['type']);
				$this->stats_type = $values['type'];
			}
		}
		return sfView::SUCCESS;
	}

	public function executeDetails(sfWebRequest $request)
	{
		$this->forward404Unless($this->actor = CoreConnector::getUserInfo($request->getParameter('actor')));

		$result = Application::getTicketLocation(date('Y-m-d'), $this->actor['id']);
		$tickets = array_pop($result);
		$query = Doctrine_Query::create()->from('Ticket t')->whereIn('id', $tickets);
		$this->data = $query->fetchArray();
		$this->sum = 0;
		$this->count = 0;
		foreach($this->data as $ticketInfo){
			$this->sum+=$ticketInfo['amount'];
			$this->count++;
		}
		return sfView::SUCCESS;
	}

	public function executeDetailsCoupon(sfWebRequest $request)
	{
		$this->forward404Unless($this->ticket = TicketTable::getInstance()->findOneByBubbleTag($request->getParameter('bubble_tag')));
		$q =/*(Doctrine_Query)*/ Doctrine_Query::create()->from('TicketTrackingEntry e')->leftJoin('e.TicketTracking tt');
		$q->where('ticket_id = ?', $this->ticket->id)->addOrderBy('e.created_at DESC');
		$this->data = $q->execute(array(), Doctrine_Core::HYDRATE_ARRAY);

		return sfView::SUCCESS;
	}

	public function executeCsv(sfWebRequest $request)
    {
        $datas = Application::computeStats($request->getParameter('date'), $request->getParameter('ref'), $request->getParameter('actor'));

    	$userInfos = CoreConnector::getAllUsersInfos();

        $return = array();
        foreach ($datas['datas'] as $actorId => $details) {
            $return[$actorId]['Acteur'] = $userInfos[$actorId]['name'];
            $return[$actorId]['Coupons'] = $details['nombre'];
            $return[$actorId]['Montant'] = $details['transaction-value'];
            if (isset($details['transaction-count'])) {
                $return[$actorId]['Transactions'] = $details['transaction-count'];
            }
            if (isset($details['average-transaction-value'])) {
                $return[$actorId]['Montant moyen des transactions'] = $details['average-transaction-value'];
            }
        }

        $csv = Application::createCSV($return);
        $this->getUser()->setFlash('csv', $csv);

        $title = sprintf('statistiques_%s', date('Ymd', strtotime($request->getParameter('date'))));
        if ($request->getParameter('ref')) {
            $title .= sprintf('_%s', date('Ymd', $request->getParameter('ref')));
        }
        $this->getUser()->setFlash('title', $title);
        $this->redirect('@exports_csv');
    }
}