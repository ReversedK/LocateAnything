<?php wp_nonce_field ('I961JpJQTj0crLKH0mGB', 'locate_anything_class_nonce' ); ?>

<h2 class="nav-tab-wrapper">
    <?php
      $tabs=array(__("Default settings","locate-anything"));
      $tabs=apply_filters("locate_anything_add_option_tab",$tabs);   

    foreach ($tabs as $tab) {
    	?>
    	 <a data-pane="<?php echo md5($tab)?>"  class="nav-tab"><?php echo $tab;?></a>
    <?php }  ?>
</h2>


<form method="post" id="form-options" >

<div class="locate-anything-map-option-pane" id="locate-anything-map-settings-page-<?php echo md5(__("Default settings","locate-anything"))?>">
<h1><?php _e("Default settings","locate-anything")?></h1>
<h2><?php _e("Map Language","locate-anything")?></h2>
<table>
<tr>
<td><?php _e("License Key");?>:</td>	<td><input type="text" style="max-width:auto" size="55" name="locate-anything-option-license-key" value="<?php echo unserialize(get_option("locate-anything-option-license-key"));?>"><br>
	<?php _e(" &nbsp;<a  target='_blank' href='http://www.locate-anything.com/addons/license/license-key/'>Get a License Key for only $4.99!</a> (Removes the 'Powered by LocateAnything' label)","locate-anything")?>
</td>
</tr>
	<tr>
<td><?php _e("GoogleMaps Key (only if you use GoogleMaps)","locate-anything");?>:</td>	<td><input type="text" name="locate-anything-option-cache-timeout" value="<?php echo unserialize(get_option("locate-anything-option-googlemaps-key"));?>"></td>
</tr>

<tr>
<td><?php _e("Map Language (GoogleMaps only)","locate-anything");?>:</td>
<td><select name="locate-anything-option-map-language">
<?php foreach (Locate_Anything_Tools::getLocaleList() as $locale => $language) {?>
<option <?php if(unserialize(get_option('locate-anything-option-map-language'))==$locale) echo "selected";?> value="<?php echo  $locale;?>">
<?php echo $language ?></option>	
<?php }?>

</select>
 </td>
</tr></table>
<h2><?php _e("What do you want to localize?","locate-anything")?></h2>
	<ul>
		<li>
<select multiple="multiple"	name="locate-anything-option-sources[]"	id="locate-anything-option-sources">
<?php
			$args = array ('public' => true);
			$post_types = get_post_types ( $args, 'objects' );
			
			$selected_items = unserialize (get_option ( 'locate-anything-option-sources' ));
			if(!is_array($selected_items)) $selected_items = array ();
			foreach ( $post_types as $post_type ) {				
				echo '<option value="' . $post_type->name . '"';
				if (array_search ( $post_type->name, $selected_items ) !== false) echo " selected ";
				echo '>' . $post_type->labels->name . '</option>';
			}			
?>
</select>
		</li>
	</ul>

	<h2><?php _e("Additional Fields","locate-anything")?></h2>
	<p><?php _e("Additional fields are useful when you need to display a specific information that will vary from marker to marker. For example, if your map is about Restaurants, you could create an additional field \"opening hours\" to store the opening hours of the place. <br/> You can add as many additional field as you want. They will appear in the marker's page.","locate-anything")?></p>
				<div id="locate-anything-additional_fields">				
					 <?php 
					 /* gets the additional fields*/
					$additional_field_list= Locate_Anything_Admin::getAdditional_field_list();					

					 /* Displays a custom field box for each  type, show only selected types*/
					foreach ( $post_types as $post_type ) {?>
						<div id="addi_fields_<?php echo $post_type->name; ?>" class="additional_fields" style='display:none'>
						<h3><?php _e("Additional fields for","locate-anything")?> : <?php echo $post_type->labels->name?></h3>
							<ul id="LA_custom_field_box_<?php echo $post_type->name?>" class="LA_custom_field_box">
									<?php 
									foreach ($additional_field_list as $field) {										
										if($field["post_type"]==$post_type->name) {?>
											<li><input type="text" data-post-type="<?php echo $field["post_type"]?>" name="<?php echo $field["field_name"]?>" id="<?php echo $field["field_name"]?>" class="locate-anything-additional-field" value="<?php echo $field["field_description"]?>"> <input type="button"  class='button-admin' value="delete" onclick="LA_removeRow('#<?php echo $field["field_name"]?>')"></li>
									<?php	}
									}?>							
							</ul>
							<input type="button" class="button-admin" onclick="LA_appendRow('#LA_custom_field_box_<?php echo $post_type->name?>','<?php echo $post_type->name?>')" value="<?php _e("Add a field","locate-anything")?>">
							</div>
					<?php }?>					
				  <textarea style='display:none' id="locate-anything-option-additional-field-list" name="locate-anything-option-additional-field-list"><?php echo json_encode($additional_field_list)?></textarea>
				</div>
	


<h2><?php _e("Cache settings","locate-anything")?></h2>
<ul>
<li><?php _e("Cache timeout (minutes)","locate-anything")?> <input type="text" name="locate-anything-option-cache-timeout" value="<?php echo unserialize(get_option("locate-anything-option-cache-timeout"));?>"></li>
<li> <?php _e("Enable cache","locate-anything")?> : <input type="radio" name="locate-anything-option-enable-cache" value="1" <?php if (unserialize(get_option("locate-anything-option-enable-cache"))==1) echo "checked";?> > <?php _e("yes","locate-anything")?> <input type="radio" <?php if (unserialize(get_option("locate-anything-option-enable-cache"))==0) echo "checked";?> name="locate-anything-option-enable-cache" value="0" > <?php _e("no","locate-anything")?>  </li>

</ul></div>

<?php echo apply_filters("locate_anything_add_option_pane","")?>
<div style="text-align: right"><input type="submit" class='button-admin' style=""></div>
</form>