/* In signup, when a user wants to see what they have typed in Password field. */
$(document).ready( function() {
  $('#pwd-eye').click( function() {
    if ($("#signup-pwd").attr('type') === 'password') {
      $("#signup-pwd").attr('type', 'text');
    } else {
      $("#signup-pwd").attr('type', 'password');
    }
  } )
} );

/* In signup, when a user wants to see what they have typed in Repeat Password field. */
$(document).ready( function() {
  $('#pwd-repeat-eye').click( function() {
    if ($("#signup-pwd-repeat").attr('type') === 'password') {
      $("#signup-pwd-repeat").attr('type', 'text');
    } else {
      $("#signup-pwd-repeat").attr('type', 'password');
    }
  } )
} );

/* In login form, when a user wants to see what they have typed in Password field. */
$(document).ready( function() {
  $('#login-pwd-eye').click( function() {
    if ($("#login-pwd").attr('type') === 'password') {
      $("#login-pwd").attr('type', 'text');
    } else {
      $("#login-pwd").attr('type', 'password');
    }
  } )
} );

/* When a user has loggen-in and wants to update their username or password,
   they have to click on pencil icon in the appropriate field so that
   it can be editable. */
$(document).ready( function() {
  $("#uid-edit").click( function() {
    $("#uid-profile").removeAttr("readonly");
  } )
} );

$(document).ready( function() {
  $("#pwd-edit").click( function() {
    $("#pwd-profile").removeAttr("readonly");
    $("#pwd-profile").removeAttr("placeholder");
  } )
} );
