<?php slot('title') ?>
<h1>Traçabilité des coupons</h1>
<?php end_slot() ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<!--  start step-holder -->
			<div id="step-holder">
				<div class="step-no">1</div>
				<div class="step-dark-left"><a href="<?php echo url_for('@tracking_index') ?>">Choix de l'adhérent</a></div>
				<div class="step-dark-right">&nbsp;</div>
				<div class="step-no-off">2</div>
				<div class="step-light-left">Saisie des coupons</div>
				<div class="step-light-round">&nbsp;</div>
				<div class="clear"></div>
			</div>
			<!--  end step-holder -->

			<div id="table-content">
				<form class="trackerActorForm" method="post" action="<?php echo url_for('@tracking_index')?>" >
					<table>
						<tr>
							<th><?php echo $form['login']->renderLabel() ?></th>
							<td><?php echo $form['login'] ?></td>
							<td>
								<?php if ($form['login']->renderError()) {
									printf('<div class="error-left"></div><div class="error-inner">%s</div>',$form['login']->renderError());
								}
								else{
									echo '&nbsp;';
								}
								 ?>
							</td>
						</tr>
					</table>
					<?php echo $form->renderHiddenFields() ?>
					<input type="submit" value="" class="form-submit" />
				</form>
			</div>
		</td>
		<td></td>
	</tr>
</table>