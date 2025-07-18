document.addEventListener("DOMContentLoaded", function () {
  // ========== MOBILE MENU ==========
  const mobileMenuToggle = document.querySelector(".mobile-menu-toggle");
  const closeMenuBtn = document.querySelector(".close-menu-btn");
  const mobileMenuOverlay = document.querySelector(".mobile-menu-overlay");

  function closeMenu() {
    mobileMenuOverlay.classList.remove("active");
    document.body.style.overflow = "";
  }

  mobileMenuToggle?.addEventListener("click", () => {
    mobileMenuOverlay.classList.add("active");
    document.body.style.overflow = "hidden";
  });

  closeMenuBtn?.addEventListener("click", closeMenu);

  mobileMenuOverlay?.addEventListener("click", (e) => {
    if (e.target === mobileMenuOverlay) closeMenu();
  });

  document.querySelectorAll(".mobile-nav-links a").forEach((link) => {
    link.addEventListener("click", closeMenu);
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && mobileMenuOverlay.classList.contains("active")) {
      closeMenu();
    }
  });

  // ========== LOGIN MODAL ==========
  const profileLink = document.getElementById("profileLink");
  profileLink?.addEventListener("click", function (e) {
    e.preventDefault();
    fetch("actions/check_login.php")
      .then((res) => res.text())
      .then((status) => {
        if (status === "logged_in") {
          window.location.href = "index.php?page=user";
        } else {
          openModal();
        }
      })
      .catch(() => openModal());
  });

  // ========== ADD TO CART FORMS ==========
  document.querySelectorAll(".add-to-cart-form").forEach((form) => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const productId = form.getAttribute("data-product-id");
      const sizeId = form.getAttribute("data-size-id");
      const quantity = 1;

      fetch("actions/add_to_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `product_id=${encodeURIComponent(
          productId
        )}&size_id=${encodeURIComponent(sizeId)}&quantity=${quantity}`,
      })
        .then((res) => res.text())
        .then((response) => {
          if (response === "added") {
            updateCartCountBadge();
          } else if (response === "unauthorized") {
            alert("⚠️ Please log in to add items to your cart.");
          } else {
            alert("❌ Something went wrong.");
          }
        })
        .catch((err) => {
          console.error("Error adding to cart:", err);
          alert("❌ Server error.");
        });
    });
  });

  // ========== INITIALIZE CART COUNT BADGE ==========
  updateCartCountBadge();
});

// ========== CART COUNT BADGE ==========
function updateCartCountBadge() {
  fetch("actions/get_cart_count.php")
    .then((res) => res.json())
    .then((data) => {
      const cartCount = document.getElementById("cartCount");
      if (cartCount) {
        cartCount.textContent = data.count;
        cartCount.style.display = data.count;
      }
    })
    .catch((err) => {
      console.error("❌ Failed to fetch cart count", err);
    });
}

// ========== MODAL OPEN/CLOSE ==========
function openModal() {
  const modalOverlay = document.querySelector(".modal-overlay");
  const modal = modalOverlay.querySelector(".modal");
  const errorDiv = document.getElementById("loginError");
  if (errorDiv) errorDiv.textContent = "";
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

  fetch("actions/login.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(
      password
    )}`,
  })
    .then((res) => res.text())
    .then((result) => {
      const errorDiv = document.getElementById("loginError");
      if (result === "redirect:profile") {
        window.location.href = "index.php?page=user";
      } else if (result === "unverified") {
        errorDiv.textContent = "⚠️ Please verify your email before logging in.";
      } else {
        errorDiv.textContent = "❌ Invalid email or password.";
      }
    })
    .catch((err) => {
      console.error("Login error:", err);
      alert("❌ An error occurred. Try again.");
    });
}
