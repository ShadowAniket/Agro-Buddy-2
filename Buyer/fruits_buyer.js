document.addEventListener('DOMContentLoaded', () => {
    const fruitProductList = document.getElementById('fruit-products');
    loadFruitProducts();

    // Function to load fruit products dynamically
    function loadFruitProducts() {
        const fruitProducts = [
            {
                name: "Apple",
                price: 120,
                description: "Crisp and sweet apples, perfect for snacking, baking, or adding to salads.",
                image: "images/img (36).jpeg"
            },
            {
                name: "Strawberry",
                price: 150,
                description: "Fresh and juicy strawberries, perfect for desserts, smoothies, or as a healthy snack.",
                image: "images/img (35).jpg"
            },
            {
                name: "Blueberry",
                price: 450,
                description: "Plump and sweet blueberries, packed with antioxidants for a healthy boost.",
                image: "images/img (42).jpeg "
            },
            {
                name: "Mango",
                price: 250,
                description: "Ripe, juicy mangoes, a tropical delight perfect for smoothies or eating fresh.",
                image: "images/img (43).jpeg"
            },
            {
                name: "Banana",
                price: 40,
                description: "Fresh bananas, great for snacks, smoothies, or adding to cereals.",
                image: "images/img (44).jpeg"
            },
            {
                name: "Orange",
                price: 80,
                description: "Citrusy and refreshing oranges, packed with vitamin C.",
                image: "images/img (45).jpeg"
            },
            {
                name: "Pineapple",
                price: 200,
                description: "Sweet and tangy pineapples, perfect for snacking or adding to fruit salads.",
                image: "images/img (46).jpeg"
            },
            {
                name: "Papaya",
                price: 100,
                description: "Sweet and tropical papayas, great for smoothies and fresh fruit bowls.",
                image: "images/img (47).jpeg"
            },
            {
                name: "Watermelon",
                price: 90,
                description: "Refreshing watermelon, perfect for hot summer days.",
                image: "images/img (56).jpeg"
            },
            {
                name: "Grapes",
                price: 180,
                description: "Fresh and juicy grapes, perfect for snacking or adding to salads.",
                image: "images/img (16).jpg"
            },
            {
                name: "Peach",
                price: 250,
                description: "Sweet and juicy peaches, perfect for desserts or eating fresh.",
                image: "images/img (48).jpeg"
            },
            {
                name: "Pomegranate",
                price: 300,
                description: "Juicy and tangy pomegranates, rich in antioxidants and perfect for snacking.",
                image: "images/img (49).jpeg"
            },
            {
                name: "Cherries",
                price: 350,
                description: "Fresh, sweet cherries, perfect for snacking or adding to desserts.",
                image: "images/img (55).jpeg"
            },
            {
                name: "Lychee",
                price: 400,
                description: "Sweet and fragrant lychees, perfect for refreshing fruit salads.",
                image: "images/img (50).jpeg"
            },
            {
                name: "Plum",
                price: 200,
                description: "Sweet and tart plums, perfect for snacking or baking.",
                image: "images/img (54).jpeg"
            },
            {
                name: "Apricot",
                price: 150,
                description: "Fresh and juicy apricots, perfect for snacking or making jams.",
                image: "images/img (58).jpeg"
            },
            {
                name: "Guava",
                price: 60,
                description: "Fresh guavas, perfect for making juice or eating as a snack.",
                image: "images/img (17).jpg"
            },
            {
                name: "Kiwi",
                price: 250,
                description: "Tangy and sweet kiwis, packed with vitamin C.",
                image: "images/img (51).jpeg"
            },
            {
                name: "Dragon Fruit",
                price: 500,
                description: "Exotic and sweet dragon fruits, perfect for fresh fruit bowls or smoothies.",
                image: "images/img (53).jpeg"
            },
            {
                "name": "Chikoo",
                "price": 80,
                "description": "Sweet and rich chikoo, perfect for smoothies, milkshakes, or enjoying fresh.",
                "image": "images/img (13).jpg"
            }
        ];

        // Function to format the price in INR
        function formatPrice(price) {
            return `₹${price.toFixed(2)} per kg`;  // Format the price
        }

        fruitProducts.forEach((fruitProduct, index) => {
            const fruitProductCard = `
                <div class="col-3"> 
                    <div class="card shadow-sm fruit-product-card" data-fruitproduct-id="${index}">
                        <img src="${fruitProduct.image}" class="card-img-top equal-img" alt="${fruitProduct.name}">
                        <div class="card-body">
                            <h5 class="card-title">${fruitProduct.name}</h5>
                            <p class="card-text">${fruitProduct.description}</p>
                            <p><strong>${formatPrice(fruitProduct.price)}</strong></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-secondary add-to-cart-btn">Add To Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            fruitProductList.innerHTML += fruitProductCard;
        });

        // Add event listeners for 'Add to Cart' buttons
        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', handleAddToCart);
        });
    }

    // Function to handle adding a fruit product to the cart
    function handleAddToCart(event) {
        const fruitProductCard = event.target.closest('.fruit-product-card');
        const fruitProductId = fruitProductCard.getAttribute('data-fruitproduct-id');
        const fruitProductName = fruitProductCard.querySelector('.card-title').textContent;
        const fruitProductPrice = fruitProductCard.querySelector('strong').textContent;

        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Check if item already exists in the cart
        const existingItem = cart.find(item => item.id === fruitProductId);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ id: fruitProductId, name: fruitProductName, price: fruitProductPrice, quantity: 1 });
        }

        localStorage.setItem('cart', JSON.stringify(cart));

        // Redirect to the cart page (which is also the checkout page)
        window.location.href = 'cart.html';
    }
});
