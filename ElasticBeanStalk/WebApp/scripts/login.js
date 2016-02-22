var AJAX_URL = "https://mobiusdev-dev.us-east-1.elasticbeanstalk.com/";

showHidePassword();
submitLoginInfo();

function showHidePassword() {
  $("body").on("click", ".hideShowPassword-toggle-hide", function() {
    $input = $(this).siblings(".mobius-password-input");
    $type = $input.attr("type");
    if ($type === "password") {
      $input.attr("type", "text");
      $(this).text("HIDE");
    }

    else {
      $input.attr("type", "password");
      $(this).text("SHOW");
    }
  });
}

function submitLoginInfo() {
  $("body").on("click", "#mobius-submit-button", function() {
    var $incorrectDescription = $(".mobius-incorrect-description");
    var $emailInput = $(".mobius-email-input");
    var $passInput = $(".mobius-password-input");
    var email = $emailInput.val();
    var password = $passInput.val();
    if (IsEmail(email)) {
      $incorrectDescription.removeClass("visible");
      $emailInput.removeClass("incorrect-info");

      $.ajax({
        url: AJAX_URL + "WebApp/login/login.php",
        type: "post",
        data: {'action' : 'login', 'email' : email, 'password' : password} ,
        dataType: "text",
        success: function(data) {
          alert(data);
          if (data == "Already Logged In" || data == "Logged In. Welcome to Mobius.") {
            window.location.href = AJAX_URL + "recent";
          }
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);

        }
      });



    }
    else {
      $incorrectDescription.text("Incorrect Email Format");
      $incorrectDescription.addClass("visible");
      $emailInput.addClass("incorrect-info");
    }
  });
}




// Returns false if it is not email format, returns true if it is
function IsEmail(email) {
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  return regex.test(email);
}
