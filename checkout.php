<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: Login.php');
    exit;
}

require_once 'config.php';

// Process order submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $state = trim($_POST['state']);
    $zip = trim($_POST['zip']);
    $payment_method = trim($_POST['payment_method']);
    $cart_data = isset($_POST['cart_data']) ? $_POST['cart_data'] : '';
    $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;

    // Validate data
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($city) || empty($state) || empty($zip) || empty($payment_method)) {
        $error_message = 'All fields are required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Invalid email format';
    } elseif (empty($cart_data)) {
        $error_message = 'Your cart is empty';
    } else {
        try {
            // Start transaction
            $conn->beginTransaction();

            // Create order
            $stmt = $conn->prepare("INSERT INTO orders (user_id, name, email, phone, address, city, state, zip, payment_method, total_amount, status)
                                   VALUES (:user_id, :name, :email, :phone, :address, :city, :state, :zip, :payment_method, :total_amount, 'pending')");

            $result = $stmt->execute([
                'user_id' => $_SESSION['user_id'],
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'address' => $address,
                'city' => $city,
                'state' => $state,
                'zip' => $zip,
                'payment_method' => $payment_method,
                'total_amount' => $total_amount
            ]);

            if ($result) {
                $order_id = $conn->lastInsertId();

                // Decode cart data
                $cart_items = json_decode($cart_data, true);

                if (is_array($cart_items) && count($cart_items) > 0) {
                    // Insert order items
                    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
                                           VALUES (:order_id, :product_id, :product_name, :price, :quantity)");

                    foreach ($cart_items as $item) {
                        // Get product name from database if not available in cart
                        $product_name = isset($item['name']) ? $item['name'] : '';
                        $product_id = null; // Default to null for product_id

                        // Check if the product exists in the database
                        if (isset($item['id'])) {
                            $product_stmt = $conn->prepare("SELECT id, name FROM products WHERE id = :id");
                            $product_stmt->execute(['id' => $item['id']]);
                            $product = $product_stmt->fetch(PDO::FETCH_ASSOC);

                            if ($product) {
                                // If product exists in database, use its ID
                                $product_id = $product['id'];
                                if (empty($product_name)) {
                                    $product_name = $product['name'];
                                }
                            }
                        }

                        // If name is still empty, use a placeholder
                        if (empty($product_name)) {
                            $product_name = 'Product #' . (isset($item['id']) ? $item['id'] : 'Unknown');
                        }

                        $stmt->execute([
                            'order_id' => $order_id,
                            'product_id' => $product_id, // This can be null if product doesn't exist
                            'product_name' => $product_name,
                            'price' => $item['price'],
                            'quantity' => $item['quantity']
                        ]);
                    }

                    // Commit transaction
                    $conn->commit();

                    $success_message = 'Order placed successfully! Your order ID is #' . $order_id;

                    // Clear cart
                    echo "<script>localStorage.removeItem('cart_" . $_SESSION['user_id'] . "');</script>";
                } else {
                    throw new Exception('Invalid cart data');
                }
            } else {
                throw new Exception('Failed to create order');
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            $conn->rollBack();
            $error_message = 'Error processing order: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - AgroBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 60px;
            background-color: #f8f9fa;
        }

        .checkout-container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
        }

        .checkout-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .cart-item {
            border: 1px solid #eee;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Image styles removed */

        .form-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .payment-methods {
            margin-top: 20px;
        }

        .payment-method {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .payment-method:hover, .payment-method.selected {
            border-color: #4CAF50;
            background-color: #f0f9f0;
        }

        .payment-method.selected {
            box-shadow: 0 0 0 2px #4CAF50;
        }

        .btn-success {
            background-color: #4CAF50;
            border-color: #4CAF50;
        }

        .btn-success:hover {
            background-color: #3e8e41;
            border-color: #3e8e41;
        }

        .empty-cart {
            text-align: center;
            padding: 50px 0;
        }

        .empty-cart i {
            font-size: 5rem;
            color: #ddd;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="container">
        <div class="checkout-container">
            <div class="checkout-header">
                <h2><i class="fas fa-shopping-cart me-2"></i>Checkout</h2>
                <p class="text-muted">Complete your purchase</p>
            </div>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success_message); ?>
                    <div class="mt-3">
                        <a href="dashboard.php" class="btn btn-outline-success">Go to Dashboard</a>
                    </div>
                </div>
            <?php else: ?>
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <div id="cart-items-container">
                    <!-- Cart items will be loaded here -->
                </div>

                <div id="checkout-form-container" style="display: none;">
                    <form id="checkout-form" method="post" action="checkout.php">
                        <input type="hidden" name="cart_data" id="cart-data">
                        <input type="hidden" name="total_amount" id="total-amount">

                        <div class="form-section">
                            <h4>Shipping Information</h4>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="state" class="form-label">State</label>
                                    <input type="text" class="form-control" id="state" name="state" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="zip" class="form-label">ZIP Code</label>
                                    <input type="text" class="form-control" id="zip" name="zip" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h4>Payment Method</h4>
                            <div class="payment-methods">
                                <div class="payment-method" data-method="cod">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment-cod" value="cod" checked>
                                        <label class="form-check-label" for="payment-cod">
                                            <i class="fas fa-money-bill-wave me-2"></i>Cash on Delivery
                                        </label>
                                    </div>
                                </div>
                                <div class="payment-method" data-method="card">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment-card" value="card">
                                        <label class="form-check-label" for="payment-card">
                                            <i class="fas fa-credit-card me-2"></i>Credit/Debit Card
                                        </label>
                                    </div>
                                </div>
                                <div class="payment-method" data-method="upi">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" id="payment-upi" value="upi">
                                        <label class="form-check-label" for="payment-upi">
                                            <i class="fas fa-mobile-alt me-2"></i>UPI
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="Cart.php" class="btn btn-outline-secondary me-md-2">Back to Cart</a>
                            <button type="submit" class="btn btn-success">Place Order</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const userId = '<?php echo isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : ""; ?>';
            const cartKey = `cart_${userId}`;
            let cart = JSON.parse(localStorage.getItem(cartKey)) || [];

            // Display cart items
            function displayCartItems() {
                const cartContainer = $('#cart-items-container');

                if (cart.length === 0) {
                    cartContainer.html(`
                        <div class="empty-cart">
                            <i class="fas fa-shopping-cart"></i>
                            <h3>Your cart is empty</h3>
                            <p>Add some products to your cart to proceed with checkout.</p>
                            <a href="Product.php" class="btn btn-success mt-3">Continue Shopping</a>
                        </div>
                    `);
                    $('#checkout-form-container').hide();
                } else {
                    let cartHtml = '<h4>Order Summary</h4>';
                    let totalAmount = 0;

                    cartHtml += '<div class="table-responsive">';
                    cartHtml += '<table class="table">';
                    cartHtml += '<thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Total</th></tr></thead>';
                    cartHtml += '<tbody>';

                    cart.forEach(item => {
                        const itemTotal = item.price * item.quantity;
                        totalAmount += itemTotal;

                        cartHtml += `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0">${item.name}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>₹${item.price.toFixed(2)}</td>
                                <td>${item.quantity}</td>
                                <td>₹${itemTotal.toFixed(2)}</td>
                            </tr>
                        `;
                    });

                    cartHtml += '</tbody>';
                    cartHtml += '<tfoot>';
                    cartHtml += `<tr><td colspan="3" class="text-end fw-bold">Subtotal:</td><td>₹${totalAmount.toFixed(2)}</td></tr>`;
                    cartHtml += `<tr><td colspan="3" class="text-end fw-bold">Shipping:</td><td>₹50.00</td></tr>`;
                    cartHtml += `<tr><td colspan="3" class="text-end fw-bold">Total:</td><td class="fw-bold">₹${(totalAmount + 50).toFixed(2)}</td></tr>`;
                    cartHtml += '</tfoot>';
                    cartHtml += '</table>';
                    cartHtml += '</div>';

                    cartContainer.html(cartHtml);
                    $('#checkout-form-container').show();

                    // Set cart data and total amount for form submission
                    $('#cart-data').val(JSON.stringify(cart));
                    $('#total-amount').val((totalAmount + 50).toFixed(2));
                }
            }

            // Handle payment method selection
            $('.payment-method').click(function() {
                $('.payment-method').removeClass('selected');
                $(this).addClass('selected');
                const method = $(this).data('method');
                $(`#payment-${method}`).prop('checked', true);
            });

            // Initialize
            displayCartItems();

            <?php if (!empty($success_message)): ?>
            // Clear cart after successful order
            localStorage.removeItem('<?php echo "cart_" . $_SESSION['user_id']; ?>');
            <?php endif; ?>
        });
    </script>
</body>
</html>
