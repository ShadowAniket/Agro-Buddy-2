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
    <title>About - AgroBuddy</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding-top: 60px;
        }
        
        .about-header {
            background-color: #4CAF50;
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
            text-align: center;
        }
        
        .about-header h1 {
            font-weight: 700;
            margin-bottom: 20px;
        }
        
        .about-header p {
            font-size: 1.2rem;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .about-section {
            padding: 40px 0;
        }
        
        .about-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .about-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .about-card h3 {
            color: #4CAF50;
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 15px;
        }
        
        .about-card h3:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: #4CAF50;
        }
        
        .about-card p {
            color: #555;
            line-height: 1.7;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        
        .team-section {
            background-color: #f0f7f0;
            padding: 60px 0;
            margin-top: 40px;
        }
        
        .team-section h2 {
            text-align: center;
            margin-bottom: 40px;
            color: #333;
        }
        
        .about-card h3.text-center:after {
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>

    <div class="about-header">
        <div class="container">
            <h1>About AgroBuddy</h1>
            <p>Connecting farmers and consumers for a sustainable future</p>
        </div>
    </div>

    <div class="container">
        <div class="about-section">
            <div class="row">
                <div class="col-md-4">
                    <div class="about-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h3 class="text-center">Our Vision</h3>
                        <p>To create a fair and transparent marketplace where consumers enjoy fresh, healthy food, and farmers receive proper prices without middlemen.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="about-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-bullseye"></i>
                        </div>
                        <h3 class="text-center">Our Mission</h3>
                        <p>To empower farmers and deliver fresh, affordable food directly to consumers, ensuring fair trade and sustainability in the process.</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="about-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-handshake"></i>
                        </div>
                        <h3 class="text-center">Our Values</h3>
                        <p>Transparency, sustainability, fairness, and community support are at the core of everything we do at AgroBuddy.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="about-section">
            <div class="row">
                <div class="col-12">
                    <div class="about-card">
                        <h3>About AgroBuddy</h3>
                        <p>AgroBuddy is a B2C (Business-to-Consumer) platform designed to connect consumers directly with farmers for buying and selling fresh, healthy food. Our goal is to create a fair and simple marketplace where buyers can enjoy high-quality produce, while farmers receive the full value for their products—free from the involvement of intermediaries.</p>
                        <p>Our website makes it easy for farmers to list their fresh products with transparent pricing, while consumers can effortlessly browse and purchase from a variety of farm-fresh options. By bridging the gap between farms and homes, AgroBuddy ensures fairness in the food market and supports local agriculture.</p>
                        <p>With a clear vision to provide a transparent marketplace and a mission to empower farmers and deliver fresh food to consumers, AgroBuddy is committed to sustainable and fair trade in every transaction.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="about-section">
            <div class="row">
                <div class="col-md-6">
                    <div class="about-card h-100">
                        <h3>For Farmers</h3>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success me-2"></i> Direct access to consumers</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Fair pricing for your produce</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Simple platform to list and sell products</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Secure payment processing</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Weather and soil analysis tools</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Support for sustainable farming practices</li>
                        </ul>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="about-card h-100">
                        <h3>For Consumers</h3>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check-circle text-success me-2"></i> Access to fresh, locally grown produce</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Transparent pricing and product information</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Support local farmers and sustainable agriculture</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Convenient online shopping experience</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Secure payment options</li>
                            <li><i class="fas fa-check-circle text-success me-2"></i> Reliable delivery to your doorstep</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
