<?php
/* This code gets executed when admin visits page CHARTS PER HOUR and
   selects a specific date from the right side wrapper. */

/* Make sure that the AJAX query has sent the necessary data,
   that is the date the admin selected. */
if ( isset($_POST['date']) ) {

/* First connect to the database */
  require 'dbh.inc.php';

  $date = $_POST['date'];
  $day = date_create($date);
  $day = $day->format('Y-m-d');

/* $hours array will contain the hours of a day plus the beginning hour of the next day.
   So, it will have the values 00:00:00, 01:00:00, ..., 23:00:00, 00:00:00 */
  $hours = array();

/* Declare the arrays that will be returned as response to the AJAX query
   and they will contain the x-axis labels of the chart, the total visits
   per hour and the COVID-19 active visits per hour. */
  $labels = array();
  $visits = array();
  $covid_visits = array();

/* Initialise the four arrays that were declared above. */
  for ($i=0; $i <= 24; $i++) {
    array_push( $hours, sprintf('%02u', $i).":00:00");
    array_push( $labels, sprintf('%02u', $i).":00");
    $visits[$i] = 0;
    $covid_visits[$i] = 0;
  }

/* Search for all users' visits that happened on the date. */
  $sql = "SELECT * FROM presence WHERE DATE(timestmp) = '$day'";
  $exec = mysqli_query($conn, $sql);
  while ( $tmp = mysqli_fetch_assoc($exec) ) {
    $visit_time = $tmp['timestmp'];
    $visit_day = date_create($visit_time);
    $visit_date = $visit_day->format('Y-m-d');
    $visit_hour = $visit_day->format('H:m:s');

/* If the specific user's visit happens between two hours of the day,
   then add this visit to the total number of visits of the lower hour. */
    for ($j=1; $j <= 24; $j++) {
      if ($visit_hour < $hours[$j] && $visit_hour >= $hours[$j-1]) {
        $visits[$j-1]++;
        break;
      }
    }

/* The following code will check if this visit is made by and active COVID-19 case.
   First, find the user that registered this visit and search for their covid registrations.
   Then, check if that covid registration day is close to their visit day and if that happens,
   add this visit to the number of active COVID-19 visits in the hour that it occured.*/
    $visit_user = $tmp['user_id'];
    $sql1 = "SELECT * FROM covid_registration WHERE user_id = '$visit_user'";
    $exec1 = mysqli_query($conn, $sql1);

/* The presence of flag will ensure that a specific visit will be recorded as an active COVID-19 case
   only once and it will not be taken into consideration more times in case that user has recorded themselves
   as a COVID-19 case more than once. */
    $flag = true;
    while ( $tmp1 = mysqli_fetch_assoc($exec1) and $flag ) {
      $covid_reg_date = $tmp1['reg_date'];
      $covid_reg_day = date_create($covid_reg_date);

/* First condition covers the case where visit day was before the day that the user got detected positive
   to COVID-19 but the visit happened less than seven days before the detection day.
   The second condition covers the case where the COVID-19 case has been detected positive and
   visits some POI during the next 14 days. */
      if ( (date_diff($visit_day, $covid_reg_day)->format('%r%a') >= 0 && date_diff($visit_day, $covid_reg_day)->format('%r%a') <= 7)
      || (date_diff($covid_reg_day, $visit_day)->format('%r%a') >= 0 && date_diff($covid_reg_day, $visit_day)->format('%r%a') <= 14) ) {

        for ($j=1; $j <= 24; $j++) {
            if ($visit_hour < $hours[$j] && $visit_hour >= $hours[$j-1]) {
              $covid_visits[$j-1]++;
/* $flag becomes false because the particular visit has already been recorded
   as an active COVID-19 one. */
              $flag = false;
              break;
           }
        }
      }
    }
  }

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
