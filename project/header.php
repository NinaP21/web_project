<!-- Put session_start() here, so that all front-end pages are on the current session -->
<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <link rel="icon" href="images/logo.ico" type="image/icon type">

    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
    <script src="scripts/simple_scripts.js"></script>
    <script src="scripts/map_scripts.js"></script>
    <script src="scripts/statistics_scripts.js"></script>
    <script src="scripts/chart_scripts.js"></script>
    <script src="scripts/user_profile_scripts.js"></script>

    <title>Web project</title>
  </head>

<header>

<!-- Create the navigation bar that contain the logo (redirects to the home page)
     the Home and the About page. -->
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php">
          <img class="logo" src="images/logo.png" alt="logo">
        </a>
        </div>
          <ul class="nav navbar-nav" id = "navbar-header">
            <li>
              <a href="index.php">Home</a>
            </li>
            <li>
              <a href="about.php">About</a>
            </li>
            <?php
/* If a simple user has logged in, then the navigation bar also contains
   the Covid Awareness page. If the administrator has logged in, the nav bar
   will contain the tabs of Statistics and a dropdown menu of Charts that
   contain the Per Day Charts and the Per Hour Charts. */
              if ( isset($_SESSION['userId']) ) {
                echo "<li>
                        <a href=\"covid.php\">Covid awareness</a>
                      </li>";
              } elseif ( isset($_SESSION['adminId']) and  $_SESSION['adminId'] == true  ) {
                  echo "<li>
                          <a href=\"admin-statistics.php\">Statistics</a>
                        </li>";
                  echo "<li class=\"dropdown\">
                          <a href=\"\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">Charts <span class=\"caret\"></span></a>
                          <ul class=\"dropdown-menu\">
                            <li class=\"navbar-dropdown\"><a href=\"admin-charts-per-day.php\">Charts per Day</a></li>
                            <li class=\"navbar-dropdown\"><a href=\"admin-charts-per-hour.php\">Charts per Hour</a></li>
                          </ul>
                        </li>";
              }
            ?>
          </ul>

          <div>
            <?php
/* If a user or the admin have logged in, then the nav bar will contain a Logout button. */
              if ( isset($_SESSION['userId']) or ( isset($_SESSION['adminId']) and  $_SESSION['adminId'] == true ) ) {
                echo '<form  class="logout navbar-form navbar-right" action="includes/logout.inc.php" method="post">
                        <button class="btn" type="submit" name="logout-submit">Logout</button>
                      </form>';
/* Show welcome message for the admin */
                      if ( isset($_SESSION['adminId']) and  $_SESSION['adminId'] == true ) {
                        echo '<ul class="nav navbar-nav navbar-right welcome-msg">
                                <li> Welcome admin! </li>
                              </ul>';
                      }
                      else {
/* If a user has logged in, firstly the program shows a welcome message and then the navigation bar
   contains a button that redirects them to their profile page.*/
                        echo
                              '<a href="profile.php" >
                              <button type="button" id="profile-btn" class="nav navbar-nav navbar-right btn btn-default">
                                <span class="glyphicon glyphicon-user" aria-hidden="true"></span> My Profile
                              </button>
                              </a>
                              <ul class="nav navbar-nav navbar-right welcome-msg">
                                <li> Welcome "'. $_SESSION['userUid'] .'"! </li>
                              </ul>';
                      }
              }
/* If noone has logged in yet, the navigation bar will contain an inline log in form
   and a Signup link.
   If the user wants to see what they type in the password field, they can click on the eye-icon.
   If they click again, then the password will be represented by dots, as before.*/
              else {
                  echo '<a class="signup-link navbar-right" href="signup.php">Signup</a>
                        <form class="navbar-form navbar-right" action="includes/login.inc.php" method="post">
                          <div class="form-group">
                            <input class="form-control" type="text" name="uid" placeholder="Username...">
                            <div class="input-group">
                              <input class="form-control" id = "login-pwd" type="password" name="pwd" placeholder="Password...">
                              <div class="input-group-addon">
                	               <span class="glyphicon glyphicon-eye-open" id = "login-pwd-eye"></span>
                               </div>
                            </div>
                          </div>
                          <button class="login btn" type="submit" name="login-submit">Login</button>
                        </form>';
              }
            ?>
          </div>
        </div>
      </nav>

</header>
</html>
