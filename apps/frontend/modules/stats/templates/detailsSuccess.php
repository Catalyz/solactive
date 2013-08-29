<?php slot('title') ?>
	<h1>Statistiques - <?php echo Application::keepPrivacy($actor['role'])?'Anonyme':$actor['name']; ?></h1>
<?php end_slot() ?>

<?php printf('<p>Ce membre possède %d coupons pour un montant total de %d %s dont les références et les montants sont présentés ci-dessous:</p>', $count, $sum, ($sum>1)?sfConfig::get('app_project_currency_plural'):sfConfig::get('app_project_currency_single')); ?>

<table id="product-table" cellpadding="3" cellspacing="3" border="0">
<thead>
	<th class="table-header-check-long">Référence</th>
	<th class="table-header-options line-left">Montant</th>
</thead>
<tbody>


<?php

//var_dump($data->getRawValue());
foreach($data->getRawValue() as $ticket){
	printf('<tr><td>%s [<a href="%s">?</a>]</td><td>%d %s</td></tr>', $ticket['bubble_tag'],
			url_for('@stats_details_coupon?bubble_tag='.$ticket['bubble_tag']), $ticket['amount'], ($ticket['amount']>1)?sfConfig::get('app_project_currency_plural'):sfConfig::get('app_project_currency_single'));
}

?>

</tbody>
</table>
