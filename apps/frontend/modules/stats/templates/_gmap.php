<?php use_javascript('http://www.google.com/jsapi'); ?>


	<script type="text/javascript">
google.load('maps', '3', { other_params: 'sensor=false' });
google.setOnLoadCallback(initialize);

  var map;
  function initialize() {
    var myOptions = {
      zoom: 8,
      disableDoubleClickZoom: true,
      scrollwheel: false,
      center: new google.maps.LatLng(44.0177169,1.3546225),
      mapTypeId: google.maps.MapTypeId.ROADMAP
  }
  map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
 var LatLngBounds = false;
  <?php

  $allInfos = CoreConnector::getAllUsersInfos();

$mapDetails = $mapDetails->getRawValue();
foreach ($datas['datas']->getRawValue() as $item => $details) {
    $cpt = $item;
  	if ($latLong = Application::getActorCoord($item)) {
if(!in_array($stats_type, array('transaction-count', 'average-transaction-value')) || $details['transaction-value']){



		$actorName = Application::getActorName($allInfos[$item], $item);

        $popupinfos = sprintf("<div style=\"height:80px;\"><b>Adhérent :</b> %s<br/>",esc_js($actorName));
        $popupinfos .= sprintf("<b>Téléphone :</b> %s<br/>", isset($allInfos[$item])?$allInfos[$item]['phone']:'-');
        $popupinfos .= sprintf("<b>N° carte :</b> %s<br/>", isset($allInfos[$item])?$allInfos[$item]['card_no']:'-');
        $popupinfos .= sprintf("<b>Coupons :</b> %s<br/>", $details['nombre']);
        $popupinfos .= sprintf("<b>Montant :</b> %s %s<br />", $details['transaction-value'], sfConfig::get('app_project_currency_plural'));

        if (!empty($details['transaction-count'])) {
            $popupinfos .= sprintf("<b>Transactions :</b> %s (%d%%)<br />", $details['transaction-count'],
            	number_format((100 * $details['transaction-count'])  / $datas['totalTransaction'], 0, ',', ' '));
        }
        if (!empty($details['average-transaction-value'])) {
            $popupinfos .= sprintf("<b>Montant moyen :</b> %s %s<br />", $details['average-transaction-value'], ($details['average-transaction-value']>1)?sfConfig::get('app_project_currency_plural'):sfConfig::get('app_project_currency_single'));
        }
        $popupinfos .= '</div>';

	?>


  //add maker to map
 	myLatlng<?php echo $cpt ?> = new google.maps.LatLng(<?php echo $latLong ?>);
  	marker<?php echo $cpt ?> = new google.maps.Marker({ position: myLatlng<?php echo $cpt ?>, map: map});

  	infowindow<?php echo $cpt ?> = new google.maps.InfoWindow({
	        content: '<div style="min-height: 150px"><?php echo html_entity_decode($popupinfos) ?></div>',
	        position: marker<?php echo $cpt ?>.getPosition()
	        });


	<?php

	 if (!empty($mapDetails[$cpt])):?>
  	// Add a Circle overlay to the map.
        var circle = new google.maps.Circle({
          map: map,
          strokeColor: "#A349A4",
          fillColor: "#A349A4",
          radius: <?php echo $mapDetails[$cpt]/* *5 */ ?>
        });

        // Since Circle and Marker both extend MVCObject, you can bind them
        // together using MVCObject's bindTo() method.  Here, we're binding
        // the Circle's center to the Marker's position.
        // http://code.google.com/apis/maps/documentation/v3/reference.html#MVCObject
        circle.bindTo('center', marker<?php echo $cpt ?>, 'position');
	<?php endif;?>

  //center map
  	if (LatLngBounds == false) {
  		LatLngBounds = new google.maps.LatLngBounds(myLatlng<?php echo $cpt ?>,myLatlng<?php echo $cpt ?>);
  	} else {
  		LatLngBounds.extend(myLatlng<?php echo $cpt ?>);
  	}
	map.fitBounds(LatLngBounds);



  //add table link
  //google.maps.event.addDomListener($('#click_<?php echo $cpt ?>'), 'click', function() {
//	map.setCenter(myLatlng<?php echo $cpt ?>);
	//infowindow<?php echo $cpt ?>.open(map, marker<?php echo $cpt ?>);
  	//});
//alert('<?php echo $cpt ?>');

  //add click pop up
  google.maps.event.addListener(marker<?php echo $cpt ?>, 'click', function() {
    map.setCenter(myLatlng<?php echo $cpt ?>);
	infowindow<?php echo $cpt ?>.open(map, marker<?php echo $cpt ?>);
  });



  <?php
        // $cpt++;
    }}
}

?>
}
</script>

