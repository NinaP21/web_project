<!-- This is the home page of administrator -->

<!-- Background image -->
<img class="bgd" src="images/back.jpg" alt="">

  <div class = "container-fluid web-form" >
    <div class="row" id="admin-page">

<!-- Create a file upload form -->
      <div class="col-md-4 admin-form-wrapper form-group">
        <center class="form-header">Upload a file</center>
        <form class="" action="includes/upload.inc.php" method="post" enctype="multipart/form-data">
          <div class="form-group">
            <label>Choose file:</label>
            <input type="file" name="userfile">
            <button class="btn btn-default btn-block form-button" type="submit" name="submit">Upload</button>
          </div>
        </form>

        <?php
/* When the admin tries to upload a file, then a Bootstrap alert
   will appear whether the procedure was successful or not. */
        if ( isset( $_GET['upload'] ) && $_GET['upload'] == 'success')
          echo '<div class="alert alert-success signup-alert" role="alert">Successful upload!</div>';
        elseif ( isset( $_GET['upload'] ) && $_GET['upload'] == 'error') {
          echo
            '<div class="alert alert-danger signup-alert" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            <span class="sr-only">Error: </span>';
            if ( isset($_GET['error'] ) ) {
              if ($_GET['error'] == 'size') {
                echo 'File is too big!';
              }
              elseif ($_GET['error'] == 'filetype') {
                echo 'You can only upload .json files';
              }
            } else {
              echo 'Could not upload your file';
            }
            echo '</div>';
        }
        ?>
      </div>

      <div class="col-md-2"></div>

<!--Delete all data form with a confirmation message (JavaScript confirm() function) -->
      <div class="col-md-3 admin-form-wrapper form-group">
        <center class="form-header">Delete all data</center>
        <form action="includes/delete.inc.php" method="post" onsubmit="return confirm('Are you sure you want to delete all data?');">
          <div class="form-group">
            <button class="btn btn-default btn-block form-button" type="submit" name="submit">Delete</button>
          </div>
        </form>
        <?php
/* When the admin tries to delete all data, then a Bootstrap alert
   will appear whether the procedure was successful or not. */
          if ( isset( $_GET['delete'] ) && $_GET['delete'] == 'success')
            echo '<div class="alert alert-success signup-alert" role="alert">All uploads and their data are deleted!</div>';
          elseif ( isset( $_GET['delete'] ) && $_GET['delete'] == 'error') {
            echo
              '<div class="alert alert-danger signup-alert" role="alert">
              <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
              <span class="sr-only">Error: Could not delete data </span>';
            }
         ?>
      </div>
    </div>
  </div>
</div>
