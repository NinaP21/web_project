/* These scripts are related to the map that showes up to a logged in user */

/* This function is called when the logged in user clicks on a suggestion
   that is shown to them. In this case, the selected suggestion fills the
   search field and the list of suggestions disappears. */
function fill(value) {
 $('#search-field').val(value);
 $('#suggestions').hide();
}

/* This script is executed when the logged in user starts typing in the search field
   of the map, so the live search will show them all suggestions on types of POIs. */
$(document).ready( function() {
  $("#search-field").keyup( function() {
    let name = $("#search-field").val();
    if (name == "") {
      $("#suggestions").html("");
    }
    else {
      $.ajax( {
        type: "POST",
        url:"includes/search-suggestions.inc.php",
        data: {
          search: name
        },
        success: function(data) {
            $('#suggestions').html(data).show();
          }
        } );
      }
  } );
} );


/* When the user chooses to register their presence in a POI,
   the "Register my presence" button becomes green and unclickable. */
function presenceSubmit(name) {
  $.ajax( {
    type: "POST",
    url:"includes/presence.inc.php",
    data: {
      place: name
    },
    success: function() {
      $("#presence-submit").css("background-color","#7FFF00");
      $("#presence-submit").html("Successful submit");
      $("#presence-submit").prop("disabled", true);
    }
  } );
}


/* When a user wants to submit their estimation of people in a POI,
   an AJAX query is executed that asks if the given value is valid
   (integer and non-negative) and according to the answer given
   from back-end, the appropriate message is shown to the user. */
function submit_estimation(place){
  let estimation = $("#estimation-number").val();
  $.ajax( {
    type: "POST",
    url:"includes/estimation.inc.php",
    data: {
      place: place,
      estimation: estimation
    },
    success: function(data) {
      if (data == true) {
        $("#estimation-submit").css("background-color","#7FFF00");
        $("#estimation-submit").html("Successful submit");
        $("#estimation-submit").prop("disabled", true);
      } else if (data == false) {
        $("#error-msg").html("Please give a valid number of people");
      }
    }
  } );
}


/* This script gets executed when the search button in the search field is clicked,
   so the user wants to see all the relevant POI markers. For that, an AJAX query
   is executed that asks for the necessary POI names, coordinates, estimated population,
   average visitors and percentage of traffic. */
$(document).ready( function() {
/* First declare the coloured markers. */
  var greenIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });

  var orangeIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-orange.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });

    var redIcon = new L.Icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
      });

  $("#search-btn").click( function() {
    let name = $("#search-field").val();
    if (name !== "") {
      $.ajax( {
        type: "POST",
        url:"includes/search-poi.inc.php",
        data: {
          place: name
        },
        dataType: 'json',
        success: function(data) {

          let result = data;
          for (let i = 0; i < result[0].length; i++)
          {
            let result_name = String(result[0][i]);
            let result_lat = result[1][i];
            let result_long = result[2][i];
            let result_population = String(result[3][i]);
            let average_population = String(result[4][i]);
            let percentage = result[5][i];

/* latlng_a is the Latlng object of user's current position. */
            latlng_new = new L.latLng(result_lat, result_long);
            if (latlng_a.distanceTo(latlng_new) < 5000 ) {
              let colorIcon;
              if (percentage <= 32) {
                colorIcon = greenIcon;
              } else if (percentage <= 65) {
                colorIcon = orangeIcon;
              } else {
                colorIcon = redIcon;
              }
              let marker = L.marker([result_lat, result_long], {icon: colorIcon});
              marker.addTo(map);

/* Construct the text for the pop-up. */
              let str = "<p id='place_name'>" + result_name + "</p>" + "Estimated population:" + result_population
                        + "<br>" + "Average visitors:" + average_population;
              if ( latlng_a.distanceTo(latlng_new) <= 20 ) {
                str += "<br><br>";
                str += "<button type='button' id='presence-submit' onclick=\"presenceSubmit('" + result_name + "')\">Record my presence here!</button><br>";
                str += "<br> Current people estimation:<br>";
/* Estimation number input will be of type "number" so that user cannot type any letter or symbol other than dot.
   Check for integer will be performed in back-end script. */
                str += "<input name=\"estimation-number\" type=\"number\" min=\"0\" step=\"1\" id=\"estimation-number\" placeholder=\"Number of people...\">";
                str += "<div style=\"color: red;\" id=\"error-msg\"> </div> <br>";
                str += "<button type='button' name='estimation-submit' id=\"estimation-submit\" onclick=\"submit_estimation('" + result_name + "')\">Submit</button> <br>";
              }
              marker.bindPopup(str);
            }
          }
        }
      } );
    }
  } );
} );
