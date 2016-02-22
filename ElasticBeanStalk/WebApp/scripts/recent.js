// TO DO: Read and display notification based on PHP data
// Make notification appear for a bit after document is ready

$(document).ready(function() {
  updateMessage();
  previewArticle();
});


function updateMessage() {
  console.log("1");
  if ($("#update-message").text() !== "") {
    console.log("2");

    $updateContainer = $("#update-container");
    $updateContainer.css({"opacity" : "1"});
    setTimeout(function(){ $updateContainer.css("opacity", "0"); },8000);
  }
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
      var locationArchiveIcon = $a.data("locationarchiveicon");
      var locationArchive = $a.data("locationarchive");
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

      console.log(authorImg);
			// How to check te data and create data element
			// AuthorImg will create error because image is not found on server currently (DB implementation will fix it)
			$li.append('<div class="content-more-info">' +
							'<div class="left-info">' +
								'<div class="action-icon">' +
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
                '<p class="content-archive"><i class="fa fa-' + locationArchiveIcon + '"></i>' + locationArchive + '</p>' +
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
