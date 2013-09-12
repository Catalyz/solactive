<?php $datas = $datas->getRawValue(); ?>
<?php $amounts = array_keys($datas); ?>
<table cellpadding="3" cellspacing="2" border="1">
<tr>
	<td width="150">Type</td>
	<td width="150" bgcolor="#FFFF99">Mis en circulation</td>
	<td width="150" bgcolor="#FFFF99">Déposés</td>
	<td width="150" bgcolor="#D6E3BC" class="hide">Mis à jour</td>
	<td width="150" bgcolor="#B8CCE4" class="hide">Confirmé</td>
	<td width="150" bgcolor="#FFFF99" class="hide">Expiré</td>
	<td width="150">Total</td>
</tr>
<?php foreach($amounts as $amount): ?>
<tr>
	<td><?php echo $amount; ?> <?php echo sfConfig::get('app_project_currency_single') ?></td>
	<td bgcolor="#FFFF99"><?php echo $datas[$amount]['delivered']; ?></td>
	<td bgcolor="#FFFF99"><?php echo $datas[$amount]['removed']; ?></td>
	<td bgcolor="#D6E3BC" class="hide"><?php echo $datas[$amount]['updated']; ?></td>
	<td bgcolor="#B8CCE4" class="hide"><?php echo $datas[$amount]['confirmed']; ?></td>
	<td bgcolor="#FFFF99" class="hide"><?php echo $datas[$amount]['expired']; ?></td>
	<td><?php echo array_sum($datas[$amount]) * $amount; ?> <?php echo sfConfig::get('app_project_currency_plural') ?></td>
</tr>
<?php endforeach; ?>
<tr>
	<td>Total en coupons</td>
	<td bgcolor="#FFFF99"><?php $value = 0;
foreach($amounts as $amount) {
	$value += $datas[$amount]['delivered'];
}
echo $value; ?></td>
	<td bgcolor="#FFFF99"><?php $value = 0;
foreach($amounts as $amount) {
	$value += $datas[$amount]['removed'];
}
echo $value; ?></td>
	<td bgcolor="#D6E3BC" class="hide"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['updated'];
}
echo $value; ?></td>
	<td bgcolor="#B8CCE4" class="hide"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['confirmed'];
}
echo $value; ?></td>
	<td bgcolor="#FFFF99" class="hide"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['expired'];
}
echo $value; ?></td>
	<td><?php $value = 0;
foreach($amounts as $amount) {
    $value += array_sum($datas[$amount]);
}
echo $value; ?></td>
</tr>
<tr>
	<td>Total en <?php echo sfConfig::get('app_project_currency_plural') ?></td>
	<td bgcolor="#FFFF99"><?php $value = 0;
foreach($amounts as $amount) {
	$value += $datas[$amount]['delivered'] * $amount;
}
echo $value; ?> <?php echo sfConfig::get('app_project_currency_plural') ?></td>
	<td bgcolor="#FFFF99"><?php $value = 0;
foreach($amounts as $amount) {
	$value += $datas[$amount]['removed'] * $amount;
}
echo $value; ?> <?php echo sfConfig::get('app_project_currency_plural') ?></td>
	<td bgcolor="#D6E3BC" class="hide"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['updated'] * $amount;
}
echo $value; ?> <?php echo sfConfig::get('app_project_currency_plural') ?></td>
	<td bgcolor="#B8CCE4" class="hide"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['confirmed'] * $amount;
}
echo $value; ?> <?php echo sfConfig::get('app_project_currency_plural') ?></td>
	<td bgcolor="#FFFF99" class="hide"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['expired'] * $amount;
}
echo $value; ?> <?php echo sfConfig::get('app_project_currency_plural') ?></td>
	<td><?php $value = 0;
foreach($amounts as $amount) {
    $value += array_sum($datas[$amount]) * $amount;
}
echo $value; ?> <?php echo sfConfig::get('app_project_currency_plural') ?></td>
</tr>

</table>

<br />