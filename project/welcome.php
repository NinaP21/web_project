<img class="bgd" src="images/back.jpg" alt="">

<main>
  <?php
  /* When someone tries to log in and they fail, then the program redirects them
     to this page with some url information about the type of error.
     It can be a wrong password, a username that does not exist in the db or
     there are input fields that have been left empty.
     The appropriate bootstrap alert appears to the user. */
    if ( isset($_GET['error']) ) {
      echo
        '<div class="alert alert-danger login-alert" role="alert">
        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
        <span class="sr-only">Error:</span>';
      if ( $_GET['error'] == 'wrongpwd' ) {
        echo 'Wrong password, please try again';
      } elseif ( $_GET['error'] == 'nouser' ) {
        echo "There is no user with this Username";
      }
      elseif ( $_GET['error'] == 'emptyfields' ) {
        echo 'Please fill in both fields';
      }
      echo "</div>";
    }
  ?>

<!-- Just a welcome message -->
  <div class="jumbotron jumbotron-fluid">
    <div class="container">
      <h1 class="display-4">Welcome to our website</h1>
    </div>
  </div>

</main>
