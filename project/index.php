<?php
  include_once "header.php";
?>

<body>
  <?php
  /* The program distinguishes the admin only if $_SESSION['adminId'] has
     the true boolean value. Then, it checks if a user has logged in, via the
     superglobal $_SESSION['userId'] or if noone has logged in yet.
     According to those conditions, it shows the appropriate home page.
  */
    if ( isset($_SESSION['adminId']) and $_SESSION['adminId'] == true ) {
      include_once "admin.php";
    }
    elseif ( isset($_SESSION['userId'] ) ) {
      include_once "map.php";
    } else {
      include_once "welcome.php";
    }
  ?>
</body>

<?php
  include_once "footer.php";
?>
