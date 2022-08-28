<!-- This page appears when the user chooses the SIGNUP link -->
<?php
  require_once "header.php";
?>

<!-- Background image -->
<img class="bgd" src="images/back.jpg" alt="">

<div class = "container-fluid web-form">
  <div class="row">

<!-- Input form that asks for the username, the e-mail, the password and a password verification-->
    <div class="col-md-4 wrapper form-group">
        <form name="registration" action="includes/signup.inc.php" method="post">
          <div class="form-group">
            <label for="uid">Username</label>
            <input class="field form-control" type="text" name="uid" placeholder="Username">
          </div>
          <div class="form-group">
            <label for="email">E-mail</label>
            <input class="field form-control" type="text" name="email" placeholder="E-mail">
          </div>
          <div class="form-group">
            <label for="pwd">Password</label>
<!-- When the user mouseovers the question mark icon, they can see the form of a valid password -->
            <i id="pwd-tips" class="glyphicon glyphicon-question-sign"
              title="Password must contain at least one capital and one lowercase letter,
one number digit and at least one special symbol like #, @, * etc."></i>
            <div class="input-group">
              <input class="field form-control" id="signup-pwd" type="password" name="pwd" placeholder="Password">
<!-- If the user wants to see what they type, they can click on the eye-icon.
     If they click again, then the password will be represented by dots.-->
              <div class="input-group-addon" id="pwd-eye">
	               <span class="glyphicon glyphicon-eye-open"></span>
               </div>
            </div>
          </div>
          <div class="form-group">
            <label for="pwd-repeat">Repeat Password</label>
            <div class="input-group">
              <input class="field form-control" id="signup-pwd-repeat" type="password" name="pwd-repeat" placeholder="Repeat Password">
              <div class="input-group-addon" id="pwd-repeat-eye">
	               <span class="glyphicon glyphicon-eye-open pwd-repeat-eye"></span>
               </div>
            </div>
          </div>

          <button class="btn btn-default btn-block form-button" type="submit" name="signup-submit">Signup</button>
        </form>

        <?php
/* When the user submits the signup form, a Bootstrap alert appears that informs them
   if the procedure was successful or not, according to the information that
   back-end .php script has added to the url.*/

/* Successful signup */
        if ( isset( $_GET['signup'] ) && $_GET['signup'] == 'success')
          echo '<div class="alert alert-success signup-alert" role="alert">Successful signup!</div>';
/* The following messages appear if the corresponding errors have happened.
   Of course, more than one errors can happen at the same time, but the website
   will show an error message for the first error that it detects. */
        elseif ( isset($_GET['error'] ) ) {
          echo
            '<div class="alert alert-danger signup-alert" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>';
            if ($_GET['error'] == 'uidexists') {
              echo 'This Username already exists</div>';
            }
            elseif ($_GET['error'] == 'emptyfields'){
              echo 'Please fill in all fields</div>';
            }
            elseif ($_GET['error'] == 'invalidusername&email'){
              echo 'Enter valid username and email address </div>';
            }
            elseif ($_GET['error'] == 'invalidusername'){
              echo 'Enter valid username </div>';
            }
            elseif ($_GET['error'] == 'invalidmail'){
              echo 'Enter valid email address </div>';
            }
            elseif ($_GET['error'] == 'passwordlength'){
              echo 'Password must be at least 8 characters </div>';
            }
            elseif ($_GET['error'] == 'passwordnumbers'){
              echo 'Password must contain at least one number </div>';
            }
            elseif ($_GET['error'] == 'passwordcapitals'){
              echo 'Password must contain at least one capital letter </div>';
            }
            elseif ($_GET['error'] == 'passwordlowercase'){
              echo 'Password must contain at least one lowercase letter </div>';
            }
            elseif ($_GET['error'] == 'passwordsymbols'){
              echo 'Password must contain at least one keyboard symbol </div>';
            }
            elseif ($_GET['error'] == 'passwordcheck'){
              echo 'The two given passwords must agree </div>';
            }
        }
        ?>

    </div>
    <div class="col-md-8"> </div>
  </div>
</div>

<?php
  require_once "footer.php";
?>
