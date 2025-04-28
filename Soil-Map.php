<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soil Map of India - AgroBuddy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            padding-top: 70px;
            background-color: #f8f9fa;
        }

        .map-container {
            position: relative;
            width: 100%;
            height: 100vh;
            min-height: 1100px;
            margin-bottom: 30px;
        }

        .map-legend {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }

        .color-box {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border-radius: 3px;
        }

        .page-header {
            background: linear-gradient(135deg, #4CAF50, #2E7D32);
            color: white;
            padding: 30px 0;
            margin-bottom: 30px;
            border-radius: 0 0 10px 10px;
        }

        .soil-info-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .soil-info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }

        .soil-info-card .card-header {
            background: linear-gradient(to right, #4CAF50, #81C784);
            color: white;
            font-weight: bold;
            border: none;
        }

        .soil-info-card .card-body {
            padding: 20px;
        }

        /* Soil Map Popup Styles */
        #soil-info-box {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 300px;
            background-color: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: none;
            z-index: 1000;
        }

        .soil-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 20px;
        }

        .soil-legend-item {
            display: flex;
            align-items: center;
            margin-right: 10px;
            margin-bottom: 5px;
        }

        .soil-legend-color {
            width: 15px;
            height: 15px;
            margin-right: 5px;
            border-radius: 3px;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php include 'nav.php'; ?>

    <div class="container-fluid px-0">
        <section class="page-header">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-4 fw-bold"><i class="fas fa-map-marked-alt me-3"></i>Soil Map of India</h1>
                        <p class="lead">Explore the diverse soil types across different regions of India</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="Soil-Analysis.php" class="btn btn-light btn-lg"><i class="fas fa-seedling me-2"></i>Soil Analysis</a>
                    </div>
                </div>
            </div>
        </section>

        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card soil-info-card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-info-circle me-2"></i>About India's Soil Map</h3>
                        </div>
                        <div class="card-body">
                            <p>India's diverse geography results in a wide variety of soil types across the country. This interactive map shows the distribution of major soil types in different regions of India. Understanding soil types is crucial for agricultural planning, crop selection, and sustainable farming practices.</p>
                            <p>Use the interactive map below to explore soil types by region. Click on different areas to learn more about the specific soil characteristics and suitable crops for each region.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card soil-info-card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-map me-2"></i>Interactive Soil Map</h3>
                        </div>
                        <div class="card-body p-0 position-relative">
                            <div class="map-container">
                                <!-- Interactive Map -->
                                <div id="soil-info-box"></div>
                                <iframe title="Soil Types of India"
                                        aria-label="Map"
                                        id="datawrapper-chart-4mzcJ"
                                        src="https://datawrapper.dwcdn.net/4mzcJ/2/"
                                        scrolling="yes"
                                        frameborder="0"
                                        style="width: 100%; height: 100%; border: none;"
                                        data-external="1">
                                </iframe>
                            </div>
                        </div>
                        <div class="card-footer bg-white">
                            <p class="text-muted mb-0"><small><i class="fas fa-info-circle me-1"></i>Source: National Bureau of Soil Survey and Land Use Planning </small></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card soil-info-card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-list me-2"></i>Soil Types</h3>
                        </div>
                        <div class="card-body">
                            <h5>Major Soil Types in India</h5>
                            <div class="soil-legend d-flex flex-wrap justify-content-center">
                                <div class="soil-legend-item mx-3">
                                    <div class="soil-legend-color" style="background-color: #386CB0;"></div>
                                    <span>Alluvial soil</span>
                                </div>
                                <div class="soil-legend-item mx-3">
                                    <div class="soil-legend-color" style="background-color: #7FC97F;"></div>
                                    <span>Black soil</span>
                                </div>
                                <div class="soil-legend-item mx-3">
                                    <div class="soil-legend-color" style="background-color: #F002C7;"></div>
                                    <span>Red soil</span>
                                </div>
                                <div class="soil-legend-item mx-3">
                                    <div class="soil-legend-color" style="background-color: #FFFF99;"></div>
                                    <span>Laterite soil</span>
                                </div>
                                <div class="soil-legend-item mx-3">
                                    <div class="soil-legend-color" style="background-color: #9ABFF3;"></div>
                                    <span>Desert soil</span>
                                </div>
                                <div class="soil-legend-item mx-3">
                                    <div class="soil-legend-color" style="background-color: #C1008E;"></div>
                                    <span>Mountain soil</span>
                                </div>
                                <div class="soil-legend-item mx-3">
                                    <div class="soil-legend-color" style="background-color: #FDC086;"></div>
                                    <span>Forest soil</span>
                                </div>
                                <div class="soil-legend-item mx-3">
                                    <div class="soil-legend-color" style="background-color: #BEAED4;"></div>
                                    <span>Coastal soil</span>
                                </div>
                            </div>
                            <hr>
                            <p class="mb-0 text-center">Hover over any state in the map to view detailed information about its soil type, characteristics, and suitable crops.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card soil-info-card">
                        <div class="card-header">
                            <h3 class="mb-0"><i class="fas fa-seedling me-2"></i>Soil and Agriculture</h3>
                        </div>
                        <div class="card-body">
                            <p>The soil type in a region significantly influences agricultural practices and crop selection. Here's how different soil types affect agriculture:</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Alluvial Soil</h5>
                                    <p>Found in river basins and deltas, alluvial soil is highly fertile and rich in minerals. It's ideal for growing rice, wheat, sugarcane, and various vegetables.</p>

                                    <h5>Black Soil</h5>
                                    <p>Also known as regur or cotton soil, black soil has excellent water retention capacity. It's best suited for cotton, sugarcane, tobacco, and oilseeds.</p>

                                    <h5>Red Soil</h5>
                                    <p>Red soil is rich in iron but poor in nitrogen and phosphorus. With proper fertilization, it's suitable for growing millets, pulses, and fruits.</p>

                                    <h5>Laterite Soil</h5>
                                    <p>Found in high rainfall areas, laterite soil is acidic and less fertile. It's suitable for plantation crops like tea, coffee, rubber, and cashew.</p>
                                </div>

                                <div class="col-md-6">
                                    <h5>Desert Soil</h5>
                                    <p>Desert soil has low organic content and high salt concentration. With irrigation, it can support crops like bajra, pulses, and date palms.</p>

                                    <h5>Mountain Soil</h5>
                                    <p>Found in hilly regions, mountain soil is rich in humus but shallow in depth. It's suitable for growing fruits, potatoes, and tea.</p>

                                    <h5>Forest Soil</h5>
                                    <p>Rich in organic matter, forest soil is found in forested regions. It's good for growing coffee, spices, and tropical fruits.</p>

                                    <h5>Coastal Soil</h5>
                                    <p>Coastal soil varies in composition and is often saline. With proper management, it can support coconut, rice, and various vegetables.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Function to show soil information when hovering over a state in the iframe
        function showSoilInfo(state, soilType, description, products) {
            const infoBox = document.getElementById('soil-info-box');
            infoBox.innerHTML = `
                <h4>${state}</h4>
                <p><strong>Soil Type:</strong> ${soilType}</p>
                <p><strong>Description of Soil:</strong> ${description}</p>
                <p><strong>Products:</strong> ${products}</p>
            `;
            infoBox.style.display = 'block';
        }

        // Function to hide soil information
        function hideSoilInfo() {
            const infoBox = document.getElementById('soil-info-box');
            infoBox.style.display = 'none';
        }

        // Add event listener to the iframe to capture events from the embedded map
        document.addEventListener('DOMContentLoaded', function() {
            const iframe = document.getElementById('datawrapper-chart-4mzcJ');

            // Listen for messages from the iframe
            window.addEventListener('message', function(event) {
                // Check if the message is from datawrapper
                if (event.origin.includes('datawrapper')) {
                    try {
                        const data = JSON.parse(event.data);
                        if (data.type === 'hover' && data.data) {
                            // Show soil info based on the hovered state
                            const state = data.data.name;
                            const soilType = data.data.soil || 'Various soil types';
                            const description = 'Soil characteristics vary by region.';
                            const products = 'Various crops suitable for the region.';

                            showSoilInfo(state, soilType, description, products);
                        } else if (data.type === 'unhover') {
                            hideSoilInfo();
                        }
                    } catch (e) {
                        console.error('Error parsing message from iframe:', e);
                    }
                }
            });
        });
    </script>
</body>
</html>
