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
    <title>Products - AgroBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 60px; /* Add padding to prevent content from going behind navbar */
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

      .glow-green {
        box-shadow: 0 0 10px rgba(11, 108, 38, 0.5); /* Glow effect with #0B6C26 */
        border-width: 2px;
        border-radius: 5px; /* Increase for more roundness if needed */
        background-color: white; /* White background */
        color: #4caf50; /* Text color updated to #4CAF50 */
      }

      .glow-green:hover {
        box-shadow: 0 0 15px rgba(11, 108, 38, 0.7); /* Stronger glow with #0B6C26 on hover */
      }

      /* Button Style */
      .thin-border {
        border-width: 1px;
        border-style: solid;
        border-color: #4caf50; /* Updated border color */
        background-color: #4caf50; /* Keep green background */
        color: white; /* White text */
      }

      /* Form Elements (Focus and Hover Effects) */
      /* Green glowing effect on hover or focus */
      .form-control:focus,
      .form-select:focus,
      .form-check-input:focus {
        box-shadow: 0 0 8px 2px rgba(11, 108, 38, 0.8); /* Glowing effect with #0B6C26 */
        border-color: #4caf50; /* Green border */
      }

      .form-control:hover,
      .form-select:hover,
      .form-check-input:hover {
        box-shadow: 0 0 8px 2px rgba(11, 108, 38, 0.8); /* Glowing effect with #0B6C26 */
      }

      /* List Items */
      li {
        list-style-type: none; /* No bullet points */
      }

      /* Button */
      .btn-outline-secondary {
        color: #000000; /* Black text */
        border-color: #4caf50; /* Updated darker green border */
      }

      .btn-outline-secondary:hover {
        background-color: #0b6c26; /* Updated background color on hover */
      }

      /* Product Cards */
      .col-3 {
        flex-basis: 20%; /* Set to 25% for 4 cards in a row */
        min-width: 200px; /* Ensure minimum width for responsiveness */
        margin: 0; /* Remove bottom margin */
        padding: 0; /* Remove padding if any */
      }

      .row {
        display: flex;
        align-items: stretch; /* Allow cards to stretch to the same height */
        flex-wrap: wrap; /* Enable wrapping if necessary */
        justify-content: center; /* Align items to the start */
      }

      /* Ensure equal height for images */
      .equal-img {
        width: 100%;
        height: 250px; /* Set a fixed height for all images */
        object-fit: cover; /* Ensures the image covers the box while maintaining aspect ratio */
      }

      /* Card */
      .card {
        box-shadow: 0 4px 8px rgb(0, 0, 0); /* Light shadow effect */
        margin: 0; /* Remove margin from cards */
        width: 100%;
        height: 500px;
        border: 1px solid #ddd;
        border-radius: 10px;
        overflow: hidden;
        background-color: #fff;
        transition: transform 0.2 ease-in-out;
      }

      .card:hover {
        transform: translateY(-5px);
      }

      .card-body {
        padding: 15px;
        background-color: #ffffff; /* White background */
        display: flex;
        flex-direction: column;
        justify-content: space-between; /* Ensure space between elements */
        height: 100%; /* Make the card-body stretch to fill the card */
      }

      .card-img-top {
        width: 100%;
        height: 250px; /* Set a fixed height for all images */
        object-fit: cover; /* Ensures the image covers the box while maintaining aspect ratio */
      }

      /* Text */
      .text-body-secondary {
        color: #000000; /* Black text */
      }

      .glow {
        text-shadow: 0 0 10px rgba(11, 108, 38, 0.5),
          0 0 15px rgba(76, 175, 80, 0.7); /* Updated glow effect */
      }

      /* Background */
      .bg-body-tertiary {
        background-color: #f1f1f1; /* Light gray background */
      }

      /* Footer */
      /* Footer Background */
      footer {
        background-color: #ffffff; /* Light background */
      }

      /* Footer Links */
      footer a {
        color: #4caf50; /* Updated color for footer links */
      }

      footer a:hover {
        text-decoration: underline;
      }

      /* Placeholder */
      /* SVG Placeholder Background */
      .bd-placeholder-img {
        background-color: #ffffff; /* White background for SVG placeholders */
      }

      /* SVG Placeholder Text Color */
      .bd-placeholder-img text {
        fill: #000000; /* Black text in SVG placeholder */
      }

      /*List Items */
      li {
        list-style-type: none; /* No bullet points */
      }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">All Products</h1>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-center">
                    <div class="btn-group" role="group" aria-label="Product Categories">
                        <a href="Product.php" class="btn btn-success active">All Products</a>
                        <a href="Fruits.php" class="btn btn-outline-success">Fruits</a>
                        <a href="Vegetables.php" class="btn btn-outline-success">Vegetables</a>
                        <a href="Pulses.php" class="btn btn-outline-success">Pulses</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="product-list">
            <!-- Products will be loaded here -->
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading products...</p>
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
                .text('Product added to cart!')
                .appendTo('body');

            setTimeout(() => {
                toast.fadeOut(() => toast.remove());
            }, 2000);
        }

        // Load products from JSON
        $(document).ready(function() {
            $.getJSON('foodproduct.json', function(data) {
                const productList = $('#product-list');
                productList.empty(); // Clear loading spinner

                if (!data || data.length === 0) {
                    productList.html('<div class="col-12 text-center py-5"><p>No products found.</p></div>');
                    return;
                }

                // Display all products
                data.forEach((product) => {
                    productList.append(`
                        <div class="col-md-3 mb-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-img-container" style="height: 200px; overflow: hidden;">
                                    <img src="${product.image}" class="card-img-top" alt="${product.name}"
                                         style="object-fit: cover; height: 100%; width: 100%;"
                                         onerror="this.src='images/placeholder.jpg'">
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
                const productList = $('#product-list');
                productList.html('<div class="col-12 text-center py-5"><p>Error loading products. Please try again later.</p></div>');
                console.error("Error loading products: " + textStatus + ", " + error);
            });
        });

        // Add toast notification style
        const style = document.createElement('style');
        style.textContent = `
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
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
