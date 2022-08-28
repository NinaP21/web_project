<?php
/* This code gets executed when the administrator visits page STATISTICS,
   so an AJAX request happens, that asks for the statistical data
   in the left wrapper. */

/* First connect to the database. */
require 'dbh.inc.php';

/* Total number of user visits */
$sql1 = "SELECT * FROM presence";
if ( $exec1 = mysqli_query($conn, $sql1)) {
    $visits = mysqli_num_rows( $exec1 );
 }

/* Total number of COVID-19 registrations */
$sql2 = "SELECT * FROM covid_registration";
if ( $exec2 = mysqli_query($conn, $sql2)) {
    $covid_registrations = mysqli_num_rows( $exec2 );
 }

/* Total number of visits by COVID-19 positive cases */
$active_covid_visits = 0;

/* For every covid registration, we keep the user id and
   we check if the specific user's visits are made while
   the user could have transmitted COVID-19 to others. */
while ( $tmp = mysqli_fetch_assoc($exec2) ) {
  $user_id = $tmp['user_id'];
/* Current covid registration date */
  $covid_reg_date = date_create($tmp['reg_date']);

  $sql3 = "SELECT * FROM presence WHERE user_id = '$user_id'";
  $exec3 = mysqli_query($conn, $sql3);
  while ( $tmp1 = mysqli_fetch_assoc($exec3) ) {
/* Current visit date and time */
    $visit_date = date_create($tmp1['timestmp']);

/* First condition covers the case where visit day was before the day that the user got detected positive
   to COVID-19 but the visit happened less than seven days before the detection day.
   The second condition covers the case where the COVID-19 case has been detected positive and
   visits some POI during the next 14 days. */
    if ( (date_diff($visit_date, $covid_reg_date)->format('%r%a') >= 0 && date_diff($visit_date, $covid_reg_date)->format('%r%a') <= 7)
      || (date_diff($covid_reg_date, $visit_date)->format('%r%a') >= 0 && date_diff($covid_reg_date, $visit_date)->format('%r%a') <= 14) ) {
      $active_covid_visits++;
    }
  }
}

mysqli_close( $conn );

echo json_encode(array($visits, $covid_registrations, $active_covid_visits));
