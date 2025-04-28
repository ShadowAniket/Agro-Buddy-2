document.addEventListener("DOMContentLoaded", () => {
  // If on the cart page (has #cart-items), load the cart
  if (document.getElementById("cart-items")) {
    loadCart();
  }
});

// Fetch and render items in the cart along with order summary
function loadCart() {
  // user_id is hardcoded to 1 for demo; adjust for real auth
  fetch("http://localhost:8080/api/cart?user_id=1")
    .then((response) => response.json())
    .then((cartItems) => {
      const cartContainer = document.getElementById("cart-items");
      const emptyCartMsg = document.getElementById("empty-cart");
      const totalItemsElem = document.getElementById("total-items");
      const totalPriceElem = document.getElementById("total-price");

      cartContainer.innerHTML = "";
      let totalItems = 0;
      let totalPrice = 0;

      if (cartItems.length === 0) {
        // Show "empty cart" message if there are no items
        emptyCartMsg.style.display = "block";
        totalItemsElem.innerText = "";
        totalPriceElem.innerText = "";
      } else {
        emptyCartMsg.style.display = "none";

        cartItems.forEach((item) => {
          totalItems += item.quantity;
          totalPrice += item.quantity * parseFloat(item.price);

          const itemDiv = document.createElement("div");
          itemDiv.classList.add("card", "mb-3");
          itemDiv.innerHTML = `
                <div class="row g-0">
                  <div class="col-md-4">
                    <img src="${item.image_url}" class="card-img-top equal-img" alt="${item.name}">
                    
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title">${item.name}</h5>
                      <p class="card-text">Price: ₹${item.price}</p>
                      <p class="card-text">
                        Quantity:
                        <button class="btn btn-sm btn-outline-secondary" onclick="updateCart(${item.product_id}, 'decrease')">-</button>
                        <span id="quantity-cart-${item.product_id}">${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary" onclick="updateCart(${item.product_id}, 'increase')">+</button>
                      </p>
                    </div>
                  </div>
                </div>
              `;
          cartContainer.appendChild(itemDiv);
        });

        totalItemsElem.innerText = `Total Items: ${totalItems}`;
        totalPriceElem.innerText = `Total Price: ₹${totalPrice.toFixed(2)}`;
      }
    })
    .catch((error) => console.error("Error fetching cart items:", error));
}

/**
 * Update cart quantity from the cart page
 * @param {number} productId - ID of the product to update
 * @param {string} action - 'increase' or 'decrease'
 */
function updateCart(productId, action) {
  // Decide which HTTP method to use based on action
  const method = action === "increase" ? "POST" : "DELETE";

  fetch("http://localhost:8080/api/cart", {
    method: method,
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: productId, user_id: 1 }),
  })
    .then((response) => response.json())
    .then((data) => {
      // After updating, re-load the cart to reflect changes
      loadCart();
    })
    .catch((error) => console.error("Error updating cart:", error));
}

function checkout() {
  // Hard-coded user_id = 1 for demo; replace with real user logic as needed
  fetch("http://localhost:8080/api/checkout", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ user_id: 1 }),
  })
    .then((response) => response.json())
    .then((data) => {
      // Show success message from the server
      alert(data.message); // e.g. "Your order has been placed successfully!"
      // Reload the cart so it shows empty
      loadCart();
    })
    .catch((error) => console.error("Error during checkout:", error));
}
