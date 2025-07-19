document.addEventListener("DOMContentLoaded", function () {
  // ==============================
  // Promo Code Setup
  // ==============================
  const promoInput = document.getElementById("promoCodeInput");
  const applyPromoBtn = document.getElementById("applyPromoBtn");
  const promoMessage = document.getElementById("promoMessage");
  const promoDiscountRow = document.querySelector(".promo-discount");
  const promoCodeName = document.querySelector(".promo-code-name");
  const discountAmount = document.querySelector(".discount-amount");
  const removePromoBtn = document.querySelector(".promo-discount-remove");

  const validPromoCodes = {
    VENUSIA20: { discount: 0.2, type: "percent" },
    FREESHIP: { discount: 10, type: "fixed-shipping" },
    SUMMER15: { discount: 0.15, type: "percent" },
  };

  let activePromo = null;

  // ==============================
  // Quantity Controls
  // ==============================
  document.querySelectorAll(".quantity-selector").forEach((section) => {
    const input = section.querySelector(".quantity-input");
    const minus = section.querySelector(".minus");
    const plus = section.querySelector(".plus");
    const cartItem = section.closest(".cart-item");
    const cartItemId = cartItem.dataset.cartItemId;

    function updateQuantity(newQty) {
      if (newQty < 1) return;
      input.value = newQty;

      fetch("actions/update_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `cart_item_id=${cartItemId}&quantity=${newQty}`,
      })
        .then((res) => res.text())
        .then((res) => {
          if (res.trim() !== "updated") {
            alert("❌ Failed to update quantity.");
          } else {
            updateCartTotals();
            updateCartCountBadge();
          }
        });
    }

    minus.addEventListener("click", () => {
      const current = parseInt(input.value);
      if (current > 1) updateQuantity(current - 1);
    });

    plus.addEventListener("click", () => {
      const current = parseInt(input.value);
      updateQuantity(current + 1);
    });

    input.addEventListener("change", () => {
      let val = parseInt(input.value);
      if (isNaN(val) || val < 1) val = 1;
      updateQuantity(val);
    });
  });

  // ==============================
  // Remove Item
  // ==============================
  document.querySelectorAll(".remove-item").forEach((button) => {
    button.addEventListener("click", function () {
      const cartItem = this.closest(".cart-item");
      const cartItemId = cartItem.dataset.cartItemId;

      fetch("actions/remove_from_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `cart_item_id=${cartItemId}`,
      })
        .then((res) => res.text())
        .then((response) => {
          if (response === "removed") {
            cartItem.remove();
            updateCartTotals();
            checkEmptyCart();
            updateCartCountBadge();
          } else {
            alert("❌ Failed to remove item.");
          }
        });
    });
  });

  // ==============================
  // Promo Code Logic
  // ==============================
  applyPromoBtn.addEventListener("click", applyPromoCode);
  promoInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") applyPromoCode();
  });
  removePromoBtn.addEventListener("click", removePromoCode);

  function applyPromoCode() {
    const code = promoInput.value.trim().toUpperCase();
    promoMessage.className = "promo-message";

    if (!code) {
      promoMessage.textContent = "Please enter a promo code";
      promoMessage.classList.add("promo-error");
      return;
    }

    if (activePromo) {
      promoMessage.textContent = "A promo code is already applied";
      promoMessage.classList.add("promo-error");
      return;
    }

    fetch("actions/apply_promo.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `promo_code=${encodeURIComponent(code)}`,
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          activePromo = {
            code,
            discount: data.discount,
            type: data.type,
          };

          promoMessage.textContent = "Promo code applied!";
          promoMessage.classList.add("promo-success");

          promoDiscountRow.style.display = "flex";
          promoCodeName.textContent = `(${code})`;

          updateCartTotals();
        } else {
          promoMessage.textContent = data.message || "Invalid promo code";
          promoMessage.classList.add("promo-error");
        }
      })
      .catch(() => {
        promoMessage.textContent = "Something went wrong";
        promoMessage.classList.add("promo-error");
      });
  }

  function removePromoCode() {
    activePromo = null;
    promoDiscountRow.style.display = "none";
    promoInput.value = "";
    promoMessage.className = "promo-message";
    updateCartTotals();
  }

  // ==============================
  // Update Cart Totals
  // ==============================
  function updateCartTotals() {
    let subtotal = 0;
    let selectedCount = 0;
    let discount = 0;
    let shipping = 0;

    document.querySelectorAll(".cart-item").forEach((item) => {
      const checkbox = item.querySelector(".item-checkbox");
      const quantity = parseInt(item.querySelector(".quantity-input").value);
      const priceText = item.querySelector(".item-price").textContent;
      const price = parseFloat(priceText.replace(/[₱,]/g, ""));

      if (checkbox.checked) {
        selectedCount++;
        subtotal += quantity * price;
      }
    });

    if (activePromo) {
      if (activePromo.type === "percent") {
        discount = subtotal * activePromo.discount;
      } else if (activePromo.type === "fixed-shipping") {
        shipping = -activePromo.discount;
      }
      discountAmount.textContent = `-₱${discount.toFixed(2)}`;
    }

    const tax = (subtotal - discount) * 0.09;
    const total = subtotal + tax + shipping - discount;

    document.getElementById("selected-count").textContent = selectedCount;
    document.getElementById("subtotal").textContent = subtotal.toFixed(2);
    document.getElementById("tax").textContent = tax.toFixed(2);
    document.getElementById("total").textContent = total.toFixed(2);
    document.getElementById("shipping").textContent =
      activePromo?.type === "fixed-shipping"
        ? `-₱${Math.abs(shipping).toFixed(2)}`
        : subtotal > 0
        ? "Free"
        : "₱0.00";

    document.querySelector(".checkout-btn").disabled = selectedCount === 0;
  }

  function checkEmptyCart() {
    const items = document.querySelectorAll(".cart-item");
    if (items.length === 0) {
      document.querySelector(".cart-items").style.display = "none";
      document.querySelector(".empty-cart").style.display = "block";
      document.querySelector(".order-summary").style.display = "none";
    }
  }

  // ==============================
  // Modal Customization (Size/Color)
  // ==============================
  const modal = document.getElementById("customizationModal");
  const closeModal = document.querySelector(".close-cart-modal");
  const cancelBtn = document.querySelector(".cart-cancel-btn");
  let currentItem = null;
  let currentOption = null;

  document.querySelectorAll(".change-size, .change-color").forEach((button) => {
    button.addEventListener("click", function () {
      currentItem = this.closest(".cart-item");
      currentOption = this.classList.contains("change-size") ? "size" : "color";

      const currentValue = currentItem.querySelector(
        `.current-${currentOption}`
      ).textContent;
      const options = document.querySelectorAll(
        `#${currentOption}Options .option-value`
      );

      options.forEach((option) => {
        option.classList.toggle(
          "selected",
          option.dataset.value === currentValue
        );
      });

      document.querySelector(
        ".cart-modal-title"
      ).textContent = `Change ${capitalize(currentOption)}`;
      modal.style.display = "flex";
    });
  });

  document.querySelectorAll(".option-value").forEach((option) => {
    option.addEventListener("click", function () {
      this.parentElement
        .querySelectorAll(".option-value")
        .forEach((opt) => opt.classList.remove("selected"));
      this.classList.add("selected");
    });
  });

  document
    .querySelector(".cart-save-btn")
    .addEventListener("click", function () {
      if (currentItem && currentOption === "size") {
        const selectedOption = document.querySelector(
          "#sizeOptions .option-value.selected"
        );
        if (!selectedOption) return closeModalFunc();

        const newSizeLabel = selectedOption.dataset.value;
        const newSizeId = selectedOption.dataset.id;
        const cartItemId = currentItem.dataset.cartItemId;

        fetch("actions/update_cart_size.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `cart_item_id=${cartItemId}&size_id=${newSizeId}`,
        })
          .then((res) => res.text())
          .then((response) => {
            if (response === "updated") {
              currentItem.querySelector(".current-size").textContent =
                newSizeLabel;
              currentItem.querySelector(".current-size").dataset.sizeId =
                newSizeId;
              closeModalFunc();
            } else {
              alert("❌ Failed to update size.");
            }
          });
      } else {
        closeModalFunc();
      }
    });

  closeModal.addEventListener("click", closeModalFunc);
  cancelBtn.addEventListener("click", closeModalFunc);

  window.addEventListener("click", function (e) {
    if (e.target === modal) closeModalFunc();
  });

  function closeModalFunc() {
    modal.style.display = "none";
  }

  function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  // ==============================
  // Checkbox Watchers
  // ==============================
  document.querySelectorAll(".item-checkbox").forEach((checkbox) => {
    checkbox.addEventListener("change", function() {
      const cartItem = this.closest(".cart-item");
      const cartItemId = cartItem.dataset.cartItemId;
      const selected = this.checked;

      // Update database
      fetch("actions/update_cart_selection.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `cart_item_id=${cartItemId}&selected=${selected ? 1 : 0}`,
      })
        .then((res) => res.text())
        .then((response) => {
          if (response === "updated") {
            updateCartTotals();
          } else {
            // Revert checkbox state if update failed
            this.checked = !selected;
            alert("❌ Failed to update selection.");
          }
        })
        .catch(() => {
          // Revert checkbox state if request failed
          this.checked = !selected;
          alert("❌ Failed to update selection.");
        });
    });
  });

  // ==============================
  // Checkout Button Handler
  // ==============================
  const checkoutBtn = document.querySelector(".checkout-btn");
  if (checkoutBtn) {
    checkoutBtn.addEventListener("click", function() {
      window.location.href = 'index.php?page=payment';
    });
  }

  // ==============================
  // Empty Cart Display Logic
  // ==============================
  function handleEmptyCartDisplay() {
    const cartItems = document.querySelectorAll(".cart-item");
    if (cartItems.length === 0) {
      document.querySelector(".cart-items").style.display = "none";
      document.querySelector(".order-summary").style.display = "none";
      document.querySelector(".empty-cart").style.display = "block";
    }
  }

  // Init
  handleEmptyCartDisplay();
  updateCartTotals();
});
