<?php
/* This code is executed when a user wants to record their presence in a POI. */

/* Make sure that the user has clicked the "Record my presence here!" button
   in the popup of a POI and the AJAX query has sent the POI name. */
if ( isset($_POST['place']) ) {

/* First connect to the database and get the current session to access
   the SESSION global variables. */
  require 'dbh.inc.php';
  session_start();
  $user = $_SESSION['userId'];
  $place = $_POST['place'];

  $sql1 = "SELECT * FROM points_of_interest WHERE name = '$place'";
  $exec1 = mysqli_query($conn, $sql1);

  if ( $result1 = mysqli_fetch_assoc($exec1) ) {
    $place_id = $result1['id'];
/* Set right timezone for date() function */
    date_default_timezone_set("Europe/Athens");
    $timestmp = date('Y-m-d H:i:s');

/* Insert user's presence into the database. */
    $sql2 = "INSERT INTO presence (user_id, poi_id, timestmp)
            VALUES('$user', '$place_id', '$timestmp') ";

    mysqli_query($conn, $sql2);
  }
  mysqli_close( $conn );
}
/* If someone tries to visit this page without clicking "Record my presence here!"
   then the program will redirect them to home page. */
else {
  header("Location: ../index.php");
  exit();
}
