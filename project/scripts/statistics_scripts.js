/* This file contains scripts that are executed when admin visits the
   STATISTICS page.*/

/* This script implements an AJAX request to fetch the necessary data
   for the statistics in the left wrapper of the page. */
$(document).ready( function() {
  $.ajax( {
    type: "POST",
    url:"includes/admin-statistics.inc.php",
    dataType: 'json',
    success: function(data) {
/* The returned data contain three values: total number of visits,
   total number of COVID-19 cases and total number of visits
   registered by people with COVID-19 */
      let str = data[0];
      $("#nr-visits").html(str);
      str = data[1];
      $("#nr-covid-cases").html(str);
      str = data[2];
      $("#nr-covid-visits").html(str);
      }
  } );
} );


/* This script is executed when the document is loaded or when the admin clicks
   to see the ranking of POIs according to number of user's visits. */
$(document).ready( function() {
  $.ajax( {
    type: "POST",
    url:"includes/poi_types_ranking.inc.php",
    dataType: 'json',
    success: function(data) {
      let str = "<div class=\"panel panel-default\">";
      str += "<table class=\"table\"> <tr> <th>Type of place</th> <th>Number of visits</th> </tr>";
/* We will display up to 5 types of POIs on every page. */

/* Case when we have up to 5 types of POIs. */
      if ( data[0].length <= 5 ) {
/* If a type of POI has the symbol _, then we replace it with a space. */
        $.each(data[0], function(index,element){
          str += "<tr> <td>" + index.replace(/_/g, " ") + "</td> <td>" + element + "</td> </tr>";
        } );
        str += "</table> </div>";
        $("#user_visits").html(str);
      }
      else {
/* data_index is the index of "Number of users' visits" in returned data fron the AJAX request
   counter helps with replace() function below
   iterator represents the number of array data[0] element that we show first.
   Here iterator is 1 as we start from the first element.*/
          let data_index = 0;
          let counter = 1;
          let iterator = 1;
/* Replace symbol _ with a space in the five types of POIs that will be shown in this page. */
          $.each(data[0], function(index,element){
            str += "<tr> <td>" + index.replace(/_/g, " ") + "</td> <td>" + element + "</td> </tr>";
            if (counter == 5) {
             return false;
            } else {
             counter++;
            }
          } );
          str += "</table> </div>";
          $("#user_visits").html(str);
/* If admin clicks the Next button, an onclick function is called with these
   parameters: iterator, so that the program will know what types of POIs to show and
   data_index, in order to know which array to use from the returned data in AJAX request. */
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_types_button(" + iterator + ", " + data_index + ")\">Next</button>"
          $("#user_visits").append(str);
      }
    }
  } );
/* When admin clicks the Number of users' visits,
   this tab becomes active and the Number of active COVID-19 visits
   becomes inactive.*/
  $("#user_visitsTab").click( function() {
    $("#user_visitsTab").addClass("activeTab");
    $("#user_visitsTab").removeClass("inactiveTab");

    $("#active_covid_visitsTab").addClass("inactiveTab");
    $("#active_covid_visitsTab").removeClass("activeTab");

    $("#active_covid_visits").addClass("inactiveTab_content");
    $("#active_covid_visits").removeClass("activeTab_content");

    $("#user_visits").addClass("activeTab_content");
    $("#user_visits").removeClass("inactiveTab_content");
  } );
} );


/* This script is executed when the admin clicks to see the ranking of POIs
   according to number of active COVID-19 visits. */
$(document).ready( function() {
  $("#active_covid_visitsTab").click( function() {
/* When admin clicks the Number of active COVID-19 visits ,
   this tab becomes active and the Number of users' visits
   becomes inactive.*/
    $("#active_covid_visitsTab").addClass("activeTab");
    $("#active_covid_visitsTab").removeClass("inactiveTab");

    $("#user_visitsTab").addClass("inactiveTab");
    $("#user_visitsTab").removeClass("activeTab");

    $("#user_visits").addClass("inactiveTab_content");
    $("#user_visits").removeClass("activeTab_content");

    $("#active_covid_visits").addClass("activeTab_content");
    $("#active_covid_visits").removeClass("inactiveTab_content");

    $.ajax( {
      type: "POST",
      url:"includes/poi_types_ranking.inc.php",
      dataType: 'json',
      success: function(data) {
        let str = "<div class=\"panel panel-default\">";
        str += "<table class=\"table\"> <tr> <th>Type of place</th> <th>Number of visits</th> </tr>";
/* We will display up to 5 types of POIs on every page. */

/* Case when we have up to 5 types of POIs. */
        if ( data[1].length <= 5 ) {
/* If a type of POI has the symbol _, then we replace it with a space. */
          $.each(data[1], function(index,element){
            str += "<tr> <td>" + index.replace(/_/g, " ") + "</td> <td>" + element + "</td> </tr>";
          } );
          str += "</table> </div>";
          $("#active_covid_visits").html(str);
        }
        else {
/* data_index is the index of "Number of active COVID-19 visits" in returned data fron the AJAX request
   counter helps with replace() function below
   iterator represents the number of array data[1] element that we show first.
   Here iterator is 1 as we start from the first element of the returned array.*/
            let data_index = 1;
            let counter = 1;
            let iterator = 1;
/* Replace symbol _ with a space in the first five types of POIs that will be shown on this page. */
            $.each(data[1], function(index,element){
              str += "<tr> <td>" + index.replace(/_/g, " ") + "</td> <td>" + element + "</td> </tr>";
              if (counter == 5) {
               return false;
              } else {
               counter++;
              }
            } );
            str += "</table> </div>";
            $("#active_covid_visits").html(str);
/* If admin clicks the Next button, an onclick function is called with these
   parameters: iterator, so that the program will know what types of POIs to show and
   data_index, in order to know which array to use from the returned data in AJAX request. */
            str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_types_button(" + iterator + ", " + data_index + ")\">Next</button>"
            $("#active_covid_visits").append(str);
        }
      }
    } );
  } );
} );


/* This function is called whenever the administrator clicks Next or Previous
   in the two tables of STATISTICS page that show a ranking of POIs types.
   The two parameters help the function know where to start in the for loop (iterator)
   and what array it should look up from the returned data in the AJAX request(data_index). */
function seemore_types_button(iterator, data_index) {
  $.ajax( {
    type: "POST",
    url: "includes/poi_types_ranking.inc.php",
    dataType: 'json',
    success: function(data) {
/* selector is the variable that stores the id of the HTML element that
   is connected with current execution */
      let selector;
      if ( data_index == 0 ) {
        selector = "user_visits";
      } else if ( data_index == 1 ) {
        selector = "active_covid_visits";
      }
/* update start value */
      let i = 1 + iterator*5;
      let str = "<div class=\"panel panel-default\">";
      str += "<table class=\"table\"> <tr> <th>Type of place</th> <th>Number of visits</th> </tr>";

/* Replace symbol _ with a space for all types of POIs that we want to display. */
      let counter = 0;
      $.each(data[data_index], function(index,element){
        if (counter == iterator*5 + 5) {
          return false;
        }
        else if ( counter >= iterator*5 ) {
          str += "<tr> <td>" + index.replace(/_/g, " ") + "</td> <td>" + element + "</td> </tr>";
          counter++;
        } else {
          counter++;
        }
      } );
      str += "</table> </div>";
      $('#' + selector).html(str);
/* The following condition means that there are more elements to show that those current 5 types of POIs */
      if ( counter == iterator*5 + 5 ) {
/* In this case we are not on the first page and there are more elements to show,
   so we create both Next and Previous buttons. */
        if ( iterator - 1 >= 0 ) {
          iterator = iterator - 1;
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_types_button(" + iterator + ", " + data_index + ")\">Previous</button>"
          $('#' + selector).append(str);
          iterator = iterator + 2;
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_types_button(" + iterator + ", " + data_index + ")\">Next</button>"
          $('#' + selector).append(str);
        }
        else {
/* In this case, we are on the first page but there are more elements to show,
   so we only create a Next button. */
          iterator = iterator + 1;
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_types_button(" + iterator + ", " + data_index + ")\">Next</button>"
          $('#' + selector).append(str);
        }
      } else {
/* In this case there are no more types of POIs to show, so if we are not
   on the first page then we create only a Previous button.*/
        if ( iterator - 1 >= 0 ) {
          iterator = iterator - 1;
          str = "<button type=\"button\" class=\"btn btn-md btn-default seemore\" onclick=\"seemore_types_button(" + iterator + ", " + data_index + ")\">Previous</button>"
          $('#' + selector).append(str);
        }
      }
    }
  } );
}
