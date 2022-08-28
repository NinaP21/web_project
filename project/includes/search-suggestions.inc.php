<?php
/* This code is executed when a user starts typing in the search box of map.
   Then, an AJAX query occurs and this code responds to it with the html
   code that will create the suggestions list. */

/* First connect to the database */
require 'dbh.inc.php';

/* Make sure that the required data is sent through the AJAX query. */
if (isset( $_POST['search'] )) {
  $type = $_POST['search'];
/* Find all types of POIs that start with the already typed letters. */
  $sql = "SELECT * FROM poi_type WHERE type LIKE '$type%'";
  $exec = mysqli_query($conn, $sql);

/* Return the html code that creates the list with the suggestions. */
  echo "<ul class=\"list-group suggestions\">";
  $tmp = array();

  while ( $result = mysqli_fetch_assoc($exec) ) {
    $result_type = $result['type'];
/* Make suggestions more pretty by replacing _ symbol with space. */
    $result_type = str_replace('_', ' ', $result_type);
/* If a type of POI is already in the list of suggestions, then do not add it again. */
    if ( !in_array($result_type, $tmp) ) {
/* The onclick function fills the search box with the type of POI that was clicked. */
      echo "<li class=\"list-group-item choice\" onclick='fill(\"".$result_type."\")'>".$result_type."</li>";
      array_push($tmp, $result_type);
    }
  }
}
