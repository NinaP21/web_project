<?php
/* This code gets executed when admin visits page PER DAY CHARTS
   and chooses a specific month, so an AJAX request happens. This script
   responds to this AJAX request with the necessary data. */

/* Make sure that the AJAX query has sent the necessary data,
   that is the selected month. */
if ( isset($_POST['week']) ) {
/* First connect to the database */
  require 'dbh.inc.php';

  $week = $_POST['week'];
  $week = date("Y-m-d", strtotime($week));
  $date = date_create($week);
/* format W means week number of year */
  $week_nr = $date->format("W");

/* Declare the arrays that will be sent back to the query.
   visits will contain the number of visits for each day of the week
   covid_visits will contain the number of visits by COVID-19 cases for each day of the week
   labels will contain the x-axis labels for the chart */
  $visits = array();
  $covid_visits = array();
  $labels = array();

/* Initialise the two arrays that contain the number of visits. */
  for ($i=0; $i <7; $i++) {
    $visits[$i] = 0;
    $covid_visits[$i] = 0;
  }

/* Search for all users' visits that happened in the selected week. */
/* In WEEK() mode=3 means that it starts counting weeks from 1 and
   the first day of the week is Monday, just like in HTML input. */
  $sql = "SELECT * FROM presence WHERE WEEK(timestmp, 3) = '$week_nr'";
  $exec = mysqli_query($conn, $sql);
  while ( $tmp = mysqli_fetch_assoc($exec) ) {
/* Record this visit in the total number of visits of the date that it happened. */
/* format "j" shows the day of the month without leading zeros */
    $visit_time = $tmp['timestmp'];
    $visit_day = date_create($visit_time);
/* format w is the numeric representation of the day of the week.
   Zero stands for Sunday and six for Saturday. */
    $day = $visit_day->format("w");
    if ( $day == 0 ) {
      $day = 6;
    } else {
      $day = $day - 1;
    }
    $visits[$day]++;

  /* The following code will check if this visit is made by and active COVID-19 case.
     First, find the user that registered this visit and search for their covid registrations.
     Then, check if that covid registration day is close to their visit day and if that happens,
     add this visit to the number of active COVID-19 visits in the hour that it occured.*/
    $visit_user = $tmp['user_id'];
    $sql1 = "SELECT * FROM covid_registration WHERE user_id = '$visit_user'";
    $exec1 = mysqli_query($conn, $sql1);

    while ( $tmp1 = mysqli_fetch_assoc($exec1)  ) {
      $covid_reg_date = $tmp1['reg_date'];
      $covid_reg_day = date_create($covid_reg_date);

  /* First condition covers the case where visit day was before the day that the user got detected positive
     to COVID-19 but the visit happened less than seven days before the detection day.
     The second condition covers the case where the COVID-19 case has been detected positive and
     visits some POI during the next 14 days. */
      if ( (date_diff($visit_day, $covid_reg_day)->format('%r%a') >= 0 && date_diff($visit_day, $covid_reg_day)->format('%r%a') <= 7)
      || (date_diff($covid_reg_day, $visit_day)->format('%r%a') >= 0 && date_diff($covid_reg_day, $visit_day)->format('%r%a') <= 14) )
      {
        $covid_visits[$day]++;
        break;
      }
/* The presence of break will ensure that a specific visit will be recorded as an active COVID-19 case
   only once and it will not be taken into consideration more times in case that user has recorded themselves
   as a COVID-19 case more than once. */
    }
  }

  $labels = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

/* Close the connection with the database and answer to the AJAX query
   with the wanted data. */
  mysqli_close( $conn );

  echo json_encode(array($labels, $visits, $covid_visits));
}
/* Here we can see that someone is trying to access this page without
   choosing to see a chart, so we redirect them to home page. */
else {
  header("Location: ../index.php");
  exit();
}
