<?php
/**
 * Tools
 *
 * @package Locate_Anything
 * @subpackage Locate_Anything/admin
 * @author 4GOA <locateanything@4goa.net>
 */
class Locate_Anything_Addon_Helper
{
    /**
     * Add overlays to the overlay list in BO
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
        }
        , 10, 1);
    }
    /**
     * Add marker icons to the list in BO
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
     * Add map layouts to the list in BO
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
     * Allows the creation of new choices in the filterS tab of the BO
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
    
    public static function add_option_pane($fn,$addon_name, $html) {
        add_filter("locate_anything_add_option_tab", function ($tabs) use ($addon_name) {
            $tabs[] = $addon_name;
            return $tabs;
        }
        , 1000, 1);

        add_filter("locate_anything_add_option_pane", function ($h) use ($html, $addon_name,$fn) {
            eval("\$html = $fn();");            
            $h.= "<div id='locate-anything-map-settings-page-" . md5($addon_name) . "' class='locate-anything-map-option-pane' style='display:none'>
                    <h1>$addon_name Settings</h1>" . $html . "</div>";
            return $h;
        }
        , 1000, 1);
    }
}
?>