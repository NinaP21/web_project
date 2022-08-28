<?php
/* This code gets executed when a logged in user visits page
   PROFILE, where they can see their history of COVID-19 registrations. */

/* Connect to database and get the current session to access
   the SESSION global variables. */
  require 'dbh.inc.php';
  session_start();

  $user_id = $_SESSION['userId'];

/* Search for the COVID-19 registrations that this user has made
   and if they exist, save the registration date to array registrations(). */
  $sql = "SELECT * FROM covid_registration WHERE user_id = '$user_id'";
  $exec = mysqli_query($conn, $sql);

  $registrations = array();
  while ( $result = mysqli_fetch_assoc($exec) ) {
    array_push($registrations, $result['reg_date']);
  }

  echo json_encode($registrations);
