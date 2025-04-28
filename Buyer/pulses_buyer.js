document.addEventListener('DOMContentLoaded', () => {
    // Check if the current page has a pulses products listing
    const pulsesProductsList = document.getElementById('pulsesproducts');
    if (pulsesProductsList) {
        loadPulsesProducts(); // Call the loadPulsesProducts function if on the product page
    }

    // Check if the current page has a cart display
    const cartItemsContainer = document.getElementById('cart-items');
    if (cartItemsContainer) {
        loadCart(); // Call the loadCart function if on the cart/checkout page
    }

    // Function to load pulses products dynamically
    function loadPulsesProducts() {
        const pulsesProducts = [
            {
                name: "Red Kidney Beans",
                price: 120,
                description: "Nutritious and protein-rich red kidney beans, perfect for curries or salads.",
                image: "images/img (62).jpg"
            },
            {
                name: "Wheat (Dal)",
                price: 40,
                description: "Whole wheat dal, great for making traditional Indian dishes like chapati and dal.",
                image: "images/img (10).jpg"
            },
            {
                name: "Yellow Split Peas (Toor Dal)",
                price: 70,
                description: "Nutritious yellow split peas, also known as toor dal, a staple in Indian cuisine.",
                image: "images/img (12).jpg"
            },
            {
                name: "Black Gram (Urad Dal)",
                price: 80,
                description: "Protein-rich black gram, ideal for making dals, curries, and South Indian dishes.",
                image: "images/img (63).jpg"
            },
            {
                name: "Chickpeas (Kabuli Chana)",
                price: 95,
                description: "Versatile chickpeas, perfect for curries, salads, and hummus.",
                image: "images/img (64).jpg"
            },
            {
                name: "Split Green Gram (Moong Dal)",
                price: 70,
                description: "Nutritious split green gram, ideal for making dals and soups.",
                image: "images/img (74).jpeg"
            },
            {
                name: "Black Eyed Peas (Lobia)",
                price: 60,
                description: "Tasty and nutrient-packed black-eyed peas, great for curries and stews.",
                image: "images/img (66).jpeg"
            },
            {
                name: "Pigeon Peas (Arhar Dal)",
                price: 85,
                description: "Classic pigeon peas, a staple in Indian households for making dal.",
                image: "images/img (60).jpg"
            },
            {
                name: "Horse Gram (Kulthi Dal)",
                price: 50,
                description: "Highly nutritious horse gram, known for its earthy flavor and health benefits.",
                image: "images/img (67).jpeg"
            },
            {
                name: "Split Bengal Gram (Chana Dal)",
                price: 75,
                description: "Popular split Bengal gram, perfect for dal, snacks, and desserts.",
                image: "images/img (68).jpeg"
            },
            {
                name: "Green Gram (Whole Moong)",
                price: 90,
                description: "Healthy green gram, great for sprouting, salads, and dals.",
                image: "images/img (37).jpeg"
            },
            {
                name: "Brown Lentils (Masoor Dal)",
                price: 65,
                description: "Quick-cooking brown lentils, perfect for making comforting dals and soups.",
                image: "images/img (73).jpeg"
            },
            {
                name: "Split Red Lentils (Masoor Malka)",
                price: 75,
                description: "Easy-to-cook split red lentils, ideal for dals and one-pot dishes.",
                image: "images/img (72).jpeg"
            },
            {
                name: "Moth Beans (Matki)",
                price: 55,
                description: "Wholesome moth beans, used for making sprouts and traditional Indian dishes.",
                image: "images/img (69).jpeg"
            },
            {
                name: "Soya Chunks",
                price: 90,
                description: "High-protein soya chunks, perfect for curries and stir-fries.",
                image: "images/img (70).jpeg"
            },
            {
                name: "Field Beans (Val Dal)",
                price: 65,
                description: "Nutritious field beans, used in making traditional curries and dals.",
                image: "images/img (71).jpeg"
            }
        ];

        // Function to format the price in INR
        function formatPrice(price) {
            return `₹${price.toFixed(2)} per kg`; // Format the price
        }

        pulsesProducts.forEach((product, index) => {
            const productCard = `
                <div class="col-3"> 
                    <div class="card shadow-sm pulses-product-card" data-product-id="${index}">
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
            pulsesProductsList.innerHTML += productCard;
        });

        // Add event listeners for 'Add to Cart' buttons
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', handleAddToCart);
        });
    }

    // Function to handle adding a product to the cart
    function handleAddToCart(event) {
        const productCard = event.target.closest('.pulses-product-card');
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
        window.location.href = 'confirmation.html'; // Redirect to confirmation page
    }
});
