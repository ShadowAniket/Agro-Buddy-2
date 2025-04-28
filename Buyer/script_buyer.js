document.addEventListener('DOMContentLoaded', () => {
    // Check if the current page has a product listing
    const productList = document.getElementById('product-list');
    if (productList) {
        loadProducts();  // Call the loadProducts function if we are on the product page
    }

    // Check if the current page has a cart display
    const cartItemsContainer = document.getElementById('cart-items');
    if (cartItemsContainer) {
        loadCart();  // Call the loadCart function if we are on the cart/checkout page
    }

    // Function to load products dynamically
    function loadProducts() {
        const products = [
            {
                name: "Corn",
                price: 30,
                description: "Fresh and organic corn, harvested directly from the farm. Ideal for cooking or as a healthy snack.",
                image: "images/img (1).jpg"
            },
            {
                name: "Red Capsicum",
                price: 100,
                description: "Crisp and vibrant red capsicums, perfect for salads, stir-fries, or grilling.",
                image: "images/img (2).jpg"
            },
            {
                name: "Cabbage",
                price: 30,
                description: "Crunchy cabbage, ideal for slaws, salads, or cooking in a variety of dishes.",
                image: "images/img (6).jpg"
            },
            {
                name: "Brinjal",
                price: 60,
                description: "Fresh brinjal, also known as eggplant, great for grilling, roasting, or curries.",
                image: "images/img (5).jpg"
            },
    
            // Fruits (Second Row)
            {
                name: "Blue-Berry",
                price: 450,
                description: "Plump and sweet blueberries, packed with antioxidants for a healthy boost.",
                image: "images/img (3).jpg"
            },
            {
                name: "Pumpkin",
                price: 40,
                description: "Rich and hearty pumpkins, excellent for soups, pies, or roasting.",
                image: "images/img (4).jpg"
            },
            {
                name: "Strawberry",
                price: 150,
                description: "Fresh and juicy strawberries, perfect for desserts, smoothies, or as a healthy snack.",
                image: "images/img (35).jpg"
            },
            {
                name: "Apple",
                price: 120,
                description: "Crisp and sweet apples, perfect for snacking, baking, or adding to salads.",
                image: "images/img (36).jpeg"
            },
    
            // Pulses (Third Row)
            {
                name: "Yellow Split Peas (Toor Dal)",
                price: 70,
                description: "Nutritious yellow split peas, also known as toor dal, a staple in Indian cuisine.",
                image: "images/img (12).jpg"
            },
            {
                name: "Red Kidney Beans",
                price: 120,
                description: "Nutritious and protein-rich red kidney beans, perfect for curries or salads.",
                image: "images/img (7).jpg"
            },
            {
                name: "Wheat (Dal)",
                price: 40,
                description: "Whole wheat dal, great for making traditional Indian dishes like chapati and dal.",
                image: "images/img (10).jpg"
            },
            {
                name: "Moong",
                price: 80,
                description: "Nutritious and protein-rich moong dal, ideal for making dals, soups, and sprouts.",
                image: "images/img (37).jpeg"
            }
        ];

        // Function to format the price in INR
        function formatPrice(price) {
            return `₹${price.toFixed(2)} per kg`;  // Format the price
        }

        products.forEach((product, index) => {
            const productCard = `
                <div class="col-3"> 
                    <div class="card shadow-sm product-card" data-product-id="${index}">
                        <img src="${product.image}" class="card-img-top equal-img" alt="${product.name}">
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <p class="card-text">${product.description}</p>
                            <p><strong>${formatPrice(product.price)}</strong></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-secondary add-to-cart-btn">Add To Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            productList.innerHTML += productCard;
        });

        // Add event listeners for 'Add to Cart' buttons
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', handleAddToCart);
        });
    }

    // Function to handle adding a product to the cart
    function handleAddToCart(event) {
        const productCard = event.target.closest('.product-card');
        const productId = productCard.getAttribute('data-product-id');
        const productName = productCard.querySelector('.card-title').textContent;
        const productPrice = productCard.querySelector('strong').textContent;

        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Check if item already exists in the cart
        const existingItem = cart.find(item => item.id === productId);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ id: productId, name: productName, price: productPrice, quantity: 1 });
        }

        localStorage.setItem('cart', JSON.stringify(cart));

        // Redirect to the cart page (which is also the checkout page)
        window.location.href = 'cart.html';
    }

    // Function to load the cart and handle checkout logic
    function loadCart() {
        const cart = JSON.parse(localStorage.getItem('cart')) || [];
        let totalPrice = 0;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
        } else {
            cartItemsContainer.innerHTML = ''; // Clear the container before adding items
            cart.forEach(item => {
                const itemTotalPrice = parseFloat(item.price.replace('₹', '').replace(' per kg', '')) * item.quantity;
                totalPrice += itemTotalPrice;

                const itemDiv = document.createElement('div');
                itemDiv.classList.add('cart-item');
                itemDiv.innerHTML = `
                    <h3>${item.name}</h3>
                    <p>Price: ${item.price} x ${item.quantity} = ₹${itemTotalPrice.toFixed(2)}</p>
                `;
                cartItemsContainer.appendChild(itemDiv);
            });

            const totalPriceDiv = document.createElement('div');
            totalPriceDiv.classList.add('total-price');
            totalPriceDiv.innerHTML = `<h4>Total: ₹${totalPrice.toFixed(2)}</h4>`;
            cartItemsContainer.appendChild(totalPriceDiv);
        }

        // Ensure checkout button exists before adding event listener
        const checkoutBtn = document.getElementById('checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', completeCheckout);
        }
    }

    // Function to handle checkout and clear the cart
    function completeCheckout() {
        localStorage.removeItem('cart');
        alert('Checkout complete! Thank you for your purchase.');
        window.location.href = 'confirmation.html';  // Redirect to confirmation page
    }

    // scroll control
    const video = document.getElementById('plantVideo');
    const totalScrollHeight = document.documentElement.scrollHeight - window.innerHeight;

    // Set a variable to control the speed of the animation
    const animationSpeed = 1.5; // Adjust this value to make it faster or slower

    window.addEventListener('scroll', function() {
    const scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
    const scrollFraction = scrollPosition / totalScrollHeight;  // Normalized scroll value

    // Map the scrollFraction to video duration with animation speed
    const videoTime = scrollFraction * video.duration * animationSpeed;

    // Ensure the video only plays while scrolling upwards and within bounds
    if (videoTime >= 0 && videoTime <= video.duration) {
        video.currentTime = videoTime;  // Adjust playback according to scroll position
    }
    });

    // Profile page

    // indian rs changing in every code
   


function retrieveProducts() {
    const storedProducts = JSON.parse(localStorage.getItem('products'));

    if (storedProducts) {
        let productHtml = '<h2>Stored Products:</h2>';
        storedProducts.forEach(product => {
            productHtml += `
                <div>
                    <h3>${product.name}</h3>
                    <p>${product.description}</p>
                    <p>Price: ₹${product.price}</p>
                    <img src="${product.image}" alt="${product.name}" style="width: 100px;">
                </div>
                <hr>
            `;
        });
        document.getElementById('storage-demo').innerHTML = productHtml;
    } else {
        document.getElementById('storage-demo').innerHTML = '<p>No products found in localStorage.</p>';
    }
}

// Store the products in local storage
function storeProducts() {
    localStorage.setItem('products', JSON.stringify(products));  // Save products to localStorage
    alert('Products have been stored in localStorage.');
}

// Retrieve products from local storage and display them
function retrieveProducts() {
    const storedProducts = JSON.parse(localStorage.getItem('products'));  // Retrieve products from localStorage
    if (storedProducts) {
        let productHtml = '<h2>Stored Products:</h2>';
        storedProducts.forEach(product => {
            productHtml += `
                <div>
                    <h3>${product.name}</h3>
                    <p>${product.description}</p>
                    <p>Price: ₹${product.price}</p>
                    <img src="${product.image}" alt="${product.name}" style="width: 100px;">
                </div>
                <hr>
            `;
        });
        document.getElementById('storage-demo').innerHTML = productHtml;  // Display stored products
    } else {
        document.getElementById('storage-demo').innerHTML = '<p>No products found in localStorage.</p>';
    }
}

// Soil-Analysis
const tooltip = document.getElementById("tooltip");
const mapImage = document.getElementById("india-map");

// Add hover event listeners to the map's areas
document.querySelectorAll("area").forEach(area => {
    area.addEventListener("mouseenter", (event) => {
        const stateName = event.target.alt;
        tooltip.textContent = stateName;
        tooltip.style.display = "block";
    });

    area.addEventListener("mousemove", (event) => {
        tooltip.style.left = `${event.pageX + 10}px`;
        tooltip.style.top = `${event.pageY + 10}px`;
    });

    area.addEventListener("mouseleave", () => {
        tooltip.style.display = "none";
    });
});


});
