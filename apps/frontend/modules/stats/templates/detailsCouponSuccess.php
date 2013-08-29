<?php slot('title') ?>
	<h1>Historique du coupon <?php echo $ticket->bubble_tag ?></h1>
<?php end_slot() ?>


<table id="product-table" cellpadding="3" cellspacing="3" border="0">
<thead>
	<th class="table-header-check-long">Date</th>
	<th class="table-header-repeat line-left">Transaction</th>
	<th class="table-header-options line-left">Adhérent</th>
</thead>
<tbody>


<?php

$usersInfos = CoreConnector::getAllUsersInfos();
foreach($data->getRawValue() as $info){

	switch($info['status']){
		case TicketTrackingEntry::STATUS_UPDATED:
			$transaction = 'Mise à jour';
			break;
		case TicketTrackingEntry::STATUS_CONFIRMED:
			$transaction = 'Confirmation';
			break;
		case TicketTrackingEntry::STATUS_NEW:
			$transaction = 'Mise en circulation';
			break;
		case TicketTrackingEntry::STATUS_EXPIRED:
			$transaction = 'Expiration';
			break;
	}

	$userName = Application::getActorName($usersInfos[$info['TicketTracking']['actor_id']], $info['TicketTracking']['actor_id']);

	printf('<tr><td>%s</td><td>%s</td><td>%s [<a href="%s">?</a>]</td></tr>', date('d/m/Y H:i', strtotime($info['created_at'])),
		$transaction,
		$userName,
		url_for('@stats_details_actor?actor='.$usersInfos[$info['TicketTracking']['actor_id']]['phone']));
}

?>

</tbody>
</table>
