<?php
/* This code must be executed when someone clicks the submit button (SIGNUP)
   in the signup form. */

/* Be sure that the signup button is clicked.
   Otherwise, the user should not see the context of this page.*/
if ( isset($_POST['signup-submit']) ) {

/* First, connect to our database. */
  require 'dbh.inc.php';

/* Those POST variables are passed through the signup form fields. */
  $username = $_POST['uid'];
  $email = $_POST['email'];
  $password = $_POST['pwd'];
  $passwordRepeat = $_POST['pwd-repeat'];

/* The program will insert the given data to the database, but first
   it has to check if some error has happened.
   In every case (fail or success), the program will redirect the user to signup page
   with some extra info in the url so that the appropriate message will inform
   the user about the result. */

/* User has left some form field empty. */
  if ( empty($username) || empty($email) ||empty($password) ||empty($passwordRepeat) ) {
    header("Location: ../signup.php?error=emptyfields&uid=".$username."&mail=".$email);
    exit();
  }
  else {
/* All fields are filled */

/* Check if there is another user with the same username */
    $select = mysqli_query($conn, "SELECT * FROM users WHERE email IS NOT NULL AND uid ='$username'");

    if ( mysqli_num_rows($select) ) {
      header("Location: ../signup.php?error=uidexists");
      exit();
    }
/* Usename and e-mail are both invalid */
    elseif ( !preg_match("/^[a-zA-Z0-9]*$/", $username) && !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
      header("Location: ../signup.php?error=invalidusername&email");
      exit();
    }
/* Invalid username. A valid one should contain lowercase or uppercase letters and digits. */
    elseif ( !preg_match("/^[a-zA-Z0-9]*$/", $username) ) {
      echo "<script> document.registration.uid.focus(); </script>";
      header("Location: ../signup.php?error=invalidusername&mail=".$email);
      exit();
    }
/* Invalid email */
    elseif ( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
      header("Location: ../signup.php?error=invalidmail&uid=".$username);
      exit();
    }
/* Password has less than 8 characters. */
    elseif ( strlen($password) < 8 ) {
      header("Location: ../signup.php?error=passwordlength&uid=".$username."&mail=".$email);
      exit();
    }
/* Password does not contain any digit  */
    elseif ( !preg_match("/[0-9]+/", $password) ) {
      header("Location: ../signup.php?error=passwordnumbers&uid=".$username."&mail=".$email);
      exit();
    }
/* Password does not contain any capital letter */
    elseif( !preg_match("/[A-Z]+/", $password) ) {
      header("Location: ../signup.php?error=passwordcapitals&uid=".$username."&mail=".$email);
      exit();
    }
/* Password does not contain any lowercase letter */
    elseif( !preg_match("/[a-z]+/", $password) ) {
      header("Location: ../signup.php?error=passwordlowercase&uid=".$username."&mail=".$email);
      exit();
    }
/* Password does not contain any symbol other that letters or digits */
    elseif( !preg_match("/^[A-Za-z0-9]+/", $password) ) {
      header("Location: ../signup.php?error=passwordsymbols&uid=".$username."&mail=".$email);
      exit();
    }
/* The two given passwords do not match */
    elseif ( $password !== $passwordRepeat ) {
      header("Location: ../signup.php?error=passwordcheck&uid=".$username."&mail=".$email);
      exit();
    }
/* No error has been detected */
    else {
/* Hash the given password by using the default hashing algorithm. */
      $hashedPwd = password_hash($password, PASSWORD_DEFAULT);
/* Make sure that the given password matches the hashed one. */
      if ( password_verify($password, $hashedPwd) ) {
/* Insert new user's data to the database and return a success message. */
        $sql = "INSERT INTO users (uid, email, pwd)
              VALUES('$username', '$email', '$hashedPwd')";
        mysqli_query($conn, $sql);
        header("Location: ../signup.php?signup=success");
        exit();
      }
    }

/* Close the connection with the database */
  mysqli_close( $conn );

}
}
/* Someone tries to use this page without clicking the appropriate button,
   so redirect them to home page. */
else {
  header("Location: ../signup.php");
  exit();
}
