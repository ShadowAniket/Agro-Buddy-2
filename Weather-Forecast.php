<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';

// Weather API configuration
$openweather_api_key = '82005d27a116c2880c8f0fcb866998a0'; // Updated OpenWeather API key
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Weather Forecast - AgroBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body {
            padding-top: 60px; /* Add padding to prevent content from going behind navbar */
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            /* background-color: #f8f9fa; */
            position: relative;
        }

        .navbar {
            background-color: rgba(76, 175, 80, 0.9) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand, .nav-link {
            color: white !important;
            font-weight: 500;
        }

        #bg-video {
            position: fixed;
            top: 0;
            left: 0;
            min-width: 100%;
            min-height: 100%;
            z-index: -1;
            object-fit: cover;
            filter: brightness(0.8);
            animation: slowDown 60s linear infinite;
        }

        .content {
            position: relative;
            z-index: 1;
        }

        .container {
            display: block;
            margin: 0 auto;
            border-radius: 15px;
            padding-bottom: 30px;
        }

        .city-btn {
            background-color: rgba(76, 175, 80, 0.2);
            color: #333 !important;
            border-color: #4CAF50;
            margin: 0 2px;
            font-weight: 500;
        }

        .city-btn:hover, .city-btn.active {
            background-color: #4CAF50;
            color: white !important;
        }

        .app-title {
            width: 100%;
            height: 50px;
            border-radius: 15px 15px 0 0;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            z-index: 1;
        }

        .app-title p {
            text-align: center;
            padding: 5px;
            margin: 0 auto;
            font-size: 1.5em;
            font-weight: bold;
        }

        .notification {
            background-color: #f8d7da;
            display: none;
            padding: 10px;
            border-radius: 0 0 10px 10px;
        }

        .notification p {
            color: #721c24;
            font-size: 1.2em;
            margin: 0;
            text-align: center;
        }

        .weather-container {
            width: 100%;
            height: 260px;
            padding: 20px;
            border-radius: 0 0 15px 15px;
        }

        .weather-icon {
            width: 100%;
            height: 128px;
            display: flex;
            justify-content: center;
        }

        .weather-icon img {
            display: block;
            margin: 0 auto;
            width: 128px;
            height: 128px;
        }

        .temperature-value {
            width: 100%;
            height: 60px;
            text-align: center;
        }

        .temperature-value p {
            padding: 0;
            margin: 0;
            color: #000000;
            font-size: 4em;
            text-align: center;
            cursor: pointer;
            font-weight: bold;
        }

        .temperature-value span {
            color: #000000;
            font-size: 0.5em;
        }

        .temperature-description p {
            padding: 8px;
            margin: 0;
            color: #000000;
            text-align: center;
            font-size: 1.2em;
            text-transform: capitalize;
        }

        .location p {
            margin: 0;
            padding: 0;
            color: #000000;
            text-align: center;
            font-size: 1em;
        }

        .weather-forecast {
            margin-top: 30px;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .weather-forecast h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .forecast-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .forecast-day {
            flex: 1;
            min-width: 150px;
            background-color: rgba(244, 249, 255, 0.8);
            border-radius: 10px;
            padding: 15px;
            margin: 5px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .forecast-day .date {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .forecast-day .temp {
            margin: 10px 0;
        }

        .forecast-day .high {
            color: #dc3545;
            font-weight: bold;
            margin-right: 10px;
        }

        .forecast-day .low {
            color: #007bff;
            font-weight: bold;
        }

        .farming-tips {
            margin-top: 30px;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .farming-tips h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        .farming-tips ul {
            list-style-type: none;
            padding: 0;
        }

        .farming-tips li {
            background-color: rgba(244, 249, 255, 0.8);
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            position: relative;
            padding-left: 35px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .farming-tips li:before {
            content: "🌱";
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <video autoplay muted loop id="bg-video">
        <source src="weather forcaste/moving clouds.mp4" type="video/mp4">
    </video>

    <div class="content">
        <!-- Weather Header Section -->
        <div class="container-fluid py-4 mb-4" style="background: linear-gradient(135deg, #4CAF50, #2E7D32);">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center text-white">
                        <h1><i class="fas fa-cloud-sun me-2"></i>Weather Forecast</h1>
                        <p class="lead">Get real-time weather updates and farming advice for Indian cities</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mt-4 mb-5" style="width: 350px;">
            <div class="app-title bg-success text-white p-3 rounded-top">
                <h4 class="mb-0">Current Weather</h4>
            </div>
            <div class="notification"></div>
            <div class="weather-container bg-gradient p-4 rounded-bottom" style="background: linear-gradient(135deg, #4CAF50, #2E7D32);">
                <div class="weather-icon text-center mb-3">
                    <img src="https://openweathermap.org/img/wn/10d@2x.png" alt="Weather Icon" id="weather-icon" class="bg-white rounded-circle p-2">
                </div>
                <div class="temperature-value text-center text-white">
                    <p class="display-4 mb-0">- °<span>C</span></p>
                </div>
                <div class="temperature-description text-center text-white">
                    <p class="fs-5 mb-2">-</p>
                </div>
                <div class="location text-center text-white">
                    <p class="fs-6"><i class="fas fa-map-marker-alt me-2"></i>-</p>
                </div>
            </div>
        </div>

        <div class="container mb-5">
            <div class="row">
                <div class="col-md-10 offset-md-1">
                    <div class="weather-forecast bg-gradient p-4 rounded mb-4" style="background: linear-gradient(135deg, #3498db, #1a5276);">
                        <h3 class="text-white mb-3"><i class="fas fa-calendar-alt me-2"></i>5-Day Forecast</h3>
                        <div class="forecast-container" id="forecast-container">
                            <!-- Forecast data will be loaded here -->
                            <div class="text-center py-3 text-white">
                                <div class="spinner-border text-light" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Loading forecast data...</p>
                            </div>
                        </div>
                    </div>

                    <div class="farming-tips bg-gradient p-4 rounded" style="background: linear-gradient(135deg, #e67e22, #d35400);">
                        <h3 class="text-white mb-3"><i class="fas fa-leaf me-2"></i>Weather-based Farming Tips</h3>
                        <div id="farming-advice" class="text-white">
                            <!-- Farming advice will be loaded here -->
                            <div class="text-center py-3">
                                <div class="spinner-border text-light" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Generating farming advice...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-5">
        <?php include 'footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // DOM elements
        const iconElement = document.querySelector("#weather-icon");
        const tempElement = document.querySelector(".temperature-value p");
        const descElement = document.querySelector(".temperature-description p");
        const locationElement = document.querySelector(".location p");
        const notificationElement = document.querySelector(".notification");

        // App data
        const weather = {
            temperature: {
                value: 0,
                unit: "celsius"
            },
            description: "",
            iconId: "unknown",
            city: "",
            country: ""
        };

        // API key
        const key = "<?php echo $openweather_api_key; ?>";

        // Default to Mumbai coordinates if geolocation fails or isn't available
        const defaultCities = [
            { name: "Mumbai", lat: 19.0760, lon: 72.8777 },
            { name: "Delhi", lat: 28.6139, lon: 77.2090 },
            { name: "Bangalore", lat: 12.9716, lon: 77.5946 },
            { name: "Chennai", lat: 13.0827, lon: 80.2707 },
            { name: "Kolkata", lat: 22.5726, lon: 88.3639 }
        ];

        // Create city selector
        const citySelector = document.createElement('div');
        citySelector.className = 'city-selector mt-3';
        citySelector.innerHTML = `
            <div class="d-flex justify-content-center">
                <div class="btn-group" role="group">
                    ${defaultCities.map(city =>
                        `<button type="button" class="btn btn-outline-light city-btn"
                         data-lat="${city.lat}" data-lon="${city.lon}">${city.name}</button>`
                    ).join('')}
                </div>
            </div>
        `;

        document.querySelector('.weather-container').appendChild(citySelector);

        // Add event listeners to city buttons
        document.querySelectorAll('.city-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const lat = parseFloat(this.getAttribute('data-lat'));
                const lon = parseFloat(this.getAttribute('data-lon'));

                // Update active button
                document.querySelectorAll('.city-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');

                getWeather(lat, lon);
                getForecast(lat, lon);
            });
        });

        // Try geolocation first, fall back to Mumbai if it fails
        if('geolocation' in navigator){
            navigator.geolocation.getCurrentPosition(setPosition, useDefaultLocation);
        } else {
            useDefaultLocation();
        }

        // Set user's position from geolocation
        function setPosition(position){
            let latitude = position.coords.latitude;
            let longitude = position.coords.longitude;

            getWeather(latitude, longitude);
            getForecast(latitude, longitude);
        }

        // Use Mumbai as default location
        function useDefaultLocation() {
            // Select Mumbai by default
            const defaultCity = defaultCities[0];
            getWeather(defaultCity.lat, defaultCity.lon);
            getForecast(defaultCity.lat, defaultCity.lon);

            // Highlight Mumbai button
            const mumbaiBtn = document.querySelector(`.city-btn[data-lat="${defaultCity.lat}"]`);
            if (mumbaiBtn) {
                mumbaiBtn.classList.add('active');
            }
        }

        // Show error when there is an issue with geolocation service
        function showError(error){
            notificationElement.style.display = "block";
            notificationElement.innerHTML = `<p>${error.message}</p>`;
        }

        // Get weather from API provider
        function getWeather(latitude, longitude){
            let api = `https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&units=metric&appid=${key}`;

            fetch(api)
                .then(function(response){
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(data){
                    if (data && data.main && data.weather && data.weather.length > 0) {
                        weather.temperature.value = Math.floor(data.main.temp);
                        weather.description = data.weather[0].description;
                        weather.iconId = data.weather[0].icon;
                        weather.city = data.name || 'Unknown';
                        weather.country = data.sys && data.sys.country ? data.sys.country : '';

                        // Save location to session
                        fetch('update_location.php?lat=' + latitude + '&lon=' + longitude, {
                            method: 'GET'
                        });
                    } else {
                        throw new Error('Invalid weather data structure');
                    }
                })
                .then(function(){
                    displayWeather();
                })
                .catch(function(error) {
                    console.error("Error fetching current weather:", error);
                    notificationElement.style.display = "block";
                    notificationElement.innerHTML = "<p>Unable to fetch weather data. Please try again later.</p>";

                    // Display fallback weather
                    weather.temperature.value = "--";
                    weather.description = "Unavailable";
                    weather.iconId = "10d"; // Use a default icon that exists
                    weather.city = "Unknown";
                    weather.country = "";
                    displayWeather();
                });
        }

        // Get forecast from API provider
        function getForecast(latitude, longitude){
            let api = `https://api.openweathermap.org/data/2.5/forecast?lat=${latitude}&lon=${longitude}&units=metric&appid=${key}`;

            fetch(api)
                .then(function(response){
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(data){
                    if (data && data.list && data.list.length > 0) {
                        displayForecast(data);
                        generateFarmingAdvice(data);
                    } else {
                        throw new Error('Invalid forecast data structure');
                    }
                })
                .catch(function(error) {
                    console.error("Error fetching forecast:", error);

                    // Display error message in forecast container
                    const container = document.getElementById('forecast-container');
                    container.innerHTML = `
                        <div class="alert alert-warning text-center">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Unable to load forecast data. Please try again later.
                        </div>
                    `;

                    // Display generic farming advice
                    const advice = document.getElementById('farming-advice');
                    advice.innerHTML = `
                        <div class="alert alert-info">
                            <h5><i class="fas fa-leaf me-2"></i>General Farming Tips</h5>
                            <ul>
                                <li>Monitor soil moisture regularly and adjust irrigation accordingly.</li>
                                <li>Apply mulch to conserve soil moisture and suppress weeds.</li>
                                <li>Rotate crops to prevent soil depletion and reduce pest problems.</li>
                                <li>Implement integrated pest management for sustainable pest control.</li>
                            </ul>
                        </div>
                    `;
                });
        }

        // Display weather to UI
        function displayWeather(){
            // Fix for missing icons - use OpenWeatherMap icons directly
            iconElement.src = `https://openweathermap.org/img/wn/${weather.iconId}@2x.png`;
            tempElement.innerHTML = `${weather.temperature.value}°<span>C</span>`;
            descElement.innerHTML = weather.description;
            locationElement.innerHTML = `${weather.city}, ${weather.country}`;

            // Remove loading indicators
            document.querySelector('.notification').style.display = 'none';
        }

        // Display forecast to UI
        function displayForecast(data){
            const container = document.getElementById('forecast-container');
            container.innerHTML = '';

            // Get one forecast per day (at noon)
            const dailyForecasts = {};

            data.list.forEach(item => {
                const date = new Date(item.dt * 1000);
                const day = date.toLocaleDateString('en-US', { weekday: 'short' });

                if (!dailyForecasts[day] && Object.keys(dailyForecasts).length < 5) {
                    dailyForecasts[day] = item;
                }
            });

            // Create forecast cards
            Object.values(dailyForecasts).forEach(forecast => {
                const date = new Date(forecast.dt * 1000);
                const dayCard = document.createElement('div');
                dayCard.className = 'forecast-day';
                dayCard.innerHTML = `
                    <div class="date">${date.toLocaleDateString('en-US', { weekday: 'long' })}</div>
                    <img src="https://openweathermap.org/img/wn/${forecast.weather[0].icon}@2x.png" alt="${forecast.weather[0].description}">
                    <div class="temp">
                        <span class="high">${Math.round(forecast.main.temp_max)}°C</span>
                        <span class="low">${Math.round(forecast.main.temp_min)}°C</span>
                    </div>
                    <div class="conditions">${forecast.weather[0].description}</div>
                `;
                container.appendChild(dayCard);
            });
        }

        // Generate farming advice based on weather
        function generateFarmingAdvice(data){
            const advice = document.getElementById('farming-advice');
            let tips = '<ul>';

            // Current conditions from first forecast item
            const current = data.list[0];
            const temp = current.main.temp;
            const humidity = current.main.humidity;
            const windSpeed = current.wind.speed;
            const weather = current.weather[0].main.toLowerCase();

            // Temperature-based advice
            if (temp > 30) {
                tips += '<li>High temperature detected. Consider additional irrigation for crops and provide shade for sensitive plants.</li>';
            } else if (temp < 10) {
                tips += '<li>Low temperature alert. Protect frost-sensitive crops with covers or move potted plants indoors.</li>';
            }

            // Humidity-based advice
            if (humidity > 80) {
                tips += '<li>High humidity conditions. Monitor for fungal diseases and ensure proper ventilation in greenhouses.</li>';
            } else if (humidity < 30) {
                tips += '<li>Low humidity detected. Increase watering frequency and consider mulching to retain soil moisture.</li>';
            }

            // Weather condition advice
            if (weather.includes('rain') || weather.includes('drizzle')) {
                tips += '<li>Rainy conditions expected. Delay pesticide application and ensure proper drainage in fields.</li>';
            } else if (weather.includes('clear')) {
                tips += '<li>Clear skies forecasted. Good opportunity for harvesting, planting, or applying treatments.</li>';
            } else if (weather.includes('cloud')) {
                tips += '<li>Cloudy conditions. Ideal for transplanting seedlings to minimize transplant shock.</li>';
            }

            // Wind-based advice
            if (windSpeed > 5) {
                tips += '<li>Windy conditions detected. Secure young plants and delay spraying operations.</li>';
            }

            tips += '</ul>';
            advice.innerHTML = tips;
        }

        // Video playback rate
        const video = document.getElementById('bg-video');
        if (video) {
            video.playbackRate = 0.5; // Slows the video to half speed
        }
    </script>
</body>
</html>