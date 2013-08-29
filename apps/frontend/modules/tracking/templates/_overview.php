<?php $datas = $datas->getRawValue(); ?>
<?php $amounts = array_keys($datas); ?>
<table cellpadding="3" cellspacing="2" border="1">
<tr>
	<td width="150">Type</td>
	<td width="150" bgcolor="#FFFF99">Mis en circulation</td>
	<td width="150" bgcolor="#D6E3BC">Mis à jour</td>
	<td width="150" bgcolor="#B8CCE4">Confirmé</td>
	<td width="150" bgcolor="#FFFF99">Expiré</td>
	<td width="150">Total</td>
</tr>
<?php foreach($amounts as $amount): ?>
<tr>
	<td><?php echo $amount; ?> Sol</td>
	<td bgcolor="#FFFF99"><?php echo $datas[$amount]['delivered']; ?></td>
	<td bgcolor="#D6E3BC"><?php echo $datas[$amount]['updated']; ?></td>
	<td bgcolor="#B8CCE4"><?php echo $datas[$amount]['confirmed']; ?></td>
	<td bgcolor="#FFFF99"><?php echo $datas[$amount]['expired']; ?></td>
	<td><?php echo array_sum($datas[$amount]) * $amount; ?> Sols</td>
</tr>
<?php endforeach; ?>
<tr>
	<td>Total en coupons</td>
	<td bgcolor="#FFFF99"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['delivered'];
}
echo $value; ?></td>
	<td bgcolor="#D6E3BC"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['updated'];
}
echo $value; ?></td>
	<td bgcolor="#B8CCE4"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['confirmed'];
}
echo $value; ?></td>
	<td bgcolor="#FFFF99"><?php $value = 0;
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
	<td>Total en sols</td>
	<td bgcolor="#FFFF99"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['delivered'] * $amount;
}
echo $value; ?> Sols</td>
	<td bgcolor="#D6E3BC"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['updated'] * $amount;
}
echo $value; ?> Sols</td>
	<td bgcolor="#B8CCE4"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['confirmed'] * $amount;
}
echo $value; ?> Sols</td>
	<td bgcolor="#FFFF99"><?php $value = 0;
foreach($amounts as $amount) {
    $value += $datas[$amount]['expired'] * $amount;
}
echo $value; ?> Sols</td>
	<td><?php $value = 0;
foreach($amounts as $amount) {
    $value += array_sum($datas[$amount]) * $amount;
}
echo $value; ?> Sols</td>
</tr>

</table>

<br />