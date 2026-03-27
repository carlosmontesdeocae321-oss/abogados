
var google;

function init() {
    // Basic options for a simple Google Map
    // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
    // Fallback center (New York)
    var myLatlng = new google.maps.LatLng(40.7128, -74.0060);
    // 39.399872
    // -8.224454
    
    var mapOptions = {
        // How zoomed in you want the map to start at (always required)
        zoom: 12,

        // The latitude and longitude to center the map (always required)
        center: myLatlng,

        // How you would like to style the map. 
        scrollwheel: false,
        styles: [{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"},{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#f49935"}]},{"featureType":"road.highway","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"hue":"#fad959"}]},{"featureType":"road.arterial","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"hue":"#a1cdfc"},{"saturation":30},{"lightness":49}]}]
    };

    

    // Get the HTML DOM element that will contain your map 
    // We are using a div with id="map" seen below in the <body>
    var mapElement = document.getElementById('map');

    // Create the Google Map using out element and options defined above
    var map = new google.maps.Map(mapElement, mapOptions);
    
    // Dirección de ejemplo (se muestra en el bloque de dirección también)
    var addresses = ['198 West 21th Street, New York NY 10016'];

    for (var x = 0; x < addresses.length; x++) {
        (function(addr){
            $.getJSON('https://maps.googleapis.com/maps/api/geocode/json?address='+encodeURIComponent(addr)+'&sensor=false', null, function (data) {
                if (!data || !data.results || !data.results[0]) return;
                var p = data.results[0].geometry.location;
                var latlng = new google.maps.LatLng(p.lat, p.lng);
                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    icon: 'images/loc.png'
                });
                var info = new google.maps.InfoWindow({
                    content: '<div style="font-size:14px;"><strong>Dirección</strong><br>'+addr+'</div>'
                });
                marker.addListener('click', function(){
                    info.open(map, marker);
                });
                // center map at first result
                map.setCenter(latlng);
                map.setZoom(14);
            });
        })(addresses[x]);
    }
    
}
google.maps.event.addDomListener(window, 'load', init);