<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
require_once 'check_session.php';

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$stmt = $conn->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$order_count = $stmt->fetchColumn();


// Fetch recent weather data from OpenWeather API
$lat = isset($_SESSION['lat']) ? $_SESSION['lat'] : 20.5937;
$lon = isset($_SESSION['lon']) ? $_SESSION['lon'] : 78.9629;
$weather_url = "https://api.openweathermap.org/data/2.5/weather?lat={$lat}&lon={$lon}&units=metric&appid={$openweather_api_key}";

// Add error handling for weather API
$weather_data = [];
try {
    // Use cURL instead of file_get_contents for better error handling
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $weather_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $curl_error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response !== false && $http_code == 200) {
        $weather_data = json_decode($response, true);
        // Set default location name if not available
        if (!isset($weather_data['name']) || empty($weather_data['name'])) {
            $weather_data['name'] = 'Your Location';
        }
    } else {
        error_log("Weather API request failed: " . $curl_error . " HTTP Code: " . $http_code);
        // Set default weather data for better user experience
        $weather_data = [
            'name' => 'India',
            'main' => [
                'temp' => 28,
                'temp_min' => 26,
                'temp_max' => 30,
                'humidity' => 65
            ],
            'weather' => [
                [
                    'description' => 'clear sky',
                    'icon' => '01d'
                ]
            ],
            'wind' => [
                'speed' => 3.5
            ]
        ];
    }
} catch (Exception $e) {
    error_log("Weather API error: " . $e->getMessage());
    // Set default weather data in case of exception
    $weather_data = [
        'name' => 'India',
        'main' => [
            'temp' => 28,
            'temp_min' => 26,
            'temp_max' => 30,
            'humidity' => 65
        ],
        'weather' => [
            [
                'description' => 'clear sky',
                'icon' => '01d'
            ]
        ],
        'wind' => [
            'speed' => 3.5
        ]
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - AgroBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 60px; /* Add padding to prevent content from going behind navbar */
            background-color: #f8f9fa;
        }

        .widget {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        .weather-widget {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .weather-widget::after {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background: linear-gradient(rgba(255,255,255,0.1), rgba(255,255,255,0));
            pointer-events: none;
        }

        .profile-widget {
            text-align: center;
            background: linear-gradient(to bottom, #ffffff, #f9f9f9);
            border: 1px solid #eaeaea;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-bottom: 20px;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            text-decoration: none;
            color: #333;
            transition: all 0.3s;
            background: white;
        }

        .action-btn:hover {
            background: #f0f9f0;
            transform: translateY(-3px);
            color: #198754;
            box-shadow: 0 5px 15px rgba(25, 135, 84, 0.1);
        }

        .action-btn i {
            font-size: 28px;
            margin-bottom: 12px;
            color: #4CAF50;
        }

        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            animation: fadeIn 0.3s, fadeOut 0.3s 1.7s;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateY(0); }
            to { opacity: 0; transform: translateY(20px); }
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'nav.php'; ?>

    <div class="container mt-5 pt-4">
        <div class="row">
            <!-- Profile Widget -->
            <div class="col-md-4">
                <div class="widget profile-widget">
                    <img src="<?php echo isset($user['avatar']) ? htmlspecialchars($user['avatar']) : 'images/default-avatar.png'; ?>"
                         alt="Profile" class="profile-img">
                    <h4><?php echo htmlspecialchars($user['username']); ?></h4>
                    <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                    <button class="btn btn-outline-success btn-sm" onclick="location.href='edit_profile.php'">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                </div>

                <!-- Quick Stats Widget -->
                <div class="widget">
                    <h5 class="mb-3">Quick Stats</h5>
                    <div class="row text-center">
                        <div class="col">
                            <h3 class="text-success">0</h3>
                            <p class="text-muted mb-0">Orders</p>
                        </div>
                        <div class="col">
                            <h3 class="text-success">0</h3>
                            <p class="text-muted mb-0">Products</p>
                        </div>
                        <div class="col">
                            <h3 class="text-success">0</h3>
                            <p class="text-muted mb-0">Reviews</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Weather Widget -->
                <div class="widget weather-widget">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Current Weather</h5>
                            <?php if (!empty($weather_data) && isset($weather_data['name'])): ?>
                                <p class="mb-2"><?php echo htmlspecialchars($weather_data['name']); ?></p>
                                <?php if (isset($weather_data['main']) && isset($weather_data['main']['temp'])): ?>
                                    <h2 class="mb-0"><?php echo round($weather_data['main']['temp']); ?>°C</h2>
                                <?php else: ?>
                                    <h2 class="mb-0">--°C</h2>
                                <?php endif; ?>

                                <?php if (isset($weather_data['weather']) && isset($weather_data['weather'][0]) && isset($weather_data['weather'][0]['description'])): ?>
                                    <p class="mb-0"><?php echo ucfirst(htmlspecialchars($weather_data['weather'][0]['description'])); ?></p>
                                <?php else: ?>
                                    <p class="mb-0">Weather data unavailable</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="mb-2">Location unavailable</p>
                                <h2 class="mb-0">--°C</h2>
                                <p class="mb-0">Weather data unavailable</p>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($weather_data) && isset($weather_data['weather']) && isset($weather_data['weather'][0]) && isset($weather_data['weather'][0]['icon'])): ?>
                            <img src="https://openweathermap.org/img/wn/<?php echo htmlspecialchars($weather_data['weather'][0]['icon']); ?>@2x.png"
                                alt="Weather Icon" style="width: 100px;">
                        <?php else: ?>
                            <i class="fas fa-cloud fa-4x text-white"></i>
                        <?php endif; ?>
                    </div>
                    <hr class="border-light">
                    <div class="row text-center">
                        <div class="col">
                            <i class="fas fa-temperature-high"></i>
                            <p class="mb-0">High</p>
                            <?php if (!empty($weather_data) && isset($weather_data['main']) && isset($weather_data['main']['temp_max'])): ?>
                                <p class="mb-0"><?php echo round($weather_data['main']['temp_max']); ?>°C</p>
                            <?php else: ?>
                                <p class="mb-0">--°C</p>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <i class="fas fa-temperature-low"></i>
                            <p class="mb-0">Low</p>
                            <?php if (!empty($weather_data) && isset($weather_data['main']) && isset($weather_data['main']['temp_min'])): ?>
                                <p class="mb-0"><?php echo round($weather_data['main']['temp_min']); ?>°C</p>
                            <?php else: ?>
                                <p class="mb-0">--°C</p>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <i class="fas fa-wind"></i>
                            <p class="mb-0">Wind</p>
                            <?php if (!empty($weather_data) && isset($weather_data['wind']) && isset($weather_data['wind']['speed'])): ?>
                                <p class="mb-0"><?php echo $weather_data['wind']['speed']; ?> m/s</p>
                            <?php else: ?>
                                <p class="mb-0">-- m/s</p>
                            <?php endif; ?>
                        </div>
                        <div class="col">
                            <i class="fas fa-tint"></i>
                            <p class="mb-0">Humidity</p>
                            <?php if (!empty($weather_data) && isset($weather_data['main']) && isset($weather_data['main']['humidity'])): ?>
                                <p class="mb-0"><?php echo $weather_data['main']['humidity']; ?>%</p>
                            <?php else: ?>
                                <p class="mb-0">--%</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="widget">
                    <h5 class="mb-3">Quick Actions</h5>
                    <div class="quick-actions">
                        <a href="Product.php" class="action-btn">
                            <i class="fas fa-store text-success"></i>
                            <span>Browse Products</span>
                        </a>
                        <a href="Weather-Forecast.php" class="action-btn">
                            <i class="fas fa-cloud-sun text-success"></i>
                            <span>Weather Forecast</span>
                        </a>
                        <a href="Soil-Analysis.php" class="action-btn">
                            <i class="fas fa-seedling text-success"></i>
                            <span>Soil Analysis</span>
                        </a>
                        <a href="Cart.php" class="action-btn">
                            <i class="fas fa-shopping-cart text-success"></i>
                            <span>View Cart</span>
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="widget">
                    <h5 class="mb-3">Recent Activity</h5>
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Welcome to AgroBuddy!</h6>
                                <small class="text-muted">Just now</small>
                            </div>
                            <p class="mb-1">Start exploring our features and products.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Get user's location for weather updates
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const { latitude, longitude } = position.coords;
                fetch(`update_location.php?lat=${latitude}&lon=${longitude}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    });
            });
        }
    </script>
</body>
</html>