<?php use_javascripts_for_form($form) ?>
<?php use_stylesheet('/css/jquery-ui-1.8.11.custom.css', 'last') ?>
<!--  start page-heading -->

<?php slot('title') ?>
<h1>Exports</h1>
<?php end_slot() ?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
	<tr valign="top">
		<td>
			<div id="table-content">
				<form class="Form" method="post" action="<?php echo url_for('@exports')?>" >
				<h2>Etat des coupons</h2>
					<?php echo $form->renderHiddenFields() ?>
					<table>
						<tr valign="top">
							<th align="left">Le</th>
							<td><?php echo $form['date'] ?></td>
							<td>
								<?php
if ($form['date']->renderError()) {
    printf('<div class="error-left"></div><div class="error-inner">%s</div>', $form['date']->renderError());
}else {
    echo '&nbsp;';
}

?>
							</td>
						</tr>
						<tr valign="top">
							<th align="left"><?php echo $form['operator']->renderLabel() ?></th>
							<td><?php echo $form['operator']?></td>
							<td>
							<?php
	if ($form['operator']->renderError()) {
        printf('<div class="error-left"></div><div class="error-inner">%s</div>', $form['operator']->renderError());
    }else {
        echo '&nbsp;';
    }

    ?>
							</td>
						</tr>
					</table>
					<input type="submit" name="coupons" value="coupons" class="form-submit" />
				</form>
				<div class="clear" style="height:30px;"></div>
				<form class="Form" method="post" action="<?php echo url_for('@exports')?>" >
				<h2>Etat des transactions</h2>
					<?php echo $form2->renderHiddenFields() ?>
					<table>
						<tr valign="top">
							<th align="left">Entre le</th>
							<td><?php echo $form2['from'] ?></td>
							<td>
							<?php
    $form2 =/*(sfForm)*/ $form2;
    if ($form2->getGlobalErrors() || $form2['from']->hasError()) {
        if ($form2->getGlobalErrors()) {
            $errors = $form2->getGlobalErrors();
            $error =/*(sfValidatorError)*/ array_shift($errors);
            $error = $error->getMessage();
        } else {
            $error = $form2['from']->renderError();
        }

        printf('<div class="error-left"></div><div class="error-inner">%s</div>', $error);
    }else {
        echo '&nbsp;';
    }

    ?>
							</td>
						</tr>
						<tr valign="top">
							<th align="left">Et le</th>
							<td><?php echo $form2['to'] ?></td>
							<td>
									<?php
    if ($form2['to']->hasError()) {
        printf('<div class="error-left"></div><div class="error-inner">%s</div>', $form2['to']->renderError());
    }else {
        echo '&nbsp;';
    }

    ?>

							</td>
						</tr>
						<?php if ('sfWidgetFormInputText' == get_class($form2['operator']->getWidget())): ?>
						<tr valign="top">
							<th align="left"><?php echo $form2['operator']->renderLabel() ?></th>
							<td><?php echo $form2['operator']?></td>
							<td>
							<?php if ($form2['operator']->hasError()) {
        printf('<div class="error-left"></div><div class="error-inner">%s</div>', $form2['operator']->renderError());
    }else {
        echo '&nbsp;';
    }

    ?>
							</td>
						</tr>
						<?php endif; ?>
						<tr valign="top">
							<th align="left"><?php echo $form2['actor']->renderLabel() ?></th>
							<td><?php echo $form2['actor']?></td>
							<td>
							<?php if ($form2['actor']->hasError()) {
								printf('<div class="error-left"></div><div class="error-inner">%s</div>', $form2['actor']->renderError());
							}else {
								echo '&nbsp;';
							}

							?>
							</td>
						</tr>
					</table>
					<input type="submit" name="transactions" value="transactions" class="form-submit" />
				</form>
			</div>
		</td>
		<td></td>
	</tr>
</table>