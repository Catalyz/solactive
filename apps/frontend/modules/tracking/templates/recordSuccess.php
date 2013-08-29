<?php slot('title') ?>
<h1>Traçabilité des coupons - <?php echo $Actor['name']; ?></h1>
<?php end_slot() ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<!--  start step-holder -->
			<div id="step-holder">
				<div class="step-no-off">1</div>
				<div class="step-light-left"><a href="<?php echo url_for('@tracking_index') ?>">Choix de l'adhérent</a></div>
				<div class="step-light-right">&nbsp;</div>
				<div class="step-no">2</div>
				<div class="step-dark-left">Saisie des coupons</div>
				<div class="step-dark-right">&nbsp;</div>
				<div class="step-no">3</div>
				<div class="step-light-left">Bon de saisie</div>
				<div class="step-light-round">&nbsp;</div>
				<div class="clear"></div>
			</div>
			<!--  end step-holder -->

			<div id="table-content">

			<div id="overview"></div>

				<table cellpadding="3" cellspacing="3">
				<tr>
					<td>Référence du coupon</td>
					<td>&nbsp;</td>
					<td>
						<form onsubmit="return handleScan();" action="#"><input type="text" name="code" id="scanCode" /></form>
					</td>
					<td>&nbsp;</td>
					<td><?php echo sfConfig::get('app_project_currency_plural') ?> scann&eacute;s: <span id="sum">0</span> <?php echo sfConfig::get('app_project_currency_plural') ?></td>
				</tr>


				</table>

				<div id="log" style="padding-top: 10px;"></div>


			</div>
		</td>
		<td>

		<!--  start related-activities -->
		<div id="related-activities">

			<!--  start related-act-top -->
			<div id="related-act-top">
			<?php echo image_tag('forms/header_related_act_fr.gif',array('alt'=>'')) ?>
			</div>
			<!-- end related-act-top -->

			<!--  start related-act-bottom -->
			<div id="related-act-bottom">

				<!--  start related-act-inner -->
				<div id="related-act-inner">

					<div class="left"><?php echo image_tag('forms/icon_edit.gif',array('alt'=>'')) ?></div>
					<div class="right">
						<h5>Effectuer une autre saisie</h5>
						Une fois la saisie terminée pour cet adhérent, vous pouvez effectuer une nouvelle saisie.
						<ul class="greyarrow">
							<li><?php echo link_to('Saisir pour un autre adhérent', '@tracking_index') ?></li>
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

	</td>
	</tr>
</table>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#scanCode').focus();
	});
	//<![CDATA[
	function handleScan(){
			$.ajax({
				success: function(data, textStatus, jqXHR){
					if('UPDATED' == data.status){
						$('#log').prepend('<div class="message-green">'
						+'<table border="0" width="100%" cellpadding="0" cellspacing="0">'
						+'<tr>'
						+'	<td class="green-left"><b>' + data.code + '</'+'b> ' + data.message + '</'+'td>'
						+'	<td class="green-right"><a class="close-green"><?php echo image_tag('table/icon_close_green.gif'); ?></'+'a></'+'td>'
						+'</'+'tr>'
						+'</'+'table>'
						+'</'+'div>');
						$('#sum').html(parseInt($('#sum').html()) + parseInt(data.amount));
					}else if('CONFIRMED' == data.status){
						$('#log').prepend('<div class="message-blue">'
						+'<table border="0" width="100%" cellpadding="0" cellspacing="0">'
						+'<tr>'
						+'	<td class="blue-left"><b>' + data.code + '</'+'b> ' + data.message + '</'+'td>'
						+'	<td class="blue-right"><a class="close-blue"><?php echo image_tag('table/icon_close_blue.gif'); ?></'+'a></'+'td>'
						+'</'+'tr>'
						+'</'+'table>'
						+'</'+'div>');
					}else if('UNKNOWN' == data.status){
						$('#log').prepend('<div class="message-yellow">'
						+'<table border="0" width="100%" cellpadding="0" cellspacing="0">'
						+'<tr>'
						+'	<td class="yellow-left"><b>' + data.code + '</'+'b> ' + data.message + '</'+'td>'
						+'	<td class="yellow-right"><a class="close-yellow"><?php echo image_tag('table/icon_close_yellow.gif'); ?></'+'a></'+'td>'
						+'</'+'tr>'
						+'</'+'table>'
						+'</'+'div>');
						$('#sum').html(parseInt($('#sum').html()) + parseInt(data.amount));
					}else if('EXPIRED' == data.status){
						$('#log').prepend('<div class="message-yellow">'
						+'<table border="0" width="100%" cellpadding="0" cellspacing="0">'
						+'<tr>'
						+'	<td class="yellow-left" style="color: #FF0000"><b>' + data.code + '</'+'b> ' + data.message + '</'+'td>'
						+'	<td class="yellow-right"><a class="close-yellow"><?php echo image_tag('table/icon_close_yellow.gif'); ?></'+'a></'+'td>'
						+'</'+'tr>'
						+'</'+'table>'
						+'</'+'div>');
					}else if('ERROR' == data.status){
						$('#log').prepend('<div class="message-red">'
						+'<table border="0" width="100%" cellpadding="0" cellspacing="0">'
						+'<tr>'
						+'	<td class="red-left"><b>' + data.code + '</'+'b> ' + data.message + '</'+'td>'
						+'	<td class="red-right"><a class="close-red"><?php echo image_tag('table/icon_close_red.gif'); ?></'+'></'+'td>'
						+'</'+'tr>'
						+'</'+'table>'
						+'</'+'div>');
					}else if('IGNORE' == data.status){
						$('#log').prepend('<div class="message-red">'
						+'<table border="0" width="100%" cellpadding="0" cellspacing="0">'
						+'<tr>'
						+'	<td class="red-left"><b>' + data.code + '</'+'b> ' + data.message + '</'+'td>'
						+'	<td class="red-right"><a class="close-red"><?php echo image_tag('table/icon_close_red.gif'); ?></'+'></'+'td>'
						+'</'+'tr>'
						+'</'+'table>'
						+'</'+'div>');
					}
					if(data.overview){
						$('#overview').html(data.overview);
					}

				},
				error: function(jqXHR, textStatus, errorThrown){
					alert('ko');
				},
				url: '<?php echo url_for('@tracking_ajax?session='.$TicketTracking->id); ?>?mode=normal&code=' + $('#scanCode').val(),
				dataType: 'json'
			});
			$('#scanCode').val('');
			$('#scanCode').focus();
		return false;

	}
	//]]>
	</script>
