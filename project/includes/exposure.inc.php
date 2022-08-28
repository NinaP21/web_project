<?php
/* This code gets executed when a user logs in, so an AJAX query
   happens to create the table with user's visits that may have
   exposed them to COVID-19, in COVID AWARENESS page.*/

session_start();

/* Make sure that there is a logged-in user */
if ( isset($_SESSION['userId']) ) {

/* First connect to the database */
  require 'dbh.inc.php';
  $user_id = $_SESSION['userId'];

/* Declare auxiliary array and arrays that will send data to AJAX request. */
  $place_id_tmp = array();
  $visit_time = array();
  $place_name = array();

/* Search for all visits made by the logged in user. */
  $sql1 = "SELECT * FROM presence WHERE user_id = '$user_id' ";
  $exec1 = mysqli_query($conn, $sql1);
  while ( $tmp1 = mysqli_fetch_assoc($exec1) ) {
/* $flag ensures that a logged in user's visit will be encountered only once. */
    $flag = true;
    $seven_days_ago = strtotime('-7 days');
    //$seven_days_ago = strtotime('2022-01-01');
    
/* Compare the two timestamps.
   Keep the visits that have happened the past seven days. */
    if ( strtotime($tmp1['timestmp']) >= $seven_days_ago ) {
      $current_place_id = $tmp1['poi_id'];
      $poi_id = $tmp1['poi_id'];
      $user_visit_time = $tmp1['timestmp'];
      $user_visit_time = date_create($user_visit_time);

/* Find all visits that have happened in that place by other users and keep those that
   were registered 2 hours before or after the logged-in user's one.
   If that happens, check if that user has also registered themselves as COVID-19 case
   within 7 days before or after their visit. If yes, then register that logged-in user's
   visit as a suspicious one for exposure to COVID-19. */
      $sql2 = "SELECT * FROM presence WHERE user_id <> '$user_id' AND poi_id = '$poi_id' ";
      $exec2 = mysqli_query($conn, $sql2);
      while ( $flag && $tmp2 = mysqli_fetch_assoc($exec2)  ) {

        $other_user_time = $tmp2['timestmp'];
        $other_user_time = date_create($other_user_time);
        if ( date_diff($other_user_time, $user_visit_time)->format('%h') <= 2 ) {

          $other_user_id = $tmp2['user_id'];
          $sql3 = "SELECT * FROM covid_registration WHERE user_id = '$other_user_id'";
          $exec3 = mysqli_query($conn, $sql3);
          while ( $tmp3 = mysqli_fetch_assoc($exec3) ) {

            $other_user_covid_time = $tmp3['reg_date'];
            $other_user_covid_time = date_create($other_user_covid_time);
            if ( date_diff($other_user_covid_time, $other_user_time)->format('%d') <= 7 ) {
              array_push($place_id_tmp, $poi_id);
              array_push($visit_time, $tmp1['timestmp']);
              $flag = false;
              break;
            }
          }
        }
      }
    }
  }

/* Find POI names by their POI ids */
  for ($i=0; $i < count($place_id_tmp) ; $i++) {
    $sql4 = "SELECT * FROM points_of_interest WHERE id = '$place_id_tmp[$i]'";
    $exec4 = mysqli_query($conn, $sql4);
    if ( $tmp4 = mysqli_fetch_assoc($exec4) ) {
      array_push($place_name, $tmp4['name']);
    }
  }

/* Close the connection with the database and answer to the AJAX query
   with the wanted data. */
  mysqli_close( $conn );
  echo json_encode(array($place_name, $visit_time));
}

/* Here we can see that someone is trying to access this page without
   being a logged-in user, so we redirect them to home page. */
else {
  header("Location: ../index.php");
  exit();
}
