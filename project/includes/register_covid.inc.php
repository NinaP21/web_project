<?php
/* This code is executed when a logged in user wants to register themselves
   as a COVID-19 case in COVID AWARENESS page. */

/* Make sure that this code is run only if the user has clicked the submit button
   (REGISTER) in the form of page COVID AWARENESS.*/
if ( isset($_POST['covid-submit']) ) {

/* First connect to the database. */
  require 'dbh.inc.php';

/* Get the current session in order to access the global SESSION variables. */
  session_start();
  $user_id= $_SESSION['userId'];
  $consent = $_POST['consent'];
  $date = $_POST['date'];
/* Change timezone for date() function */
  date_default_timezone_set("Europe/Athens");
  $today = date('Y-m-d');

/* First, check if the user has left the date or the checkbox empty
   and if that happens, add some error information to the URL. */
  if ( empty($consent) || empty($date) ) {
    header("Location: ../covid.php?error=emptyfields");
    exit();
  }
/* Check if user has chosen a future date as the day that they got positive to COVID-19. */
  elseif ( strtotime($today) < strtotime($date) ) {
    header("Location: ../covid.php?error=futuredate");
    exit();
  }
/* Check if user has chosen a date that was before the beginning of COVID-19. */
  elseif ( strtotime($date) < strtotime("2019-12-15") ) {
    header("Location: ../covid.php?error=olddate");
    exit();
  } else {
/* Check if this user has registered as a COVID-19 case again
   and if it is true, check if past registrations have happened at least 14 days ago. */
      $select = "SELECT * FROM covid_registration WHERE user_id ='$user_id'";
      $exec = mysqli_query($conn, $select);

      while ( $result = mysqli_fetch_assoc($exec) ) {
          $reg_date = date_create($date);
          $previous_reg_date = date_create($result['reg_date']);
/* Compute a signed date difference, so that if the user registers
   a date before their last COVID-19 registration, the result will be < 0 */
          $datediff = date_diff($previous_reg_date, $reg_date)->format('%r%a');

          if ( $datediff < 0 && $datediff < 14 ) {
            header("Location: ../covid.php?error=days");
            exit();
          }
      }
/* Register the COVID-19 case to the database. */
      $sql = "INSERT INTO covid_registration (user_id, reg_date )
              VALUES ('$user_id', '$date')";
      mysqli_query($conn, $sql);
      header("Location: ../covid.php?register=success");
      exit();
    }
}
/* Someone tries to access this page without clicking the appropriate button,
   so redirect them to home page. */
else {
  header("Location: ../covid.php");
  exit();
}
