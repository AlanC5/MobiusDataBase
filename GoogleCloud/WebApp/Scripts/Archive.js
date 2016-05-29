// var AJAX_URL =  "http://mobi-1171.appspot.com/";
var AJAX_URL = "http://mobius-website-1.appspot.com/";


// May need to be changed depending on server changes and URL
var url = location.href;
var urlParam = url.substring(url.indexOf("?")+1).replace('=', '');
var $selectedArchive = $("#archive-panel").children("#" + urlParam);

$(document).ready(function() {

  $("#title-input").autoGrowInput({minWidth: 100,comfortZone:0});
  autosize($("#description-input"));
  changeTitle();
  // Change Privacy
  clickDropdown("#privacy-click","#privacy-dropdown", ".privacy-dropdown-option","#privacy-container", updatePrivacy);
  // Change Symbol
  clickDropdown("#symbol-click", "#symbol-dropdown", ".symbol-dropdown-option", "#symbol-container", updateSymbol);
  changeDescription();
  optionDropdown();
  previewArticle();
  deleteArticle();
});

// Allows user to click on title to change title to input box
function changeTitle () {
	var archiveTitle = $("#archive-title");
	archiveTitle.on("click", function() {
    // 20 is for margin and padding
    // getBoundingClientRect prevents rounding and avoids jittery movement
    var titleWidth = archiveTitle[0].getBoundingClientRect().width - 20;
		var $titleInput = $("#title-input");
		var $currentTitle = $("#current-title");
		var currentTitleText = $currentTitle.text();
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
    // Ensures textbox isn't empty, if it is then replace with old text
		function closeTitle(currentTitleText) {
      // Remove all whitespaces at end and beginning
			var newTitle = $titleInput.val().trim();
      if (newTitle === "") {
        newTitle = currentTitleText;
      }

      $titleInput.val(newTitle);
      $titleInput.css({"display" : "none"});
  		$currentTitle.css({"display" : "inline-block"});
			$(document).unbind("click", titleClickOutside);
  		$(document).unbind("keypress", titleEnterKey);
      if (newTitle != currentTitleText) {
        $currentTitle.html(newTitle);
        updateTitle(newTitle);
      }
		}
	});
}

function clickDropdown(clickElement, dropdownElement, optionElement, container, updateDB) {
  var click = $(clickElement);
  var dropdown = $(dropdownElement);
  click.on("click", function() {
    dropdown.removeClass("invisible");
    dropdown.children(optionElement).on("click", function() {
      var newElement = $(this).html();
      //Compares old and new, only execute call to database if they are different
      // Seperate privacy and icon
      if (container == "#privacy-container") {
        var currentPrivacyStatus = click.children("span").text();
        if ($(this).children("span").text() !== currentPrivacyStatus) {
          click.html(newElement);
          // 0 means public
          // 1 means private
          var newPrivacy = 0;
          if ($(this).children("span").text() == "Private") {
            newPrivacy = 1;
          }
          updateDB(newPrivacy);
        }
      }

      if (container == "#symbol-container") {
        var currentSymbol = click.children(".fa").attr('class').replace("fa fa-","");
        var newSymbol = $(this).children(".fa").attr('class').replace("fa fa-","");
        if (newSymbol !== currentSymbol) {
          click.html(newElement);
          updateDB(newSymbol);
        }
      }

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

function changeDescription() {
  var $descriptionInput = $("#description-input");
  $descriptionInput.on("focus", function() {
    var currentDescription = $descriptionInput.val().trim();

    $descriptionInput.on("blur", function() {
      var newDescription = $descriptionInput.val().trim();
      if (currentDescription != newDescription) {
        updateDescription(newDescription);
      }
      $descriptionInput.unbind("blur");
    });
  });

}

function deleteArchive() {
  $.ajax({
    url: AJAX_URL + "WebApp/archive/update",
    type: "post",
    data: {'update' : 'delete'},
    dataType: "text",
    success: function(data) {
      if (data === "Deleted") {
        var archiveName = $("#current-title").text();
        // redirect change window
        window.location = AJAX_URL + "recent?action=delete&archive=" + archiveName;
        // $updateContainer = $("#update-container");
        // $("#update-message").text("Deleted Archive");
        // $updateContainer.css({"opacity" : "1"});
        // setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
      }
      // Server failed
      else {
        $updateContainer = $("#update-container");
        $("#update-message").text("Failed to delete Archive");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
      }
    },
    // Never sent data
    error: function(xhr, desc, err) {
      console.log(xhr);
      console.log("Details: " + desc + "\nError:" + err);
      $updateContainer = $("#update-container");
      $("#update-message").text("Failed to delete Archive");
      $updateContainer.css({"opacity" : "1"});
      setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
    }
  });
}

function updatePrivacy(newPrivacy) {
  $.ajax({
    url: AJAX_URL + "WebApp/archive/update",
    type: "post",
    data: {'update' : 'privacy', 'newPrivacy' : newPrivacy },
    dataType: "text",
    success: function(data) {
      if (data === "Success") {
        $updateContainer = $("#update-container");
        $("#update-message").text("Updated Privacy");
        if (newPrivacy === 1) {
          $selectedArchive.children(".archive").append('<i class="fa fa-lock"></i>');
        }

        if (newPrivacy === 0) {
          $selectedArchive.children(".archive").children(".fa-lock").remove();
        }
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
      }
      // Server failed
      else {
        $updateContainer = $("#update-container");
        $("#update-message").text("Failed to update Privacy");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
      }
    },
    // Never sent data
    error: function(xhr, desc, err) {
      console.log(xhr);
      console.log("Details: " + desc + "\nError:" + err);
      $updateContainer = $("#update-container");
      $("#update-message").text("Failed to update Privacy");
      $updateContainer.css({"opacity" : "1"});
      setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
    }
  });
}

function updateSymbol(newSymbol) {
  $.ajax({
    url: AJAX_URL + "WebApp/archive/update",
    type: "post",
    data: {'update' : 'symbol', 'newSymbol' : newSymbol },
    dataType: "text",
    success: function(data) {
      if (data === "Success") {
        $updateContainer = $("#update-container");
        $("#update-message").text("Updated Symbol");
        $selectedArchive.children(".archive").children(":first-child").remove();
        $selectedArchive.children(".archive").prepend('<i class="fa fa-' + newSymbol + '"></i>');
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
      }
      // Server failed
      else {
        $updateContainer = $("#update-container");
        $("#update-message").text("Failed to update Symbol");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
      }
    },
    error: function(xhr, desc, err) {
      console.log(xhr);
      console.log("Details: " + desc + "\nError:" + err);
      $updateContainer = $("#update-container");
      $("#update-message").text("Failed to update Symbol");
      $updateContainer.css({"opacity" : "1"});
      setTimeout(function(){ $updateContainer.css("opacity", "0"); },5000);
    }
  });
}

function updateTitle(newTitle) {
  $.ajax({
    url: AJAX_URL + "WebApp/archive/update",
    type: "post",
    data: {'update' : 'title', 'newTitle' : newTitle },
    dataType: "text",
    success: function(data) {
      console.log(data);
      if (data === "Success") {
        $updateContainer = $("#update-container");
        $("#update-message").text("Updated Title");
        $selectedArchive.children(".archive").children("p").text(newTitle);
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
      }
      // Server failed
      else {
        $updateContainer = $("#update-container");
        $("#update-message").text("Failed to update Title");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
      }
    },
    error: function(xhr, desc, err) {
      console.log(xhr);
      console.log("Details: " + desc + "\nError:" + err);
      $updateContainer = $("#update-container");
      $("#update-message").text("Failed to update Title");
      $updateContainer.css({"opacity" : "1"});
      setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
    }
  });
}

function updateDescription(newDescription) {
  $.ajax({
    url: AJAX_URL + "WebApp/archive/update",
    type: "post",
    data: {'update' : 'description', 'newDescription' : newDescription },
    dataType: "text",
    success: function(data) {
      console.log(data);
      if (data === "Success") {
        $updateContainer = $("#update-container");
        $("#update-message").text("Updated Description");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
      }
      // Server failed
      else {
        $updateContainer = $("#update-container");
        $("#update-message").text("Failed to update Description");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
      }
    },
    error: function(xhr, desc, err) {
      console.log(xhr);
      console.log("Details: " + desc + "\nError:" + err);
      $updateContainer = $("#update-container");
      $("#update-message").text("Failed to update Description");
      $updateContainer.css({"opacity" : "1"});
      setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
    }
  });
}

var previousLi;
var previousContent;
function previewArticle () {
	$("#og-grid").on("click", "li", function() {

		if (previousLi && previousContent) {
			previousLi.removeClass("og-expanded");
			previousLi.css({"height" : "190px"});
			previousContent.css({"height" : "0px"});
		}

		var $li = $(this);
		var $a = $(this).children(".content-container").children("a");
		$li.addClass("og-expanded");
		$li.css({"transition" : "height 350ms ease", "height" : "475px"});

		previousLi = $li;

		if ($li.data("status") === "created") {
			$li.children(".content-more-info").css({"height" : "275px"});
			previousContent = $li.children(".content-more-info");
		}
		else {
			// append status data-attribute
			$li.attr("data-status", "created");
			var shareLink = $a.attr("href");
			var title = $a.data("title");
			var author = $a.data("author");
			var authorImg = $a.data("authorimg");			//Location
			var description = $a.data("description");
			var privacy = $a.data("privacy");
			var privacyIcon = "globe";
      var privacyState = "Public";
			if (privacy === 1) {
				privacyIcon = "lock";
        privacyState = "Private";
			}

      if (shareLink.indexOf("http://") === 0 || shareLink.indexOf("https://") === 0) {
      }
      else {
        shareLink = "http://" + shareLink;
      }

			$li.append('<div class="content-more-info">' +
							'<div class="left-info">' +
								'<div class="action-icon">' +
									'<i class="fa fa-trash"></i>' +
									'<i class="fa fa-pencil"></i>' +
								'</div>' +
								'<div class="user-info">' +
									'<div class="person" style="background:url(' + authorImg + ') 50% 50% / cover no-repeat;"></div>' +
									'<div><p class="name">' + author + '</p></div>' +
								'</div>' +
								'<span class="privacy"><i class="fa fa-' + privacyIcon +' sharability"></i>' + privacyState + '</span>' +
								'<div class="share-link">' +
									'<input type="text" name="URL-text" value="' + shareLink + '" onClick="this.select();" readonly="readonly"/>' +
								'</div>' +
								'<div class="social-media">' +
									'<i class="fa fa-facebook-official"></i>' +
									'<i class="fa fa-twitter-square"></i>' +
									'<i class="fa fa-linkedin-square"></i>' +
								'</div>' +
							'</div>' +
							'<div class="right-info">' +
								'<a href="' + shareLink + '" target="_blank"><p class="content-title">' +  title + '</p></a>' +
								'<p class="content-description">' + description + '</p>' +
							'</div>' +
						'</div>');
			previousContent = $li.children(".content-more-info");
			$li.children(".content-more-info").css({"transition" : "height 350ms ease", "height" : "275px"});
		}
		/*	Scroll to the expanded content-more-info
			Cannot get scrollTop to work, it is constantly grabbing the top value before the content finishes transition
		$('html, body').animate({
            scrollTop: $li.offset().top - 55
        }, 1000);*/
	});
}

// Deletes article, calls the database, if successfully deleted remove
function deleteArticle() {
  $("#og-grid li").on("click", ".content-more-info .action-icon .fa-trash", function() {
    var $updateContainer = $("#update-container");
    var $a = $(this).closest(".content-more-info").siblings(".content-container").children("a");
    var articleTitle = $a.data("title");
    var articleId = $a.data("articleid");

    $.ajax({
      url: AJAX_URL + "WebApp/archive/update",
      type: "post",
      data: {'update' : 'deleteArticle', 'article' : articleId },
      dataType: "text",
      success: function(data) {
        console.log(data);
        if (data === "Deleted") {
          $("#update-message").text("Deleted " + articleTitle);
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
          $(this).closest(".og-expanded").remove();
        }
        // Server failed
        else {
          $("#update-message").text("Failed to delete Article");
          $updateContainer.css({"opacity" : "1"});
          setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
        }
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
        $("#update-message").text("Failed to delete Article");
        $updateContainer.css({"opacity" : "1"});
        setTimeout(function(){ $updateContainer.css("opacity", "0"); },2000);
      }
    });
  });
}
