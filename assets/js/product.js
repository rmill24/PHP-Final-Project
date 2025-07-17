document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("addToCartForm");
  const sizeButtons = document.querySelectorAll(".size-option");
  const sizeInput = document.getElementById("selectedSizeInput");
  const messageDiv = document.getElementById("cartMessage");

  // Highlight selected size and set hidden input
  sizeButtons.forEach((button) => {
    button.addEventListener("click", function () {
      sizeButtons.forEach((btn) => btn.classList.remove("selected"));
      this.classList.add("selected");
      sizeInput.value = this.dataset.sizeId;
    });
  });

  // Handle form submission
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    if (!sizeInput.value) {
      messageDiv.textContent = "⚠️ Please select a size before adding to cart.";
      messageDiv.style.color = "darkorange";
      return;
    }

    const formData = new FormData(form);

    fetch("actions/add_to_cart.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.text())
      .then((response) => {
        if (response === "added") {
          showCartMessage("✅ Item added to cart!", "green");

          // Reset selection
          sizeButtons.forEach((btn) => btn.classList.remove("selected"));
          sizeInput.value = "";

          updateCartCountBadge();
        } else if (
          response.toLowerCase().includes("unauthorized") ||
          response.includes("You must be logged in")
        ) {
          showCartMessage(
            "⚠️ You must be logged in to add items to cart.",
            "darkorange"
          );
        } else {
          showCartMessage("❌ Something went wrong: " + response, "red");
        }
      })
      .catch((err) => {
        console.error("Error adding to cart:", err);
        messageDiv.textContent = "❌ Failed to add to cart. Try again.";
        messageDiv.style.color = "red";
      });
  });
});

function showCartMessage(text, color = "black", duration = 3000) {
  const messageDiv = document.getElementById("cartMessage");
  if (!messageDiv) return;

  messageDiv.textContent = text;
  messageDiv.style.color = color;
  messageDiv.style.display = "block";

  // Clear message after `duration` ms
  setTimeout(() => {
    messageDiv.textContent = "";
    messageDiv.style.display = "none";
  }, duration);
}
