document.addEventListener("DOMContentLoaded", function () {
  const carouselItems = document.querySelectorAll(".carousel-item");
  const indicators = document.querySelectorAll(".carousel-indicator");
  const carousel = document.querySelector(".carousel");
  let currentSlide = 0;
  const slideCount = carouselItems.length;

  function showSlide(index) {
    carouselItems.forEach((item) => item.classList.remove("active"));
    indicators.forEach((indicator) => indicator.classList.remove("active"));
    carouselItems[index].classList.add("active");
    indicators[index].classList.add("active");
    currentSlide = index;
  }

  let slideInterval = setInterval(() => {
    currentSlide = (currentSlide + 1) % slideCount;
    showSlide(currentSlide);
  }, 5000);

  indicators.forEach((indicator, index) => {
    indicator.addEventListener("click", () => {
      clearInterval(slideInterval);
      showSlide(index);
      slideInterval = setInterval(() => {
        currentSlide = (currentSlide + 1) % slideCount;
        showSlide(currentSlide);
      }, 5000);
    });
  });

  if (carousel) {
    carousel.addEventListener("mouseenter", () => clearInterval(slideInterval));
    carousel.addEventListener("mouseleave", () => {
      slideInterval = setInterval(() => {
        currentSlide = (currentSlide + 1) % slideCount;
        showSlide(currentSlide);
      }, 5000);
    });
  }

  const form = document.querySelector(".registration-form");
  const showErrors = (errors) => {
    document.getElementById("formErrors").innerHTML = errors.map(e => `<p>❌ ${e}</p>`).join("");
  };

  if (form) {
    form.addEventListener("submit", function (e) {
      const errors = [];
      const getValue = id => document.getElementById(id).value.trim();
      const pw = getValue("password");
      const cpw = getValue("confirmPassword");

      const validators = [
        { test: /^[A-Za-z\s'-]+$/.test(getValue("firstName")), message: "First name should only contain letters and spaces." },
        { test: /^[A-Za-z\s'-]+$/.test(getValue("lastName")), message: "Last name should only contain letters and spaces." },
        { test: /^\d{11}$/.test(getValue("phone")), message: "Phone number must be exactly 11 digits." },
        { test: /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(getValue("email")), message: "Email format is invalid." },
        { test: /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{12,}$/.test(pw), message: "Password must be at least 12 characters with one uppercase letter, one number, and one symbol." },
        { test: pw === cpw, message: "Passwords do not match." },
        { test: getValue("address").length >= 8, message: "Address must be at least 8 characters long." }
      ];

      validators.forEach(v => { if (!v.test) errors.push(v.message); });

      if (errors.length > 0) {
        e.preventDefault();
        showErrors(errors);
      }
    });

    // Auto-dismiss success alerts
    setTimeout(() => {
      const alert = document.querySelector(".alert.alert-success");
      if (alert) alert.style.display = "none";
    }, 10000);
  }
});
