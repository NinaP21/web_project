<!-- This page will appear when a logged in user chooses the tab
     Covid Awareness. -->
<?php
  require_once "header.php";
?>

<!-- Background image -->
<img class="bgd" src="images/back.jpg" alt="">

<div class = "container-fluid web-form">
  <div class="row">
<!-- Form where the logged in user can register themselves as a COVID-19
     case and the date that they were tested positive. -->
    <div class="col-md-5 consent-wrapper form-group">
      <div class="panel panel-info">
        <div class="panel-heading">
          Disclaimer
        </div>
        <div class="panel-body covid-panel">
           Your registration will be completely confidential and will be used only for statistical purposes and your personal information.
        </div>
      </div>
      <form name="covid-registration" action="includes/register_covid.inc.php" method="post">

            <div class="input-group">
              <input  type="checkbox" name="consent" id="consent-check">
              <label for="consent" class="consent-text">I responsibly state that I have been tested positive for COVID-19,</label>
            </div>


          <div class="form-group">
            <label for="date" class="consent-text">at date:</label>
            <div class="input-group">
              <input class="field form-control" type="date" name="date">
            </div>
          </div>

          <button class="btn btn-default btn-block form-button" type="submit" name="covid-submit">Register</button>
        </form>

        <?php
/* When the user submits the Register COVID-19 form, a Bootstrap alert appears that informs them
   if the procedure was successful or not, according to the information that
   back-end .php script has added to the url.*/

        if ( isset( $_GET['register'] ) && $_GET['register'] == 'success')
          echo '<div class="alert alert-success signup-alert" role="alert">Successful register <br> Take care and stay safe!
                <span class="glyphicon glyphicon-heart" aria-hidden="true"> </span> </div>';
        elseif ( isset($_GET['error'] ) ) {
          echo
/* An error message appears if the error value in the url shows that
   the user has left the checkbox unchecked or the date unset,
   if the user has entered a future date, a too old date or
   if they have already registered as COVID-19 positive in the past 14 days. */
            '<div class="alert alert-danger signup-alert" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>';
            if ($_GET['error'] == 'days') {
              echo 'You have already registered that you are COVID-19 positive in the past 14 days';
            }
            elseif ($_GET['error'] == 'olddate') {
              echo 'The date that you entered is too old';
            }
            elseif ($_GET['error'] == 'futuredate') {
              echo 'You entered a future date. Please try again';
            }
            elseif ($_GET['error'] == 'emptyfields') {
              echo 'Please fill in all data';
            }
            echo '</div>';
          }
        ?>
        </div>

<!-- A wrapper that contains a table with the user's visits
     where they could have possibly been exposed to COVID-19. -->
        <div class="col-md-5 covid-wrapper form-group">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <h3 class="panel-title">Possible COVID-19 exposure</h3>
            </div>
            <div class="panel-body covid-panel" >
               These are the places you have visited and another COVID-19 positive app-user has visited too, in approximate hours:
            </div>
            <table class="table" id="covid-exposure-table"> </table>
          </div>      
        </div>
      </div>
    </div>

<?php
  require_once "footer.php";
?>
