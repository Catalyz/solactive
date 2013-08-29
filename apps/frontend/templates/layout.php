<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <!--link rel="shortcut icon" href="/favicon.ico" /-->
    <?php include_stylesheets() ?>
<!--[if IE]>
<link rel="stylesheet" media="all" type="text/css" href="css/pro_dropline_ie.css" />
<![endif]-->
    <?php include_javascripts() ?>
  </head>
  <body>
<!-- Start: page-top-outer -->
<div id="page-top-outer">

<!-- Start: page-top -->
<div id="page-top">

	<!-- start logo -->
	<div id="logo">
		<?php echo link_to(image_tag('shared/logo_fr.png', array('alt' => 'logo')), '@homepage'); ?>
	</div>
	<!-- end logo -->
	<div style="float: right; color: #FFFFFF; margin-top: 50px; margin-right: 15px; text-align: right;">
		<span style="font-size: 10pt; font-size: 10pt; color: #4F4f4f">(<?php echo $sf_user->getAttribute('operator.role'); ?>)<br /><br /></span>
		<span style="font-size: 14pt;"><?php echo $sf_user->getAttribute('operator.name'); ?></span>

	</div>
 	<div class="clear"></div>

</div>
<!-- End: page-top -->

</div>
<!-- End: page-top-outer -->

<div class="clear">&nbsp;</div>

<!--  start nav-outer-repeat................................................................................................. START -->
<div class="nav-outer-repeat">
<!--  start nav-outer -->
<div class="nav-outer">

		<!-- start nav-right -->
		<div id="nav-right">


			<a href="<?php echo url_for('@logout') ?>" id="logout"><?php echo image_tag('shared/nav/nav_logout_fr.gif',array('alt'=>'')); ?></a>
			<div class="clear">&nbsp;</div>



		</div>
		<!-- end nav-right -->


		<!--  start nav -->
		<div class="nav">
		<div class="table">
		<?php if ($sf_user->hasCredential('tracking')): ?>
		<ul class="<?php echo ('tracking' == $sf_context->getModuleName()
				&& in_array($sf_context->getActionName(), array('index', 'record')))?'current':'select' ?>"><li><?php echo link_to('<b>Traçabilité</b>', '@tracking_index'); ?></li></ul>
		<?php endif ?>

		<?php if ($sf_user->hasCredential('tracking_advanced')): ?>
		<div class="nav-divider">&nbsp;</div>
		<ul class="<?php echo ('tracking' == $sf_context->getModuleName()
			&& in_array($sf_context->getActionName(), array('reset')))?'current':'select' ?>"><li><?php echo link_to('<b>Retrait</b>', '@tracking_reset'); ?></li></ul>
		<?php endif ?>

		<?php if ($sf_user->hasCredential('stats')): ?>
		<div class="nav-divider">&nbsp;</div>
		<ul class="<?php echo ('stats' == $sf_context->getModuleName())?'current':'select' ?>"><li><?php echo link_to('<b>Statistiques</b>', '@stats'); ?></li>	</ul>
		<?php endif ?>

		<?php if ($sf_user->hasCredential('exports')): ?>
		<div class="nav-divider">&nbsp;</div>
		<ul class="<?php echo ('exports' == $sf_context->getModuleName())?'current':'select' ?>"><li><?php echo link_to('<b>Exports</b>', '@exports'); ?></li></ul>
		<?php endif ?>

		<div class="nav-divider">&nbsp;</div>
		<ul class="select"><li><?php echo link_to('<b>Nouvel adhérent</b>', sfConfig::get('app_solviolette_newaccount_url'), array('target' => '_blank')); ?></li></ul>

		<div class="nav-divider">&nbsp;</div>
		<ul class="select"><li><?php echo link_to('<b>Sol-Violette.info</b>', 'http://www.sol-violette.fr/', array('target' => '_blank')); ?></li></ul>

		</div>
		</div>
		<!--  start nav -->

</div>
<div class="clear"></div>
<!--  start nav-outer -->
</div>
<!--  start nav-outer-repeat................................................... END -->

 <div class="clear"></div>

<!-- start content-outer ........................................................................................................................START -->
<div id="content-outer">
	<!-- start content -->
	<div id="content">
		<div id="page-heading">
			<?php if (has_slot('title')) {
				include_slot('title');
			}
			?>
		</div>
		<!-- end page-heading -->

		<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
			<tr>
				<th rowspan="3" class="sized"><?php echo image_tag('shared/side_shadowleft.jpg',array('alt'=>'')); ?></th>
				<th class="topleft"></th>
				<td id="tbl-border-top">&nbsp;</td>
				<th class="topright"></th>
				<th rowspan="3" class="sized"><?php echo image_tag('shared/side_shadowright.jpg',array('alt'=>'')); ?></th>
			</tr>
			<tr>
				<td id="tbl-border-left"></td>
				<td>
				<!--  start content-table-inner ...................................................................... START -->
					<div id="content-table-inner">
						<?php echo $sf_content ?>
						<div class="clear"></div>
					</div>
				</td>
				<td id="tbl-border-right"></td>
			</tr>
			<tr>
				<th class="sized bottomleft"></th>
				<td id="tbl-border-bottom">&nbsp;</td>
				<th class="sized bottomright"></th>
			</tr>
		</table>
		<div class="clear">&nbsp;</div>
		<center><?php echo image_tag('partners.png'); ?></center>
	</div>
	<!--  end content -->
	<div class="clear">&nbsp;</div>
</div>
<!--  end content-outer........................................................END -->

<div class="clear">&nbsp;</div>

<!-- start footer -->
<div id="footer">
	<!--  start footer-left -->
	<div id="footer-left">
	&copy; Copyright Sol Violette. Tous droits réservés.</div>
	<!--  end footer-left -->
	<div class="clear">&nbsp;</div>
</div>
<!-- end footer -->


  </body>
</html>
