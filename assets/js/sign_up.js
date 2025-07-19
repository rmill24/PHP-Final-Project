document.addEventListener("DOMContentLoaded", function () {
  const form = document.querySelector(".registration-form");
  const emailField = document.getElementById("email");
  const emailError = document.getElementById("emailError");
  let emailCheckTimeout;
  let isEmailAvailable = true;

  const showErrors = (errors) => {
    document.getElementById("formErrors").innerHTML = errors
      .map((e) => `<p>❌ ${e}</p>`)
      .join("");
  };

  const showEmailError = (message) => {
    emailError.innerHTML = `<p class="error-message">❌ ${message}</p>`;
    emailError.style.display = 'block';
    isEmailAvailable = false;
  };

  const clearEmailError = () => {
    emailError.innerHTML = '';
    emailError.style.display = 'none';
    isEmailAvailable = true;
  };

  const checkEmailAvailability = async (email) => {
    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      clearEmailError();
      return;
    }

    try {
      const formData = new FormData();
      formData.append('email', email);

      const response = await fetch('actions/check_email.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (!result.available) {
        showEmailError(result.message);
      } else {
        clearEmailError();
      }
    } catch (error) {
      console.error('Email check failed:', error);
      clearEmailError(); // Don't block submission if check fails
    }
  };

  // Add email field event listener for real-time checking
  if (emailField) {
    emailField.addEventListener('input', function() {
      clearTimeout(emailCheckTimeout);
      const email = this.value.trim();
      
      if (email) {
        // Debounce the email check - wait 500ms after user stops typing
        emailCheckTimeout = setTimeout(() => {
          checkEmailAvailability(email);
        }, 500);
      } else {
        clearEmailError();
      }
    });

    emailField.addEventListener('blur', function() {
      const email = this.value.trim();
      if (email) {
        checkEmailAvailability(email);
      }
    });
  }

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
