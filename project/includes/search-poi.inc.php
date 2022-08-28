<?php
/* This code gets executed when a logged-in user selects a type of POI
   in the search field of their personal map and clicks the search button,
   so an AJAX query is created that asks for all POIs of that type. */

/* Make sure that this code is executed only when the AJAX query has passed
   the necessary information, that is the type of POIs. */

if ( isset($_POST['place']) ) {

/* First connect to the database and get current session,
   in order to access the superglobal variables of $_SESSION[]. */
  require 'dbh.inc.php';
  session_start();
  $uid = $_SESSION['userId'];
  
/* Declare the arrays that will be the answer to the AJAX query.
   $result_name will contain the names of POIs
   $result_lat and $result_lng will contain the coordinates of POIs
   $result_population will contain the population estimation for the next two hours and
   $average_population will contain the average population according to users' estimations
   $percentage will contain the percentage of estimated traffic in the specific POI */
  $result_name = array();
  $result_lat = array();
  $result_lng = array();
  $result_population = array();
  $average_population = array();
  $percentage = array();

/* Make $type name compatible with its database format */
  $type = str_replace(' ', '_', $_POST['place']);
  $sql1 = "SELECT * FROM poi_type WHERE type = '$type'";
  $exec1 = mysqli_query($conn, $sql1);

  while ( $tmp = mysqli_fetch_assoc($exec1) ) {

/* Find every POI of that type */
    $result_id = $tmp['id'];
    $sql2 = "SELECT * FROM points_of_interest WHERE id = '$result_id'";
    $exec2 = mysqli_query($conn, $sql2);
    if ( $result1 = mysqli_fetch_assoc($exec2) ) {

/* For each POI of that type save its name and coordinates. */
      array_push($result_lat, $result1['coordinates_lat']);
      array_push($result_lng, $result1['coordinates_lng']);
      array_push($result_name, $result1['name']);

/* Set right timezone for date() function
   format 'l' means that we have a full textual representation of the day of the week
   format 'H' means that we have a 24-hour format of the current hour */
      date_default_timezone_set("Europe/Athens");
      $current_day = date('l');
      $current_hour = date('H');
      $poi_id = $result1['id'];

/* Find the population of that POI in the next two hours. */
      $sql2 = "SELECT * FROM popular_times_of_pois WHERE id = '$poi_id' AND day LIKE '$current_day'";
      $exec2 = mysqli_query($conn, $sql2);
      $hour = $current_hour;
      $day = $current_day;
      if ( $result2 = mysqli_fetch_assoc($exec2) ) {
        $population = 0;
        for ($i=1; $i<=2 ; $i++) {
/* We are at the end of the day so change day and execute again the SQL query. */
          if ( $hour == 23  ) {
            $hour = 0;
            $current_day = date('l', strtotime(' +1 day'));
            $exec2 = mysqli_query($conn, $sql2);
          }
          else {
            $hour++;
          }
          $hour = sprintf("%02d", $hour);
          $current_arg = 'h'.$hour;
          $population += $result2[$current_arg];
        }
        array_push($result_population, $population);

/* Find percentage of estimated traffic in the specific POI.
   First, find the maximum population of this POI and then compute
   the percentage given the $population that was calculated before. */
        $max = 0;
        $sql3 = "SELECT * FROM popular_times_of_pois WHERE id = '$poi_id'";
        $exec3 = mysqli_query($conn, $sql3);
        while ( $tmp3 = mysqli_fetch_assoc($exec3) ){
          for ($i=0; $i <= 23 ; $i++) {
            $index = sprintf("%02d", $i);
            $hour = 'h'.$index;
            if ( $tmp3[$hour] > $max ) {
              $max = $tmp3[$hour];
            }
          }
        }
        $percentage_tmp = (100 * $population) / (2 * $max);
        array_push($percentage, $percentage_tmp);
      }


/* Search for all recorded visits that happened at this particular POI
   and specifically for those made by other users in the previous 2 hours.
   format 'o-m-d H:i:s' is like 2022-05-22 22:16:05 */
      $current_hour = date('o-m-d H:i:s');
      $current_hour = date_create($current_hour);
      $visitors = 0;
      $there_is_presence = false;


/* Find all visits that happened in this place from other users in the past two hours. */
      $sql4 = "SELECT * FROM presence WHERE poi_id = '$poi_id' AND user_id <> '$uid'";
      $exec4 = mysqli_query($conn, $sql4);
      while ( $result4 = mysqli_fetch_assoc($exec4) ) {

        $presence_hour = $result4['timestmp'];
        $presence_hour = date_create($presence_hour);
        if ( date_diff($presence_hour, $current_hour)->format('%h') <= 2 ) {
          $there_is_presence = true;
          $visitors++;
        }
      }

/* Check if there was any presence recorded or not. */
      if ( $there_is_presence == false ) {
        array_push($average_population, "no estimation");
      } else {
/* Calculate the average of visitors for the past two hours. */
        $visitors_avg = ceil($visitors/2);
        array_push($average_population, $visitors_avg);
      }
    }
  }

/* Close the connection with the database and answer to the AJAX query
   with the wanted data. */
   mysqli_close( $conn );
   echo json_encode(array($result_name, $result_lat, $result_lng, $result_population, $average_population, $percentage));
}
/* Here we can see that someone is trying to access this page without
   choosing to see a chart, so we redirect them to home page. */
else {
  header("Location: ../index.php");
  exit();
}
