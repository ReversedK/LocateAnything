<?php
/**
 * @package Locate_Anything
 * @subpackage Locate_Anything/admin
 * @author 4GOA <locateanything@4goa.net> 
 */
class Locate_Anything_Addon_Helper
{
    /**
     * Registers filters in relation with the Option page
     * @param [string] Callback function name
     * @param [string] Addon name
     */ 
    public static function add_option_pane($fn,$addon_name) {
        // adds a tab in the option page horizontal menu
        add_filter("locate_anything_add_option_tab", function ($tabs) use ($addon_name) {
            $tabs[] = $addon_name;
            return $tabs;
        } , 1000, 1);
        // adds the actual option page content
        add_filter("locate_anything_add_option_pane", function ($h) use ($addon_name,$fn) {
            eval("\$html = $fn();");            
            $h.= "<div id='locate-anything-map-settings-page-" . md5($addon_name) . "' class='locate-anything-map-option-pane' style='display:none'>
                    <h1>$addon_name Settings</h1>" . $html . "</div>";
            return $h;
        } , 1000, 1);
    }

    /**
     * Add overlays to the overlay list in BackOffice
     * @param [string] $addon_name
     * @param [array] $overlays   [array of overlay objects]
     */
    public static function add_overlays($addon_name, $overlays) {
        add_filter("locate_anything_add_overlays", function ($all_overlays) use ($addon_name, $overlays) {
            $i = 0;
            foreach ($overlays as $ov) {
                $nid = sanitize_title($addon_name) . '-' . $i++;
                $ov->id = $nid;
                $all_overlays[$nid] = $ov;
            }
            return $all_overlays;
        } , 10, 1);
    }

    /**
     * Add Tooltips presets to the BackOffice
     * @param [string] $addon_name
     * @param [array] $presets   [array of presets objects]
     */
    public static function add_tooltip_presets($addon_name, $presets) {
        add_filter("locate_anything_tooltip_presets", function ($all_presets) use ($addon_name, $presets) {
            $i = 0;
            foreach ($presets as $ov) {
                $nid = sanitize_title($addon_name) . '-' . $i++;
                $ov->id = $nid;
                $all_presets[$nid] = $ov;
            }
            return $all_presets;
        } , 10, 1);
    }

    /**
     * Add Navlist presets to the BackOffice
     * @param [string] $addon_name
     * @param [array] $presets   [array of presets objects]
     */
    public static function add_navlist_presets($addon_name, $presets) {
        add_filter("locate_anything_navlist_presets", function ($all_presets) use ($addon_name, $presets) {
            $i = 0;
            foreach ($presets as $ov) {
                $nid = sanitize_title($addon_name) . '-' . $i++;
                $ov->id = $nid;
                $all_presets[$nid] = $ov;
            }
            return $all_presets;
        } , 10, 1);
    }

    /**
     * Add marker icons to the list in BackOffice
     * @param [string] $addon_name
     * @param [array] $markers   [array of marker icons objects]
     */
    public static function add_marker_icons($addon_name, $markers) {
        add_filter("locate_anything_add_marker_icons", function ($all_markers) use ($addon_name, $markers) {
            $i = 0;
            foreach ($markers as $marker) {
                $nid = sanitize_title($addon_name) . '-' . $i++;
                $marker->id = $nid;
                $all_markers[$nid] = $marker;
            }
            return $all_markers;
        }
        , 10, 1);
    }
    /**
     * Add map layouts to the list in BackOffice
     * @param [string] $addon_name
     * @param [array] $layouts   [array of map layouts objects]
     */
    public static function add_map_layouts($addon_name, $layouts) {
        add_filter("locate_anything_add_map_layouts", function ($all_layouts) use ($addon_name, $layouts) {
            $i = 0;
            foreach ($layouts as $layout) {
                $nid = sanitize_title($addon_name) . '-' . $i++;
                $layout->id = $nid;
                $all_layouts[$nid] = $layout;
            }
            return $all_layouts;
        }
        , 10, 1);
    }


     /**
     * Sets up the filters passed in argument
     * @param [array] $arr_filters  array of filters.  Form : $arr_filters = array(  field_name => array( 'name'=> filter caption,'values'=>field values ));   
     * @param [string] $scope  The scope of the filters : post_type, user or all
     * @param [string] $getDataCallbackFn    callback function to call to get the datas for a definite field and marker ID
     * @param [string] $addon_name  Addon name
     */   
     public static function define_filters($arr_filters,$scope,$getDataCallbackFn,$addon_name){ 
        // add filter refine options in BackOffice  
        Locate_Anything_Addon_Helper::add_filter_refine_options($arr_filters,$scope);
        // add filter html in FO
        Locate_Anything_Addon_Helper::add_frontoffice_filters($arr_filters);
        // define filter vars
        Locate_Anything_Addon_Helper::add_filter_vars($arr_filters,$scope,$getDataCallbackFn);    
     }

    public static function define_custom_tags($arr_tags,$scope,$getDataCallbackFn,$addon_name){ 
        // add markup tags
        Locate_Anything_Addon_Helper::add_markup(array_values($arr_tags),$scope);       
        // define markers vars
        Locate_Anything_Addon_Helper::add_markup_vars($arr_tags,$scope,$getDataCallbackFn);
    }


    /**
     * Adds new options in the filter's "Refine by" tab of the BackOffice
     * @param  [string] $arr_filters      arrays of the filters to add. Form : $arr_filters = array(  field_name => array( 'name'=> filter caption,'values'=>field values ));  
     * @param  [string] $post_type    
     * @param  [int] $map_id       Map ID
     * @return string $filter_html
     */
    public static function add_filter_refine_options($arr_filters,$scope){
        add_filter("locate_anything_add_filter_choice", function ($filter_html,$map_id,$post_type) use ($arr_filters,$scope) {        
            if($post_type==$scope || $scope=="all") {      
                foreach ($arr_filters as $fname=>$filter) {
                    $filter_html.=Locate_Anything_Addon_Helper::create_filter_choice($fname,$filter['name'],$map_id);
                }
            }
            return $filter_html;  
        }, 10 ,3);
    }

    /**
     * Add custom markup tags for the template editors
     * @param [array] $arr : LocateAnything Tags list
     * @param [string] $type : Post type (or 'user') or 'all'
     */
    public static function add_markup($tags,$scope){  
      add_filter("locate_anything_basic_markup",function($arr,$type) use ($tags,$scope) {    
            if($type==$scope || $type=="all") $arr=array_merge($arr,$tags);
            return $arr;
        },10,2);
    }

    /**
     * Ties custom markup tags to their value for a definite marker
     * @param [array] $arr  array containing the variables for this marker    
     * @param [int] $map_id  Map ID
     * @param [int] $id     Marker ID
     * @param [string] $type  post_type or "user" or "all"
     */
    public static function add_markup_vars($arr_tags,$scope,$callbackFn){
        add_filter("locate_anything_marker_vars",function($arr,$map_id,$id,$type) use ($arr_tags,$scope,$callbackFn) {    
        if($scope==$type || $scope=="all") {     
            foreach($arr_tags as $tag_field_id=>$tag_name){
                $arr[$tag_name] = call_user_func($callbackFn,$tag_field_id,$id); 
            }
       }
        return $arr;    
        }, 10 ,4);
    }

    /**
     * Adds filter-related variables to a marker
     * @param [array] $arr  array containing the variables for this marker    
     * @param [int] $map_id  Map ID
     * @param [int] $id     Marker ID
     * @param [string] $type  post_type or "user" or "all"
     */
    public static function add_filter_vars(&$arr_filters,$scope,$callbackFn){
        add_filter("locate_anything_filter_related_vars",function($arr,$map_id,$id,$type) use ($arr_filters,$scope,$callbackFn) {    
        if($scope==$type || $scope=="all") {   
        $filters=get_post_meta($map_id,"locate-anything-show-filters",true);
                
        if($filters){        
            foreach ($filters as $filter_fieldname) {
                $index = in_array($filter_fieldname, array_keys($arr_filters));         
                if($index){            
                $name=  $arr_filters[$filter_fieldname]['name'];     
                $arr[$filter_fieldname] = call_user_func($callbackFn,$filter_fieldname,$id); 
            }}}}
       
        return $arr; 
       
        }, 10 ,4);
    }

    public static function whitelist_filter_tags($arr_filters){
        add_filter("locate_anything_whitelist_params",function($whitelisted_tags) use ($arr_filters) {
          array_merge($whitelisted_tags,array_keys($arr_filters));
          return $whitelisted_tags;
        }, 10 ,3);
    }

    public static function add_frontoffice_filters($arr_filters){
         add_filter("locate_anything_add_custom_filters", function ($filter_html,$map_id,$filters) use ($arr_filters,$scope) {         
            foreach ($filters as $filter_fieldname) {
                $index = in_array($filter_fieldname, array_keys($arr_filters));
                if($index){       

                    $filter_html.=Locate_Anything_Addon_Helper::create_filter($filter_fieldname,$arr_filters[$filter_fieldname]['name'],$map_id,$arr_filters[$filter_fieldname]['values']);         
                }
            }
            return $filter_html;
            }, 10 ,3);
    }

    /**
     * Allows the creation of new choices in the filters tab of the BackOffice
     * @param  [type] $filters      [description]
     * @param  [type] $filter_field [description]
     * @param  [type] $title        [description]
     * @param  [type] $map_id       [description]
     * @return [type]               [description]
     */
    public static function create_filter_choice($filter_field, $title, $map_id) {
       $filters=get_post_meta($map_id,"locate-anything-show-filters",true);
       if(!is_array($filters)) $filters= array();
        $f.= '<div class="hide-if-' . $filter_field . '">';
        $f.= Locate_Anything_Tools::checkboxMultiple('locate-anything-show-filters[]', $title, '', $filter_field, array_combine($filters, $filters));
        
        $selected = get_post_meta($map_id, "locate-anything-display-filter-$filter_field", true);
        
        $f.= Locate_Anything_Tools::Array2Select(array(
            "checkbox" => "Checkboxes",
            "select" => "Dropdown",
            "tokenize" => "Tokenize",
            "range" => "Range"
        ) , "locate-anything-display-filter-$filter_field", $selected, "class='locate-anything-display-filter-'  item='$filter_field'");
        if ($selected == "range") $sel = "";
        else $sel = "display:none";
        $f.= '<span style="margin-left:5px;' . $sel . '" id="range-options-' . $filter_field . '"> Min : <input type="text" size="4" id="locate-anything-min-range-' . $filter_field . '" name="locate-anything-min-range-' . $filter_field . '" value="' . get_post_meta($map_id, 'locate-anything-min-range-' . $filter_field, true) . '"> Max : <input value="' . get_post_meta($map_id, 'locate-anything-max-range-' . $filter_field, true) . '" type="text" size="4" id="locate-anything-max-range-' . $filter_field . '" name="locate-anything-max-range-' . $filter_field . '"></span>';
        $f.= '</div>';
        return $f;
    }

      /**
     * Allows the creation of filters in FrontOffice

     */    
    public static function create_filter($filter, $name, $map_id, $values) {
        if ($filter) {
            $selector = get_post_meta($map_id, 'locate-anything-display-filter-' . $filter, true);
            $f = "<li><b>$name</b>";
            switch ($selector) {
                case "range":
                    $f.= '<br><br><div id="rangedval-' . $filter . '-' . $map_id . '"><span id="rangeval-' . $filter . '-' . $map_id . '"></span></div>  
  					<div class="rangeslider" min="' . get_post_meta($map_id, "locate-anything-min-range-$filter", true) . '" max="' . get_post_meta($map_id, "locate-anything-max-range-$filter", true) . '" name="' . $filter . '-' . $map_id . '"  id="' . $filter . '-' . $map_id . '"></div>';
                    break;

                case "select":
                    $f.= "<select id='" . $filter . "-$map_id'><option></option>";
                    break;

                case "tokenize":
                    $f.= "<select id='" . $filter . "-$map_id' multiple class='tokenize'>";
                    break;

                default:
                    break;
            }            
            foreach ($values as $value) {
                switch ($selector) {
                    case 'checkbox':
                    if(!empty($value)) $f.= "<input type='checkbox' checked name='" . $filter . "-" . $map_id . "[]' id='" . $filter . "-" . $map_id . "' value='" . esc_attr(stripslashes($value)) . "'>" . stripslashes($value);
                        break;

                    case "select":
                    case "tokenize":
                        $f.= "<option value='" . esc_attr(stripslashes($value)) . "'>" . stripslashes($value) . '</option>';
                        break;

                    default:
                        break;
                }
            }
            switch ($selector) {
                case "select":
                case "tokenize":
                    $f.= "</select>";
                    break;

                default:
                    break;
            }
        }
        $f.= "</li>";
        return $f;
    }
}?>