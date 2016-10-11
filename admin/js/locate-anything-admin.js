/* DOCUMENT READY */
jQuery(document).ready(function(){
    jQuery("#locate-anything-additional_fields ul li input").blur(function(){jQuery("#locate-anything-option-additional-field-list").val(LA_serializeAdditionalFieldList())})
   
    /* tabbed navigation */
    jQuery(".nav-tab").click(function(e){
        var active=jQuery(e.target).attr("data-pane");
        if (!active) return;
        jQuery('.locate-anything-map-option-pane').hide();
        jQuery('#locate-anything-map-settings-page-'+active).show();
        jQuery('.nav-tab').removeClass('active');
        jQuery(e.target).addClass('active');
        if(jQuery(e.target).attr("data-animation")) {
          //  jQuery('#map-preview').animate({'height':'100%','width':jQuery(e.target).attr("data-animation")},700)
        }   
    });

/* Refreshing the tags according to the selected source */
  jQuery("#locate-anything-source").change(function(){manage_tag_visibility()});
  manage_tag_visibility();  

/* option page events */
  jQuery("#locate-anything-option-sources").change(function(){manage_addi_field_visibility()});
  manage_addi_field_visibility();
});

/* OPTION PAGE : shows/hide additional fields according to the selected source */
function manage_addi_field_visibility(){
    jQuery(".additional_fields").hide();
    jQuery("#locate-anything-option-sources option:selected").each(function(i,o){
    jQuery("#addi_fields_"+jQuery(o).val()).show();
  });
}

/* shows/hide tags according to the selected source */
function manage_tag_visibility(){
    jQuery(".basic-markup").hide();
    jQuery(".basic-markup-basic").show();
    jQuery("#locate-anything-source option:selected").each(function(i,o){
    jQuery(".basic-markup-"+jQuery(o).val()).show();
    
    
  });
}

/*  OPTION PAGE : Appends a row to the additional field list passed in arg, generates a fieldname and refreshes the list in options*/ 
function LA_appendRow(container_id,post_type){
	var id='locate-anything-additional-field-'+ (new Date).getTime()
jQuery(container_id).append("<li><input type='text' data-post-type='"+post_type+"' name='"+id+"'  id='"+id+"' class='locate-anything-additional-field' placeholder='enter the field  human-readable name'> <input type='button' class='button-admin' id='del-"+id+"' value='delete' onclick='LA_removeRow(\"#"+id+"\")'></li>");
jQuery(container_id+" li input").blur(function(){jQuery("#locate-anything-option-additional-field-list").val(LA_serializeAdditionalFieldList())})
}


/* Serializes Additional Field list */
function LA_serializeAdditionalFieldList(){	
	var arr=new Array();	
	jQuery ("input[class='locate-anything-additional-field']").each(function(index,item){
  	if(jQuery(item).val().length>0) {
        arr.push({
  			"post_type":jQuery(item).attr("data-post-type"),
  			"field_name":""+ jQuery(item).attr("name"),
  			"field_description":jQuery(item).val()
  		});	    
    }
  });
	return JSON.stringify(arr);
}

/* Removes a row from he additional field list */ 
function LA_removeRow(field_id){
	jQuery(field_id).parent().remove();
	jQuery("#locate-anything-option-additional-field-list").val(LA_serializeAdditionalFieldList());
}

    

   /* Geocoding function */    
 function GetLocation() {
            var geocoder = new google.maps.Geocoder();
            var addr_elements=new Array('locate-anything-streetnumber','locate-anything-street','locate-anything-zip','locate-anything-city','locate-anything-state','locate-anything-country');
            var address=new Array();
            for(var i=0;i<addr_elements.length;i++) {
            	address.push(jQuery("input[name='"+addr_elements[i]+"']").val());
            }
            geocoder.geocode({ 'address': address.join(",") }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();
                    jQuery("input[name='locate-anything-lat']").val(latitude);
                    jQuery("input[name='locate-anything-lon']").val(longitude);
                } else {
                    alert("Request failed. Please check the address.");
                }
            });
        };

/* Select2 fn*/
function formatSelect (item) {
  if (!item.id) { return item.text; }
  var $item = jQuery(
    '<span><img src="'+ item.text + '" /></span>'
  );
  return $item;
};

function formatSelect2 (item) {  
  var $item = jQuery(
    '<span style="font-size:37px" class="'+ item.id +'"></span>'
  );
  return $item;
};

function formatSelected2 (item) {  
  var $item = jQuery(
    '<span  style="font-size:37px" class="'+ item.id + '" ></span>'
  );
  return $item;
};

function formatSelected (item) {
  if (!item.id) { return item.text; }
  var $item = jQuery(
    '<span><img src="'+ item.text + '"   /></span>'
  );
  return $item;
};

/* sets up the media uploader */
function initialize_media_uploader(){
      var _custom_media = true,
  _orig_send_attachment = wp.media.editor.send.attachment;
 
  
  jQuery('#locate-anything-kml-file_button').click(function(e) {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = jQuery(this);
    var id = button.attr('id').replace('_button', '');
    _custom_media = true;
    wp.media.editor.send.attachment = function(props, attachment){       
      if ( _custom_media ) {
        jQuery("#"+id).val(attachment.url);        
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    } 
    wp.media.editor.open(button);
    return false;
  });




  jQuery('#locate-anything-marker-type_button').click(function(e) {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = jQuery(this);
    var id = button.attr('id').replace('_button', '');
    _custom_media = true;
    wp.media.editor.send.attachment = function(props, attachment){
       props.size="locate-anything-marker";
      if ( _custom_media ) {
        jQuery("#"+id).val(attachment.url);
        jQuery("#default-marker-media").attr("src",attachment.url);
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    }
 
    wp.media.editor.open(button);
    return false;
  });
 
  jQuery('.add_media').on('click', function(){
    _custom_media = false;
  });

    };


function locate_anything_manage_selector(itemT){
      var  str;
      jQuery(".filter-selector-"+itemT).fadeOut();
      jQuery("#show-filters input[type=checkbox]:checked").each(function(index,item){       
        jQuery(".filter-selector-"+item.value).fadeIn();       
      });
        
      };

function locate_anything_refresh_template_tags(itemT){
      var  str;
      jQuery(".refine-hide-if-"+itemT).fadeOut();
      jQuery(".locate-anything-filter-checkbox:checked").each(function(index,item){       
        jQuery(".refine-hide-if-"+item.value).fadeIn();
        str+="<tr id='"+item.value+"'><td><b>"+item.value+"</b></td><td>|"+item.value+"|</td></tr>";
      });
      jQuery("#filter_fields_notice").html(str);    
      };


function refresh_layout_code(){
    jQuery.ajax({type: "POST", url: AJAX_URL,     
                "data": {
                  "action": "getLayoutCode",
                  "layout_id": jQuery("#locate-anything-map-template option:selected").val(),
                  "map_id": OBJECT_ID,                         
                  }
                  ,success:function(data){
                    jQuery("#layout_editor").html('');
              jQuery("#layout_editor").append("<textarea style='margin-top:1em;height:450px' name=\"locate-anything-map-template-html-"+jQuery("#locate-anything-map-template option:selected").val()+"\">"+JSON.parse(data)+"</textarea>");
                  }
                });
};

/* post datas to preview iframe */
function refresh_preview(){
  var data=jQuery('#locate-anything-class').find('input, select, textarea').serializeArray();     
    jQuery('#map-preview').append('<form action="'+ADMIN_URL+'?post_type=locateanythingmap&locateAnything_preview" method="post" target="map_preview" id="postToIframe"></form>');
    jQuery.each(data,function(index,n){
      //if(n.value.search("'")>-1 || n.value.search('"')>-1)
       n.value=encodeURIComponent(n.value);
        jQuery('#postToIframe').append('<input type="hidden" name="'+n.name+'" value="'+n.value+'" />');
    });
    jQuery('#postToIframe').submit().remove();
};

/* calls the refresh cache fn */
function refresh_cache(){
  jQuery("#result_cache").html("Pending");
  jQuery.ajax({type: "POST", url: AJAX_URL,     
                "data": {
                  "action": "refresh_cache",
                  "map_id": OBJECT_ID,
                  "output" : false                  
                  }
                  ,success:function(){jQuery("#result_cache").html("Done")}
                });
}


 /* Displays the terms allowed for the taxonomy type */
function locate_anything_refresh_taxonomy_terms(taxonomy){
          jQuery.ajax({
                type: 'POST',
                url: AJAX_URL,
                data: {
                  "action": "LAgetTaxonomyTerms",
                  "type": taxonomy,
                  "map_id": OBJECT_ID,
            },
                success: function(data){
                  jQuery("#locate-anything-allowed-filters-"+taxonomy).val(data);
                  var items=JSON.parse(data);
                  for(var i in items){
                    if(items[i].selected) var sel="selected";else var sel='';
                    jQuery("#locate-anything-allowed-filters-value-"+taxonomy).append("<option "+sel+"  value='"+items[i].term_id+"'>"+items[i].name+"</option>");
                  }
                  /* refreshes preview*/
              refresh_preview();  
                }});
      }

function register_range_events(){
      jQuery('.locate-anything-display-filter-').change(function(e){         
        var item=jQuery(e.target).attr("item");                                 
        if(jQuery("#"+jQuery(e.target).attr("id")+" option:selected").val()=="range"){
          jQuery("#range-options-"+item).show();                          
        } else {
          jQuery("#range-options-"+item).hide();
        }
      });
}

function locate_anything_get_addon_filters(){
  jQuery.ajax({
                type: 'POST',
                url: AJAX_URL,
                data: {
                  "action": "LAgetFilters",
                  "type":jQuery('#locate-anything-source').val(),
                  "map_id": OBJECT_ID
            },
                success: function(data){  
                  jQuery("#show-filters").append(data);
                    refresh_preview();
                    register_range_events();
                  }
            });
}

function initialize_marker_selector(html_id){
jQuery("#"+html_id).select2({  templateResult: formatSelect, templateSelection : formatSelected}); 
jQuery("#locate-anything-marker-symbol").select2({  templateResult: formatSelect2, templateSelection : formatSelected2}); 
}