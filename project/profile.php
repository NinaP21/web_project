<!-- This page will appear when a user chooses to see theis profile page. -->
<?php
  require_once "header.php";
?>

<!-- Background image -->
<img class="bgd" src="images/back.jpg" alt="">

<div class = "container-fluid web-form">
  <div class="row">

<!-- Form to update profile information like username and password.
     At first, the two fields are read-only in order to prevent
     unaware typing. The user will have to click the pencil icon
     to edit their personal information. -->
    <div class="col-md-4 wrapper form-group">
      <h2 class="profile-title">Update my profile data</h2>
        <form name="registration" action="includes/update.inc.php" method="post">
          <div class="form-group">
            <label for="uid">Username</label>
            <div class="input-group">
              <input class="field form-control" id="uid-profile" type="text" name="updated_uid" value="<?php echo $_SESSION['userUid'] ?>" readonly=true>
              <div class="input-group-addon" id="uid-edit">
               <span class="glyphicon glyphicon-pencil"></span>
             </div>
             </div>
          </div>
          <div class="form-group">
            <label for="pwd">Password</label>
            <div class="input-group">
              <input class="field form-control" id="pwd-profile" type="password" name="updated_pwd" readonly placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;">
              <div class="input-group-addon" id="pwd-edit">
	               <span class="glyphicon glyphicon-pencil"></span>
               </div>
            </div>
          </div>
          <button class="btn btn-default btn-block form-button" type="submit" name="update-submit">Update</button>
        </form>

        <?php
/* When the user updates their data, the appropriate Bootstrap alert appears.
   For example, a success message or a message that shows some password error. */
        if ( isset( $_GET['update'] ) && $_GET['update'] == 'success')
          echo '<div class="alert alert-success signup-alert" role="alert">Successful update!</div>';
        elseif ( isset($_GET['error']) ) {
          echo
            '<div class="alert alert-danger signup-alert" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error:</span>';

            if ($_GET['error'] == 'passwordlength'){
              echo 'Password must be at least 8 characters';
            }
            elseif ($_GET['error'] == 'passwordnumbers'){
              echo 'Password must contain at least one number';
            }
            elseif ($_GET['error'] == 'passwordcapitals'){
              echo 'Password must contain at least one capital letter';
            }
            elseif ($_GET['error'] == 'passwordlowercase'){
              echo 'Password must contain at least one lowercase letter';
            }
            elseif ($_GET['error'] == 'passwordsymbols'){
              echo 'Password must contain at least one keyboard symbol';
            }
            echo "</div>";
          }
        ?>
    </div>

<!-- A wrapper that contains two tabs. The user's visits and their COVID-19 registrations.
     The user has to click on a tab to see the appropriate table.
     Moreover, the inactive tab has blueish background on its title. -->
    <div class="col-md-5 profile-info-wrapper form-group">
      <h2 class="profile-title">My history data</h2>
      <div class="btn-group btn-group-justified" role="group" class="tab" >
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-lg btn-default activeTab" id="visitTab">My visits</button>
        </div>
        <div class="btn-group" role="group">
          <button type="button" class="btn btn-lg btn-default inactiveTab" id="registrationTab">My COVID-19 registrations</button>
        </div>
      </div>
<!-- In the two following blocks, the user will be able to see
     the tables that show their history data.-->
      <div class="activeTab_content" id="visits-tab"> </div>
      <div class="inactiveTab_content" id="resistrations-tab"> </div>
    </div>
  </div>
</div>

<?php
  include_once "footer.php";
?>
