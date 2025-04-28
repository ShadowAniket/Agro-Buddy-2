document.addEventListener('DOMContentLoaded', () => {
    const vegetableProductList = document.getElementById('vegetable-products');
    loadVegetableProducts();

    function loadVegetableProducts() {
        const vegetableProducts = [
            {
                name: "Tomato",
                price: 50,
                description: "Juicy and ripe red tomatoes, ideal for salads, sauces, and curries.",
                image: "images/img (8).jpg"
            },
            {
                name: "Potato",
                price: 40,
                description: "Versatile and hearty potatoes, perfect for mashing, frying, or baking.",
                image: "images/img (39).jpeg"
            },
            {
                name: "Carrot",
                price: 35,
                description: "Sweet and crunchy carrots, great for snacks, salads, or cooking.",
                image: "images/img (9).jpg"
            },
            {
                name: "Onion",
                price: 60,
                description: "Fresh onions, essential for enhancing flavors in various dishes.",
                image: "images/img (40).jpeg"
            },
            {
                name: "Spinach",
                price: 25,
                description: "Fresh and nutritious spinach leaves, perfect for salads and cooking.",
                image: "images/img (38).jpeg"
            },
            {
                "name": "Capsicum",
                "price": 80,
                "description": "Colorful and crisp capsicum, ideal for stir-fries, salads, or stuffing.",
                "image": "images/img (2).jpg"
            },
            {
                "name": "Cauliflower",
                "price": 70,
                "description": "Fresh cauliflower, great for curries, roasting, or making rice alternatives.",
                "image": "images/img (23).jpg"
            },
            {
                "name": "Green Peas",
                "price": 90,
                "description": "Sweet and tender green peas, perfect for curries, soups, or fried rice.",
                "image": "images/img (41).jpeg"
            },
            {
                "name": "Brinjal",
                "price": 60,
                "description": "Fresh brinjal, excellent for grilling, frying, or making curries.",
                "image": "images/img (5).jpg"
            },
            {
                "name": "Pumpkin",
                "price": 40,
                "description": "Rich and hearty pumpkins, great for soups and desserts.",
                "image": "images/img (4).jpg"
            },
            {
                "name": "Ladies Finger",
                "price": 40,
                "description": "Rich and hearty ladies finger, great for curries, stir-fries, and soups.",
                "image": "images/img (29).jpg"
            },
            {
                "name": "Cabbage",
                "price": 30,
                "description": "Rich and hearty cabbage, great for soups, salads, and stir-fries.",
                "image": "images/img (6).jpg"
            }            
            
        ];

        function formatPrice(price) {
            return `₹${price.toFixed(2)} per kg`;
        }

        vegetableProducts.forEach((vegetable, index) => {
            const vegetableCard = `
                <div class="col-3"> 
                    <div class="card shadow-sm vegetable-product-card" data-vegetable-id="${index}">
                        <img src="${vegetable.image}" class="card-img-top equal-img" alt="${vegetable.name}">
                        <div class="card-body">
                            <h5 class="card-title">${vegetable.name}</h5>
                            <p class="card-text">${vegetable.description}</p>
                            <p><strong>${formatPrice(vegetable.price)}</strong></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="button" class="btn btn-sm btn-outline-secondary add-to-cart-btn">Add To Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            vegetableProductList.innerHTML += vegetableCard;
        });

        document.querySelectorAll('.add-to-cart-btn').forEach(button => {
            button.addEventListener('click', handleAddToCart);
        });
    }

    function handleAddToCart(event) {
        const vegetableCard = event.target.closest('.vegetable-product-card');
        const vegetableId = vegetableCard.getAttribute('data-vegetable-id');
        const vegetableName = vegetableCard.querySelector('.card-title').textContent;
        const vegetablePrice = vegetableCard.querySelector('strong').textContent;

        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existingItem = cart.find(item => item.id === vegetableId);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ id: vegetableId, name: vegetableName, price: vegetablePrice, quantity: 1 });
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        window.location.href = 'cart.html';
    }
});
