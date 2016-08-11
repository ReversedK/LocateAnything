<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.4goa.net/
 * @since      1.0.0
 *
 * @package    Locate_Anything
 * @subpackage Locate_Anything/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Locate_Anything
 * @subpackage Locate_Anything/includes
 * @author     4GOA <locateanything@4goa.net>
 */
class Locate_Anything_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {	
		// pre-selecting default sources
		if(!get_option("locate-anything-option-sources",'')) update_option("locate-anything-option-sources",serialize(array('post','locateanythingmarker')), '', 'yes');
		// preselecting default language
		$curLang = substr(get_bloginfo( 'language' ), 0, 2);
		if(!get_option("locate-anything-option-map-language",''))  update_option("locate-anything-option-map-language",serialize($curLang), '', 'yes');
	}

}
