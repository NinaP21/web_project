/* This file contains the JS scripts that run when a logged-in user wants to see
   information like their visits, covid registrations or visits that may have exposed them to COVID-19 */

/* This script gets executed when the user chooses to see their history data of COVID-19 registrations. */
$(document).ready( function() {
$("#registrationTab").click( function() {
/* The tab that contains the COVID-19 registrations becomes active and its context
   is shown, while the tab that contains the user's visits becomes inactive. */
  $("#registrationTab").addClass("activeTab");
  $("#registrationTab").removeClass("inactiveTab");

  $("#visitTab").addClass("inactiveTab");
  $("#visitTab").removeClass("activeTab");

  $("#visits-tab").addClass("inactiveTab_content");
  $("#visits-tab").removeClass("activeTab_content");

  $("#resistrations-tab").addClass("activeTab_content");
  $("#resistrations-tab").removeClass("inactiveTab_content");

/* AJAX request to get the necessary data */
  $.ajax( {
    type: "POST",
    url:"includes/covid_registrations.inc.php",
    dataType: 'json',
    success: function(data) {
/* COVID-19 registrations will be shown 5 per panel-page */
      let str = "<div class=\"panel panel-default\">";
/* header of table */
      str += "<table class=\"table\"> <tr> <th>#</th> <th>Date of Diagnosis</th> </tr>";
/* case where COVID-19 registrations are <= 5 */
      if ( data.length <= 5 ) {
        for (let i = 1; i <= data.length; i++) {
          str += "<tr> <td>" + i + "</td> <td>" + String(data[i-1]) + "</td> </tr>";
        }
        str += "</table> </div>";
        $("#resistrations-tab").html(str);
      }
/* If COVID-19 registrations are > 5 then we keep a variable called iterator
   that shows the number of array data[] element that we show first.
   Here iterator is 1 because we start from the first element of the returned array. */
      else {
        let iterator = 1;
        for (let i = 1; i <= 5; i++) {
          str += "<tr> <td>" + i + "</td> <td>" + String(data[i-1]) + "</td> </tr>";
        }
        str += "</table> </div>";
        $("#resistrations-tab").html(str);
/* Create Next button and call an onclick function with iterator as parameter. */
        str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_registrations_button(" + iterator + ")\">Next</button>"
        $("#resistrations-tab").append(str);
      }
    }
  } );
} );
} );

/* This function is called when the user clicks the button Next in COVID-19 registrations page. */
function seemore_registrations_button(iterator) {
$.ajax( {
  type: "POST",
  url: "includes/covid_registrations.inc.php",
  dataType: 'json',
  success: function(data) {
/* Update the starting point of for loop variable. */
    let i = 1 + iterator*5;
    let str = "<div class=\"panel panel-default\">";
    str += "<table class=\"table\"> <tr> <th>#</th> <th>Date and Time</th> </tr>";
    if ( data.length - iterator*5 <= 5 ) {
      for ( i ; i <= data.length; i++) {
        str += "<tr> <td>" + i + "</td> <td>" + data[i-1] + "</td> </tr>";
      }
      str += "</table> </div>";
      $("#resistrations-tab").html(str);
      if ( iterator - 1 >= 0 ) {
        iterator = iterator - 1;
/* Create Previous button and call the onclick function with iterator as parameter,
   only if there are previous data to be shown. */
        str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_registrations_button(" + iterator + ")\">Previous</button>"
        $("#resistrations-tab").append(str);
      }
    }
    else {
/* Show next 5 elements */
      for ( i ; i <= iterator*5 + 5; i++) {
        str += "<tr> <td>" + i + "</td> <td>" + data[i-1] + "</td> </tr>";
      }
      str += "</table> </div>";
      $("#resistrations-tab").html(str);
      if ( iterator - 1 >= 0 ) {
        iterator = iterator - 1;
/* Here we create both Next and Previous buttons with different values of the iterator variable */
        str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_registrations_button(" + iterator + ")\">Previous</button>"
        $("#resistrations-tab").append(str);
        iterator = iterator + 2;
        str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_registrations_button(" + iterator + ")\">Next</button>"
        $("#resistrations-tab").append(str);
      }
      else {
/* In this case we are on the first page so there cannot be a Previous button. */
        iterator = iterator + 1;
        str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_registrations_button(" + iterator + ")\">Next</button>"
        $("#resistrations-tab").append(str);
      }
      }
    }
} );
}


/* This script is the same thing for the Visits tab in user's profile.
   It gets executed when the document is ready and when the user clicks
   to see their history data of visits. */
$(document).ready( function() {
    $.ajax( {
      type: "POST",
      url:"includes/profile_visits.inc.php",
      dataType: 'json',
      success: function(data) {
/* Here the AJAX request returns the name of the place and the date&time of the specific visit. */
        let str = "<div class=\"panel panel-default\">";
        str += "<table class=\"table\"> <tr> <th>#</th> <th>Place</th> <th>Date and Time</th> </tr>";
/* case where user's visits are <= 5 */
        if ( data[0].length <= 5 ) {
          for (let i = 1; i <= data[0].length; i++) {
            str += "<tr> <td>" + i + "</td> <td>" + data[0][i-1] + "</td> <td>" + data[1][i-1] + "</td> </tr>";
          }
          str += "</table> </div>";
          $("#visits-tab").html(str);
        }
        else {
/* If user's visits are > 5 then we keep a variable called iterator
   that shows the number of array data[] element that we show first.
   Here iterator is 1 because we start from the first element of the returned array. */
          let iterator = 1;
          for (let i = 1; i <= 5; i++) {
            str += "<tr> <td>" + i + "</td> <td>" + data[0][i-1] + "</td> <td>" + data[1][i-1] + "</td> </tr>";
          }
          str += "</table> </div>";
          $("#visits-tab").html(str);
/* Create Next button and call an onclick function with iterator as parameter. */
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_visits_button(" + iterator + ")\">Next</button>"
          $("#visits-tab").append(str);
        }
      }
    } );

    $("#visitTab").click( function() {
/* The tab that contains the user's visits becomes active and its context
   is shown, while the tab that contains the COVID-19 registrations becomes inactive. */
      $("#visitTab").addClass("activeTab");
      $("#visitTab").removeClass("inactiveTab");

      $("#registrationTab").addClass("inactiveTab");
      $("#registrationTab").removeClass("activeTab");

      $("#visits-tab").addClass("activeTab_content");
      $("#visits-tab").removeClass("inactiveTab_content");

      $("#resistrations-tab").addClass("inactiveTab_content");
      $("#resistrations-tab").removeClass("activeTab_content");
  } );
} );

/* This function is called when the user clicks the button Next in user's visits page.
   It keeps the same logic as in seemore_registrations_button() */
function seemore_visits_button(iterator) {
  $.ajax( {
    type: "POST",
    url: "includes/profile_visits.inc.php",
    dataType: 'json',
    success: function(data) {
/* Update the starting point of for loop variable. */
      let i = 1 + iterator*5;
      let str = "<div class=\"panel panel-default\">";
      str += "<table class=\"table\"> <tr> <th>#</th> <th>Place</th> <th>Date and Time</th> </tr>";
      if ( data[0].length - iterator*5 <= 5 ) {
        for ( i ; i <= data[0].length; i++) {
          str += "<tr> <td>" + i + "</td> <td>" + data[0][i-1] + "</td> <td>" + data[1][i-1] + "</td> </tr>";
        }
        str += "</table> </div>";
        $("#visits-tab").html(str);
        if ( iterator - 1 >= 0 ) {
          iterator = iterator - 1;
/* Create Previous button and call the onclick function with iterator as parameter,
   only if there are previous data to be shown. */
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_visits_button(" + iterator + ")\">Previous</button>"
          $("#visits-tab").append(str);
        }
      }
      else {
/* Show next 5 elements */
        for ( i ; i <= iterator*5 + 5; i++) {
          str += "<tr> <td>" + i + "</td> <td>" + data[0][i-1] + "</td> <td>" + data[1][i-1] + "</td> </tr>";
        }
        str += "</table> </div>";
        $("#visits-tab").html(str);
        if ( iterator - 1 >= 0 ) {
          iterator = iterator - 1;
/* Here we create both Next and Previous buttons with different values of the iterator variable */
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_visits_button(" + iterator + ")\">Previous</button>"
          $("#visits-tab").append(str);
          iterator = iterator + 2;
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_visits_button(" + iterator + ")\">Next</button>"
          $("#visits-tab").append(str);
        }
        else {
/* In this case we are on the first page so there cannot be a Previous button. */
          iterator = iterator + 1;
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_visits_button(" + iterator + ")\">Next</button>"
          $("#visits-tab").append(str);
        }
        }
      }
  } );
}


/* This script gets exetuted as soon as the document gets loaded.
   It creates an AJAX request to get the data needed for the table in
   COVID AWARENESS page. */
$(document).ready( function() {
  $.ajax( {
    type: "POST",
    url:"includes/exposure.inc.php",
    dataType: 'json',
    success: function(data) {
/* The returned data contain two arrays. The first one has the name of place and
   the second one has the date&time of the suspicious visit. */
      let str = "<table class=\"table\"> <tr> <th>#</th> <th>Place</th> <th>Date and Time</th> </tr>";
/* case where user's suspicious visits are <= 5 */
      if ( data[0].length <= 5 ) {
        for (let i = 1; i <= data[0].length; i++) {
          str += "<tr> <td>" + i + "</td> <td>" + data[0][i-1] + "</td> <td>" + data[1][i-1] + "</td> </tr>";
        }
        $("#covid-exposure-table").html(str);
      }
      else {
/* As above, if user's suspicious visits are > 5 then we keep a variable called iterator
   that shows the number of array data[] element that we show first.
   Here iterator is 1 because we start from the first element of the returned array. */
        let iterator = 1;
        for (let i = 1; i <= 5; i++) {
          str += "<tr> <td>" + i + "</td> <td>" + data[0][i-1] + "</td> <td>" + data[1][i-1] + "</td> </tr>";
        }
        $("#covid-exposure-table").html(str);
/* Create Next button and call an onclick function with iterator as parameter. */
        str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_covid_visits_button(" + iterator + ")\">Next</button>"
        $("#covid-exposure-table").append(str);
      }
    }
  } );
} );


/* This is the onclick function that gets called when the user wants to see
   the next page with their visits that may have exposed them to COVID-19. */
function seemore_covid_visits_button(iterator) {
  $.ajax( {
    type: "POST",
    url: "includes/exposure.inc.php",
    dataType: 'json',
    success: function(data) {
      let str = "<table class=\"table\"> <tr> <th>#</th> <th>Place</th> <th>Date and Time</th> </tr>";
/* Update the starting point of for loop variable. */
      let i = 1 + iterator*5;
      if ( data[0].length - iterator*5 <= 5 ) {
        for ( i ; i <= data[0].length; i++) {
          str += "<tr> <td>" + i + "</td> <td>" + data[0][i-1] + "</td> <td>" + data[1][i-1] + "</td> </tr>";
        }
        $("#covid-exposure-table").html(str);
        if ( iterator - 1 >= 0 ) {
          iterator = iterator - 1;
/* Create Previous button and call the onclick function with iterator as parameter,
   only if there are previous data to be shown. */
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_covid_visits_button(" + iterator + ")\">Previous</button>"
          $("#covid-exposure-table").append(str);
        }
      }
      else {
/* Show next 5 elements */
        for ( i ; i <= iterator*5 + 5; i++) {
          str += "<tr> <td>" + i + "</td> <td>" + data[0][i-1] + "</td> <td>" + data[1][i-1] + "</td> </tr>";
        }
        $("#covid-exposure-table").html(str);
        if ( iterator - 1 >= 0 ) {
          iterator = iterator - 1;
/* Here we create both Next and Previous buttons with different values of the iterator variable */
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_covid_visits_button(" + iterator + ")\">Previous</button>"
          $("#covid-exposure-table").append(str);
          iterator = iterator + 2;
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_covid_visits_button(" + iterator + ")\">Next</button>"
          $("#covid-exposure-table").append(str);
        }
        else {
/* In this case we are on the first page so there cannot be a Previous button. */
          iterator = iterator + 1;
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_covid_visits_button(" + iterator + ")\">Next</button>"
          $("#covid-exposure-table").append(str);
        }
        }
      }
  } );
}
