<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Soil Analysis - AgroBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 60px; /* Add padding to prevent content from going behind navbar */
            font-family: 'Arial', sans-serif;
        }

        h1 {
            text-align: center;
            margin: 30px 0;
            font-weight: bold;
        }

        .flip-card-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            padding: 30px;
            margin-top: 30px;
            background: linear-gradient(to right, #f0f7f0, #e8f5e9);
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .flip-card {
            perspective: 1000px;
            width: 300px;
            height: 300px;
            margin: 15px;
            display: inline-block;
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            border-radius: 15px;
            border: 2px solid #4CAF50;
        }

        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 10px;
            overflow: hidden;
        }

        .flip-card-front {
            background-color: white;
        }

        .flip-card-front img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .flip-card-back {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            transform: rotateY(180deg);
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            border: 3px solid #fff;
        }

        .flip-card-back h2 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .flip-card-back p {
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .map-container {
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            position: relative;
            margin-bottom: 30px;
        }

        iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .glow-green {
            box-shadow: 0 0 10px rgba(11, 108, 38, 0.5);
            border-width: 2px;
            border-radius: 5px;
            background-color: white;
            color: #4CAF50;
        }

        .glow-green:hover {
            box-shadow: 0 0 15px rgba(11, 108, 38, 0.7);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'nav.php'; ?>

    <div class="container-fluid mt-4 px-0">
        <section class="py-4 mb-5" style="background: linear-gradient(135deg, #4CAF50, #2E7D32);">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="text-white mb-0"><i class="fas fa-seedling me-2"></i><strong>Soil Analysis</strong></h1>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="Soil-Map.php" class="btn btn-light"><i class="fas fa-map-marked-alt me-2"></i>View Full Soil Map</a>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-4 mb-5" style="background: linear-gradient(135deg, #FF9800, #F57C00);">
            <div class="container">
                <h1 class="text-center text-white mb-0"><i class="fas fa-seedling me-2"></i><strong>Soil Types</strong></h1>
            </div>
        </section>

        <div class="container">
            <div class="flip-card-container">
            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="images/Costal Aluvilium soil.jpeg" alt="Coastal Alluvial Soil">
                    </div>
                    <div class="flip-card-back">
                        <h2>Coastal Alluvial Soil</h2>
                        <p><strong>Description:</strong> Found along coastlines, these soils are sandy, saline, and enriched with organic matter.</p>
                        <p><strong>Found in:</strong> Kerala, Tamil Nadu, Andaman and Nicobar Islands</p>
                        <p><strong>Crops:</strong> Coconut, Rice, Millets</p>
                    </div>
                </div>
            </div>

            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="images/Forest Soil.jpeg" alt="Forest Soil">
                    </div>
                    <div class="flip-card-back">
                        <h2>Forest Soil</h2>
                        <p><strong>Description:</strong> Found in forested regions, these soils are rich in organic matter and humus but are acidic in nature.</p>
                        <p><strong>Found in:</strong> Himachal Pradesh, Jammu & Kashmir, Arunachal Pradesh</p>
                        <p><strong>Crops:</strong> Tea, Coffee, Spices</p>
                    </div>
                </div>
            </div>

            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="images/Aluvial Soil.jpeg" alt="Alluvial Soil">
                    </div>
                    <div class="flip-card-back">
                        <h2>Alluvial Soil</h2>
                        <p><strong>Description:</strong> Highly fertile soil, formed by river deposits, suitable for agriculture.</p>
                        <p><strong>Found in:</strong> Uttar Pradesh, Bihar, Punjab</p>
                        <p><strong>Crops:</strong> Rice, Wheat, Sugarcane</p>
                    </div>
                </div>
            </div>

            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="images/Coral & Sandy.jpeg" alt="Sandy Soil">
                    </div>
                    <div class="flip-card-back">
                        <h2>Sandy Soil</h2>
                        <p><strong>Description:</strong> Coarse-textured soil, well-drained but low in fertility and moisture retention.</p>
                        <p><strong>Found in:</strong> Rajasthan, Gujarat</p>
                        <p><strong>Crops:</strong> Bajra, Barley, Dates</p>
                    </div>
                </div>
            </div>

            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="images/Black Soil.jpeg" alt="Black Soil">
                    </div>
                    <div class="flip-card-back">
                        <h2>Black Soil</h2>
                        <p><strong>Description:</strong> Black soil is known for its moisture-retaining capacity and high clay content, making it ideal for cotton cultivation.</p>
                        <p><strong>Found in:</strong> Maharashtra, Karnataka, Andhra Pradesh</p>
                        <p><strong>Crops:</strong> Cotton, Coffee, Sugarcane</p>
                    </div>
                </div>
            </div>

            <div class="flip-card">
                <div class="flip-card-inner">
                    <div class="flip-card-front">
                        <img src="images/Red Soil.jpeg" alt="Red Soil">
                    </div>
                    <div class="flip-card-back">
                        <h2>Red Soil</h2>
                        <p><strong>Description:</strong> Red soil is rich in iron oxide, giving it a distinct red color. It is well-drained and suitable for crops under irrigation.</p>
                        <p><strong>Found in:</strong> Chhattisgarh, Jharkhand, Odisha</p>
                        <p><strong>Crops:</strong> Millets, Pulses, Oilseeds</p>
                    </div>
                </div>
            </div>
            </div> <!-- End flip-card-container -->
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const iframe = document.getElementById("datawrapper-chart-4mzcJ");
            iframe.addEventListener("load", () => {
                console.log("Iframe has loaded successfully!");
            });
        });
    </script>
</body>
</html>