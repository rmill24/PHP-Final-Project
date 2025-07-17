document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("addToCartForm");
  const sizeButtons = document.querySelectorAll(".size-option");
  const sizeInput = document.getElementById("selectedSizeInput");
  const messageDiv = document.getElementById("cartMessage"); // optional: for inline message

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
      alert("⚠️ Please select a size before adding to cart.");
      return;
    }

    const formData = new FormData(form);

    fetch("actions/add_to_cart.php", {
      method: "POST",
      body: formData
    })
      .then(res => res.text())
      .then(response => {
        if (response === "added") {
          alert("✅ Item added to cart!");
        } else if (response.toLowerCase().includes("unauthorized") || response.includes("You must be logged in")) {
          alert("⚠️ You must be logged in to add items to cart.");
        } else {
          alert("❌ Something went wrong: " + response);
        }
      })
      .catch(err => {
        console.error("Error adding to cart:", err);
        alert("❌ Failed to add to cart. Try again.");
      });
  });
});
