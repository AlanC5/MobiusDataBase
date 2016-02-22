$(document).ready(function() {
  slideMenu();
  scrollNav();
  // must adjust resizing of windows
  // adjusts height of lower body to allow for better scrolling, avoids the fixed nav scrolling problem
  // var bodyHeight = $(window).height() - 110;
  // $("#content-body").height(bodyHeight);
});


// RESIZE WINDOW will close slide menu and resize NAV
// COULD BE CLEAN UP MORE
function slideMenu() {
  // After menu is clicked, change it so that further clicks would revert it back
  $("#menu").on("click", function() {
    slideMovement();
  });

  // Movement for when menu is clicked
  function slideMovement() {
    // 50 for top-nav and 23 for archive-top
    var archiveMenuHeight = $(window).height() - 73;
    $("#archive-panel").height(archiveMenuHeight);
    var currentWidth = $("#main-content-container").width() - 250;
    $("#main-content-container").animate({
      width: currentWidth
    }, {
      duration: 500,
      specialEasing: {
        width: "linear"
      }
    });

    $("#menu-container").animate({
      left: "0"
    }, {
      duration: 500,
      specialEasing: {
        left: "linear"
      }
    });
    // Prevent menu from being clicked another time until menu has been closed
    $("#menu").unbind("click");
  }

  // Close menu
  $("#exit-menu").on("click", function() {
    $("#main-content-container").animate({
      width: "100%"
    }, {
      duration: 500,
      specialEasing: {
        width: "linear"
      }
    });

    $("#menu-container").animate({
      left: "-250"
    }, {
      duration: 500,
      specialEasing: {
        left: "linear"
      }
    });

    $("#menu").on("click", function() {
      slideMovement();
    });
  });
}

function scrollNav() {
  var bodyHeight = $(window).height() - 110;
  $("#content-body").height(bodyHeight);
}
