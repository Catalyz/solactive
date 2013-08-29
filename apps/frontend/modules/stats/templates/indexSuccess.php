<?php use_javascripts_for_form($form) ?>
<?php use_stylesheet('/css/jquery-ui-1.8.11.custom.css', 'last') ?>

<?php slot('title') ?>
	<h1>Statistiques</h1>
<?php end_slot() ?>

<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<div id="table-content">
				<form class="statsForm" method="post" action="<?php echo url_for('@stats')?>" >
					<table>
						<tr>
							<th width="200"><?php echo $form['actor']->renderLabel() ?></th>
							<td><?php echo $form['actor'] ?></td>
							<td>
								<?php if ($form['actor']->renderError()) {
    printf('<div class="error-left"></div><div class="error-inner">%s</div>', $form['actor']->renderError());
} else {
    echo '&nbsp;';
}

?>
							</td>
						</tr>
						<tr>
							<th><?php echo $form['type']->renderLabel() ?></th>
							<td><?php echo $form['type'] ?></td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th>Afficher les statistiques du</th>
							<td><?php echo $form['date'] ?></td>
							<td>
								<?php if ($form['date']->renderError()) {
    printf('<div class="error-left"></div><div class="error-inner">%s</div>', $form['date']->renderError());
} else {
    echo '&nbsp;';
}

?>
							</td>
						</tr>
						<tr>
							<th>Comparer avec les statistiques du</th>
							<td><?php echo $form['reference_date'] ?><br /><small>Cette date n'est utilisée que pour calculer le nombre de transactions ou leur montant moyen</small></td>
							<td>
								<?php if ($form['reference_date']->renderError()) {
    printf('<div class="error-left"></div><div class="error-inner">%s</div>', $form['reference_date']->renderError());
} else {
    echo '&nbsp;';
}

?>
							</td>
						</tr>
					</table>
					<?php echo $form->renderHiddenFields() ?>
					<input type="submit" value="" class="form-submit" />
				</form>


				<?php if (isset($datas['datas']) && count($datas['datas']) > 0) : ?>
				<div class="clear"></div>
					<a name="map"></a>
					<div id="map_canvas" class="map" style="margin:10px 0;height:600px; width:900px;"></div>

					<div class="clear"></div>
					<table id="product-table" class="tablesorter" cellpadding="3" cellspacing="3" border="0" width="100%">
					<thead>
						<tr>
							<th><span>Acteur</span></th>
							<th><span>Stock</span></th>

							<?php if ($to != null) {
        printf('<th><span>Montant</span></th>
							<th><span>Transactions</span></th>
							<th><span>%% de transactions</span></th>
							<th><span>Montant moyen des transactions</span></th>');
    } else {
        echo '<th><span>Montant</span></th>';
    }

    ?>
						</tr>
						</thead>
						<?php
    $cpt = 1;

    $allInfos = CoreConnector::getAllUsersInfos();

    foreach ($datas['datas'] as $actorId => $data) {



        if ($actorId && (!in_array($stats_type, array('transaction-count', 'average-transaction-value')) || $data['transaction-value']>0)) {


			printf('<tr %s>', $cpt % 2 == 0?'class="alternate-row"':'');

            if (isset($allInfos[$actorId])) {
                $link_details = sprintf(' [<a href="%s">?</a>]', url_for('@stats_details_actor?actor=' . $allInfos[$actorId]['phone']));
            } else {
                $link_details = '';
            }


        	$actorName = Application::getActorName($allInfos[$actorId], $actorId);

            printf('<td><a id="click_%1$s" href="#map" onclick="map.setCenter(myLatlng%1$s); infowindow%1$s.open(map, marker%1$s);">%2$s</a>%3$s</td>', $actorId, $actorName, $link_details);
            printf('<td>%s</td>', $data['nombre']);
            printf('<td align="right">%s sol%s  &nbsp;</td>', $data['transaction-value'], ($data['transaction-value'] > 1)?'s':'');
            if ($to != null) {
                $totalTransac = $datas['totalTransaction'];
                $percent = ($data['transaction-count'] * 100) / $totalTransac;
                printf('<td>%s</td>', $data['transaction-count']);
                printf('<td>%s%%</td>', number_format($percent, 0, ',', ' '));
                printf('<td align="right">%s sols &nbsp;</td>', $data['average-transaction-value']);
            }
            echo '</tr>';
            $cpt++;
        }
    }

    ?>
					</table>


				<?php include_partial('gmap', array('mapDetails' => $mapDetails, 'datas' => $datas, 'stats_type'=> $stats_type)) ?>
				<?php endif ?>

			</div>

		</td>
		<td>

		<?php if (isset($datas) && count($datas) > 0) : ?>
		<!--  start related-activities -->
		<div id="related-activities">

			<!--  start related-act-top -->
			<div id="related-act-top">
			<?php echo image_tag('forms/header_related_act_fr.gif', array('alt' => '')) ?>
			</div>
			<!-- end related-act-top -->

			<!--  start related-act-bottom -->
			<div id="related-act-bottom">

				<!--  start related-act-inner -->
				<div id="related-act-inner">

					<div class="left"><?php echo image_tag('forms/icon_edit.gif', array('alt' => '')) ?></div>
					<div class="right">
						<h5>Exporter les résultats</h5>
						Vous pouvez exporter au format csv les résultats.
						<ul class="greyarrow">
							<li><?php

        $params = sprintf('date=%s', $from);
    if ($to != null) {
        $params .= '&ref=' . $to;
    }
    if ($actor != null) {
        $params .= '&actor=' . $actor;
    }

    echo link_to('Télécharger les données du tableau de résultat', sprintf('@stats_csv?%s', $params)) ?></li>
						</ul>
					</div>

					<div class="clear"></div>


				</div>
				<!-- end related-act-inner -->
				<div class="clear"></div>

			</div>
			<!-- end related-act-bottom -->

		</div>
		<!-- end related-activities -->


		<?php endif ?>
		</td>
	</tr>
</table>

<?php use_javascript('/js/jquery/jquery.tablesorter.min.js', 'last') ?>
<script type="text/javascript" id="js">
	$(document).ready(function() {
		$(".tablesorter").tablesorter();
	});
</script>

