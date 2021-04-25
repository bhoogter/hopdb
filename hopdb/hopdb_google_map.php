<?php

function hopdb_shortcode_google_map($atts)
{
    $apiKey = "AIzaSyDiOOLlEd48d8RtWGLHg23H2ltziYT6198";

    $s = "";

    $s = "<style>
    #map {
     height: 500px;
     width: 600px;
     overflow: hidden;
     float: left;
     border: thin solid #333;
     }
    #capture {
     height: 500;
     width: 600px;
     overflow: hidden;
     float: left;
     background-color: #ECECFB;
     border: thin solid #333;
     border-left: none;
     }
  </style>";

    $s .= "<div id='map'></div>
        <div id='capture'></div>";
        
    $s .= "<script>
        var map;
        var src = 'http://www.ihopnetwork.com/wp-content/plugins/hopdb/ihopnetwork.kml';
  
        function initMap() {
          map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng(38.9238005, -94.5491979),
            zoom: 4,
            mapTypeId: 'terrain'
          });
  
          var kmlLayer = new google.maps.KmlLayer(src, {
            suppressInfoWindows: true,
            preserveViewport: false,
            map: map
          });
          console.dir(kmlLayer);
          kmlLayer.addListener('click', function(event) {
            var content = event.featureData.infoWindowHtml;
            var testimonial = document.getElementById('capture');
            testimonial.innerHTML = content;
          });
        }
      </script>";
      
      $s .= "<script async src='https://maps.googleapis.com/maps/api/js?key=$apiKey&callback=initMap'></script>";

    return $s;
}
