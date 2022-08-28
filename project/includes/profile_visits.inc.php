<?php
/* This code gets executed when a logged-in user visits page PROFILE
   and chooses to see their history of visits. */

/* First connect to database and get current session so that
   we can access superglobal SESSION variables. */
  require 'dbh.inc.php';
  session_start();

  $user_id = $_SESSION['userId'];

/* Declare the arrays that will be sent as a response to the AJAX request.
   The first one will keep the POI's name and the second one will keep the
   date and time of the visit. */
  $place = array();
  $time = array();

/* Search all visits made by the currently logged-in user. */
  $sql1 = "SELECT * FROM presence WHERE user_id = '$user_id'";
  $exec1 = mysqli_query($conn, $sql1);
  while ( $result1 = mysqli_fetch_assoc($exec1) ) {

    $place_id = $result1['poi_id'];

    $sql2 = "SELECT * FROM points_of_interest WHERE id = '$place_id'";
    $exec2 = mysqli_query($conn, $sql2);

    if ( $result2 = mysqli_fetch_assoc($exec2) ) {
      array_push($place, $result2['name']);
      array_push($time, $result1['timestmp']);
    }
  }
  echo json_encode(array($place, $time));
