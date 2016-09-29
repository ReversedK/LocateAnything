var leaflet_filters_class= function (params){

	/**
	 * OBJECT PROPERTIES
	 * 
	 */
	 this.indexTaxonomyTerms= new Array();	
	 this.map=null;
	 this["map-id"]=null;
	 this.markers=new Array();
	 this.inBounds=new Array();
	 this.filters=null;
	 this.markerCluster=null;
	 this.instance_id=null;
	 this.max_nav_item_per_page=params["max_nav_item_per_page"];
	 this.params=params;	 
	 this.filtered_markers=new Array();
	 this.markersData=new Array();

	
	/**
	 * OBJECT METHODS
	 * 
	 */
 
	/**
	 * Gets the filter value according to the element type. 
	 * Note : jQuery(item_id).val() should always do the job but for some reason it doesn't work correctly in some cases
	 * 
	 * @param  {int} filter_id : html ID of the filter element
	 * @return {array} array containing the filter values
	 */
	this.getFilterValue=function(filter_id){	
		var vals=new Array();
		var filter_name=jQuery(filter_id).attr('name');
		if (jQuery(filter_id).is( "select" ) ) {
			if(jQuery(filter_id).attr("multiple")) jQuery(filter_id+" option ").each(function(i, selected){ if(jQuery(selected).attr("selected"))  vals.push(jQuery(selected).val());});
			else vals.push(jQuery(filter_id).val());	
		} else if (jQuery(filter_id).is("input:checkbox") ) {
			jQuery("input[name='"+filter_name+"']:checked").each(function() {vals.push(jQuery(this).val());});
		} else if(jQuery(filter_id).hasClass("rangeslider")) {
			vals.push(jQuery("#"+filter_name).slider("values", 0));
			vals.push(jQuery("#"+filter_name).slider("values", 1));			
		} 
		if(vals.length) return vals;else return false;
	};

	/**
	 * Get JSON data from specified URL and passes it to callback_fn
	 * @param  {string} data_url    : URL of the JSON file
	 * @param  {fn} callback_fn : Callback function
	 * @return {void}            
	 */
	this.getData=function(data_url,callback_fn){	
		var self=this;	
		 jQuery.ajax({	 	
		 	 dataType : 'text',
	         type: 'POST',
	         url: data_url,        
	         success:function(result){
	        	 result=JSON.parse(result);
	        	 /* Stores index  */
	     		self.indexTaxonomyTerms=result["index"];	
	     		callback_fn(result);	
	         	}
	         });
	};


	/**
	 * Updates the visible navigation list with markers visible in the current view
	 * @param  {int} page to show
	 * @return {void}         
	 */
	this.updateNav=function(showPage){
			var html_id='#map-nav-'+this.params["map-id"];
			var self=this;
			
			 /*empties the navlist and add the nav items to the list*/			
			jQuery(html_id).html('');	
			jQuery(html_id).hide();
			var html_id_pagination='#map-nav-pagination-'+this.params["map-id"];				
			var nb_pages=Math.ceil(this.inBounds.length/this.max_nav_item_per_page);			
			jQuery(html_id_pagination).html('');
			/* Create navigation pagination */			
			if(nb_pages>1)for(j=0;j<nb_pages;j++) jQuery(html_id_pagination).append("<div class='locate-anything-page-nav locate-anything-page-nav-"+this.params["map-id"]+"' data-page='"+j+"'>"+(j+1)+"</div>");
			
			/* Sets click event on Nav pagination */
			var self=this;
			jQuery(".locate-anything-page-nav-"+this.params["map-id"]).click(function(e) {				
					var page=jQuery(e.target).attr("data-page");
					if(page) self.updateNav(parseInt(page));			
			});
			/* fills the navlist */	
			var selectedmarkers=this.inBounds.slice();	
			selectedmarkers=selectedmarkers.splice(showPage*this.max_nav_item_per_page,this.max_nav_item_per_page);				
			for (i in selectedmarkers) jQuery(html_id).append(this.template_list(selectedmarkers[i]));			
			jQuery(html_id).fadeIn();	

			/* Set click event on Nav items : show the correspondant marker on the map*/ 
			if(this.params["navlist_event"]=="click") jQuery(html_id+" div[id^='NavMarker-']").click(function(e) {self.centerAndOpenPopup(e)});
			else jQuery(html_id+" div[id^='NavMarker-']").mouseenter(function(e) {self.centerAndOpenPopup(e)});			
		};

	

	this.centerAndOpenPopup=function(e) {				
				var self=this;	
				var html_id='#map-nav-'+this.params["map-id"];													
				var target=jQuery(e.target).closest(jQuery(html_id+" div[id^='NavMarker-']"));
				var marker_id=jQuery(target).attr("data-marker-id");											
				if(!marker_id)	return;	
				/* Open popup and zoom */
				try{
				if(marker_id){
					var openThisMarker=self.markers.filter(function(m){if (m.id == marker_id) return true;else return false;});	
					var marker=openThisMarker[0];

					if(!marker) return;
					
						self.markerCluster.zoomToShowLayer(marker, function () {											
							marker.openPopup();				
							//self.map.panTo(marker.getLatLng());							
							jQuery(html_id+" div[id^='NavMarker-']").removeClass("focus");
			 				jQuery(html_id+" #NavMarker-"+marker.id).addClass("focus");				
						});					
				}	
				} catch (e) {
					console.log("error zoomToShowLayer : "+e);					
				}				
		};


	/**
	 * Accesseur Template affichage item nav
	 * @param  {marker object} marker 
	 * @return {string}  HTML template for item of navigation
	 */
	this.template_list=function(marker) {
		var LatLng=marker.lat+","+marker.lng;	
		return this.template_nav_item(marker,LatLng);	
	};


	/**
	 * Template for Nav item, overridable
	 * @param  {marker object]} marker 
	 * @param  {string} LatLng : comma separated string representing latitude and longitude
	 * @return {string}  HTML template for item of navigation  
	 */
	this.template_nav_item=function(marker,LatLng) {
		return '<div name="NavMarker-' + marker.id
		+ '" id="NavMarker-' + marker.id
				+ '" class="map-nav-item" data-latlng="' + LatLng
				+ '" data-marker-id="' + marker.id + '"><b>' + marker.name
				+ '</b></div>';
	};


	/**
	 * Scroll to nav element passed in argument
	 */

	this.scrollNavTo=function(e){
	 	if(!jQuery("#NavMarker-"+e.id).html()) return;
	 	jQuery("div[id^='NavMarker-']").removeClass("focus");
	 	jQuery("#NavMarker-"+e.id).addClass("focus");
		jQuery("#map-nav").animate({		
	        scrollTop: jQuery("#NavMarker-"+e.id).offset().top
	    }, 2000);
	};


	/**
	 * Creates a marker
	 * @param  {string}  lat        
	 * @param  {string}  lon        
	 * @param  {string}  html      : HTML to display in the tooltip
	 * @param  {object}  properties : Object describing the marker properties
	 * @param  {Boolean|Icon object} myIcon    : icon object or false for standard icon
	 * @return {marker Object}             
	 */
	this.createMarker=function(lat, lon, html, properties,myIcon) {
		var args={}
		if(myIcon) args={icon:myIcon}
			/** Creation du marker */	
		var marker=L.marker([ lat, lon ],args);	
		var self=this;

		/* copies the extra properties in the leaflet object*/
		for(var i in properties) marker[i]=properties[i];
		/** bind html popup */	
		
		//var h=parseInt(Math.floor(jQuery("#map-container-"+self.params["map-id"]).height()*9/10));

		var popup = new L.Popup({'autoPan':false,'maxHeight':0,className:marker.css_class});
		popup.setContent(html);
		marker.bindPopup(popup);

		
		/* Sets click event on marker */
		marker.on("click",function(e){
			self.scrollNavTo(e.target);	
			
		});

		/* Stores the marker in markers array */
		this.markers.push(marker);	
		this.filtered_markers.push(marker);
		return marker;
	};

	/**
	 * Creates a Leaflet map object and configures it according to the arguments passed in the constructor
	 * @return {void}              
	 */
	this.createMap=function() {
		var self=this;
		var options={
			"center": new L.latLng(this.params["initial-lat"],this.params["initial-lon"]),
			"maxZoom":this.params["overlay"]["maxZoom"],
			"minZoom":this.params["overlay"]["minZoom"],
			"scrollWheelZoom":this.params["scrollWheelZoom"],
			"worldCopyJump"	: true		
			};	

		this.map=L.map(this.params["map-container"],options);	
		
		/* updates the navlist with the markers visible on the current view */
		this.map.on('dragend', function() {	
			   	self.getInboundMarkers();
			    self.updateNav();
			});


		this.map.on('popupopen', function(e) {
		    var px = self.map.project(e.popup._latlng); // find the pixel location on the map where the popup anchor is
		    px.y -= e.popup._container.clientHeight*65/100 // find the height of the popup container, divide by 2, subtract from the Y axis of marker location		    
		    self.map.panTo(self.map.unproject(px),{animate: true}); // pan to new center    
});

		/* preview mode events*/
		if(this.params["map-id"]=="preview") {
		 this.map.on("moveend",function(){
		 	var center=self.map.getCenter().toString().replace("LatLng(","").replace(")","");		 	
		 	jQuery("#locate-anything-start-position", window.parent.document).val(center);
		 });

		 this.map.on("zoomend",function(){
		 	var center=self.map.getCenter().toString().replace("LatLng(","").replace(")","");		 	
		 	jQuery("#locate-anything-start-position", window.parent.document).val(center);
		 	jQuery("#locate-anything-start-zoom", window.parent.document).val(self.map.getZoom());

		 });
		}

		var	stylers=[];
		/* sets tile provider */
		if(this.params["overlay"].attribution=="GoogleMaps")  {
			if(this.params["style-hue"]!="#000000") stylers.push({hue:this.params["style-hue"]});
			var styles = [{'featureType': 'all','stylers': stylers}];
			var ggl = new L.Google(this.params["overlay"].url, {mapOptions: {styles: styles}});
			this.map.addLayer(ggl);
		} else {
		var TileProvider=L.tileLayer(this.params["overlay"].url,{attribution : this.params["overlay"].attribution});
		TileProvider.addTo(this.map);
		}
		/* Adds autogeocoding */
		if(this.params["autogeocode"]=='1') this.map.locate({setView:true,maxZoom:this.params["initial-zoom"]});
		/* Adds Google Places */
		if (this.params.googleplaces) this.addGooglePlaces();
		/* loads a KML and styles it*/		
		if (this.params.kml_file) {
			 var kml_handle = omnivore.kml(this.params.kml_file).addTo(this.map);
			 
			 kml_handle.on('ready', function() {
	         this.setStyle({ 
	        	fillColor: self.params.kml_fillColor,
	        	weight: self.params.kml_weight,
	    		opacity: self.params.kml_opacity,
	    		color: self.params.kml_color,
	    		dashArray: self.params.kml_dashArray,
	    		fillOpacity: self.params.kml_fillOpacity
	    		});
        	});
     	}                                                
         
		/* sets up a cluster for this map*/
		this.setUpCluster();
		this.map.setView([ this.params["initial-lat"], this.params["initial-lon"]],this.params["initial-zoom"]);
	};

	/**
	 * Fills inBounds array
	 */
	 this.getInboundMarkers=function(){
	 	if(this.params["display_only_inbound"] == false)  {
	 		this.inBounds = this.filtered_markers;
	 		return;
	 	}
	 	this.inBounds=[];			   
		var bounds = this.map.getBounds(); 
		for(var iz in this.filtered_markers){
			var marker=this.filtered_markers[iz];
			var isInBounds=bounds.contains(marker.getLatLng());
			if (isInBounds) this.inBounds.push(marker);			        
		}	
	 }
	/**
	 * Adds Google Places search box
	 * return : {void}
	 */
	this.addGooglePlaces=function(){
		new L.Control.GoogleAutocomplete().addTo(this.map);
	};

	/**
	 * Set up the Cluster utility
	 * return : {void}
	 */
	this.setUpCluster=function (){	
		var self=this;
		this.markerCluster=L.markerClusterGroup({ chunkedLoading: true});
	};


	/**
	 * Clear the map and render the list of markers passed in argument
	 * @param  {array} markerList : array of marker Objects
	 * @return {void}            
	 */
	this.render_map=function(markerList){
		this.markerCluster.clearLayers();	
		this.markerCluster.addLayers(markerList);
		this.map.addLayer(this.markerCluster);	
		this.getInboundMarkers();
	};


	/**
	 * Apply the declared filters * 
	 * @return {void} 
	 */
	this.apply_filters=function() {		
		var self=this;
		var top=new Array();
		var bottom=new Array();
		//the slice() operation clones the array and returns the reference to the new array.
		this.filtered_markers=this.markers.slice();

		/* reorganizes the filters to have the checkboxes first : the checkboxes have a relation OR, the rest AND*/
		for(var i=0;i<this.filters.length;i++){
			if(jQuery(this.filters[i].html_id).is("input:checkbox")) top.push(this.filters[i]);else bottom.push(this.filters[i])
		}
		this.filters=new Array();
		for(var i =0;i<top.length;i++) this.filters.push(top[i]);
		for(var i =0;i<bottom.length;i++) this.filters.push(bottom[i]);	
		/* Apply the filters */
		for(var i=0;i<this.filters.length;i++){
			this.filter_markers(this.filters[i].html_id,this.filters[i].property_name);		
		}
		return this.filtered_markers;	
	};


	/**
	 * Filtering function : will determine which markers doesn't fit the conditions for the filter passed in argument and eliminate them from this.filtered_markers
	 * @param  {[type]} filter_id     : HTML id of the filter element
	 * @param  {[type]} property_name : property to filter
	 * @return {void}               :
	 */
	this.filter_markers=function(filter_id, property_name) {		
		var filter_value=this.getFilterValue(filter_id);
		console.log(filter_id,filter_value);
		if (filter_value===false) return;	
			/* remove empty elements from filter, if filter empty return */				
			 if(jQuery.isArray(filter_value)) {
				for(var i=filter_value.length-1;i>=0;i--){	 
					/* filter not empty, skip */							
					if(filter_value[i].toString().length>0) continue;				
					/* empty, remove that element */
					if(filter_value.length>1) {									
						 filter_value.splice(i,1);
					}
					else {	/* whole filter is empty, return*/								
						return ;
					}
				}
			}
			var self=this;
			this.filtered_markers=this.filtered_markers.filter(function(m){		
						var found=0;
						if(m[property_name]===null)	m[property_name]='';				
						var array_values_marker=m[property_name].split(",");
						var values_marker=','+m[property_name]+',';	
				  				
						/* apply filter */	

							if(jQuery(filter_id).hasClass("rangeslider")){								
								if(m[property_name]<filter_value[0] || m[property_name]>filter_value[1]) return false;	
							} else if(jQuery.isArray(filter_value) && filter_value.length>0) {		
								/* value is an array*/
								for(var i=0;i<filter_value.length;i++)	if( values_marker.search(','+filter_value[i]+',')!==-1) found++;													
								
								if(jQuery(filter_id).is("input:checkbox")) {if(found==0) return false;}
								else {
									if(found<filter_value.length) return false;
								}
								

							} else if (filter_value.length > 0) {			
								/* value is not an array*/
								if (array_values_marker.filter(function(mk) {if (filter_value == mk) return true; else return false;}).length==0)
								 return false;								
							}  else {
								console.log("Error : "+filter_id,filter_value,filter_value.length);
							}
							return true;
				});		
	}







	/**
	 * sets an event on the selectors of the filters
	 * @param  {array} list_filters : array of filters => { html_id : HTML element id for each filter, property_name : nom de la ppté d'objet à filtrer}
	 * @return {void} 
	 */
	this.register_filters=function(list_filters){		
		var self=this;
		this.filters=list_filters;
		for (i=0;i<this.filters.length;i++){
			if(jQuery(this.filters[i].html_id).is("select")) jQuery(this.filters[i].html_id).change(function(){self.update_markers();});
			else if(jQuery(this.filters[i].html_id).is("input:checkbox")) {
				var filter_name=jQuery(this.filters[i].html_id).attr("name");
				jQuery("input[name='"+filter_name+"']").click(function(){self.update_markers();});
			}
		}
	};

	/**
	 * displays/hide the loader
	 * @param  {boolean} show 
	 * @return {void} 
	 */
	this.showLoader=function(show){
		if(this.params["hide-splashscreen"] == true ) {jQuery('#progress-wrapper').hide();return;}
		if(show) {
			jQuery("#"+this.params["map-container"]).append('<div id="locate-anything-loader"></div>');		
		} else {	
			// terminate the progressbar	
			this.updateProgressBar(1,1,1000);
			jQuery('#locate-anything-loader').remove();
		}
	}

	/**
	 * This routine is called each time a filter is selected. 
	 * Applies the filters, recreates navlist and redraws the map
	 * Note : in a timeout to prevent the loader animation from freezing right from the start
	 * @return {void}
	 */
	this.update_markers=function(){		
				this.showLoader(true);
				var self=this;
				setTimeout(function(){					
					var visible_markers=self.apply_filters();
					self.render_map(visible_markers);
					self.updateNav();
					self.showLoader(false);
				},400);		
	};

	/**
	 * updates the progress bar
	 * @param  {int} processed   number of "chunks" processed
	 * @param  {int} total       number of "chunks" total
	 * @param  {int} elapsed     time elapsed (deprecated)
	 * @return {void}            
	 */
	this.updateProgressBar=function(processed, total, elapsed) {	
			var progress=document.getElementById('progress-'+this.params["map-id"]);
			var progressBar=document.getElementById('progress-bar-'+this.params["map-id"]);		 	
				if (elapsed > 500) {
					// if it takes more than a second to load, display the progress bar:
					progress.style.display='block';
					jQuery("#progress-wrapper").show();
					progressBar.style.width=Math.round(processed/total*100) + '%';
				}
				if (processed === total) {
					// all markers processed - hide the progress bar:
					progress.style.display='none';
					jQuery("#progress-wrapper").hide();
				}
			};

	/**
	 * Retrieves the taxonomy term in the data index 
	 * @param  {string} taxonomy taxonomy name
	 * @param  {string} terms    string or comma separated string of term ids
	 * @return {string}          string or comma separated string of term names, '' on error
	 */
	this.translateTaxonomy=function(taxonomy,terms){	
		if(!terms || !taxonomy) return "none";	
		term_id=terms.split(",");
		if(jQuery.isArray(term_id) && term_id.length>1){		
			var t=new Array();
			for(i in term_id){
				var term=term_id[i];
				if(this.indexTaxonomyTerms[taxonomy][term]) t.push(this.indexTaxonomyTerms[taxonomy][term]);
			}		
			return t.join(", ");
		} else {		 
			try {			
				 return this.indexTaxonomyTerms[taxonomy][term_id[0]];			
			}
			catch(e){console.log(e);return '';}		
		}
	};

	/**
	 * Retrieves the icon from the data index
	 * @param  {int} marker_icon_id 
	 * @return {leaflet icon|false}  returns a leaflet icon object or false on failure
	 */
	this.getMarkerIcon=function(marker_icon_id){	
		
		if(!marker_icon_id) return;	
		
		for(i in this.indexTaxonomyTerms["markers"]) {	
				if(this.indexTaxonomyTerms["markers"][i].id==marker_icon_id) {					
					var selected_icon=this.indexTaxonomyTerms["markers"][i];
					/* ionicons */
					if(marker_icon_id.indexOf("ion")==0){						
							 return L.AwesomeMarkers.icon({prefix:"ion", icon:selected_icon["symbol"] ,iconColor:selected_icon["symbol-color"], markerColor: selected_icon["marker-color"] });

					}
					else 							
						return L.icon({
					    iconUrl:selected_icon.url,
					    iconRetinaUrl: '',
					    iconSize: [selected_icon.width, selected_icon.height],
					    iconAnchor: [selected_icon.width/2,selected_icon.height],
					    popupAnchor: [0, 0-selected_icon.height-5],
					    shadowUrl: selected_icon.shadowUrl,
					    shadowRetinaUrl: '',
					    shadowSize: [selected_icon.shadowWidth,selected_icon.shadowHeight],
					    shadowAnchor: [selected_icon.width/2-10,selected_icon.height]
					}); 	
			}
			}

				return false;			
	};
}