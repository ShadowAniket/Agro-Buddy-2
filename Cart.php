<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart - AgroBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 60px; /* Add padding to prevent content from going behind navbar */
            background-color: #f8f9fa;
        }

        .cart-item {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: white;
            transition: all 0.3s ease;
        }
        .cart-item:hover {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        /* Image styles removed */
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .quantity-btn {
            background: #28a745;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .quantity-btn:hover {
            background: #218838;
            transform: scale(1.1);
        }
        .remove-btn {
            color: #dc3545;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .remove-btn:hover {
            color: #c82333;
            transform: scale(1.1);
        }
        #empty-cart {
            text-align: center;
            padding: 50px;
            color: #666;
        }
        .order-summary {
            position: sticky;
            top: 20px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .checkout-btn {
            width: 100%;
            padding: 12px;
            border-radius: 30px;
            margin-top: 15px;
            transition: all 0.3s ease;
        }
        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container my-5">
        <h1 class="text-center mb-4">Your Cart</h1>
        <div class="row">
            <div class="col-md-8" id="cart-items">
                <!-- Cart items will be dynamically inserted here -->
            </div>
            <div class="col-md-4">
                <div class="order-summary">
                    <h5 class="card-title">Order Summary</h5>
                    <hr>
                    <div id="total-items"></div>
                    <div id="subtotal"></div>
                    <div id="shipping"></div>
                    <hr>
                    <div id="total-price" class="fw-bold"></div>
                    <button class="btn btn-success checkout-btn" onclick="checkout()">
                        <i class="fas fa-shopping-cart me-2"></i>Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
        <div id="empty-cart" style="display: none;">
            <i class="fas fa-shopping-cart fa-3x mb-3 text-muted"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted">Add some products to your cart and they will show up here</p>
            <a href="Product.php" class="btn btn-success mt-3">Continue Shopping</a>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const userId = '<?php echo $_SESSION["user_id"]; ?>';

        function updateCartDisplay() {
            const cartKey = `cart_${userId}`;
            const cart = JSON.parse(localStorage.getItem(cartKey)) || [];
            const cartItems = $('#cart-items');
            const emptyCart = $('#empty-cart');

            if (cart.length === 0) {
                cartItems.hide();
                $('.order-summary').hide();
                emptyCart.show();
                return;
            }

            cartItems.empty();
            emptyCart.hide();
            $('.order-summary').show();

            let total = 0;
            let itemCount = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                itemCount += item.quantity;

                cartItems.append(`
                    <div class="cart-item">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="mb-1">${item.name}</h5>
                                <p class="mb-1">₹${item.price.toFixed(2)}</p>
                                <div class="quantity-control">
                                    <button class="quantity-btn" onclick="updateQuantity('${item.id}', ${item.quantity - 1})">-</button>
                                    <span>${item.quantity}</span>
                                    <button class="quantity-btn" onclick="updateQuantity('${item.id}', ${item.quantity + 1})">+</button>
                                    <i class="fas fa-trash ms-3 remove-btn" onclick="removeItem('${item.id}')"></i>
                                </div>
                            </div>
                            <div class="col-2 text-end">
                                <strong>₹${itemTotal.toFixed(2)}</strong>
                            </div>
                        </div>
                    </div>
                `);
            });

            const shipping = total > 500 ? 0 : 50;
            const finalTotal = total + shipping;

            $('#total-items').html(`Items (${itemCount})`);
            $('#subtotal').html(`Subtotal: ₹${total.toFixed(2)}`);
            $('#shipping').html(`Shipping: ₹${shipping.toFixed(2)}`);
            $('#total-price').html(`Total: ₹${finalTotal.toFixed(2)}`);
        }

        function updateQuantity(itemId, newQuantity) {
            if (newQuantity < 1) return;

            const cartKey = `cart_${userId}`;
            const cart = JSON.parse(localStorage.getItem(cartKey)) || [];
            const itemIndex = cart.findIndex(item => item.id === itemId);

            if (itemIndex !== -1) {
                cart[itemIndex].quantity = newQuantity;
                localStorage.setItem(cartKey, JSON.stringify(cart));
                updateCartDisplay();
            }
        }

        function removeItem(itemId) {
            const cartKey = `cart_${userId}`;
            let cart = JSON.parse(localStorage.getItem(cartKey)) || [];
            cart = cart.filter(item => item.id !== itemId);
            localStorage.setItem(cartKey, JSON.stringify(cart));
            updateCartDisplay();
        }

        function checkout() {
            window.location.href = 'checkout.php';
        }

        // Initialize cart display
        $(document).ready(updateCartDisplay);
    </script>
</body>
</html>
