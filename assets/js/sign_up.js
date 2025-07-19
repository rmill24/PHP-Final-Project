document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".registration-form");
  const showErrors = (errors) => {
    document.getElementById("formErrors").innerHTML = errors
      .map((e) => `<p>❌ ${e}</p>`)
      .join("");
  };

  if (form) {
    form.addEventListener("submit", function (e) {
      const errors = [];
      const getValue = (id) => document.getElementById(id).value.trim();
      const pw = getValue("password");
      const cpw = getValue("confirmPassword");

      const validators = [
        {
          test: /^[A-Za-z\s'-]+$/.test(getValue("firstName")),
          message: "First name should only contain letters and spaces.",
        },
        {
          test: /^[A-Za-z\s'-]+$/.test(getValue("lastName")),
          message: "Last name should only contain letters and spaces.",
        },
        {
          test: (() => {
            const phone = getValue("phone");
            const digitsOnly = phone.replace(/[^0-9]/g, "");
            return digitsOnly.length === 11 && /^[0-9+\-\s()]+$/.test(phone);
          })(),
          message:
            "Phone number must be exactly 11 digits and contain only numbers and formatting characters (+, -, spaces, parentheses).",
        },
        {
          test: /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(getValue("email")),
          message: "Email format is invalid.",
        },
        {
          test: /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]).{12,}$/.test(
            pw
          ),
          message:
            "Password must be at least 12 characters with one uppercase letter, one number, and one symbol.",
        },
        { test: pw === cpw, message: "Passwords do not match." },
        {
          test: getValue("street").length >= 5,
          message: "Street address must be at least 5 characters long.",
        },
        {
          test: getValue("city").length >= 2,
          message: "City must be at least 2 characters long.",
        },
        {
          test: getValue("state").length >= 2,
          message: "State/Province must be at least 2 characters long.",
        },
        {
          test: /^[0-9]{4,10}$/.test(getValue("zipCode")),
          message: "ZIP code must be 4-10 digits.",
        },
        {
          test: getValue("country").length > 0,
          message: "Please select a country.",
        },
      ];

      validators.forEach((v) => {
        if (!v.test) errors.push(v.message);
      });

      if (errors.length > 0) {
        e.preventDefault();
        showErrors(errors);
      }
    });
  }
});
