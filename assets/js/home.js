// Carousel Functionality
document.addEventListener("DOMContentLoaded", function () {
  const carouselItems = document.querySelectorAll(".carousel-item");
  const indicators = document.querySelectorAll(".carousel-indicator");
  let currentSlide = 0;
  const slideCount = carouselItems.length;

  // Function to show a specific slide
  function showSlide(index) {
    carouselItems.forEach((item) => item.classList.remove("active"));
    indicators.forEach((indicator) => indicator.classList.remove("active"));

    carouselItems[index].classList.add("active");
    indicators[index].classList.add("active");
    currentSlide = index;
  }

  // Auto-advance slides every 5 seconds
  function autoAdvance() {
    currentSlide = (currentSlide + 1) % slideCount;
    showSlide(currentSlide);
  }

  let slideInterval = setInterval(autoAdvance, 5000);

  // Add click event to indicators
  indicators.forEach((indicator, index) => {
    indicator.addEventListener("click", () => {
      clearInterval(slideInterval);
      showSlide(index);
      slideInterval = setInterval(autoAdvance, 5000);
    });
  });

  // Pause on hover
  const carousel = document.querySelector(".carousel");
  carousel.addEventListener("mouseenter", () => {
    clearInterval(slideInterval);
  });

  carousel.addEventListener("mouseleave", () => {
    slideInterval = setInterval(autoAdvance, 5000);
  });

  document
    .querySelector(".registration-form")
    .addEventListener("submit", function (e) {
      const pw = document.getElementById("password").value;
      const cpw = document.getElementById("confirmPassword").value;
      if (pw !== cpw) {
        alert("Passwords do not match.");
        document.getElementById("password").value = "";
        document.getElementById("confirmPassword").value = "";
        e.preventDefault();
      }
    });

  setTimeout(() => {
    const alert = document.querySelector(".alert.alert-success");
    if (alert) alert.style.display = "none";
  }, 10000);

  document.querySelector(".registration-form").addEventListener("submit", function (e) {
    const errors = [];

    const firstName = document.getElementById("firstName").value.trim();
    const lastName = document.getElementById("lastName").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    const nameRegex = /^[A-Za-z\s'-]+$/;
    const phoneRegex = /^\d{11}$/;
    const emailRegex = /^[a-zA-Z0-9_.±]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$/;

    if (!nameRegex.test(firstName)) {
        errors.push("First name should only contain letters and spaces.");
    }

    if (!nameRegex.test(lastName)) {
        errors.push("Last name should only contain letters and spaces.");
    }

    if (!phoneRegex.test(phone)) {
        errors.push("Phone number must be exactly 11 digits.");
    }

    if (!emailRegex.test(email)) {
        errors.push("Email format is invalid.");
    }

    if (password.length < 6) {
        errors.push("Password must be at least 6 characters.");
    }

    if (password !== confirmPassword) {
        errors.push("Passwords do not match.");
    }

    if (errors.length > 0) {
        e.preventDefault(); // Stop form from submitting
        document.getElementById("formErrors").innerHTML = errors.map(err => `<p>• ${err}</p>`).join("");
    }
});
});
