=== LocateAnything - Searchable map Plugin ===
Contributors: locateanything
Donate link: http://www.locate-anything.com/
Tags:   directory plugin,  geodirectory, google maps, , member directory, buddypress directory, wordpress city directory plugin, store locator, filterable map, custom post type filters, custom post type map, map layout

Requires at least: 4.0.0
Tested up to: 4.7
Stable tag: 1.1.73
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create Maps exactly the way you want using LocateAnything. 

== Description ==
LocateAnything is a versatile and highly customizable Wordpress plugin aimed at creating nice looking searchable/filterable maps.  Easily let your users search your maps by tags, custom taxonomies,xprofile fields(with the BuddyPress addon) and much more... 

LocateAnything is the ideal tool to create event map, festivals, geodirectories, community maps (user maps),store locators, social network map.


**Some of the features :**

* **Developer-friendly** : 
LocateAnything has been built on the great library LeafletJs (http://leafletjs.com/) and has been coded with extensibility in mind : addons using LocateAnything
in conjunction with another WP plugin  typically are less than 300 lines of code.  
* **Use just any taxonomy as a filter** : Easily use any taxonomy (custom taxonomies or regular taxonomies) to filter your maps.
* **Supports Custom Post Types** : Most of the WordPress plugins only support posts and pages. Not this one!Total support for any custom post type and their taxonomies!
* **Fully customizable marker icons** : You can define a custom marker icon for each location or choose to use the same marker for the whole map. It’s up to you! Choose between the plugin’s predefined marker icons, create your own markers using Ionicon or just use any image from the media library. Total flexibility!
* **Customizable Map Overlay** : Choose between 4 different map overlays… Or use any overlay you want with the Custom Overlay Addon
* **Additional fields** : Need to display a specific info on the map? Create additional fields! Additional fields are custom fields specifically designed to be displayed on the map. Let’s say your map is about coffeshops and you want to show the opening hours and the name of the nearest subway station? Create 2 additional fields : openingHours and nearestSubway. Done! Those informations are ready to be displayed in the marker list and the tooltips.
* **Fully customizable tooltips** : Customize the tooltips EXACTLY as you want them : HTML, audios, videos, images,post content… Tooltips can display nearly anything. Customize the tooltip template for each marker independently, you have total control on the information that appears…or use a tooltip preset for instant styling!
* **Fully customizable marker list** : Customize the marker list as you please : HTML, audios, videos, images,post content…
* **Ready to use** : Need a map NOW? choose a map Layout, click, you are done! Not exactly what you had in mind? No worries!Just edit the layout CSS directly in the
admin!
* **Robust** : LocateAnything has been tested with 10 000 markers containing images, videos and audio…and still ran smoothly
* **Responsive & Touch optimized** 

And many other features :

* Google Places searchbox
* import and style KML files
* Detection of user’s location
* Rounded corners / Squared corners tooltips
* Map Localization :Choose your map language (beta)
* Optional cache system : ready to handle thousands of markers
* Marker Clustering
* Addons for Buddypress, Advanced filters, new marker icons, new map layouts

[check the LocateAnything website](http://www.locate-anything.com/)


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/locateanything` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings in LocateAnything > Options to configure the plugin

See our website for more informations on how to configure the plugin : http://www.locate-anything.com/documentation


== Screenshots ==

1. Fully customizable tooltips : HTML,display video, audio, image
2. Use case 1 : Real estate locator
3. Use case 2 : searchable Community map (with User or Buddypress addon)

== Changelog ==

= 1.0 =
* First version
= 1.1.0 =
* heavily modified Addon_Helper class
* css fixes
* added hide splashscreen option
= 1.1.1 =
* added addon upgrader class
= 1.1.2 =
* added "Powered By" text
= 1.1.4 =
* added KML import function
* fixed some minor bugs
= 1.1.5 =
* added hooks
* fixed Google Places selector bug
= 1.1.6 =
* added hooks for compat addons
= 1.1.7 =
* optimized loading of styles and scripts
= 1.1.71 =
* bug fix
= 1.1.72 =
* Sometimes the preview mode was in conflict with 3rd party plugins. This update should fix that problem
= 1.1.8 =
Added nice textarea editors
= 1.1.93 =
Added radio button, select multiple as filters