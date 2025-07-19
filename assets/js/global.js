document.addEventListener("DOMContentLoaded", function () {
  // ========== PAGE LOAD ANIMATION ==========
  const pageLoader = document.getElementById('pageLoader');
  const body = document.body;
  
  // Check if this is a fresh visit (tab was closed and reopened)
  const hasVisited = sessionStorage.getItem('venusia_visited');
  
  if (!hasVisited) {
    // First visit or tab was reopened - show loading animation
    body.classList.add('loading');
    
    // Simulate loading time and hide the loader
    setTimeout(() => {
      pageLoader.classList.add('fade-out');
      body.classList.remove('loading');
      body.classList.add('loaded');
      
      // Mark as visited for this session
      sessionStorage.setItem('venusia_visited', 'true');
      
      // Remove loader from DOM after animation completes
      setTimeout(() => {
        pageLoader.remove();
        // Trigger page content animation after loader is removed
        initPageTransitions();
      }, 800);
    }, 3000); // 3 second loading time
  } else {
    // Already visited in this session - hide loader immediately
    pageLoader.remove();
    body.classList.add('loaded');
    // Trigger page content animation immediately
    initPageTransitions();
  }

  // ========== PAGE TRANSITIONS ==========
  function initPageTransitions() {
    // Animate main page content
    const pageContent = document.querySelector('.page-content');
    if (pageContent) {
      setTimeout(() => {
        pageContent.classList.add('fade-in');
      }, 100);
    }

    // Animate individual page elements with staggered delays
    const elementsToAnimate = document.querySelectorAll('.carousel-container, .category-layout, .featured, .about-header-content, .container, .cart-content, .product-container');
    
    elementsToAnimate.forEach((element, index) => {
      element.classList.add('page-element');
      setTimeout(() => {
        element.classList.add('animate');
      }, 200 + (index * 100)); // Staggered animation with 100ms delay between elements
    });

    // Animate cards and items that appear in grids
    const cardElements = document.querySelectorAll('.product-card, .cart-item, .member, .story-highlight, .value-card');
    cardElements.forEach((element, index) => {
      element.classList.add('page-element');
      setTimeout(() => {
        element.classList.add('animate');
      }, 400 + (index * 50)); // Faster staggered animation for smaller elements
    });
  }

  // ========== NAVIGATION LINK ANIMATIONS ==========
  // Add smooth transitions when clicking navigation links
  const navLinks = document.querySelectorAll('.nav-links a, .mobile-nav-links a');
  navLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      // Only apply transition effect for same-origin links
      if (this.hostname === window.location.hostname) {
        const pageContent = document.querySelector('.page-content');
        if (pageContent) {
          pageContent.classList.remove('fade-in');
          // Small delay to allow fade-out effect to be visible
          setTimeout(() => {
            window.location.href = this.href;
          }, 150);
          e.preventDefault();
        }
      }
    });
  });

  // ========== STICKY HEADER SCROLL EFFECT ==========
  const header = document.querySelector('header');
  let lastScrollTop = 0;

  window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    // Add/remove scrolled class based on scroll position
    if (scrollTop > 50) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
    
    lastScrollTop = scrollTop;
  });

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

  // ========== ADD TO CART ==========
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

  // ========== INIT CART COUNT ==========
  updateCartCountBadge();

  const registerForm = document.getElementById("registerForm");

  registerForm?.addEventListener("submit", function (e) {
    const phoneInput = document.getElementById("registerPhone");
    const phone = phoneInput.value.trim();
    const digitsOnly = phone.replace(/[^0-9]/g, '');

    if (digitsOnly.length !== 11 || !/^[0-9+\-\s()]+$/.test(phone)) {
      e.preventDefault();
      const errorDiv = document.getElementById("registerError");
      errorDiv.textContent = "⚠️ Phone number must be exactly 11 digits and contain only numbers and formatting characters.";
      phoneInput.classList.add("input-error");
    }
  });
});

// ========== GLOBAL FUNCTIONS ==========
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
