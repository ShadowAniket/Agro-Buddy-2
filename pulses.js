document.addEventListener("DOMContentLoaded", () => {
  loadFruitProducts();
});

function loadFruitProducts() {
  fetch("http://localhost:8080/api/products?category=pulses")
    .then((response) => response.json())
    .then((products) => {
      const productList = document.getElementById("product-list");
      productList.innerHTML = ""; // Clear any existing content

      products.forEach((product) => {
        const col = document.createElement("div");
        col.classList.add("col-md-3", "mb-3");
        col.innerHTML = `
                  <div class="card" style="display: flex; flex-direction: column; height: 100%;">
                    <img src="${product.image_url}" class="card-img-top equal-img" alt="${product.name}" style="height:200px; object-fit: cover; width:100%;">
                    <div class="card-body">
                      <h5 class="card-title">${product.name}</h5>
                      <p class="card-text">${product.description}</p>
                      <p class="card-text">₹${product.price}</p>
                      <button class="btn btn-success" id="add-btn-${product.id}" onclick="addToCart(${product.id})">
                        Add to Cart
                      </button>
                      <div id="quantity-controls-${product.id}" class="d-none">
                        <button class="btn btn-secondary" onclick="decreaseQuantity(${product.id})">–</button>
                        <span id="quantity-${product.id}">1</span>
                        <button class="btn btn-secondary" onclick="increaseQuantity(${product.id})">+</button>
                      </div>
                    </div>
                  </div>
                `;
        productList.appendChild(col);
      });
    })
    .catch((error) => console.error("Error fetching pulses:", error));
}

function addToCart(productId) {
  fetch("http://localhost:8080/api/cart", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: productId, user_id: 1 }),
  })
    .then((response) => response.json())
    .then((data) => {
      document.getElementById(`add-btn-${productId}`).classList.add("d-none");
      document
        .getElementById(`quantity-controls-${productId}`)
        .classList.remove("d-none");
      updateQuantityDisplay(productId, 1);
      alert("Product added to cart!");
    })
    .catch((error) => console.error("Error adding product to cart:", error));
}

function increaseQuantity(productId) {
  fetch("http://localhost:8080/api/cart", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: productId, user_id: 1 }),
  })
    .then((response) => response.json())
    .then((data) => {
      const quantityElem = document.getElementById(`quantity-${productId}`);
      let currentQuantity = parseInt(quantityElem.innerText);
      updateQuantityDisplay(productId, currentQuantity + 1);
    })
    .catch((error) => console.error("Error increasing quantity:", error));
}

function decreaseQuantity(productId) {
  fetch("http://localhost:8080/api/cart", {
    method: "DELETE",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id: productId, user_id: 1 }),
  })
    .then((response) => response.json())
    .then((data) => {
      const quantityElem = document.getElementById(`quantity-${productId}`);
      let currentQuantity = parseInt(quantityElem.innerText);
      currentQuantity--;
      if (currentQuantity <= 0) {
        document
          .getElementById(`quantity-controls-${productId}`)
          .classList.add("d-none");
        document
          .getElementById(`add-btn-${productId}`)
          .classList.remove("d-none");
        updateQuantityDisplay(productId, 0);
      } else {
        updateQuantityDisplay(productId, currentQuantity);
      }
    })
    .catch((error) => console.error("Error decreasing quantity:", error));
}

function updateQuantityDisplay(productId, quantity) {
  document.getElementById(`quantity-${productId}`).innerText = quantity;
}
