
<!DOCTYPE html>
<html>
  <head>
    <title>Event Location</title>
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <h1>Event Location</h1>
    <div id="map"></div>
    <script>

      // In the following example, markers appear when the user clicks on the map.
      // The markers are stored in an array.
      // The user can then click an option to hide, show or delete the markers.
      var map;
      var marker;

      function initMap() {
        var lat1 = <?= $_GET['lat']; ?>;
        var lng1 = <?= $_GET['lng']; ?>;
        var ucf = {lat: lat1, lng: lng1};

        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: ucf,
          mapTypeId: 'terrain'

        });

        var marker = new google.maps.Marker({
          position: ucf,
          map: map,
          title: 'Event'
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6rdxll0iRju8QJxyeLKXDOd-8_2ZWrCU&callback=initMap">
    </script>
  </body>
</html>
