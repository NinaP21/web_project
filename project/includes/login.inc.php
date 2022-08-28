<?php
/* This code must be executed when someone clicks the submit button (LOGIN) in the
   inline login form of header. */

/* Be sure that the login button is clicked.
   Otherwise, the user should not see the context of this page.*/
if ( isset($_POST['login-submit']) ) {
/* Connect to the database */
  require 'dbh.inc.php';

/* These are the values that the form passes to the server. */
  $userid = $_POST['uid'];
  $password = $_POST['pwd'];

/* If user has left any of the two fields empty,
   then redirect them to homepage with some error info in the URL. */
  if ( empty($userid) || empty($password)) {
    header("Location: ../index.php?error=emptyfields");
    exit();
  }
  else {
/* First, check if there is a user with such Username in our database. */
    $sql = "SELECT * FROM users WHERE (uid = '$userid');";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if ( $resultCheck > 0 ) {
      if ( $row = mysqli_fetch_assoc( $result ) ) {
/* A simple user has their eimail field set, while admin's email is NULL.
   So, this condition seperates admin from plain users.*/
        if ( $row['email'] ) {
/* Verify that the given password matches the hashed one that is stored in the database. */
          $pwdCheck = password_verify($password, $row['pwd']);
          if ( $pwdCheck === false ) {
/* Password is wrong so redirect user to homepage with some error info in the URL. */
            header("Location: ../index.php?error=wrongpwd");
            exit();
          }
          elseif ( $pwdCheck === true ) {
/* Password is correct so start a session with the appropriate session variables. */
            session_start();
            $_SESSION['userId'] = $row['id'];
            $_SESSION['userUid'] = $row['uid'];
            header("Location: ../index.php?login=success");
            exit();
          }
        }
/* email field is not set in the database, so it is about administrator.
   Admin's password is not hashed and we validate it with a simple comparison. */
        elseif ( $password === 'ceid12345' ) {
/* Admin is connected so start a session and set the appropriate session variable. */
            session_start();
            $_SESSION['adminId'] = true;
            header("Location: ../index.php?login=admin");
            exit();
        }
/* Here the code is executed when admin tries to login (email is NULL)
   but they give the wrong password. */
        else {
          header("Location: ../index.php?error=wrongpwd");
          exit();
        }
      }
    }
/* In this case, there was no user found in the database with such username. */
    else {
      header("Location: ../index.php?error=nouser");
      exit();
    }
  }
}
/* Here we can see that someone is trying to access this page without submitting
   the login form, so we redirect them to home page. */
else {
  header("Location: ../index.php");
  exit();
}
