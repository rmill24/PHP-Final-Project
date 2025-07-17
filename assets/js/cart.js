document.addEventListener("DOMContentLoaded", function () {
  const promoInput = document.getElementById("promoCodeInput");
  const applyPromoBtn = document.getElementById("applyPromoBtn");
  const promoMessage = document.getElementById("promoMessage");
  const promoDiscountRow = document.querySelector(".promo-discount");
  const promoCodeName = document.querySelector(".promo-code-name");
  const discountAmount = document.querySelector(".discount-amount");
  const removePromoBtn = document.querySelector(".promo-discount-remove");

  let activePromo = null;
  const validPromoCodes = {
    VENUSIA20: { discount: 0.2, type: "percent" },
    FREESHIP: { discount: 10, type: "fixed-shipping" },
    SUMMER15: { discount: 0.15, type: "percent" },
  };

  // Quantity selector functionality
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

  // Remove item functionality
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
          } else {
            alert("❌ Failed to remove item.");
          }
        });
    });
  });

  // Handle checkbox toggles
  document.querySelectorAll(".item-checkbox").forEach((checkbox) => {
    checkbox.addEventListener("change", updateCartTotals);
  });

  // Promo Code Logic
  applyPromoBtn.addEventListener("click", applyPromoCode);
  promoInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter") {
      applyPromoCode();
    }
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

    if (validPromoCodes[code]) {
      activePromo = validPromoCodes[code];
      activePromo.code = code;

      promoMessage.textContent = "Promo code applied!";
      promoMessage.classList.add("promo-success");

      promoDiscountRow.style.display = "flex";
      promoCodeName.textContent = `(${code})`;

      updateCartTotals();
    } else {
      promoMessage.textContent = "Invalid promo code";
      promoMessage.classList.add("promo-error");
    }
  }

  function removePromoCode() {
    activePromo = null;
    promoDiscountRow.style.display = "none";
    promoInput.value = "";
    promoMessage.className = "promo-message";
    updateCartTotals();
  }

  function updateCartTotals() {
    let subtotal = 0;
    let selectedCount = 0;

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

    const tax = subtotal * 0.09;
    let shipping = 0;
    let discount = 0;

    if (activePromo) {
      if (activePromo.type === "percent") {
        discount = subtotal * activePromo.discount;
      } else if (activePromo.type === "fixed-shipping") {
        shipping = -activePromo.discount;
      }

      discountAmount.textContent = `-₱${discount.toFixed(2)}`;
    }

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

  // Initial totals
  updateCartTotals();
});
