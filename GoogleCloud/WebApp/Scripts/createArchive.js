// Prior to sending data via AJAX, confirm title and icon are set

// var AJAX_URL =  "http://mobi-1171.appspot.com/";
var AJAX_URL = "http://mobius-website-1.appspot.com/";

$(document).ready(function() {
  $("#title-input").autoGrowInput({minWidth: 100,comfortZone:0});
  autosize($("#description-input"));
  changeTitle();
  // Change Privacy
  clickDropdown("#privacy-click","#privacy-dropdown", ".privacy-dropdown-option","#privacy-container");
  // Change Symbol
  clickDropdown("#symbol-click", "#symbol-dropdown", ".symbol-dropdown-option", "#symbol-container");
  submitData();

});

function changeTitle () {
	var archiveTitle = $("#archive-title");
  var firstClick = 1;
	archiveTitle.on("click", function() {
    // 20 is for margin and padding
    // getBoundingClientRect prevents rounding and avoids jittery movement
    var titleWidth = archiveTitle[0].getBoundingClientRect().width - 20;
		var $titleInput = $("#title-input");
		var $currentTitle = $("#current-title");
		var currentTitleText = $currentTitle.text();

    if (firstClick === 1) {
      firstClick ++;
      $titleInput.val("");
      $currentTitle.css({"color" : "#20AAEA"});
    }

    $titleInput.width(titleWidth);
		$titleInput.attr("value", currentTitleText);
		$currentTitle.css({"display" : "none"});
		$titleInput.css({"display" : "inline-block"});
		$titleInput.focus();


		$(document).on("click", titleClickOutside);

		$(document).keypress(titleEnterKey);

    function titleClickOutside(event) {
      if (!$(event.target).closest("#archive-title").length) {
				closeTitle(currentTitleText);
			}
    }

    function titleEnterKey(event) {
      var keycode = (event.keyCode ? event.keyCode : event.which);
			if(keycode == '13') {
				closeTitle(currentTitleText);
			}
    }

    // Compares new and old text to ensure there is actually a difference
		function closeTitle(currentTitleText) {
			var newTitle = $titleInput.val();
      if (newTitle.trim().length > 0) {
        if (newTitle != currentTitleText) $currentTitle.html(newTitle);
      }
      // Cannot have empty string, box will collapse
      else {
        $currentTitle.css({"color" : "#A9ABBC"});
        $currentTitle.text("TITLE");
        firstClick = 1;
      }
      $titleInput.css({"display" : "none"});
			$currentTitle.css({"display" : "inline-block"});
			$(document).unbind("click", titleClickOutside);
			$(document).unbind("keypress", titleEnterKey);
		}
	});

}

function clickDropdown(clickElement, dropdownElement, optionElement, container) {
  var click = $(clickElement);
  var dropdown = $(dropdownElement);
  var firstClick = 1;
  click.on("click", function() {
    dropdown.removeClass("invisible");
    dropdown.children(optionElement).on("click", function() {
      var newElement = $(this).html();
      //Compares old and new, only execute call to database if they are different
      // Seperate privacy and icon
      // Note for privacy and DB 0 means public, 1 means private
      if (firstClick === 1 && container == "#symbol-container") {
        firstClick ++;
        $("#archive-center #symbol-click").removeClass("visible-border-color");
        $("#archive-center #symbol-click").addClass("transparent-border-color");
      }

      click.html(newElement);

      closeDropdown();

    });

    $(document).on("click", dropdownClickOutside);

    function dropdownClickOutside(event) {
      if (!$(event.target).closest(container).length) {
				closeDropdown();
			}
    }

    function closeDropdown() {
      $(document).unbind("click", dropdownClickOutside);
			dropdown.children(optionElement).unbind("click");
			dropdown.addClass("invisible");
    }
  });
}

function optionDropdown() {
  var click = $("#archive-option-click");
  var dropdown = $("#archive-option-dropdown");
  click.on("click", function() {
    dropdown.removeClass("invisible");
    dropdown.children("#delete-archive").on("click", function() {
      deleteArchive();
      closeDropdown();
    });

    $(document).on("click", optionClickOutside);

    function optionClickOutside(event) {
      if (!$(event.target).closest("#archive-option-container").length) {
				closeDropdown();
			}
    }

    function closeDropdown() {
      $(document).unbind("click", optionClickOutside);
			dropdown.children("#delete-archive").unbind("click");
			dropdown.addClass("invisible");
    }

  });
}

// Checks if title and icon are filled out. If so, send data to DB. If not, highlight the borders bright red and leave update message
function submitData() {
  $("#submit-button").on("click", function() {
    var title = $("#title-input").val().trim();
    //extract only icon name, clean up string by removing 'fa fa-'
    var icon = $("#symbol-click").children("i").attr("class");
    var description = $("#description-input").val();
    var privacy = $("#privacy-click span").text();

    if (icon === "") {
      $updateContainer = $("#update-container");
      $("#update-message").text("Required: Pick an Symbol");
      $updateContainer.css({"opacity" : "1"});
      setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
    }

    if (title === "") {
      $updateContainer = $("#update-container");
      $("#update-message").text("Required: Add a Title");
      $updateContainer.css({"opacity" : "1"});
      setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
    }

    if (title !== "" && icon !== "") {
      // Remove icon "fa fa-"
      icon = icon.replace('fa fa-', '');

      $.ajax({
        url: AJAX_URL + "WebApp/createArchive",
        type: "post",
        data: {'create' : 'newArchive',
                'title' : title, 'icon' : icon,
                'description' : description,
                'privacy' : privacy },
        dataType: "text",
        success: function(data) {
          // if successful go to recent feed use ? to signal to recent php that notification needs to be displayed
          // Checks if number
          if (!isNaN(data)) {
            console.log(data);
            $updateContainer = $("#update-container");
            // window.location = "http://localhost/website/archive/index.php?archive=" + data;
            $("#update-message").text("Created Archive");
            $updateContainer.css({"opacity" : "1"});
            setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
          }

          else if (data === "Exists") {
            $updateContainer = $("#update-container");
            $("#update-message").text("Archive already exists. Please change Title.");
            $updateContainer.css({"opacity" : "1"});
            setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
          }
          // Server failed
          else {
            $updateContainer = $("#update-container");
            $("#update-message").text("Failed to create Archive");
            $updateContainer.css({"opacity" : "1"});
            setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
          }
        },
        error: function(xhr, desc, err) {
          console.log(xhr);
          console.log("Details: " + desc + "\nError:" + err);
          $updateContainer = $("#update-container");
          $("#update-message").text("Failed to create Archive");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
        }
      });
    }
  });
}
