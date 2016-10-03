/*
 * L.Control.GoogleAutocomplete - search for an address and zoom to it's location
 * https://github.com/rmunglez/leaflet-google-autocomplete
 */

(function($, undefined) {
L.GoogleAutocomplete = {};

// MSIE needs cors support
jQuery.support.cors = true;

L.GoogleAutocomplete.Result = function (x, y, label) {
    this.X = x;
    this.Y = y;
    this.Label = label;
};

L.Control.GoogleAutocomplete = L.Control.extend({
    options: {
        position: 'topright'
    },

    initialize: function (options) {
        this._config = {};
        if (!options) {
            options = {};
        }
        var optionsTmp = {
            'searchLabel': options.searchLabel || 'search for address...',
            'closeToMeLabel': options.closeToMeLabel || '',
            'notFoundMessage' : options.notFoundMessage || 'Sorry, that address could not be found.',
            'zoomLevel': options.zoomLevel || 13
        }
        L.Util.extend(this.options, optionsTmp);        
        /*$.ajax({
            url: "https://maps.googleapis.com/maps/api/js?v=3&callback=onLoadGoogleApiCallback&sensor=false&libraries=places",
            dataType: "script"
        });*/        
    },

    onAdd: function (map) {
        var $controlContainer = $(map._controlContainer);

        if ($controlContainer.children('.leaflet-top.leaflet-center').length == 0) {
            $controlContainer.append('<div class="leaflet-top leaflet-right"></div>');
            map._controlCorners.topcenter = $controlContainer.children('.leaflet-top.leaflet-right').first()[0];
        }

        this._map = map;
        this._container = L.DomUtil.create('div', 'leaflet-control-googleautocomplete');

        var searchwrapper = document.createElement('div');
        searchwrapper.className = 'leaflet-control-googleautocomplete-wrapper';
        
        var searchbox = document.createElement('input');
        searchbox.id = 'leaflet-control-googleautocomplete-qry';
        searchbox.type = 'text';
        searchbox.placeholder = this.options.searchLabel;
        this._searchbox = searchbox;

        var closetomebox = document.createElement('div');
        closetomebox.id = 'leaflet-control-googleautocomplete-closetome';
        closetomebox.className = 'leaflet-control-googleautocomplete-closetome';
        this._closetomebox = closetomebox;

        $(searchwrapper).append(this._searchbox);
        $(this._container).append(searchwrapper, this._closetomebox);
        $(this._closetomebox).html("<span>"+this.options.closeToMeLabel+"</span>");

        L.DomEvent.addListener(this._container, 'click', L.DomEvent.stop);
        L.DomEvent.disableClickPropagation(this._container);
        
        L.DomEvent.addListener(this._closetomebox, 'click', this._closeToMe, this);
        L.DomEvent.disableClickPropagation(this._closetomebox);
        
        // init google autocomplete
        var autocomplete = new google.maps.places.Autocomplete(this._searchbox);
        autocomplete.setTypes(['geocode']);

        var Me = this;
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                // Inform the user that the place was not found and return.
                $('leaflet-control-googleautocomplete-qry').addClass('notfound');
                return;
            }

            // If the place has a geometry, then update the map
          
            if (place.geometry.location) {
                console.log(place.geometry.location);
                $('leaflet-control-googleautocomplete-qry').removeClass('notfound');
                map.panTo([place.geometry.location.lat(), place.geometry.location.lng()]);
                map.setZoom(Me.options.zoomLevel);
            }
        });        
        
        return this._container;
    },
            
    _closeToMe: function (e) {
        if (navigator.geolocation) {
            var Me = this;
            navigator.geolocation.getCurrentPosition(function(position) {
                Me._map.panTo([position.coords.latitude, position.coords.longitude]);
                Me._map.setZoom(Me.options.zoomLevel);
            });
        }
    },
});
})(jQuery);
