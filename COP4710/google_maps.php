
<!DOCTYPE html>
<html>
  <head>
    <title>Set Event Location</title>
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
      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
    </style>
  </head>
  <body>
    <h1>Set Event Location</h1>
    <div id="floating-panel">
      <input onclick="clearMarkers();" type=button value="Hide Markers">
      <input onclick="showMarkers();" type=button value="Show All Markers">
      <input onclick="deleteMarkers();" type=button value="Delete Markers">
    </div>
    <div id="map"></div>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;sensor=false"
            type="text/javascript"></script>
    <script>

      // In the following example, markers appear when the user clicks on the map.
      // The markers are stored in an array.
      // The user can then click an option to hide, show or delete the markers.
      var map;
      var marker;

      function initMap() {
        var ucf = {lat: 28.6024, lng: -81.2001};

        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 12,
          center: ucf,
          mapTypeId: 'terrain'
        });

        // This event listener will call addMarker() when the map is clicked.
        map.addListener('click', function(event) {
          addMarker(event.latLng);
        });
      }

      // Adds a marker to the map and push to the array.
      function addMarker(location) {
        if (marker == null)
        {
        marker = new google.maps.Marker({
          position: location,
          map: map
        });
        window.location.replace("create_event.php?lat=" + marker.getPosition().lat() + "&lng=" + marker.getPosition().lng());}
      }

      // Sets the map on all markers in the array.
      function setMapOnAll(map) {
          marker.setMap(map);
      }

      // Removes the markers from the map, but keeps them in the array.
      function clearMarkers() {
        setMapOnAll(null);
      }

      // Shows any markers currently in the array.
      function showMarkers() {
        setMapOnAll(map);
      }

      // Deletes all markers in the array by removing references to them.
      function deleteMarkers() {
        clearMarkers();
        marker = null
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6rdxll0iRju8QJxyeLKXDOd-8_2ZWrCU&callback=initMap">
    </script>
  </body>
</html>
