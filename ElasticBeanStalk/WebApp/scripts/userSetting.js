var AJAX_URL = "http://mobiusdev-dev.us-east-1.elasticbeanstalk.com/";


$(document).ready(function() {
  $("#name-input").autoGrowInput({minWidth: 100,comfortZone:0});
  contentNav();
  changeProfilePic();
  changeName();
  changeEmail();
  showHidePassword();
  changePassword();
});

// Navigate between Email and Password
function contentNav() {
  var $contentNavSpan = $(".content-nav-span");
  var $emailBody = $("#email-body");
  var $passwordBody = $("#password-body");

  // Click on Email Nav
  $("#content-nav span:nth-child(1)").on("click", function() {
    $contentNavSpan.removeClass("selected-content-nav");
    $(this).addClass("selected-content-nav");
    $passwordBody.addClass("inactive-info");
    $emailBody.removeClass("inactive-info");
  });

  // Click on Password Nav
  $("#content-nav span:nth-child(2)").on("click", function() {
    $contentNavSpan.removeClass("selected-content-nav");
    $(this).addClass("selected-content-nav");
    $emailBody.addClass("inactive-info");
    $passwordBody.removeClass("inactive-info");
  });
}

// Change Profile Picture
function changeProfilePic() {
  displayImage();

  function displayImage () {
  	function readURL(input) {
  		if (input.files && input.files[0] && input.files[0].type.match(/image.*/)) {
  			var reader = new FileReader();
  			reader.onload = function (e) {
  				$('#user-pic').css({"background" : "url(" + e.target.result + ") no-repeat center center", "background-size" : "cover"});
  			};
  			reader.readAsDataURL(input.files[0]);
  		}
  	}
  	$("#img_file").change(function(){
  		readURL(this);
      imageUpload();
  	});
  }

  function imageUpload() {
    $updateContainer = $("#update-container");

    var imageData = new FormData();
    imageData.append('file', $("#img_file")[0].files[0]);
    $.ajax({
      url: AJAX_URL + "WebApp/userSetting/imageUpload.php?files",
      type: 'post',
      data: imageData,
      dataType: 'text',
      processData: false,
      contentType: false,
      success: function(data) {
        if (data === "Updated") {
          $("#update-message").text("Updated Profile Picture");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
        }

        else if (data === "Invalid file size or type"){
          $("#update-message").text("Invalid file type or size");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
        }

        // Server failed
        else {
          console.log(data);
          $("#update-message").text("Failed to update Profile Picture");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
        }
      },
      // Never sent data
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
        $("#update-message").text("Failed to update Profile Picture");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
      }
    });
  }
}


// Allows editing of the name
function changeName() {
	var nameContainer = $("#name-container");
	nameContainer.on("click", function() {
    // 20 is for margin and padding
    // getBoundingClientRect prevents rounding and avoids jittery movement
    var nameWidth = nameContainer[0].getBoundingClientRect().width - 20;
		var $nameInput = $("#name-input");
		var $currentName = $("#current-name");
		var currentNameText = $currentName.text();
    $nameInput.width(nameWidth);
		$nameInput.attr("value", currentNameText);
    $currentName.css({"display" : "none"});
		$nameInput.css({"display" : "inline-block"});
		$nameInput.focus();

		$(document).on("click", titleClickOutside);

		$(document).keypress(titleEnterKey);

    function titleClickOutside(event) {
      if (!$(event.target).closest("#name-container").length) {
				closeName(currentNameText);
			}
    }

    function titleEnterKey(event) {
      var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13') {
				closeName(currentNameText);
			}
    }

    // Compares new and old text to ensure there is actually a difference
		function closeName(currentNameText) {
			var newName = $nameInput.val().trim();
      if (newName === "") {
        newName = currentNameText;
      }

      $nameInput.val(newName);
      $nameInput.css({"display" : "none"});
			$currentName.css({"display" : "inline-block"});
			$(document).unbind("click", titleClickOutside);
			$(document).unbind("keypress", titleEnterKey);
      if (newName != currentNameText) {
        $currentName.html(newName);
        updateName(newName);
      }
		}

    function updateName(newName) {
      $updateContainer = $("#update-container");

      $.ajax({
        url: AJAX_URL + "WebApp/userSetting/update",
        type: "post",
        data: {'updateUser' : 'name', 'newName' : newName},
        dataType: "text",
        success: function(data) {
          if (data === "Updated") {

            $("#update-message").text("Updated Name");
            $updateContainer.css({"opacity" : "1"});
            setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
          }
          // Server failed
          else {
            $("#update-message").text("Failed to update Name");
            $updateContainer.css({"opacity" : "1"});
            setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
          }
        },
        // Never sent data
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
          $("#update-message").text("Failed to update Name");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
        }
      });
    }
	});
}

// Allows user to enter a new email and prompt the database to change current email
// Remember to check with current email before sending
function changeEmail() {
  var $emailInput = $("#new-email");
  $emailInput.on("focus", function() {

    // Hitting enter key will submit
    $emailInput.keydown(function(event){
    var keyCode = (event.keyCode ? event.keyCode : event.which);
    if (keyCode == 13) {
      checkEmail();
      $(this).unbind("blur");
      $(this).blur();
    }
    });

    // Clicking outside of textbox will submit
    $emailInput.on("blur", function(event) {
      checkEmail();
    });

    function checkEmail() {
      var newEmail = $emailInput.val().trim();
      if (IsEmail(newEmail)) {
        $emailInput.css({"border-color" : ""});
        updateEmail(newEmail);
      }

      else  {
        $emailInput.css({"border-color" : "#ff0000"});
        $updateContainer = $("#update-container");
        $("#update-message").text("Incorrect Email Format");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
      }

      $emailInput.unbind("blur");
      $emailInput.unbind("keydown");
    }
  });

  // Returns false if it is not email format, returns true if it is
  function IsEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  }

  // Calls ajax to update email
  function updateEmail(newEmail) {
    $updateContainer = $("#update-container");
    $.ajax({
      url: AJAX_URL + "WebApp/userSetting/update",
      type: "post",
      data: {'updateUser' : 'email', 'newEmail' : newEmail},
      dataType: "text",
      success: function(data) {
        if (data === "Updated") {
          // if successful go to recent feed use ? to signal to recent php that notification needs to be displayed
          $("#update-message").text("Updated Email");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
        }
        // Server failed
        else {
          $("#update-message").text("Failed to update Email");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
        }
      },
      // Never sent data
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
        $("#update-message").text("Failed to update Email");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
      }
    });
  }
}

// Allows user to show or hide password to confirm what they typed
function showHidePassword() {
  $toggle = $(".hideShowPassword-toggle-hide");
  $toggle.on("click", function() {
    $input = $(this).siblings(".password-input");
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

// Updates passwords, but first checks the old password match
function changePassword() {
  $submitButton = $("#submit-button");
  $submitButton.on("click", function() {
    $updateContainer = $("#update-container");
    oldPassword = $("#old-password").val();
    newPassword = $("#new-password").val();
    $(".password-container").css({"border-color" : ""});

    $.ajax({
      url: AJAX_URL + "WebApp/userSetting/update",
      type: "post",
      data: {'updateUser' : 'password', 'oldPassword' : oldPassword, 'newPassword' : newPassword},
      dataType: "text",
      success: function(data) {
        if (data === "Updated") {
          $("#update-message").text("Updated Password");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
        }

        // Old password is incorrect retype it
        else if (data === "Incorrect") {
          $("#old-pass-container").css({"border-color" : "#ff0000"});
          $("#update-message").text("Old Password doesn't match");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
        }
        // Server failed
        else {
          console.log(data);
          $("#update-message").text("Failed to update Password");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
        }
      },
      // Never sent data
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
        $("#update-message").text("Failed to update Password");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
      }
    });
  });
}
