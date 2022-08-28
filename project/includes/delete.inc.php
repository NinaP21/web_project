<?php

/* Make sure that this code is executed only if admin has pressed
  the "Delete" button in the delete data form*/
if ( isset($_POST['submit']) ) {

/* Search for all files inside uploads folder and delete them. */
  foreach ( glob("../uploads/*") as $filename ) {
    if ( !unlink($filename) ) {
      header("Location: ../index.php?delete=error");
      exit();
    }
  }
/* This function deletes all relevant data from the database. */
  clear_db_POI();
  header("Location: ../index.php?delete=success");
  exit();
}
/* If someone tries to visit this page without pressing "Delete"
  then the system will redirect them to home page. */
else {
  header("Location: ../index.php");
  exit();
}

function clear_db_POI() {
/* First connect to our database */
  require 'dbh.inc.php';

/* Create all DELETE sql queries, that will clear the tables. */
  $sql1 = "DELETE FROM popular_times_of_pois";
  mysqli_query($conn, $sql1);

  $sql2 = "DELETE FROM poi_type";
  mysqli_query($conn, $sql2);

  $sql3 = "DELETE FROM points_of_interest";
  mysqli_query($conn, $sql3);

  $sql4 = "DELETE FROM covid_registration";
  mysqli_query($conn, $sql4);

  $sql5 = "DELETE FROM presence";
  mysqli_query($conn, $sql5);

  $sql6 = "DELETE FROM popularity_estimation";
  mysqli_query($conn, $sql6);

/* Close connection to the database. */
  mysqli_close($conn);
}
