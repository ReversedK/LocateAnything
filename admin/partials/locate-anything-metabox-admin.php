 <?php wp_nonce_field( "I961JpJQTj0crLKH0mGB" , 'locate_anything_class_nonce' ); 

function makeInput($type,$fieldname,$object_id,$default='') {?>
 <input type="<?php echo $type?>" value="<?php echo get_post_meta($object_id,$fieldname,true)?get_post_meta($object_id,$fieldname,true):$default; ?>" name="<?php echo $fieldname;?>">
<?php } ?>



<table id='locate-anything-main-table' style="width: 100%">
<tr><td id="map-preview" style="width: 100%">
<!-- Map preview -->
<iframe scrolling="no" seamless="seamless" name="map_preview" src="<?php echo admin_url()?>?post_type=locateanythingmap&locateAnything_preview&id=preview"></iframe>			 	 
</td></tr></table>

<h2 class="nav-tab-wrapper">
    <a  data-pane="1"  data-animation="50%" class="active nav-tab"><?php _e("Map settings","locate-anything");?></a>
    <a class="nav-tab" data-pane="6" data-animation="50%"><?php _e("Filters","locate-anything");?></a>
     <a class="nav-tab" data-pane="4" data-animation="50%"><?php _e("Markers settings","locate-anything");?></a>   
    <a class="nav-tab" data-pane="2" data-animation="50%"><?php _e("Tooltip & Nav List","locate-anything");?></a>
   	<a class="nav-tab" data-pane="3" data-animation="50%"><?php _e("Map Layouts","locate-anything");?></a>

<a class="nav-tab" data-pane="5" data-animation="50%"><?php _e("Tools & Shortcodes","locate-anything");?></a>
</h2>

<div id="locate-anything-wrapper">
<table id='locate-anything-main-table' style="width: 100%">
<tr>
<td style="width: 100%">
<table  id="locate-anything-map-settings-page-1" class="locate-anything-map-option-pane locate-anything-map-settings-list-ul">
<tr><td><h2><?php _e("General settings","locate-anything")?></h2></td></tr>  

  <!-- Map provider -->
<tr id="map-provider">
<td><?php _e("Map Overlay","locate-anything");?> &nbsp;<input type="button" data-target="map-provider" class="locate-anything-help"></td>
<td nowrap><select name="locate-anything-map-provider" id="locate-anything-map-provider">

<?php foreach (Locate_Anything_Assets::getMapOverlays() as $overlay){?>
	<option value="<?php echo $overlay->id;?>" data-url="<?php echo $overlay->url;?>" data-attribution="<?php echo $overlay->attribution;?>" <?php if(get_post_meta($object->ID,'locate-anything-map-provider',true)==$overlay->id) echo "selected";?> ><?php echo $overlay->name?></option>
<?php }?>

</select><br><small><?php _e("<b>Important : </b> if you choose GoogleMaps you MUST enter a GoogleMaps API key in the <a href='".admin_url()."edit.php?post_type=locateanythingmap&page=locate-anything-settings'>options page</a>","locate-anything");?> </small>
</td></tr>	

<tr id='show-attr-label' >
<td><?php _e("Show attribution label","locate-anything")?> &nbsp;<input type="button" data-target="show-attr-label" class="locate-anything-help"> </td>
<td>	 
			  <input type="radio" name="locate-anything-show-attribution-label" value="1" <?php if (get_post_meta( $object->ID, 'locate-anything-show-attribution-label', true )=="1") echo "checked" ;?>> <?php _e("yes","locate-anything")?>
			  <input type="radio" name="locate-anything-show-attribution-label" value="0" <?php if (get_post_meta( $object->ID, 'locate-anything-show-attribution-label', true )=="0" ||  get_post_meta( $object->ID, 'locate-anything-show-attribution-label', true )==false) echo "checked" ;?>> <?php _e("no","locate-anything")?></td>
</tr>

<?php echo do_action("LocateAnything-general-settings-form",$object->ID)?>



<!-- Map Style for GoogleMaps -->
<tr id="map-hue">
<td><?php _e("Map hue (GoogleMaps only)","locate-anything");?> &nbsp;<input type="button" data-target="map-hue" class="locate-anything-help"></td>
<td><input type="color" name="locate-anything-googlemaps-hue" value="<?php echo  esc_attr( get_post_meta( $object->ID, 'locate-anything-googlemaps-hue', true ) );?>"></td>
</tr>


<!-- Map Settings -->
<tr id="map-width">
<td><?php _e("Map width","locate-anything");?>&nbsp;<input type="button" data-target="map-width" class="locate-anything-help"></td>
<td> <input type="text" size="5" placeholder="100%,850px,..."  name="locate-anything-map-width" value="<?php if(get_post_meta( $object->ID, 'locate-anything-map-width', true )) echo  esc_attr(get_post_meta( $object->ID, 'locate-anything-map-width', true )); else echo "100%";?>"></td>
</tr>
<tr id="map-height">
<td><?php _e("Map height","locate-anything");?> &nbsp;<input type="button" data-target="map-height" class="locate-anything-help"></td>
<td> <input type="text" size="5" placeholder="500px"  name="locate-anything-map-height" value="<?php if(get_post_meta( $object->ID, 'locate-anything-map-height', true )) echo  esc_attr(get_post_meta( $object->ID, 'locate-anything-map-height', true)); else echo "500px";?>"></td>
</tr>
<tr id="startposition">
<td><?php _e("Default start position","locate-anything");?> &nbsp;<input type="button" data-target="startposition" class="locate-anything-help"></td>
<td> <input type="text" size="12" placeholder="lat,lon"  name="locate-anything-start-position" id="locate-anything-start-position" value="<?php echo  esc_attr( get_post_meta( $object->ID, 'locate-anything-start-position', true ) );?>"></td>
</tr>
<tr id="maxzoom">
<td><?php _e("Max zoom","locate-anything")?> &nbsp;<input type="button" data-target="maxzoom" class="locate-anything-help"> </td>
<td>
<input name="locate-anything-max-zoom" type ="range" min ="1" max="18" step ="1" value ="<?php   $v= esc_attr( get_post_meta( $object->ID, 'locate-anything-max-zoom', true ) );echo $v?$v:18?>"/>
</td>
</tr>

<tr id="minzoom">
<td><?php _e("Min zoom","locate-anything")?> &nbsp;<input type="button" data-target="minzoom" class="locate-anything-help"> </td>
<td>
<input name="locate-anything-min-zoom" type ="range" min ="1" max="18" step ="1" value ="<?php   $v= esc_attr( get_post_meta( $object->ID, 'locate-anything-min-zoom', true ) );echo $v?$v:2?>"/>
</tr>
<tr id="startzoom">
<td><?php _e("Initial zoom","locate-anything")?> &nbsp;<input type="button" data-target="startzoom" class="locate-anything-help"> </td>
<td>
<input name="locate-anything-start-zoom" id="locate-anything-start-zoom" type ="range" min ="1" max="18" step ="1" value ="<?php $v= esc_attr( get_post_meta( $object->ID, 'locate-anything-start-zoom', true ) );echo $v?$v:5?>"/>
</td>
</tr>

<tr >
<td><?php _e("Zoom using mousewheel","locate-anything")?> &nbsp; </td>
<td>	 
			  <input type="radio" name="locate-anything-scrollWheelZoom" value="1" <?php if (get_post_meta( $object->ID, 'locate-anything-scrollWheelZoom', true )=="1") echo "checked" ;?>> <?php _e("yes","locate-anything")?>
			  <input type="radio" name="locate-anything-scrollWheelZoom" value="0" <?php if (get_post_meta( $object->ID, 'locate-anything-scrollWheelZoom', true )=="0" ||  get_post_meta( $object->ID, 'locate-anything-scrollWheelZoom', true )==false) echo "checked" ;?>> <?php _e("no","locate-anything")?>

</td>
</tr>

<tr id="autogeocode">
<td><?php _e("Enable user geolocation","locate-anything")?> &nbsp;<input type="button" data-target="autogeocode" class="locate-anything-help"> </td>
<td>	 
			  <input type="radio" name="locate-anything-usergeolocation-zoom" value="1" <?php if (get_post_meta( $object->ID, 'locate-anything-usergeolocation-zoom', true )=="1") echo "checked" ;?>> <?php _e("yes","locate-anything")?>
			  <input type="radio" name="locate-anything-usergeolocation-zoom" value="0" <?php if (get_post_meta( $object->ID, 'locate-anything-usergeolocation-zoom', true )=="0" ||  get_post_meta( $object->ID, 'locate-anything-usergeolocation-zoom', true )==false) echo "checked" ;?>> <?php _e("no","locate-anything")?>

</td>
</tr>

<tr id="googleplaces">
<td><?php _e("Enable Google Places searchbox","locate-anything")?>  &nbsp;<input type="button" data-target="googleplaces" class="locate-anything-help"></td>
<td>			 
			  <input type="radio" name="locate-anything-googleplaces" value="1" <?php if (get_post_meta( $object->ID, 'locate-anything-googleplaces', true )=="1") echo "checked" ;?>> <?php _e("yes","locate-anything")?>
			  <input type="radio" name="locate-anything-googleplaces" value="0" <?php if (get_post_meta( $object->ID, 'locate-anything-googleplaces', true )=="0" || get_post_meta( $object->ID, 'locate-anything-googleplaces', true )==false) echo "checked" ;?>> <?php _e("no","locate-anything")?>
			</td>
</tr>
<tr id="navnumbers">
<td><?php _e("Max number of results displayed in the list","locate-anything")?> &nbsp;<input type="button" data-target="navnumbers" class="locate-anything-help"></td>
<td><input type="text"  size="5" name="locate-anything-nav-number" value="<?php $v=get_post_meta($object->ID, 'locate-anything-nav-number', true );echo $v?$v:10;?>"></td>
</tr>

<tr id="display_only_inbound">
<td><?php _e("Refresh list as you go","locate-anything")?> &nbsp;<input type="button" data-target="display_only_inbound" class="locate-anything-help"></td>
<td>
		  <input type="radio" name="locate-anything-display_only_inbound" value="1" <?php if (get_post_meta( $object->ID, 'locate-anything-display_only_inbound', true )=="1") echo "checked" ;?>> <?php _e("yes","locate-anything")?>
			  <input type="radio" name="locate-anything-display_only_inbound" value="0" <?php if (get_post_meta( $object->ID, 'locate-anything-display_only_inbound', true )=="0" || get_post_meta( $object->ID, 'locate-anything-display_only_inbound', true )==false) echo "checked" ;?>> <?php _e("no","locate-anything")?>
</td>
</tr>

<tr id="hide-splashscreen">
<td><?php _e("Hide loader screen","locate-anything")?></td>
<td>
		  <input type="radio" name="locate-anything-hide-splashscreen" value="1" <?php if (get_post_meta( $object->ID, 'locate-anything-hide-splashscreen', true )=="1") echo "checked" ;?>> <?php _e("yes","locate-anything")?>
			  <input type="radio" name="locate-anything-hide-splashscreen" value="0" <?php if (get_post_meta( $object->ID, 'locate-anything-hide-splashscreen', true )=="0" || get_post_meta( $object->ID, 'locate-anything-hide-splashscreen', true )==false) echo "checked" ;?>> <?php _e("no","locate-anything")?>
</td>
</tr>


<tr id="">
<td><?php _e("Remove the 'Powered by LocateAnything' label","locate-anything")?></td>
<td><?php _e(" &nbsp;<a target='_blank' href='http://www.locate-anything.com/addons/license/license-key/'>Get a License Key for only $4.99!</a>","locate-anything")?></td>
</tr>


</table>
			 
<table id="locate-anything-map-settings-page-6" class="locate-anything-map-settings-list-ul locate-anything-map-option-pane" style="display:none" >	
<!-- Post Type / Source -->		
<tr><td colspan="2"><h2><?php _e("Choose a post type","locate-anything");?></h2></td></tr>
<tr id="map-source">
<td>
<b><?php _e("Post Type","locate-anything");?></b>&nbsp;<input type="button" data-target="map-source" class="locate-anything-help"></td>
<td>
<input type="hidden" name="locate-anything-filters" value='' >			 
<select name="locate-anything-source" id="locate-anything-source"> 
		<?php 
		/* Sources */
		$sources=array();		
		$sources=apply_filters("locate_anything_add_sources",$sources);
		$selected_element=get_post_meta( $object->ID, 'locate-anything-source', true );
		foreach ($sources as $key => $value) {
			if ($selected_element==$key) $sel="selected";else $sel='';
			?>
			<option <?php echo $sel?> value="<?php echo $key?>"><?php echo $value?></option>
		<?php }	

		$post_types = unserialize (get_option ( 'locate-anything-option-sources' ));			  
			  foreach ( $post_types as $post_type ) { ?>
			  	<option value="<?php echo $post_type?>" <?php if ($selected_element==$post_type) echo " selected ";?> ><?php echo get_post_type_object($post_type)->labels->singular_name?></option>
			  <?php	} ?>
</select></td>
</tr>
<!-- Filters -->
<tr><td colspan="2"><h2><?php _e("Filter the markers (optional)","locate-anything");?></h2></td></tr>
<tr>
<td colspan="2"><b><?php _e("Filter the markers ","locate-anything");?></b> &nbsp;<input type="button" data-target="map-filters" class="locate-anything-help"></td>
</tr>
<?php echo do_action("LocateAnything-general-settings-form-filters",$object->ID)?>
<tr  id="map-filters">
<td colspan="2"><span id='filters'></span></td>
</tr>

<tr><td colspan="2"><h2><?php _e("How is this map filterable ?","locate-anything");?></h2></td></tr>
<tr  id="tr-show-filters">
<!-- defines show filters to empty, will be overwritten by the real show filters if all checkboxes are not unchecked -->
<input type="hidden" name="locate-anything-show-filters[]" value=''>
<td><b><?php _e("Make this map filterable by","locate-anything");?></b>:&nbsp;<input type="button" data-target="tr-show-filters" class="locate-anything-help"></td>
<td ><span id='show-filters'></span></td>
</tr>
</table>	 
			
<table id="locate-anything-map-settings-page-4" class="locate-anything-map-settings-list-ul locate-anything-map-option-pane" style="display:none" >	
<tr><td colspan="2"><h2><?php _e("Choose the default marker icon","locate-anything")?></h2></td></tr>  
<tr>
<td><input type="radio" <?php if (get_post_meta($object->ID,"locate-anything-marker-type",true)=="standard" || get_post_meta($object->ID,"locate-anything-marker-type",true)==false) echo 'checked' ?> name="locate-anything-marker-type" value="standard"> <b><?php _e("Choose an icon","locate-anything")?></b></td>

<td> 
			   <!-- marker selector -->
			   <div id="locate-anything-marker-selector">			
			<select style='width:80px !important' name="locate-anything-default-marker" id="locate-anything-default-marker">				 
				 <?php foreach (Locate_Anything_Assets::getMarkers() as $marker){?>
				 	<option value="<?php echo $marker->id?>" <?php if(esc_attr(get_post_meta($object->ID,"locate-anything-default-marker",true))==$marker->id) echo "selected"?>><?php echo $marker->url?></option>	 		
		<?php }?>  
			</select>		
</div></td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td id="medialibrary"><input type="radio" <?php if (get_post_meta($object->ID,"locate-anything-marker-type",true)=="medialibrary") echo 'checked' ?> name="locate-anything-marker-type" value="medialibrary"> <b><?php _e("Add an icon from the media library","locate-anything")?></b>&nbsp;<input type="button" data-target="medialibrary" class="locate-anything-help"></td>
<td><img id="default-marker-media">
	<div class="uploader">
	<input id="locate-anything-marker-type" name="locate-anything-default-marker-media" type="hidden" value="<?php  echo esc_attr(get_post_meta($object->ID,"locate-anything-default-marker-media",true))?>" /> <input id="locate-anything-marker-type_button" class="button-admin"  name="locate-anything-marker-type_button" type="text" value="<?php _e("Add","locate-anything")?>" />
</div>

</td>
</tr>

<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td><input type="radio" <?php if (get_post_meta($object->ID,"locate-anything-marker-type",true)=="awesomemarker") echo 'checked' ?> name="locate-anything-marker-type" value="awesomemarker"> <b><?php _e("Create an icon","locate-anything")?></b></td>

<td >
<!-- Awesome marker creator -->
			<div id="locate-anything-marker-creator">		
					<?php _e("Symbol","locate-anything")?> : <select style='width:80px !important'  name="locate-anything-marker-symbol" id="locate-anything-marker-symbol">
					<?php 
					$selected_awesome=get_post_meta($object->ID,"locate-anything-marker-symbol",true);
					include plugin_dir_path ( __FILE__ ) . "../../includes/ionicon-options.php"?>
					</select>
					<br>
					<?php _e("Symbol color","locate-anything")?> : <input type="color" value="<?php echo get_post_meta($object->ID,"locate-anything-marker-symbol-color",true)?>"  name="locate-anything-marker-symbol-color">
					<br>
					<?php _e("Marker color","locate-anything")?> : 
					<select name="locate-anything-marker-color">
					<?php foreach(array('red', 'darkred', 'orange', 'green', 'darkgreen', 'blue', 'purple', 'darkpurple', 'cadetblue') as $color){
						?>
						<option <?php if($color==get_post_meta($object->ID,"locate-anything-marker-color",true)) echo "selected"; ?> value="<?php echo $color?>"><?php echo $color?></option>
					<?php }?>
					</select>
					</div>
</td>
</tr>
<tr><td colspan="2"><h2><?php _e("Choose the global icon size for AwesomeMarkers","locate-anything")?></h2></td></tr>  
<tr><td><b><?php _e("Icon size","locate-anything")?></b></td><td><input name="locate-anything-marker-size" type="range" min="10" max="20" step="1" value="<?php echo get_post_meta($object->ID,"locate-anything-marker-size",true)?>"></td></tr>
</table>

<table id="locate-anything-map-settings-page-5" class="locate-anything-map-settings-list-ul locate-anything-map-option-pane" style='display:none' >	
<tr><td><h2><?php _e("Load a KML file (beta)")?></h2></td></tr>
<tr>
<td id="kml"><b><?php _e("KML Style Options","locate-anything")?></b></td>
<td>
<ul>
<li>Fill Color : <?php makeInput("color","locate-anything-kml_fillColor",$object->ID,'#cbbdfb') ?></li>
<li>Opacity : <?php makeInput("text","locate-anything-kml_opacity",$object->ID,'1') ?></li>
<li>Line width : <?php makeInput("number","locate-anything-kml_weight",$object->ID,'2') ?></li>
<li>Color :  <?php makeInput("color","locate-anything-kml_color",$object->ID,'#000') ?></li>
<li>Fill Opacity :  <?php makeInput("text","locate-anything-kml_fillOpacity",$object->ID,'0.5') ?></li>
<li>Dash array :  <?php makeInput("number","locate-anything-kml_dashArray",$object->ID,'2') ?></li>
</ul>




	<div class="uploader">
	<input id="locate-anything-kml-file" name="locate-anything-kml-file" type="text" value="<?php  echo esc_attr(get_post_meta($object->ID,"locate-anything-kml-file",true))?>" /> <input id="locate-anything-kml-file_button" class="button-admin"  name="locate-anything-kml-file_button" type="text" value="<?php _e("Select file","locate-anything")?>" />
</div>

</td>
</tr>



<tr><td><h2><?php _e("Shortcodes")?></h2></td></tr>
<tr><td>	
<ul>
<li><b><?php _e("Display the map with a predefined layout","locate-anything")?></b> : [LocateAnything map_id=<?php echo $object->ID?>]</li>
<li><b><?php _e("Display the map separately","locate-anything")?></b> : [LocateAnything_map map_id=<?php echo $object->ID?>]</li>
<li><b><?php _e("Display the filters separately","locate-anything")?></b> : [LocateAnything_filters map_id=<?php echo $object->ID?>]</li>
<li><b><?php _e("Display the navigation list separately","locate-anything")?></b> : [LocateAnything_navlist map_id=<?php echo $object->ID?>]</li>
</ul>
</td></tr>

<tr><td><h2><?php _e("Cache")?></h2></td></tr>
<tr>
<td><?php  if(unserialize(get_option("locate-anything-option-enable-cache"))==0) _e("The cache is currently disabled. You can activate it in the options page","locate-anything"); else {?>
<a onclick='refresh_cache()'><?php _e("Refresh cache for this map","locate-anything")?></a><br/><?php _e("Status","locate-anything")?> : <span id="result_cache"><?php _e("ready","locate-anything")?></span>
<?php }?>
</td>
</tr>
</table>
   <!-- Tooltips -->
<table id="locate-anything-map-settings-page-2" class="locate-anything-map-settings-list-ul locate-anything-map-option-pane" style='display:none' >
<tr><td><h2><?php _e("Navlist Settings","locate-anything")?></h2></td></tr> 
<tr><td><?php _e("Event","locate-anything")?></td><td><input type="radio" <?php if(get_post_meta($object->ID,"locate-anything-navlist-event",true)=="click") echo "checked" ?> name="locate-anything-navlist-event" value="click">Click <input type="radio" <?php if(get_post_meta($object->ID,"locate-anything-navlist-event",true)=="hover" || get_post_meta($object->ID,"locate-anything-navlist-event",true)==false) echo "checked" ?> name="locate-anything-navlist-event" value="hover">Hover</radio></td></tr>
<tr><td><h2><?php _e("Tooltips Settings","locate-anything")?></h2></td></tr>  
<tr>
<td><b><?php _e("Tooltip style","locate-anything")?> </b>:</td>
<td><select name="locate-anything-tooltip-style"><option <?php if(get_post_meta($object->ID,"locate-anything-tooltip-style",true)=="rounded") echo "selected" ?> value="rounded"><?php _e("Rounded corners","locate-anything")?></option><option <?php if(get_post_meta($object->ID,"locate-anything-tooltip-style",true)=="squared") echo "selected" ?> value="squared"><?php _e("Squared corners","locate-anything")?></option></select></td>
</tr>
<tr>
<td><b><?php _e("Tooltip Preset","locate-anything")?> </b>:</td>
<td><select name="locate-anything-tooltip-preset" id="locate-anything-tooltip-preset">
<?php 
$u=Locate_Anything_Admin::getDefaultTemplates();
/* tooltip presets */
$tooltip_presets=array(
       (object)array("class"=>'',"name"=>__('none',"locate-anything"),"template"=>''),
       (object)array("class"=>'nice-tooltips',"name"=>'Nice Tooltips',"template"=>$u["tooltip"])
       );                       
 $tooltip_presets=apply_filters("locate_anything_tooltip_presets",$tooltip_presets);
 $selectedPreset=get_post_meta($object->ID,"locate-anything-tooltip-preset",true);
 foreach ($tooltip_presets as  $preset) {
 	if($selectedPreset==$preset->class) $say="selected";else $say='';
 	echo '<option '.$say.' value="'.$preset->class.'" data-template="'.$preset->template.'">'.$preset->name.'</option>';
 }?>
</select>
</td>
</tr>

<tr>
<td><b><?php _e("Navlist Preset","locate-anything")?> </b>:</td>
<td><select name="locate-anything-navlist-preset" id="locate-anything-navlist-preset">
<?php 
$u=Locate_Anything_Admin::getDefaultTemplates();
/* navlist presets */
$navlist_presets=array(
       (object)array("class"=>'',"name"=>__('none',"locate-anything"),"template"=>'')     
       );                       
 $navlist_presets=apply_filters("locate_anything_navlist_presets",$navlist_presets);
 $selectedPreset=get_post_meta($object->ID,"locate-anything-navlist-preset",true);
 foreach ($navlist_presets as  $preset) {
 	if($selectedPreset==$preset->class) $say="selected";else $say='';
 	echo '<option '.$say.' value="'.$preset->class.'" data-template="'.$preset->template.'">'.$preset->name.'</option>';
 }?>
</select>
</td>
</tr>
<tr id="nice-tooltips-settings">
<td><?php _e("Nice Tooltips settings","locate-anything")?> : &nbsp;<input type="button" data-target="nice-tooltips-settings" class="locate-anything-help"></td><td><?php _e("Main image max-height","locate-anything")?> : <input type="text" value="<?php echo get_post_meta($object->ID,"locate-anything-nice-tooltips-img-height",true)?get_post_meta($object->ID,"locate-anything-nice-tooltips-img-height",true):"200px"?>" name="locate-anything-nice-tooltips-img-height">
</td></tr>
<tr><td><h2><?php _e("Templates","locate-anything")?></h2></td></tr> 
<tr>
<td colspan="2" id="addifields"><div class="LA_additional_fields_notice">
				<b><?php _e("Available fields","locate-anything")?> &nbsp;<input type="button" data-target="addifields" class="locate-anything-help"></b>	
				<p></p>			
				<?php Locate_Anything_Admin::displayAdditionalFieldNotice($selected_element)?>			
			</div></td>
</tr>
<tr >		
<td colspan="2" id="templates"><br><b ><?php _e("Default navigation list template","locate-anything")?>&nbsp;<input type="button" data-target="templates" class="locate-anything-help"></b> <br> <textarea  name="locate-anything-default-nav-template" id="locate-anything-default-nav-template"><?php  $ct=esc_attr( get_post_meta( $object->ID, 'locate-anything-default-nav-template', true ) ); if(!$ct) echo $u["navlist"];else echo $ct;?></textarea>
			  <br/>
			  <b><?php _e("Default Tooltip template","locate-anything")?>&nbsp;<input type="button" data-target="templates" class="locate-anything-help"> </b> <br> <textarea  name="locate-anything-default-tooltip-template" id="locate-anything-default-tooltip-template"><?php  $ct=esc_attr( get_post_meta( $object->ID, 'locate-anything-default-tooltip-template', true ) ); if(!$ct) echo $u["tooltip"];else echo $ct;?></textarea></td>

</tr>

</table>



 <table id="locate-anything-map-settings-page-3" class="locate-anything-map-settings-list-ul locate-anything-map-option-pane" style='display:none' >
   <!-- Map Layout -->
<tr><td colspan="2"><h2><?php _e("Map Layout","locate-anything")?>&nbsp;<input type="button" data-target="maplayout" class="locate-anything-help"></h2></td></tr>

<tr id="maplayout">
<td><?php _e("Map Layout","locate-anything")?> </td>
<td><select name="locate-anything-map-template" id="locate-anything-map-template"><?php foreach (Locate_Anything_Assets::getMapTemplates() as $template){?>
			 	 	  <option data-url='<?php echo json_encode($template->url);?>' value="<?php echo $template->id;?>" <?php if(get_post_meta($object->ID,'locate-anything-map-template',true)==$template->id) echo "selected";?> ><?php echo $template->name?></option>
			 	 	  		<?php }?></select></td></tr>
<tr>				 	 	  		
<td colspan="2" id="layout_editor"></td>
</tr>		
</table>	  
	
</td></tr></table>
</div>	
<script type="text/javascript">
	var AJAX_URL= "<?php echo admin_url( 'admin-ajax.php'); ?>";
	var PARTIAL_DIR= "<?php echo  plugin_dir_url(__FILE__); ?>";
	var ADMIN_URL= "<?php echo  admin_url() ?>";
	var OBJECT_ID='<?php echo $object->ID?>';
jQuery(document).ready(function(){

	/* initializes media uploader*/
		initialize_media_uploader();
	/* refreshes Layout code editor*/
	refresh_layout_code()
	/* Layout code editor event*/
jQuery("#locate-anything-map-template").change(function(e){	refresh_layout_code();});

	/* if something changes refresh preview */
	jQuery("input, select, textarea").change(function(){
		refresh_preview();
	});
	/* help texts */
	<?php include plugin_dir_path(__FILE__)."locate-anything-help.php";?>
		/* initializes taxonomies */
			locate_anything_refresh_filters();			
		/* Listener : on change of post type,refresh taxonomies */
			jQuery('#locate-anything-source').change(locate_anything_refresh_filters);
		/* initializes marker selector */ 
		initialize_marker_selector("locate-anything-default-marker");
		jQuery("#locate-anything-navlist-preset").change(function(e){locate_anything_select_navpreset(e)});	
		jQuery("#locate-anything-tooltip-preset").change(function(e){locate_anything_select_preset(e)});			
  });



 /* Displays the taxonomies associated with a post type or the user filters */
function locate_anything_refresh_filters(){
	jQuery("#filters").html('');
	jQuery("#show-filters").html('');
	if(jQuery('#locate-anything-source').val()!=='user'){
		/* Post filters */
		      jQuery.ajax({
			          type: 'POST',
			          url: AJAX_URL,
			          data: {
			          	"action": "LAgetTaxonomies",
			          	"type":jQuery('#locate-anything-source').val()
			      },
			          success: function(data){
			          	var selected='|<?php 
			          		$the_filters=get_post_meta( $object->ID, 'locate-anything-filters',true);
			          		if(is_array($the_filters)) echo implode("|",$the_filters)?>|';
			          	var selectedShow='|<?php 
			          		$the_filters=get_post_meta( $object->ID, 'locate-anything-show-filters',true);
			          		if(is_array($the_filters)) echo implode("|",$the_filters)?>|';	
			          	<?php 
			          	$jsObj='';			          	
			          	if(is_array($the_filters))foreach($the_filters as $filter){
			          		 $r=get_post_meta( $object->ID,"locate-anything-display-filter-".$filter,true);
			          		 if(!empty($r)) $jsObj.= "'$filter':'$r',";
			          		 $m=get_post_meta( $object->ID,"locate-anything-min-range-".$filter,true) ;	
			          		 $M=get_post_meta( $object->ID,"locate-anything-max-range-".$filter,true);	
			          		 if(!empty($m)) {
			          		  	$jsObj.= "'locate-anything-min-range-$filter':'$m',";
			          		  	$jsObj.= "'locate-anything-max-range-$filter':'$M',";
			          		  }	          		 
			          	}	
			          	echo 'var display_filters={'.$jsObj.'};';	

			          	?>
                           
			          	
			          	data=JSON.parse(data);

			          		for(var i=0;i<data.length;i++) {
			          			var item=data[i];
			          			var isChecked='';  
			          			var isCheckedShow=''; 
			          			if(selected.indexOf("|"+item+"|")>-1) isChecked=' checked="checked" ';
			          			if(selectedShow.indexOf("|"+item+"|")>-1) isCheckedShow=' checked="checked" ';
			          			jQuery("#filters").append('<br><input onclick="locate_anything_refresh_template_tags(\''+item+'\')" class="locate-anything-filter-checkbox" type="checkbox" name="locate-anything-filters[]" '+isChecked+' value=\"'+item+'\">Refine by '+item);
			          			jQuery("#show-filters").append('<div style=""><br><input onclick="locate_anything_manage_selector(\''+item+'\')" '+isCheckedShow+' type=\"checkbox\" name=\"locate-anything-show-filters[]\"  value=\"'+item+'\">'+item+'</div>' );

			          			var isCheckbox='', isSelect='',isTokenize='',isRange='';

			          			if(display_filters[item]=="checkbox")  isCheckbox="selected";else if(display_filters[item]=="select")  isSelect="selected"; else if(display_filters[item]=="tokenize") isTokenize="selected";else if(display_filters[item]=="range") isRange="selected";
			          			if(isCheckedShow.length==0) var displaynone="display:none;";else var displaynone="";
			          			jQuery("#show-filters").append('<div style="'+displaynone+'" class="hide-if-'+item+' filter-selector-'+item+'"><b> Selector </b> : <select class="locate-anything-display-filter-" id="locate-anything-display-filter-'+item+'" item="'+item+'" name="locate-anything-display-filter-'+item+'"><option '+isCheckbox+' value="checkbox">Checkboxes</option><option '+isSelect+' value="select">Dropdown</option><option '+isTokenize+' value="tokenize">Tokenize</option><option '+isRange+' value="range">Range</option></select></div>');
			          			/* adding range options */
			          			var rangeOptionsVisible;
			          			if(isRange) rangeOptionsVisible='';else rangeOptionsVisible='style="display:none"';
			          			jQuery(".hide-if-"+item).append('<span '+rangeOptionsVisible+' id="range-options-'+item+'"> Min : <input type="text" size="4" id="locate-anything-min-range-'+item+'" name="locate-anything-min-range-'+item+'" value="'+(display_filters['locate-anything-min-range-'+item]|| "")+'"> Max : <input value="'+(display_filters['locate-anything-max-range-'+item] || "")+'" type="text" size="4" id="locate-anything-max-range-'+item+'" name="locate-anything-max-range-'+item+'"></span>');
			          			/* allowed terms */	
			          			jQuery("#filters").append('<div style="'+displaynone+'" class="refine-hide-if-'+item+'"><b>Allowed terms  :</b> <select multiple class="locate-anything-allowed-filters" name="locate-anything-allowed-filters-value-'+item+'[]" id="locate-anything-allowed-filters-value-'+item+'"></select></div></div>');
			          			

			          			

			          			locate_anything_refresh_taxonomy_terms(item);
			          			/* if something changes refresh preview */
								jQuery("input, select, textarea").change(function(){
									refresh_preview();
								});

			          		}
			          locate_anything_refresh_template_tags();
			          /* Event on range selection*/
			          register_range_events();
			          }
			      });
	} 
		/* addon filters*/
		     locate_anything_get_addon_filters();    			
	
		     
}


function locate_anything_select_navpreset(e){
  if(confirm("<?php _e('Do you want to overwrite the current navlist template?','locate-anything')?>")) {jQuery("#locate-anything-default-nav-template").val(jQuery('#locate-anything-navlist-preset :selected').attr("data-template"));refresh_preview();}
}

function locate_anything_select_preset(e){
  if(confirm("<?php _e('Do you want to overwrite the current tooltip template?','locate-anything')?>")) {jQuery("#locate-anything-default-tooltip-template").val(jQuery('#locate-anything-tooltip-preset :selected').attr("data-template"));refresh_preview();}
}
</script>