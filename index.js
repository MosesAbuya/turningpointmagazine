// https://stackoverflow.com/a/76978444/383904
const flipBook = (elBook) => {
  elBook.style.setProperty("--c", 0); // Set current to first page
  elBook.querySelectorAll(".page").forEach((page, i) => {
    page.style.setProperty("--i", i);
    page.addEventListener("click", (evt) => {
      const c = !!evt.target.closest(".back") ? i : i + 1;
      elBook.style.setProperty("--c", c);
    });
  });
};

document.querySelectorAll(".book").forEach(flipBook);

document.getElementById("menu-icon").addEventListener("click", function () {
  var navLinks = document.getElementById("nav-links");
  if (navLinks.style.display === "flex") {
    navLinks.style.display = "none";
  } else {
    navLinks.style.display = "flex";
  }
});

// Add event listeners to each .et-hero-tab to close the menu when clicked
var heroTabs = document.querySelectorAll(".et-hero-tab");
heroTabs.forEach(function (tab) {
  tab.addEventListener("click", function () {
    var navLinks = document.getElementById("nav-links");
    navLinks.style.display = "none";
  });
});

// Function to handle success and failure popups
function handlePopupFeedback(param, value, isSuccess) {
  const urlParams = new URLSearchParams(window.location.search);

  if (urlParams.has(param) && urlParams.get(param) === value) {
    // Determine which popup to display
    const successPopup = document.getElementById("success");
    const failedPopup = document.getElementById("failed");

    // Set the appropriate message and display the corresponding popup
    if (isSuccess) {
      document.getElementById("success-message").innerText = value;
      successPopup.style.display = "block";
    } else {
      document.getElementById("failed-message").innerText = value;
      failedPopup.style.display = "block";
    }

    // Remove the query parameter from the URL
    window.history.pushState({}, document.title, window.location.pathname);

    // Hide the popup after 3 seconds
    setTimeout(function () {
      successPopup.style.display = "none";
      failedPopup.style.display = "none";
    }, 3000);
  }
}

// Check different query parameters and handle accordingly
handlePopupFeedback("feedback", "success", true); // Success
handlePopupFeedback("feedback", "failed", false); // Failed

handlePopupFeedback("feedback-s", "success", true); // Success
handlePopupFeedback("feedback-s", "failed", false); // Failed
handlePopupFeedback("feedback-s", "e-failed", false); // Failed

handlePopupFeedback("feedback-c", "success", true); // Success
handlePopupFeedback("feedback-c", "failed", false); // Failed
handlePopupFeedback("feedback-c", "error", false); // Failed

const counts = document.querySelectorAll(".count");
const speed = 97;

counts.forEach((counter) => {
  function upDate() {
    const target = Number(counter.getAttribute("data-target"));
    const count = Number(counter.innerText);
    const inc = target / speed;
    if (count < target) {
      counter.innerText = Math.floor(inc + count);
      setTimeout(upDate, 15);
    } else {
      counter.innerText = target;
    }
  }
  upDate();
});
