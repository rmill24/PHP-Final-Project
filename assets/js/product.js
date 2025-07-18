document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("addToCartForm");
  const sizeButtons = document.querySelectorAll(".size-option");
  const sizeInput = document.getElementById("selectedSizeInput");
  const messageDiv = document.getElementById("cartMessage");

  sizeButtons.forEach(button => {
    button.addEventListener("click", function () {
      sizeButtons.forEach(btn => btn.classList.remove("selected"));
      this.classList.add("selected");
      sizeInput.value = this.dataset.sizeId;
    });
  });

  if (form) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      if (!sizeInput.value) {
        return showCartMessage("⚠️ Please select a size before adding to cart.", "darkorange");
      }

      fetch("actions/add_to_cart.php", {
        method: "POST",
        body: new FormData(form),
      })
        .then(res => res.text())
        .then(response => {
          if (response === "added") {
            showCartMessage("✅ Item added to cart!", "green");
            sizeButtons.forEach(btn => btn.classList.remove("selected"));
            sizeInput.value = "";
            updateCartCountBadge?.();
          } else if (response.toLowerCase().includes("unauthorized")) {
            showCartMessage("⚠️ You must be logged in to add items to cart.", "darkorange");
          } else {
            showCartMessage("❌ Something went wrong: " + response, "red");
          }
        })
        .catch(() => showCartMessage("❌ Failed to add to cart. Try again.", "red"));
    });
  }

  function showCartMessage(text, color = "black", duration = 3000) {
    if (!messageDiv) return;
    messageDiv.textContent = text;
    messageDiv.style.color = color;
    messageDiv.style.display = "block";
    setTimeout(() => {
      messageDiv.textContent = "";
      messageDiv.style.display = "none";
    }, duration);
  }
});
