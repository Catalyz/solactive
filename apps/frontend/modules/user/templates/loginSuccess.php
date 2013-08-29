<?php

use_stylesheets_for_form($form);
use_javascripts_for_form($form);

?>

<?php
 echo form_tag('@login');
 echo $form->renderHiddenFields();
  ?>



<!-- Start: login-holder -->
<div id="login-holder">

	<!-- start logo -->
	<div id="logo-login">
		<?php echo link_to(image_tag('shared/logo_fr.png', array('alt' => 'logo')), '@homepage'); ?>
	</div>
	<!-- end logo -->

	<div class="clear"></div>

	<!--  start loginbox ................................................................................. -->
	<div id="loginbox">

	<!--  start login-inner -->
	<div id="login-inner">




		<table border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>&nbsp;</th>
			<td><div class="message-red"><?php if(!empty($errorMessage)){ echo $errorMessage;} ?>&nbsp;</div></td>
		</tr>
		<tr>
			<th><?php echo $form['login']->renderLabel(); ?></th>
			<td><?php echo $form['login']->render(array('class' => 'login-inp')); ?></td>
		</tr>
		<tr>
			<th><?php echo $form['password']->renderLabel(); ?></th>
			<td><?php echo $form['password']->render(array('class' => 'login-inp')); ?></td>
		</tr>
		<tr>
			<th></th>
			<td><input type="submit" class="submit-login"  /></td>
		</tr>
		</table>
	</div>
 	<!--  end login-inner -->
 </div>
 <!--  end loginbox -->

</div>
<!-- End: login-holder -->
</form>