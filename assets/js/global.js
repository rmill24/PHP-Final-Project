document.addEventListener("DOMContentLoaded", function () {
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
  const closeMenuBtn = document.querySelector(".close-menu-btn");
  const mobileMenuOverlay = document.querySelector(".mobile-menu-overlay");
  const mobileMenuContainer = document.querySelector(".mobile-menu-container");

  // Toggle mobile menu
  mobileMenuToggle.addEventListener("click", function () {
    mobileMenuOverlay.classList.add("active");
    document.body.style.overflow = "hidden"; // Prevent scrolling
  });

  // Close menu when clicking close button or overlay
  function closeMenu() {
    mobileMenuOverlay.classList.remove("active");
    document.body.style.overflow = ""; // Re-enable scrolling
  }

  closeMenuBtn.addEventListener("click", closeMenu);
  mobileMenuOverlay.addEventListener("click", function (e) {
    if (e.target === mobileMenuOverlay) {
      closeMenu();
    }
  });

  // Close menu when clicking on a link
  const mobileNavLinks = document.querySelectorAll(".mobile-nav-links a");
  mobileNavLinks.forEach((link) => {
    link.addEventListener("click", closeMenu);
  });

  // Close menu when pressing Escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && mobileMenuOverlay.classList.contains("active")) {
      closeMenu();
    }
  });
});

function openModal() {
  const modalOverlay = document.querySelector(".modal-overlay");
  const modal = modalOverlay.querySelector(".modal");
  const errorDiv = document.getElementById("loginError");

  if (errorDiv) errorDiv.textContent = ""; // Clear old errors

  modalOverlay.classList.add("active");
  modal.classList.add("active");
}

function closeModal() {
  const modalOverlay = document.querySelector(".modal-overlay");
  const modal = modalOverlay.querySelector(".modal");

  modal.classList.remove("active");
  modalOverlay.classList.remove("active");
}

function handleLogin(e) {
  e.preventDefault();

  const email = document.getElementById("loginEmail").value;
  const password = document.getElementById("loginPassword").value;

  fetch("/PHP-Final-Project/actions/login.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(
      password
    )}`,
  })
    .then((response) => response.text())
    .then((result) => {

      console.log("Server response:", result); // Add this

      if (result === "success") {
        window.location.href = "index.php?page=home";
      } else {
        const errorDiv = document.getElementById("loginError");
        if (result === "success") {
          window.location.href = "index.php?page=home";
        } else if (result === "unverified") {
          errorDiv.textContent =
            "⚠️ Please verify your email before logging in.";
        } else {
          errorDiv.textContent = "❌ Invalid email or password.";
        }
      }
    })
    .catch((err) => {
      console.error("Login error:", err);
      alert("❌ An error occurred. Try again.");
    });
}
