<!-- The first page shown to the user when they log in -->

<!-- Create the search box so the user can search for the type of POI -->
  <div class="input-group">
        <span class="input-group-btn" id="search-btn">
          <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
        </span>
        <input type="text" id="search-field" class="form-control" placeholder="Search for...">
  </div>

<!-- Here is the block that the suggestions of the live search will appear -->
<div id="suggestions"> </div>

<!-- Creation of block for the map -->
<div id="map"></div>

<script>

/* Default initial position is Patras center */
      var map = L.map('map',
                {center: [38.246639, 21.734573], //Patras center
                zoom: 18});

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
          attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'})
          .addTo(map);

/* Ask for the computer's location */
      L.Control.geocoder().addTo(map);
      if (!navigator.geolocation) {
          console.log("Your browser doesn't support geolocation feature!");
      } else {
              navigator.geolocation.getCurrentPosition(getPosition);
      };
      let lat, long;
      var root_marker, circle;
      var latlng_a;

/* Create latlng_a object for current user's location.
   This object is global so that the other scripts can
   access its coordinates, to compute distance */
      function getPosition(position) {
          //lat = 38.2499822;
          //long = 21.7379614;

          lat = position.coords.latitude;
          long = position.coords.longitude;
          latlng_a = new L.latLng(lat, long);


/* Calculate latitude, longitude and radius for new circle and marker */
          root_marker = L.marker([lat, long]).addTo(map);
          circle = L.circle([lat, long], 5000).addTo(map);

          root_marker.bindPopup("This is your current location");

/* Add marker and circle to map */
          var featureGroup = L.featureGroup([root_marker, circle]).addTo(map);
          map.fitBounds(circle.getBounds());
      }

</script>
