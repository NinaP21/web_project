<?php

/* This code implements the connection with the database of our project
   (webproject) in PHPMyAdmin. The credentials have the default values. */

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "webproject";

$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);

if( !$conn ) {
  die("Connection failed: ".mysqli_connect_error());
}
