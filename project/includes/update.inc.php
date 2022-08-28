<?php
/* This code is executed when a logged-in user tries to update their Username
   or Password in PROFILE page. */

/* Make sure that this code is executed only if user has pressed
  the "Update" button in the update user info form. */
if ( isset($_POST['update-submit']) ) {

/* First, connect to the database */
  require 'dbh.inc.php';

/* Open the current session so that we can access the superglobal variables.  */
  session_start();
  $old_username = $_SESSION['userUid'];
  $new_username = $_POST['updated_uid'];
  $new_password = $_POST['updated_pwd'];

/* The two fields are set and filled with data and the username has changed. */
  if ( isset( $new_username ) && isset( $new_password ) && !empty( $new_username ) && !empty( $new_password )
      && strcmp( $old_username, $new_username ) ) {
/* Call this function that checks if password is valid. */
    update_pwd_check( $new_password );
    $sql = "UPDATE users
            SET uid='$new_username',
                pwd='$new_password'
            WHERE uid = '$old_username'";
    mysqli_query($conn, $sql);
/* Change the session variable of username so that the user does not have to
   close the browser and open it up again. */
    $_SESSION['userUid'] = $new_username;
    mysqli_close( $conn );
    header("Location: ../profile.php?update=success");
    exit();
/* The field of username are set and filled with new data but password has not changed. */
  } elseif ( isset( $new_username ) && !empty( $new_username ) && strcmp( $old_username, $new_username ) ) {
      $sql = "UPDATE users
              SET uid='$new_username'
              WHERE uid='$old_username'";
      mysqli_query($conn, $sql);
      $_SESSION['userUid'] = $new_username;
      mysqli_close( $conn );
      header("Location: ../profile.php?update=success");
      exit();
/* Here, the username stays the same, while the password has changed to a new one. */
  } elseif ( isset( $new_password ) && !empty( $new_password ) ) {
      update_pwd_check( $new_password );
      $sql = "UPDATE users
              SET pwd = '$new_password'
              WHERE uid = '$old_username'";
      mysqli_query($conn, $sql);
      mysqli_close( $conn );
      header("Location: ../profile.php?update=success");
      exit();
/* No action is required as data have not changed. */
  } else {
      mysqli_close( $conn );
      header("Location: ../profile.php");
      exit();
  }

}
/* If user tries to visit this page without pressing "Update"
   then the system will redirect them to home page */
else {
  header("Location: ../index.php");
  exit();
}

/* This function checks for password validation.
   If some error has happened, then redirect the user to PROFILE page
   with some extra info in the URL so that the appropriate Bootstrap message
   appears to them. The validation criteria are the same as in signup.inc.php */
function update_pwd_check( $password ){
  if ( strlen($password) < 8 ) {
    header("Location: ../profile.php?error=passwordlength");
    exit();
  }
  elseif ( !preg_match("/[0-9]+/", $password) ) {
    header("Location: ../profile.php?error=passwordnumbers");
    exit();
  }
  elseif( !preg_match("/[A-Z]+/", $password) ) {
    header("Location: ../profile.php?error=passwordcapitals");
    exit();
  }
  elseif( !preg_match("/[a-z]+/", $password) ) {
    header("Location: ../profile.php?error=passwordlowercase");
    exit();
  }
  elseif( !preg_match("/^[A-Za-z0-9]+/", $password) ) {
    header("Location: ../profile.php?error=passwordsymbols");
    exit();
  }
}
