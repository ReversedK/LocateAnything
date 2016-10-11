<?php
wp_head(); 
$_POST["map_id"]="preview";
foreach ($_POST as $key => $value) if(is_string($value)) $_POST[$key]= urldecode($value);	
$r=Locate_Anything_Public::generateJSON($_POST,false);

echo do_shortcode("[LocateAnything_map map_id=preview]");
?>
<style>
html { margin-top:0 !important; }
#map-container-preview {width:100%;height:100%;z-index: 200;position: absolute;}
<?php if($_POST['locate-anything-tooltip-style']=="squared") 
				 echo ".leaflet-popup-content-wrapper {border-radius: 0 !important;}\n";
if( $_POST['locate-anything-marker-size']) 
				 echo '#map-container-preview .awesome-marker i {font-size:'.$_POST['locate-anything-marker-size']."px !important;}\n";	
				

				 ?>
</style>