<?php
/* This code gets executed when admin visits page STATISTICS and
   chooses to see the ranking of POIs. */

/* First connect to database and declare the arrays that will have as indexes
   the types of POIs and as values the total number of visits in each type
   or the number of active COVID-19 visits. */
require 'dbh.inc.php';

$nr_of_visits = array();
$nr_of_covid_visits = array();

/* Initialise both arrays with zeros for every type of POI. */
$sql = "SELECT * FROM poi_type";
$exec = mysqli_query($conn, $sql);
while ( $tmp = mysqli_fetch_assoc($exec) ) {
  if ( !isset($nr_of_visits[$tmp['type']]) )
  {
    $nr_of_visits[$tmp['type']] = 0;
  }
  if ( !isset($nr_of_covid_visits[$tmp['type']]) )
  {
    $nr_of_covid_visits[$tmp['type']] = 0;
  }
}

$sql1 = "SELECT * FROM presence";
$exec1 = mysqli_query($conn, $sql1);
while ( $tmp1 = mysqli_fetch_assoc($exec1) ) {
  $poi_id = $tmp1['poi_id'];
  $user_id = $tmp1['user_id'];

/* For every user registration of presence in a POI,
   increase the number of visits to that specific type of POI
   and then check if that visit satisfies the conditions so that it
   can be considered a visit of a COVID-19 case. */
  $sql2 = "SELECT * FROM poi_type WHERE id = '$poi_id'";
  $exec2 = mysqli_query($conn, $sql2);
  while ( $tmp2 = mysqli_fetch_assoc($exec2) ) {
    $nr_of_visits[$tmp2['type']]++;

    $sql3 = "SELECT * FROM covid_registration WHERE user_id = '$user_id'";
    $exec3 = mysqli_query($conn, $sql3);
    while ( $tmp3 = mysqli_fetch_assoc($exec3) ) {
      $visit_date = date_create($tmp1['timestmp']);
      $covid_reg_date = date_create($tmp3['reg_date']);

/* First condition covers the case where visit day was before the day that the user got detected positive
   to COVID-19 but the visit happened less than seven days before the detection day.
   The second condition covers the case where the COVID-19 case has been detected positive and
   visits some POI during the next 14 days. */
      if ( (date_diff($visit_date, $covid_reg_date)->format('%r%a') >= 0 && date_diff($visit_date, $covid_reg_date)->format('%r%a') <= 7)
        || (date_diff($covid_reg_date, $visit_date)->format('%r%a') >= 0 && date_diff($covid_reg_date, $visit_date)->format('%r%a') <= 14) )
      {
        $nr_of_covid_visits[$tmp2['type']]++;
      }
    }
  }
}

mysqli_close( $conn );

/* Sort both tables according to their values in descending order and maintain index association. */
arsort($nr_of_visits);
arsort($nr_of_covid_visits);
echo json_encode(array($nr_of_visits, $nr_of_covid_visits));
