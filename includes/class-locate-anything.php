<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.4goa.net/
 * @since      1.0.0
 *
 * @package    Locate_Anything
 * @subpackage Locate_Anything/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Locate_Anything
 * @subpackage Locate_Anything/includes
 * @author     4GOA <locateanything@4goa.net>
 */
class Locate_Anything
{
    
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Locate_Anything_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;
    
    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;
    
    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;
    
    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {
        
        $this->plugin_name = 'locate-anything';
        $this->version = '1.0.0';
        
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->load_default_assets();
    }
    
    /**
     * Loads the assets of the basic pack
     * @return void
     */
    public function load_default_assets() {
        
        /* Load default layouts */
        $layouts = array((object)array("url" => plugin_dir_path(dirname(__FILE__)) . '/assets/mapTemplates/template2.php', "name" => 'Default Layout 1'), (object)array("url" => plugin_dir_path(dirname(__FILE__)) . '/assets/mapTemplates/template4.php', "name" => 'Default Layout 2'),);
        Locate_Anything_Addon_Helper::add_map_layouts("basic", $layouts);
        
        /* Load default marker icons */
        $markers = array((object)array("url" => plugin_dir_url(dirname(__FILE__)) . '/public/js/leaflet-0.7.3/images/marker-icon.png', "description" => '', "width" => 25, "height" => 41, "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/public/js/leaflet-0.7.3/images/marker-shadow.png', "shadowWidth" => '25', "shadowHeight" => '41'), 
        (object)array(
        "url" => plugin_dir_url(dirname(__FILE__)) . "/assets/markers/48x48-marker-8.png", "description" => "", "width" => "48", "height" => "48", "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/assets/markers/marker-shadow48.png', "shadowWidth" => '48', "shadowHeight" => "48"), 
        (object)array(
        "url" => plugin_dir_url(dirname(__FILE__)) . "/assets/markers/48x48-marker-7.png", "description" => "", "width" => "48", "height" => "48", "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/assets/markers/marker-shadow48.png', "shadowWidth" => '48', "shadowHeight" => "48"), 
        (object)array(
        "url" => plugin_dir_url(dirname(__FILE__)) . "/assets/markers/48x48-marker-9.png", "description" => "", "width" => "48", "height" => "48", "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/assets/markers/marker-shadow48.png', "shadowWidth" => '48', "shadowHeight" => "48"), 
        (object)array(
        "url" => plugin_dir_url(dirname(__FILE__)) . "/assets/markers/48x48-marker-6.png", "description" => "", "width" => "48", "height" => "48", "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/assets/markers/marker-shadow48.png', "shadowWidth" => '48', "shadowHeight" => "48"), 
        (object)array(
        "url" => plugin_dir_url(dirname(__FILE__)) . "/assets/markers/48x48-marker-3.png", "description" => "", "width" => "48", "height" => "48", "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/assets/markers/marker-shadow48.png', "shadowWidth" => '48', "shadowHeight" => "48"), 
        (object)array(
        "url" => plugin_dir_url(dirname(__FILE__)) . "/assets/markers/48x48-marker-13.png", "description" => "", "width" => "48", "height" => "48", "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/assets/markers/marker-shadow48.png', "shadowWidth" => '48', "shadowHeight" => "48"), 
        (object)array(
        "url" => plugin_dir_url(dirname(__FILE__)) . "/assets/markers/48x48-marker-4.png", "description" => "", "width" => "48", "height" => "48", "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/assets/markers/marker-shadow48.png', "shadowWidth" => '48', "shadowHeight" => "48"), 
        (object)array(
        "url" => plugin_dir_url(dirname(__FILE__)) . "/assets/markers/48x48-marker-10.png", "description" => "", "width" => "48", "height" => "48", "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/assets/markers/marker-shadow48.png', "shadowWidth" => '48', "shadowHeight" => "48"), 
        (object)array(
        "url" => plugin_dir_url(dirname(__FILE__)) . "/assets/markers/48x48-marker-12.png", "description" => "", "width" => "48", "height" => "48", "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/assets/markers/marker-shadow48.png', "shadowWidth" => '48', "shadowHeight" => "48"), 
        (object)array(
        "url" => plugin_dir_url(dirname(__FILE__)) . "/assets/markers/48x48-marker-5.png", "description" => "", "width" => "48", "height" => "48", "shadowUrl" => plugin_dir_url(dirname(__FILE__)) . '/assets/markers/marker-shadow48.png', "shadowWidth" => '48', "shadowHeight" => "48"),);
        Locate_Anything_Addon_Helper::add_marker_icons("basic", $markers);
        
        /* Load default map overlays */
        $overlays = array((object)array("name" => 'OpenStreetMap', "url" => 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', "attribution" => 'OpenStreetMap', "maxZoom" => 18, "minZoom" => 2), (object)array("name" => 'GoogleMaps TERRAIN', "url" => 'TERRAIN', "attribution" => 'GoogleMaps', "maxZoom" => 18, "minZoom" => 2), (object)array("name" => 'GoogleMaps ROADMAP', "url" => 'ROADMAP', "attribution" => 'GoogleMaps', "maxZoom" => 18, "minZoom" => 2), (object)array("name" => 'GoogleMaps SATELLITE', "url" => 'SATELLITE', "attribution" => 'GoogleMaps', "maxZoom" => 18, "minZoom" => 2));
        Locate_Anything_Addon_Helper::add_overlays("basic", $overlays);
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Locate_Anything_Loader. Orchestrates the hooks of the plugin.
     * - Locate_Anything_i18n. Defines internationalization functionality.
     * - Locate_Anything_Admin. Defines all hooks for the admin area.
     * - Locate_Anything_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
         /**
         * This class contains the Upgrader that addons will use
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class.upgrademe.php';    
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-locate-anything-loader.php';
        
        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-locate-anything-i18n.php';
        
        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-locate-anything-admin.php';
        
        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-locate-anything-public.php';
        
        /**
         * This class holds utilitary functions
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-locate-anything-tools.php';
        
        /**
         * This class contains the Assets
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-locate-anything-assets.php';
        
        /**
         * This class contains the Addon helpers
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-locate-anything-addon-helper.php';
           
        $this->loader = new Locate_Anything_Loader();
    }
    
    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Locate_Anything_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {
        
        $plugin_i18n = new Locate_Anything_i18n();
        $plugin_i18n->set_domain($this->get_plugin_name());
        
        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }
    
    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {
        
        $plugin_admin = new Locate_Anything_Admin($this->get_plugin_name(), $this->get_version());
        $plugin_public = new Locate_Anything_Public($this->get_plugin_name(), $this->get_version());
        
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        
        /* additional hooks*/
        $this->loader->add_action('wp_ajax_LAgetTaxonomies', $plugin_admin, 'LA_getTaxonomies', 10, 0);
        $this->loader->add_action('wp_ajax_nopriv_LAgetTaxonomies', $plugin_admin, 'LA_getTaxonomies', 10, 0);
        
        $this->loader->add_action('wp_ajax_LAgetTaxonomyTerms', $plugin_admin, 'LA_getTaxonomyTerms', 10, 0);
        $this->loader->add_action('wp_ajax_nopriv_LAgetTaxonomyTerms', $plugin_admin, 'LA_getTaxonomyTerms', 10, 0);
        
        $this->loader->add_action('wp_ajax_refresh_cache', $plugin_public, 'refresh_cache', 10, 2);
        $this->loader->add_action('wp_ajax_nopriv_refresh_cache', $plugin_public, 'refresh_cache', 10, 2);
        
        $this->loader->add_action('wp_ajax_getLayoutCode', $plugin_admin, 'getLayoutCode', 10, 0);
        $this->loader->add_action('wp_ajax_nopriv_getLayoutCode', $plugin_admin, 'getLayoutCode', 10, 0);
        
        $this->loader->add_action('wp_ajax_LAgetFilters', $plugin_admin, 'getFilters', 10, 0);
        $this->loader->add_action('wp_ajax_nopriv_LAgetFilters', $plugin_admin, 'getFilters', 10, 0);
        
        $this->loader->add_action('admin_menu', $plugin_admin, "setup_admin_menu", 10, 0);
        $this->loader->add_action('init', $plugin_admin, 'createCustomType',0);
        $this->loader->add_action('admin_init', $plugin_admin, 'load_preview',0);

        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_post_meta_boxes');
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_admin_meta_boxes',0);
        
        $this->loader->add_action('save_post', $plugin_admin, 'save_metabox_data', 10, 2);
        $this->loader->add_action('admin_init', $plugin_admin, 'save_options', 10, 2);

        $this->loader->add_action('admin_notices', $plugin_admin, 'check_cache_permissions', 10, 2);
        /* filters */
        $this->loader->add_filter('upload_mimes',$plugin_admin, 'add_mime_types', 1, 1);
    }
    
    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {        
        $plugin_public = new Locate_Anything_Public($this->get_plugin_name(), $this->get_version());
        $plugin_public->setup_shortcodes();
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        
        /* additional hooks*/
        $this->loader->add_action('wp_ajax_getMarkers', $plugin_public, 'getMarkers', 0);
        $this->loader->add_action('wp_ajax_nopriv_getMarkers', $plugin_public, 'getMarkers', 0);
    }


    
    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }
    
    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }
    
    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Locate_Anything_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }
    
    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}