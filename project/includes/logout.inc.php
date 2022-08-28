<?php
/* This code gets executed when loggen in user or admin choose to log out. */

/* Get the current session and unset its superglobal variables.
   Then, destroy all of the data associated with the current session. */
session_start();
session_unset();
session_destroy();
header("Location: ../index.php");
