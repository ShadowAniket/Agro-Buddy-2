<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container">
        <a class="navbar-brand" href="Home.php">
            <img src="images/agrobuddy.png" alt="AgroBuddy Logo" height="40">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="Home.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Products
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="Product.php">All Products</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="Fruits.php">Fruits</a></li>
                        <li><a class="dropdown-item" href="Vegetables.php">Vegetables</a></li>
                        <li><a class="dropdown-item" href="Pulses.php">Pulses</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Soil Info
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="Soil-Analysis.php">Soil Analysis</a></li>
                        <li><a class="dropdown-item" href="Soil-Map.php">Soil Map</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="Weather-Forecast.php">Weather</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="About.php">About</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user"></i>
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="dashboard.php"><i class="fas fa-columns"></i> Dashboard</a></li>
                            <li><a class="dropdown-item" href="Cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="logout()">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="Login.php">
                            <i class="fas fa-sign-in-alt"></i> Login / Sign Up
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<script>
function logout() {
    fetch('logout.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'Home.php';
            }
        })
        .catch(error => {
            console.error('Logout failed:', error);
        });
}
</script>