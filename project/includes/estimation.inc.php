<?php
/* This code is executed when a logged-in user chooses a POI in their map
   and tries to write down their estimation about the current popularity of this POI.
   In this case, an AJAX query is generated that asks if the given input is valid
   (return true) or not (return false). */

/* Make sure that the AJAX request has provided the necessary information,
   that is the name of the POI and the user's estimation number. */
if ( isset($_POST['place']) && isset($_POST['estimation']) ) {

/* First connect to the database and get the current session, so that
   we can access the superglobal SESSION variables. */
  require 'dbh.inc.php';
  session_start();

  $user_id = $_SESSION['userId'];
  $estimation = $_POST['estimation'];
  $place = $_POST['place'];

/* A valid estimation number should contain only digits (so it will not be float)
   and it should also be non negative. */
  if ( ctype_digit($estimation) && $estimation >= 0  ) {

/* Make sure that $user_id has a valid value. */
    $sql1 = "SELECT * FROM users WHERE id = '$user_id'";
    $exec1 = mysqli_query($conn, $sql1);
    if ( $result1 = mysqli_fetch_assoc($exec1) ) {

/* Find POI's id given its name. */
      $sql2 = "SELECT * FROM points_of_interest WHERE name = '$place'";
      $exec2 = mysqli_query($conn, $sql2);
      if ( $result2 = mysqli_fetch_assoc($exec2) ) {

        $place_id = $result2['id'];
/* Set right timezone for date() function */
        date_default_timezone_set("Europe/Athens");
        $timestmp = date('Y-m-d H:i:s');

/* Insert user's estimation to the database. */
        $sql2 = "INSERT INTO popularity_estimation (user_id, place_id, estimation, timestmp)
                VALUES ('$user_id', '$place_id', '$estimation', '$timestmp')";
        if ( mysqli_query($conn, $sql2) ) {
          echo true;
        }
      }
    }
  }
/* The given number is invalid, so return false. */
  else {
    echo false;
  }
}

/* If someone tries to visit this page without clicking "Submit",
   then the system will redirect them to home page. */
else {
  header("Location: ../index.php");
  exit();
}
