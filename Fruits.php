<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fruits - AgroBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 60px; /* Add padding to prevent content from going behind navbar */
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .card-img-container {
            overflow: hidden;
            position: relative;
        }

        .card-img-top {
            transition: transform 0.5s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px 25px;
            border-radius: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 1000;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Fresh Fruits</h1>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-center">
                    <div class="btn-group" role="group" aria-label="Product Categories">
                        <a href="Product.php" class="btn btn-outline-success">All Products</a>
                        <a href="Fruits.php" class="btn btn-success active">Fruits</a>
                        <a href="Vegetables.php" class="btn btn-outline-success">Vegetables</a>
                        <a href="Pulses.php" class="btn btn-outline-success">Pulses</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="fruits-list">
            <!-- Fruits will be loaded here -->
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading fruits...</p>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const userId = '<?php echo isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : ""; ?>';

        function addToCart(productId, name, price, image) {
            if (!userId) {
                window.location.href = 'Login.php';
                return;
            }

            const cartKey = `cart_${userId}`;
            let cart = JSON.parse(localStorage.getItem(cartKey)) || [];

            const existingProduct = cart.find(item => item.id === productId);
            if (existingProduct) {
                existingProduct.quantity += 1;
            } else {
                cart.push({
                    id: productId,
                    name: name,
                    price: price,
                    image: image,
                    quantity: 1
                });
            }

            localStorage.setItem(cartKey, JSON.stringify(cart));

            // Show success message
            const toast = $('<div>').addClass('toast-notification')
                .text('Added to cart!')
                .appendTo('body');

            setTimeout(() => {
                toast.fadeOut(() => toast.remove());
            }, 2000);
        }

        // Load fruit products from JSON
        $(document).ready(function() {
            $.getJSON('foodproduct.json', function(data) {
                const fruitsList = $('#fruits-list');
                fruitsList.empty(); // Clear loading spinner

                if (!data || data.length === 0) {
                    fruitsList.html('<div class="col-12 text-center py-5"><p>No fruits found.</p></div>');
                    return;
                }

                // Filter for fruits (you may need to adjust this based on your data structure)
                const fruits = data.filter(product =>
                    product.category === 'fruits' ||
                    ['Apple', 'Mango', 'Banana', 'Orange', 'Strawberry', 'Blueberry', 'Grapes', 'Watermelon', 'Pomegranate'].includes(product.name)
                );

                if (fruits.length === 0) {
                    fruitsList.html('<div class="col-12 text-center py-5"><p>No fruits found.</p></div>');
                    return;
                }

                // Add product ID if not present
                fruits.forEach((product, index) => {
                    if (!product.id) {
                        product.id = 'fruit-' + (index + 1);
                    }

                    fruitsList.append(`
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                    <img src="${product.image}" class="card-img-top" alt="${product.name}"
                                         style="object-fit: cover; height: 100%; width: 100%;">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">${product.name}</h5>
                                    <p class="card-text text-muted mb-1">${product.description || ''}</p>
                                    <div class="mt-auto">
                                        <p class="card-text fw-bold text-success mb-2">₹${product.price.toFixed(2)}</p>
                                        <button class="btn btn-success w-100"
                                            onclick="addToCart('${product.id}', '${product.name}', ${product.price}, '${product.image}')">
                                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });
            }).fail(function(jqxhr, textStatus, error) {
                const fruitsList = $('#fruits-list');
                fruitsList.html('<div class="col-12 text-center py-5"><p>Error loading fruits. Please try again later.</p></div>');
                console.error("Error loading fruits: " + textStatus + ", " + error);
            });
        });
    </script>
</body>
</html>
