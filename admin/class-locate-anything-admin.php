<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.4goa.net/
 * @since      1.0.0
 *
 * @package    Locate_Anything
 * @subpackage Locate_Anything/admin
 */
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package Locate_Anything
 * @subpackage Locate_Anything/admin
 * @author 4GOA <locateanything@4goa.net>
 */
class Locate_Anything_Admin
{
	/**
	 * The ID of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var string $version The current version of this plugin.
	 */
	private $version;
	/**
	 * The Gmaps key for this app, used for geocoding in the admin
	 *
	 * @since 1.0.1
	 * @access private
	 * @var string $app_Gmaps_key
	 		 The GoogleMaps key.
	 */
	 static $Gmaps_API_key = 'AIzaSyC0lZ7MbGfowxNTZva7fAyeTJ18dAWMUp0';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name
	 *        	The name of this plugin.
	 * @param string $version
	 *        	The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;		
	}

	/**
	 * Register new  mime types
	 * 
	 *
	 * @since    1.1.4
	 * @access   private
	 */
	public function add_mime_types($mime_types){
	    $mime_types['kml'] = 'application/vnd.google-earth.kml+xml'; //Adding kml extension       
	    return $mime_types;
	}

	/**
	 * serves the Gmaps Key
	 *
	 * @since 1.0.0
	 */
	public static function getGmapsAPIKey() {
		$key = get_option("locate-anything-option-googlemaps-key");
		if($key===false || empty($key)) $key = Locate_Anything_Admin::$Gmaps_API_key;		
		return $key;
	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_script($this->plugin_name . "-adminjs", plugin_dir_url(__FILE__) . 'js/locate-anything-admin.js');
		// leaflet JS
		wp_enqueue_script($this->plugin_name . "-leaflet", 'http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.js', array(
			'jquery'
		) , $this->version, false);
		// leaflet-filters JS
		wp_enqueue_script($this->plugin_name . "-leaflet-filters", plugin_dir_url(__FILE__) . '../public/js/leaflet-filters/leaflet-filters.js', array(
			$this->plugin_name . "-leaflet"
		) , $this->version, false);
		wp_enqueue_script($this->plugin_name . "-googleAPI", "https://maps.googleapis.com/maps/api/js?key=".$this->getGmapsAPIKey()."&v=3.exp&libraries=places&language=en" . unserialize(get_option("locate-anything-option-map-language")) , array(
			$this->plugin_name . "-leaflet-filters"
		) , $this->version, false);
		wp_enqueue_script($this->plugin_name . "-select2", '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.min.js');
		// Awesome markers
		wp_enqueue_script($this->plugin_name . "-awesomemarkers", plugin_dir_url(__FILE__) . '../public/js/leaflet.awesome-markers-2.0/leaflet.awesome-markers.js', array(
			$this->plugin_name . "-leaflet"
		) , $this->version, false);
		// annotation plugin
		wp_enqueue_script($this->plugin_name . "-anno", plugin_dir_url(__FILE__) . 'js/anno/anno.js', array(
			'jquery'
		) , $this->version, false);
		wp_enqueue_script($this->plugin_name . "-anno-dependency", plugin_dir_url(__FILE__) . 'js/anno/scrollintoview/jquery.scrollintoview.min.js', array() , $this->version, false);
		// Google Tiles
		wp_enqueue_script($this->plugin_name . "-googleTiles", plugin_dir_url(__FILE__) . '../public/js/leaflet-plugins-master/layer/tile/Google.js', array(
			$this->plugin_name . "-leaflet"
		) , $this->version, false);
		// leaflet markerCluster JS
		wp_enqueue_script($this->plugin_name . "-leaflet-marker-cluster", plugin_dir_url(__FILE__) . '../public/js/leaflet.markercluster/leaflet.markercluster.js', array(
			'jquery'
		) , $this->version, false);
		// google autocomplete
		wp_enqueue_script($this->plugin_name . "-googleautojs", plugin_dir_url(__FILE__) . '../public/js/leaflet-google-autocomplete/js/leaflet-google-autocomplete.js', array(
			$this->plugin_name . "-googleAPI") , $this->version, false);

	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style($this->plugin_name . "-admincss", plugin_dir_url(__FILE__) . 'css/locate-anything-admin.css', array() , $this->version, 'all');
		wp_enqueue_style($this->plugin_name . "-annocss", plugin_dir_url(__FILE__) . 'js/anno/anno.css', array() , $this->version, 'all');
		
		wp_enqueue_style($this->plugin_name . "-select2css", "//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css", array() , $this->version, 'all');
		// Ionicons
		wp_enqueue_style($this->plugin_name . "-ioniconscss", 'http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css', array() , $this->version, 'all');
		// Awesome markers
		wp_enqueue_style($this->plugin_name . "-awesomemarkerscss", plugin_dir_url(__FILE__) . '../public/js/leaflet.awesome-markers-2.0/leaflet.awesome-markers.css', array() , $this->version, 'all');
		// leaflet css
		wp_enqueue_style($this->plugin_name . "-leaflet", plugin_dir_url(__FILE__) . '../public/js/leaflet-0.7.3/leaflet.css', array() , $this->version, 'all');
		// leaflet-filters css
		wp_enqueue_style($this->plugin_name . "-leaflet-filters", plugin_dir_url(__FILE__) . '../public/js/leaflet-filters/leaflet-filters.css', array() , $this->version, 'all');
		// leaflet markerCluster css
		wp_enqueue_style($this->plugin_name . "-leaflet-marker-cluster-default", plugin_dir_url(__FILE__) . '../public/js/leaflet.markercluster/MarkerCluster.Default.css', array() , $this->version, 'all');
		wp_enqueue_style($this->plugin_name . "-leaflet-marker-cluster", plugin_dir_url(__FILE__) . '../public/js/leaflet.markercluster/MarkerCluster.css', array() , $this->version, 'all');
		// leaflet Google automplete CSS
		wp_enqueue_style($this->plugin_name . "-googleauto", plugin_dir_url(__FILE__) . '../public/js/leaflet-google-autocomplete/css/leaflet-google-autocomplete.css', array() , $this->version, 'all');
	}
	/**
	 * Adds metaboxes to the post types selected in the options page
	 */
	public function add_post_meta_boxes() {
		// fetch the post types where LocateAnything will be active
		$selected_post_types = unserialize(get_option('locate-anything-option-sources'));
		// add the LocateAnything Metabox for each of them
		if (is_array($selected_post_types)) $selected_post_types[] = "locateanythingmarker";
		else $selected_post_types = array(
			"locateanythingmarker"
		);
		
		foreach ($selected_post_types as $type) {
			add_meta_box('locate-anything-class', // Unique ID
			esc_html__('LocateAnything', 'locate-anything') , // Title
			'Locate_Anything_Admin::post_class_meta_box', // Callback function
			$type, // Admin page (or post type)
			'normal', // Context
			'high'); // Priority
			
		}
	}

	/**
	 * Loads the preview pane
	 */
	public function load_preview() {	
		if(isset($_GET["locateAnything_preview"])){	
			include(plugin_dir_path(dirname(__FILE__)).'/admin/partials/locate-anything-preview.php');	
			die();
		}	
	}

	/**
	 * Adds metaboxes to the post types selected in the options page
	 */
	public function add_admin_meta_boxes() {
		add_meta_box('locate-anything-class', // Unique ID
		esc_html__('LocateAnything - Wordpress Geo Plugin', 'locate-anything') , // Title
		'Locate_Anything_Admin::admin_class_meta_box', // Callback function
		'locateanythingmap', // Admin page (or post type)
		'normal', // Context
		'high'); // Priority
		
	}
	
	/**
	 * Checks cache permissions, called on action admin_notices
	 *
	 */
	public static function check_cache_permissions() {
		$path=plugin_dir_path(dirname(__FILE__)) ."cache";
		if(!is_writable($path)){if(!@chmod($path, 0777)) {
			echo '<div class="update-nag"><p>'.__("<b>Error<b> : Please add write permissions on the follwing directory : $path","locate-anything").'</p></div>';
			}
		}
	}

	/**
	 * Displays the settings page
	 *
	 */
	public static function admin_settings_page() {		
		include (plugin_dir_path(__FILE__) . 'partials/locate-anything-settings-admin.php');
	}
	/**
	 * Display the admin meta box.
	 *
	 */
	public static function admin_class_meta_box($object, $box) {
		include (plugin_dir_path(__FILE__) . 'partials/locate-anything-metabox-admin.php');
	}
	/**
	 * Display the post meta box.
	 *
	 */
	public static function post_class_meta_box($object) {
		include (plugin_dir_path(__FILE__) . 'partials/locate-anything-metabox-post.php');
	}
	/**
	 * Display the user meta box.
	 *
	 */
	public static function user_class_meta_box($object) {
		$post_type = "user";
		include (plugin_dir_path(__FILE__) . 'partials/locate-anything-metabox-post.php');
	}
	/**
	 * saves metabox fields
	 * @param  int $post_id [description]
	 * @param  WP Post Object $post
	 * @return int post_id
	 */
	public function save_metabox_data($post_id, $post) {
		/* Verify the nonce before proceeding. */
		if (!isset($_POST['locate_anything_class_nonce']) || !wp_verify_nonce($_POST['locate_anything_class_nonce'], "I961JpJQTj0crLKH0mGB")) return $post_id;
		/* Get the post type object. */
		$post_type = get_post_type_object($post->post_type);
		/* Check if the current user has permission to edit the post. */
		if (!current_user_can($post_type->cap->edit_post, $post_id)) return $post_id;
		foreach ($_POST as $meta_key => $new_meta_value) {
			if (strpos($meta_key, "locate-anything") !== false) Locate_Anything_Admin::add_update_metas($post_id, $meta_key, $new_meta_value);
		}
		return $post_id;
	}
	/**
	 * Save the settings set in Option page
	 */
	public static function save_options() {
		foreach ($_POST as $k => $v) {
			if (strpos($k, "locate-anything-option-") !== false) {
				update_option($k, serialize($v) , '', 'yes');
			}
		}
	}
	/**
	 * Utilitary function to add, delete, update metas
	 */
	public function add_update_metas($post_id, $meta_key, $new_meta_value) {
		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta($post_id, $meta_key, true);
		/* If a new meta value was added and there was no previous value, add it. */
		if ($new_meta_value !== false && '' == $meta_value) {
			add_post_meta($post_id, $meta_key, $new_meta_value, true);
			update_post_meta($post_id, $meta_key, $new_meta_value);
		}
		/* If the new meta value does not match the old value, update it. */
		elseif ($new_meta_value !== false && $new_meta_value != $meta_value) update_post_meta($post_id, $meta_key, $new_meta_value);
		/* If there is no new meta value but an old value exists, delete it. */
		elseif ('' == $new_meta_value && $meta_value) delete_post_meta($post_id, $meta_key, $meta_value);
	}
	/**
	 * creates Admin Page in WP admin menu
	 */
	public function setup_admin_menu() {
		add_submenu_page("edit.php?post_type=locateanythingmap", "Options", "Options", "edit_posts", "locate-anything-settings", "Locate_Anything_Admin::admin_settings_page");
	}
	/**
	 * defines a custom types for the maps
	 */
	public function createCustomType() {
		$labels = array(
			'name' => __('LocateAnything Maps', 'locate-anything') ,
			'singular_name' => __('Map', 'locate-anything') ,
			'add_new' => __('Add New', 'locate-anything') ,
			'add_new_item' => __('Add New Map', 'locate-anything') ,
			'edit_item' => __('Edit Map', 'locate-anything') ,
			'new_item' => __('New Map', 'locate-anything') ,
			'all_items' => __('All Map', 'locate-anything') ,
			'view_item' => __('View Map', 'locate-anything') ,
			'search_items' => __('Search Maps', 'locate-anything') ,
			'not_found' => __('No map found', 'locate-anything') ,
			'not_found_in_trash' => __('No map found in Trash', 'locate-anything') ,
			'menu_name' => __('LocateAnything Maps', 'locate-anything')
		);
		$supports = array(
			'title'
		);
		$slug = "locateanythingMap";
		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_icon' => 'dashicons-admin-site',
			'query_var' => true,
			'rewrite' => array(
				'slug' => $slug
			) ,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => $supports
		);
		register_post_type('locateanythingMap', $args);
		/* marker custom post type*/
		
		$labels = array(
			'name' => __('Marker Categories', "locate-anything") ,
			'singular_name' => __('Marker Category', "locate-anything") ,
			'search_items' => __('Search Categories', "locate-anything") ,
			'all_items' => __('All Categories', "locate-anything") ,
			'parent_item' => __('Parent Category', "locate-anything") ,
			'parent_item_colon' => __('Parent Category:', "locate-anything") ,
			'edit_item' => __('Edit Category', "locate-anything") ,
			'update_item' => __('Update Category', "locate-anything") ,
			'add_new_item' => __('Add New Category', "locate-anything") ,
			'new_item_name' => __('New Category Name', "locate-anything") ,
			'menu_name' => __('Categories', "locate-anything") ,
		);
		
		$args = array(
			'hierarchical' => true,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'locateanythingmarkercategory'
			) ,
		);
		register_taxonomy('locateanythingmarkercategory', 'locateanythingMarker', $args);

		$labels = array(
			'name' => __('Marker Tags', "locate-anything") ,
			'singular_name' => __('Marker Tag', "locate-anything") ,
			'search_items' => __('Search Tags', "locate-anything") ,
			'all_items' => __('All Tags', "locate-anything") ,
			'parent_item' => __('Parent Tag', "locate-anything") ,
			'parent_item_colon' => __('Parent Tag:', "locate-anything") ,
			'edit_item' => __('Edit Tag', "locate-anything") ,
			'update_item' => __('Update Tag', "locate-anything") ,
			'add_new_item' => __('Add New Tag', "locate-anything") ,
			'new_item_name' => __('New Tag Name', "locate-anything") ,
			'menu_name' => __('Tags', "locate-anything") ,
		);
		
		$args = array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug' => 'locateanythingmarkertag'
			) ,
		);
		register_taxonomy('locateanythingmarkertag', 'locateanythingMarker', $args);

		$labels = array(
			'name' => __('LocateAnything Markers', 'locate-anything') ,
			'singular_name' => __('Marker', 'locate-anything') ,
			'add_new' => __('Add New', 'locate-anything') ,
			'add_new_item' => __('Add New Marker', 'locate-anything') ,
			'edit_item' => __('Edit Marker', 'locate-anything') ,
			'new_item' => __('New Marker', 'locate-anything') ,
			'all_items' => __('All Marker', 'locate-anything') ,
			'view_item' => __('View Marker', 'locate-anything') ,
			'search_items' => __('Search Markers', 'locate-anything') ,
			'not_found' => __('No Marker found', 'locate-anything') ,
			'not_found_in_trash' => __('No Marker found in Trash', 'locate-anything') ,
			'menu_name' => __('LocateAnything Markers', 'locate-anything')
		);
		$supports = array(
			'title',
			'editor',
			'excerpt','thumbnail'
		);
		$slug = "locateanythingMarker";
		$args = array(
			'labels' => $labels,
			'public' => false,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array(
				'slug' => $slug
			) ,
			'capability_type' => 'post',
			'has_archive' => true,
			'hierarchical' => false,
			'menu_position' => null,
			'supports' => $supports,
			'taxonomies' => array(
				'locateanythingmarkercategory','locateanythingmarkertag'				
			)
		);
		register_post_type('locateanythingMarker', $args);
	}
	/**
	 * Returns the additional field list stored in options
	 * @param  boolean $post_type [description]
	 * @return [type]             [description]
	 */
	public static function getAdditional_field_list($post_type = false) {
		$additional_field_list_json = stripslashes(unserialize(get_option('locate-anything-option-additional-field-list', '')));
		if ($additional_field_list_json) $additional_field_list = json_decode($additional_field_list_json, true);
		if (!is_array($additional_field_list)) $additional_field_list = array();
		
		if ($post_type !== false) {
			foreach ($additional_field_list as $key => $field) {
				if ($field["post_type"] !== $post_type) unset($additional_field_list[$key]);
			}
		}
		return $additional_field_list;
	}
	
	/**
	 * displays additional fields and their tags
	 *
	 */
	public static function displayAdditionalFieldNotice($post_type) {
		$additional_field_list_json = stripslashes(unserialize(get_option('locate-anything-option-additional-field-list', '')));
		if ($additional_field_list_json) $additional_field_list = json_decode($additional_field_list_json, true); ?>
				<div id="basic_fields_notice">									
	<?php	  
			$post_types = array("basic"=>"basic");
			$post_types += unserialize (get_option ( 'locate-anything-option-sources' ));
			$post_types = apply_filters("locate_anything_add_sources",$post_types);	

			$already_displayed_tags =array();
			  		
			 foreach ( $post_types as $posttype =>$postTypeName ) {
			 	if($posttype=="Users") $posttype = 'user';			 	
			 	$markups = Locate_Anything_Public::getBasicMarkupList($posttype);
			 	foreach ($markups as $tag => $nothing) {	
			 			if(in_array($tag,$already_displayed_tags))	continue;
			 			array_push($already_displayed_tags,$tag);
			 		?>
					<div class='basic-markup basic-markup-<?php echo $posttype?>'><b><?php echo ucfirst(str_replace(array("|","_") , array(""," ") , $tag)) ?></b> : <?php echo $tag ?></div>
				<?php }
				} ?>
				
				</div>
				<div id="additional_fields_notice">				
				<table id="additional_fields_notice">							
				<?php
		if (is_array($additional_field_list)) foreach ($additional_field_list as $field) {
			if (is_null(@$field["field_description"]) || is_null(@$field["field_name"]) || @$field['post_type']!==$post_type) continue;
?>
					<tr class='basic-markup basic-markup-<?php
			echo $field['post_type']?>'><td><b><?php
			echo $field["field_description"] . "(" . $field['post_type'] . ")" ?></b></td><td>|<?php
			echo $field['post_type'] . "::" . sanitize_key(remove_accents($field["field_description"])) ?>|</td></tr>
				<?php
		} ?>
				
				<tfoot id="filter_fields_notice"></tfoot>
				</table>
				</div>
				<?php
	}
	/** 
	 * Display additional fields form
	 *
	 */
	public static function displayAdditionalFields($post) {
		if (get_post_type($post) == false) return;
?>
		<ul id="additional_fields">
					 <?php
		$additional_field_list_json = stripslashes(unserialize(get_option('locate-anything-option-additional-field-list', '')));
		if ($additional_field_list_json) $additional_field_list = json_decode($additional_field_list_json, true);
		if ($additional_field_list) {
			if (is_array($additional_field_list) && $post !== "user") foreach ($additional_field_list as $field) {
				if ($field["post_type"] == get_post_type($post)) { ?>
				  	<li><b><?php
					echo $field["field_description"] ?></b><br/> <textarea name="<?php
					echo $field["field_name"] ?>"><?php
					echo get_post_meta($post->ID, $field["field_name"], true); ?></textarea></li>
				 <?php
				}
			}
		}
?></ul><?php
	}
	/** 
	 * returns default templates for new map
	 *
	 */
	public static function getDefaultTemplates() {
		return array(
			"tooltip" => "|small_thumbnail|
<p>|content_stripped|</p>",
			"navlist" => "<b>|title|</b>
<span> |city|, |country|</span>
<div>|content_stripped|</div>
|small_thumbnail|"
		);
	}
	/**
	 * Returns all the metas for this post
	 * @param  [int] $id [post ID]
	 * @return [array]  array [metaname]=metavalue
	 */
	public static function getPostMetas($id) {
		$t = array();
		foreach (get_post_meta($id) as $k => $v) {
			$t[$k] = current($v);
		}
		return $t;
	}
	/**
	 * Returns all the metas for this user
	 * @param  [int] $id [post ID]
	 * @return [array]  array [metaname]=metavalue
	 */
	public static function getUserMetas($id) {
		$t = array();
		foreach (get_user_meta($id) as $k => $v) {
			$t[$k] = current($v);
		}
		return $t;
	}
	/**
	 * Returns select licences seed
	 * @param  [int] $id [licence id]
	 * @return [string]  seed
	 */
	public static function getLicence($id) {
		$licences = array('label'=>"-license-lvl1");
		$licences = apply_filters("add_seed_licence",$licences);		
		$license_key  =unserialize(get_option("locate-anything-option-".$licences[$id]."-license"));
		return array('seed'=>$licences[$id],'key'=>$license_key);
	}

	/**
	 * Geocodes address,
	 * @param  [string] $address
	 * @return [false | array]  returns false if unable to geocode address
	 */
	public static function geocode($address) {
		// url encode the address
		$address = urlencode($address);
		// google map geocode api url
		$url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";
		// get the json response
		$resp_json = file_get_contents($url);
		// decode the json
		$resp = json_decode($resp_json, true);
		// response status will be 'OK', if able to geocode given address
		if ($resp['status'] == 'OK') {
			// get the important data
			$lati = $resp['results'][0]['geometry']['location']['lat'];
			$longi = $resp['results'][0]['geometry']['location']['lng'];
			$formatted_address = $resp['results'][0]['formatted_address'];
			// verify if data is complete
			if ($lati && $longi && $formatted_address) {
				// put the data in the array
				$data_arr = array();
				array_push($data_arr, $lati, $longi, $formatted_address);
				return $data_arr;
			} 
			else return false;
		} 
		else return false;
	}
	/**
	 * AJAX function : returns JSON encoded array of taxonomies tied to a post type
	 */
	/* get Taxonomies associated with type passed in request */
	public function LA_getTaxonomies() {
		echo json_encode(get_object_taxonomies(sanitize_text_field($_REQUEST['type'])));
		die();
	}
	/**
	 * AJAX function : returns JSON encoded array of taxonomies tied to a post type
	 */
	/* get Taxonomy terms associated with type passed in request */
	public function LA_getTaxonomyTerms() {
		$selected = get_post_meta($_REQUEST['map_id'], "locate-anything-allowed-filters-value-" . $_REQUEST['type'], true);
		$terms = get_terms(sanitize_text_field($_REQUEST['type']));
		if ($terms) foreach ($terms as $in => $term) {
			if (is_array($selected) && array_search($term->term_id, $selected) !== false) $terms[$in]->selected = 1;
			else $terms[$in]->selected = 0;
			if (!$selected) $terms[$in]->selected = 1;
		}
		echo json_encode($terms);
		die();
	}
	/**
	 * AJAX function : returns JSON encoded html code for layout
	 */
	public function getLayoutCode() {
		$record = get_post_meta($_POST["map_id"], "locate-anything-map-template-html-" . $_POST["layout_id"], true);
		if ($record == false) echo json_encode(file_get_contents(Locate_Anything_Assets::getMapTemplates($_POST["layout_id"])->url));
		else echo json_encode($record);
		die();
	}
	/**
	 * AJAX function : returns HTML of current filters
	 */
	public function getFilters() {
		echo apply_filters("locate_anything_add_filter_choice", '', $_POST["map_id"], $_POST["type"]);
		die();
	}


}