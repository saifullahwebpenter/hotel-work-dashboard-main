
  // side bar drop down
  document.addEventListener("DOMContentLoaded", function () {
    var dropdowns = document.querySelectorAll(".dropdown-btn");
    dropdowns.forEach(function (dropdown) {
      // Initially hide the dropdown content
      var dropdownContent = dropdown.nextElementSibling;
      dropdownContent.style.maxHeight = "0";
  
      dropdown.addEventListener("click", function () {
        // Close all other dropdowns
        dropdowns.forEach(function (otherDropdown) {
          var otherContent = otherDropdown.nextElementSibling;
          if (otherDropdown !== dropdown) {
            otherContent.style.maxHeight = "0";
            otherContent.classList.remove("show");
          }
        });
  
        // Toggle the clicked dropdown
        if (dropdownContent.style.maxHeight === "0px") {
          dropdownContent.style.maxHeight = dropdownContent.scrollHeight + "px";
          dropdownContent.classList.add("show");
        } else {
          dropdownContent.style.maxHeight = "0";
          dropdownContent.classList.remove("show");
        }
      });
    });
  });

  // 
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector(".form-control");
    const searchButton = document.querySelector(".search-btn");
  
    searchButton.addEventListener("click", function () {
      searchInput.style.width = "200px";
    });
  });
  
  document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.querySelector(".form-control");
    const searchButton = document.querySelector(".clear-btn");
  
    searchButton.addEventListener("click", function () {
      searchInput.style.width = "0";
    });
  });

// profile btn dropdown
  document.addEventListener("DOMContentLoaded", function () {
    const profileButton = document.querySelector(".profile");
    const profileDropdown = document.querySelector(".profile-dropdown");
  
    profileButton.addEventListener("click", function (event) {
      event.stopPropagation(); // Prevent the click event from bubbling up
      if (profileDropdown.style.display === "block") {
        profileDropdown.style.display = "none";
      } else {
        profileDropdown.style.display = "block";
      }
    });
  
    // Optional: Close the dropdown when clicking outside of it
    document.addEventListener("click", function (event) {
      if (!profileButton.contains(event.target) && !profileDropdown.contains(event.target)) {
        profileDropdown.style.display = "none";
      }
    });
  });


//   screen zoom
document.querySelector('.zoom button').addEventListener('click', function() {
    const icon = this.querySelector('i'); // Select the <i> tag inside the button
    
    if (!document.fullscreenElement) {
        // Enter full-screen mode
        const chartContainer = document.documentElement; // You can replace this with a specific element if needed
        
        if (chartContainer.requestFullscreen) {
            chartContainer.requestFullscreen();
        } else if (chartContainer.mozRequestFullScreen) { // Firefox
            chartContainer.mozRequestFullScreen();
        } else if (chartContainer.webkitRequestFullscreen) { // Chrome, Safari, and Opera
            chartContainer.webkitRequestFullscreen();
        } else if (chartContainer.msRequestFullscreen) { // IE/Edge
            chartContainer.msRequestFullscreen();
        }
        
        // Change icon to "minimize" when entering full-screen
        icon.classList.remove('fa-expand');
        icon.classList.add('fa-arrows-maximize');
        
    } else {
        // Exit full-screen mode
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) { // Firefox
            document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) { // Chrome, Safari, and Opera
            document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { // IE/Edge
            document.msExitFullscreen();
        }
        
        // Change icon back to "maximize" when exiting full-screen
        icon.classList.remove('fa-arrows-maximize');
        icon.classList.add('fa-expand');
    }
});

document.getElementById('fail-withdraw-btn').addEventListener('click', function() {
  document.getElementById('fail-withdraw').style.display = 'block'; // Show the div
});

document.getElementById('fail-withdraw-cancel-btn').addEventListener('click', function() {
  document.getElementById('fail-withdraw').style.display = 'none'; // Hide the div
});



