jQuery(document).ready(function(){   
   
    setup_range_sliders();
});


function setup_range_sliders(){ 
    /*Range slider events */
if( jQuery('.rangeslider'))
  jQuery('.rangeslider').each(function(i,slider){
    var mini,maxi,the_slider;
     mini= parseInt(jQuery('#'+jQuery(slider).attr("id")).attr("min"));
     maxi= parseInt(jQuery('#'+jQuery(slider).attr("id")).attr("max"));
     the_slider=slider;

  jQuery('#'+jQuery(slider).attr("id")).slider({    
    range: true,
    min: mini,
    max:maxi,
    values: [jQuery('#'+jQuery(the_slider).attr("id")).attr("min"), jQuery('#'+jQuery(the_slider).attr("id")).attr("max") ],
    create   : function( event, ui ) {  
        var values=jQuery(this).slider("values");                
           jQuery(this).find('.ui-slider-handle').first().html("<b>"+values[0]+"</b>");
           jQuery(this).find('.ui-slider-handle').last().html("<b>"+values[1]+"</b>"); 
              
    }  ,
    slide: function( event, ui ) {                
           jQuery(this).find('.ui-slider-handle').first().html("<b>"+ui.values[0]+"</b>");
           jQuery(this).find('.ui-slider-handle').last().html("<b>"+ui.values[1]+"</b>");
      current_map.update_markers();
    }
  });

  




});
}
    
 function centerMap(map) {
            var geocoder = new google.maps.Geocoder();
            var addr_elements=new Array('LA_searchbox');
            var address=new Array();
            for(var i=0;i<addr_elements.length;i++) {
            	address.push(jQuery("input[id='"+addr_elements[i]+"']").val());
            }
            geocoder.geocode({ 'address': address.join(",") }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();
                   map.panTo([longitude,latitude]);
                } else {
                    alert("Request failed. Please check the address.");
                }
            });
        };

/* Substitute for decodeURIComponent */
    function decode(s){
    s= s.replace(/%([EF][0-9A-F])%([89AB][0-9A-F])%([89AB][0-9A-F])/gi,
        function(code,hex1,hex2,hex3)
        {
            var n1= parseInt(hex1,16)-0xE0;
            var n2= parseInt(hex2,16)-0x80;
            if (n1 == 0 && n2 < 32) return code;
            var n3= parseInt(hex3,16)-0x80;
            var n= (n1<<12) + (n2<<6) + n3;
            if (n > 0xFFFF) return code;
            return String.fromCharCode(n);
        });
    s= s.replace(/%([CD][0-9A-F])%([89AB][0-9A-F])/gi,
        function(code,hex1,hex2)
        {
            var n1= parseInt(hex1,16)-0xC0;
            if (n1 < 2) return code;
            var n2= parseInt(hex2,16)-0x80;
            return String.fromCharCode((n1<<6)+n2);
        });
    s= s.replace(/%([0-7][0-9A-F])/gi,
        function(code,hex)
        {
            return String.fromCharCode(parseInt(hex,16));
        });
    return s;
}