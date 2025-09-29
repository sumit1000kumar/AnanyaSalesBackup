<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header("Location: admin/dashboard.php");
        exit;
    } elseif ($_SESSION['user_role'] === 'user') {
        header("Location: user/user-dashboard.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required Meta Tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Professional Service & Calibration for Blood Bank Equipment">
  <meta name="keywords" content="blood bank equipment, plasma freezers, centrifuge calibration, AMC, CMC, blood storage">
  <meta name="author" content="Ananya Sales & Service">

  <!-- Page Title -->
  <title>Ananya Sales & Service | Blood Bank Equipment Specialists</title>

  <!-- Favicon -->
  <link rel="icon" href="assets/images/favicon/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="assets/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="/manifest.json">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #e30613;          /* Main red */
      --secondary-color: #a9030d;        /* Darker red */
      --accent-color: #ff5964;           /* For highlights */
      --success-color: #198754;          /* Bootstrap green */
      --warning-color: #f59e0b;          /* Amber */
      --info-color: #0ea5e9;             /* Light blue */
      --danger-color: #ef4444;           /* Bright red */
      --light-bg: #f8f9fa;               /* Light background */
      --dark-text: #212529;
      --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
      --gradient-primary: linear-gradient(135deg, var(--primary-color), var(--accent-color));
      --gradient-secondary: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
    }
    
    body {
      font-family: 'Open Sans', sans-serif;
      color: var(--dark-text);
      scroll-behavior: smooth;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
    }


    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, rgba(227, 6, 19, 0.95), rgba(169, 3, 13, 0.95)), 
                  url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: white;
      padding: 8rem 0 6rem;
      position: relative;
      overflow: hidden;
    }

    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent 30%, rgba(255, 255, 255, 0.1) 50%, transparent 70%);
      animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
      0% { transform: translateX(-100%); }
      100% { transform: translateX(100%); }
    }

    .hero-content {
      position: relative;
      z-index: 2;
    }

    .hero-logo {
      height: 100px;
      width: auto;
      margin-bottom: 20px;
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }

    /* Section Styling */
    section {
      padding: 5rem 0;
    }

    .section-title {
      position: relative;
      margin-bottom: 3rem;
    }

    .section-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--gradient-primary);
      border-radius: 2px;
    }

    /* About Section */
    .about-img {
      border-radius: 15px;
      box-shadow: var(--card-shadow);
      transition: transform 0.3s;
    }

    .about-img:hover {
      transform: scale(1.02);
    }

    /* Services Section */
    .service-card {
      border: none;
      border-radius: 15px;
      box-shadow: var(--card-shadow);
      transition: all 0.3s ease;
      height: 100%;
      background: white;
      margin-bottom: 2rem;
      position: relative;
      overflow: hidden;
    }

    .service-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: var(--gradient-primary);
    }
    
    .service-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(227, 6, 19, 0.15);
    }

    .service-icon {
      font-size: 3rem;
      color: var(--primary-color);
      margin-bottom: 1rem;
      transition: all 0.3s ease;
    }

    .service-card:hover .service-icon {
      transform: scale(1.2);
    }

    /* Products Section */
    .product-card {
      border: none;
      border-radius: 15px;
      box-shadow: var(--card-shadow);
      transition: all 0.3s ease;
      height: 100%;
      margin-bottom: 2rem;
      overflow: hidden;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .product-img {
      height: 200px;
      object-fit: cover;
      transition: transform 0.5s;
    }

    .product-card:hover .product-img {
      transform: scale(1.05);
    }

    /* Testimonials Section */
    .testimonial-card {
      border: none;
      border-radius: 15px;
      box-shadow: var(--card-shadow);
      padding: 2rem;
      transition: all 0.3s ease;
      height: 100%;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .client-img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid var(--primary-color);
    }

    .star-rating i {
      color: var(--warning-color);
    }

    /* Buttons */
    .btn-primary {
      background: var(--gradient-primary);
      border: none;
      padding: 0.75rem 2rem;
      font-weight: 600;
      border-radius: 50px;
      box-shadow: 0 4px 15px rgba(227, 6, 19, 0.3);
      transition: all 0.3s ease;
    }

    .btn-primary:hover {
      background: var(--gradient-secondary);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(227, 6, 19, 0.4);
    }

    .btn-outline-primary {
      color: var(--primary-color);
      border: 2px solid var(--primary-color);
      border-radius: 50px;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
      background: var(--gradient-primary);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(227, 6, 19, 0.3);
    }

    /* Footer */
    .main-footer {
      background: linear-gradient(135deg, #1a1a1a 0%, var(--dark-text) 100%);
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .main-footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 6px;
      background: var(--gradient-primary);
    }

    .footer-column h5 {
      position: relative;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .footer-column h5::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 2px;
      background: var(--primary-color);
    }

    .footer-link {
      color: rgba(255, 255, 255, 0.7);
      text-decoration: none;
      transition: all 0.3s;
      display: block;
      margin-bottom: 8px;
    }

    .footer-link:hover {
      color: white;
      transform: translateX(5px);
    }

    .social-btn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: rgba(255, 255, 255, 0.1);
      color: white;
      transition: all 0.3s;
      margin-right: 10px;
    }

    .social-btn:hover {
      background: var(--primary-color);
      transform: translateY(-3px);
    }

    /* Animations */
    .scroll-animate {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }

    .scroll-animate.animate {
      opacity: 1;
      transform: translateY(0);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .hero-section {
        padding: 6rem 0 4rem;
        text-align: center;
      }
      
      section {
        padding: 3rem 0;
      }
    }
  </style>
</head>
<body>
  <?php include 'includes/navbar.php'; ?>
  <!-- Hero Section -->
  <section id="home" class="hero-section">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8 mx-auto text-center hero-content">
          <img src="assets/images/logo/logo-noBg.png" alt="Ananya Sales & Service" class="hero-logo">
          <h1 class="display-4 fw-bold mb-4">Ananya Sales & Service</h1>
          <p class="lead mb-5">Specialized Maintenance & Calibration for Blood Bank Equipment</p>
          <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="#services" class="btn btn-primary btn-lg">
              <i class="bi bi-tools me-2"></i>Our Services
            </a>
            <a href="tel:+919876543210" class="btn btn-outline-light btn-lg">
              <i class="bi bi-telephone me-2"></i>Emergency Service
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="bg-light scroll-animate">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-5 mb-lg-0">
          <h2 class="section-title text-center text-lg-start">About Us</h2>
          <p class="lead">Ananya Sales & Service is a premier provider of specialized maintenance and calibration services for blood bank equipment across India.</p>
          <p>With over 15 years of experience in the healthcare equipment industry, we have established ourselves as trusted partners for blood banks, hospitals, and medical institutions.</p>
          <p>Our team of certified technicians undergoes regular training to stay updated with the latest technologies and regulatory requirements, ensuring that your critical blood bank equipment operates at peak performance.</p>
          <div class="mt-4">
            <a href="#contact" class="btn btn-primary me-3">Contact Us</a>
            <a href="#services" class="btn btn-outline-primary">Our Services</a>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="row g-3">
            <div class="col-6">
              <img src="https://images.unsplash.com/photo-1581093450021-4a7360e9a7b1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                   alt="Blood bank equipment" class="img-fluid about-img">
            </div>
            <div class="col-6">
              <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                   alt="Technician at work" class="img-fluid about-img">
            </div>
            <div class="col-6 mt-4">
              <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                   alt="Medical equipment" class="img-fluid about-img">
            </div>
            <div class="col-6 mt-4">
              <img src="https://images.unsplash.com/photo-1576671414122-03789e8f8a3a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                   alt="Laboratory" class="img-fluid about-img">
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section id="services" class="scroll-animate">
    <div class="container">
      <h2 class="section-title text-center">Our Services</h2>
      <p class="lead text-center mb-5">Comprehensive maintenance and calibration solutions for all your blood bank equipment</p>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="card service-card text-center p-4">
            <div class="service-icon">
              <i class="bi bi-snow"></i>
            </div>
            <h4 class="mb-3">Plasma Freezers</h4>
            <p>Precision calibration and maintenance for optimal temperature control and reliability.</p>
            <a href="#" class="btn btn-outline-primary btn-sm mt-3">Learn More</a>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
          <div class="card service-card text-center p-4">
            <div class="service-icon">
              <i class="bi bi-droplet"></i>
            </div>
            <h4 class="mb-3">Blood Storage</h4>
            <p>Comprehensive servicing of blood storage refrigerators and temperature monitoring systems.</p>
            <a href="#" class="btn btn-outline-primary btn-sm mt-3">Learn More</a>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
          <div class="card service-card text-center p-4">
            <div class="service-icon">
              <i class="bi bi-arrow-repeat"></i>
            </div>
            <h4 class="mb-3">Centrifuge Calibration</h4>
            <p>Expert calibration and repair services for blood separation and component equipment.</p>
            <a href="#" class="btn btn-outline-primary btn-sm mt-3">Learn More</a>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-3">
          <div class="card service-card text-center p-4">
            <div class="service-icon">
              <i class="bi bi-thermometer-snow"></i>
            </div>
            <h4 class="mb-3">Walking Chambers</h4>
            <p>Maintenance and temperature validation for blood bank walk-in chambers and cold rooms.</p>
            <a href="#" class="btn btn-outline-primary btn-sm mt-3">Learn More</a>
          </div>
        </div>
      </div>
      
      <div class="text-center mt-5">
        <div class="row justify-content-center">
          <div class="col-lg-8">
            <div class="card bg-primary text-white p-4">
              <div class="card-body">
                <h3 class="card-title mb-3">Service Contracts</h3>
                <p class="card-text mb-4">Ensure uninterrupted operation with our comprehensive maintenance contracts including AMC and CMC options with priority service and discounted rates.</p>
                <a href="tel:+919876543210" class="btn btn-light">Request a Quote</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Products Section -->
  <section id="products" class="bg-light scroll-animate">
    <div class="container">
      <h2 class="section-title text-center">Our Products</h2>
      <p class="lead text-center mb-5">High-quality blood bank equipment and supplies from trusted manufacturers</p>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="card product-card">
            <img src="https://images.unsplash.com/photo-1582719471384-894fbb16e074?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                 class="card-img-top product-img" alt="Blood Bank Refrigerator">
            <div class="card-body">
              <h5 class="card-title">Blood Bank Refrigerators</h5>
              <p class="card-text">Temperature-controlled refrigerators specifically designed for safe blood storage with digital monitoring.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-primary fw-bold">From ₹85,000</span>
                <a href="#" class="btn btn-primary btn-sm">Inquire</a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card product-card">
            <img src="https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                 class="card-img-top product-img" alt="Plasma Freezer">
            <div class="card-body">
              <h5 class="card-title">Plasma Freezers</h5>
              <p class="card-text">Ultra-low temperature freezers for plasma storage with precise temperature control and alarm systems.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-primary fw-bold">From ₹1,20,000</span>
                <a href="#" class="btn btn-primary btn-sm">Inquire</a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card product-card">
            <img src="https://images.unsplash.com/photo-1582719471384-894fbb16e074?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                 class="card-img-top product-img" alt="Laboratory Centrifuge">
            <div class="card-body">
              <h5 class="card-title">Laboratory Centrifuges</h5>
              <p class="card-text">High-speed centrifuges for blood component separation with programmable settings and safety features.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-primary fw-bold">From ₹65,000</span>
                <a href="#" class="btn btn-primary btn-sm">Inquire</a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card product-card">
            <img src="https://images.unsplash.com/photo-1581093450021-4a7360e9a7b1?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                 class="card-img-top product-img" alt="Blood Bank Monitors">
            <div class="card-body">
              <h5 class="card-title">Temperature Monitors</h5>
              <p class="card-text">Digital temperature monitoring systems with remote alerts and data logging capabilities.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-primary fw-bold">From ₹15,000</span>
                <a href="#" class="btn btn-primary btn-sm">Inquire</a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card product-card">
            <img src="https://images.unsplash.com/photo-1579684385127-1ef15d508118?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                 class="card-img-top product-img" alt="Blood Bank Analyzers">
            <div class="card-body">
              <h5 class="card-title">Blood Bank Analyzers</h5>
              <p class="card-text">Automated analyzers for blood grouping and cross-matching with high accuracy and efficiency.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-primary fw-bold">From ₹2,50,000</span>
                <a href="#" class="btn btn-primary btn-sm">Inquire</a>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="card product-card">
            <img src="https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=80" 
                 class="card-img-top product-img" alt="Blood Storage Cabinets">
            <div class="card-body">
              <h5 class="card-title">Blood Storage Cabinets</h5>
              <p class="card-text">Specialized cabinets for organized blood product storage with inventory management features.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-primary fw-bold">From ₹45,000</span>
                <a href="#" class="btn btn-primary btn-sm">Inquire</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section id="testimonials" class="scroll-animate">
    <div class="container">
      <h2 class="section-title text-center">Client Testimonials</h2>
      <p class="lead text-center mb-5">What our valued clients say about our services</p>
      
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="testimonial-card">
            <div class="star-rating mb-3">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
            <p class="mb-4">"Ananya's technicians resolved our plasma freezer issue within 2 hours of calling. Their expertise is unmatched and their emergency service is truly reliable."</p>
            <div class="d-flex align-items-center">
              <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Dr. Sharma" class="client-img me-3">
              <div>
                <h6 class="mb-0">Dr. Sharma</h6>
                <small class="text-muted">Delhi Blood Bank</small>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="testimonial-card">
            <div class="star-rating mb-3">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
            </div>
            <p class="mb-4">"Their AMC program has saved us thousands in unexpected repair costs. The preventive maintenance approach ensures our equipment is always in optimal condition."</p>
            <div class="d-flex align-items-center">
              <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Ms. Patel" class="client-img me-3">
              <div>
                <h6 class="mb-0">Ms. Patel</h6>
                <small class="text-muted">Mumbai Medical Center</small>
              </div>
            </div>
          </div>
        </div>
        
        <div class="col-md-6 col-lg-4">
          <div class="testimonial-card">
            <div class="star-rating mb-3">
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-fill"></i>
              <i class="bi bi-star-half"></i>
            </div>
            <p class="mb-4">"The calibration certificates provided meet all NABH requirements. Their documentation is thorough and has made our regulatory compliance much easier."</p>
            <div class="d-flex align-items-center">
              <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Mr. Kumar" class="client-img me-3">
              <div>
                <h6 class="mb-0">Mr. Kumar</h6>
                <small class="text-muted">Bangalore Hospital</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Client Portal Section -->
  <section class="bg-primary text-white">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-8 text-center text-lg-start">
          <h3 class="mb-3">Client Portal Access</h3>
          <p class="mb-0">Manage your service contracts, view service history, and request support through our client portal.</p>
        </div>
        <div class="col-lg-4 text-center text-lg-end mt-4 mt-lg-0">
          <a href="auth/login.php" class="btn btn-light me-3">Client Login</a>
          <a href="auth/register.php" class="btn btn-outline-light">Register</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="main-footer pt-5">
    <div class="container">
      <div class="row g-4">
        <!-- Company Info -->
        <div class="col-lg-4 col-md-6 footer-column">
          <h5>Ananya Sales & Service</h5>
          <p class="mt-3">Specialists in blood bank equipment maintenance, calibration, and service contracts with over 15 years of trusted service.</p>
          <div class="d-flex mt-4">
            <a href="#" class="social-btn"><i class="bi bi-facebook"></i></a>
            <a href="#" class="social-btn"><i class="bi bi-twitter"></i></a>
            <a href="#" class="social-btn"><i class="bi bi-linkedin"></i></a>
            <a href="#" class="social-btn"><i class="bi bi-instagram"></i></a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-6 footer-column">
          <h5>Quick Links</h5>
          <a href="#home" class="footer-link">Home</a>
          <a href="#about" class="footer-link">About Us</a>
          <a href="#services" class="footer-link">Services</a>
          <a href="#products" class="footer-link">Products</a>
          <a href="#testimonials" class="footer-link">Testimonials</a>
        </div>

        <!-- Services -->
        <div class="col-lg-3 col-md-6 footer-column">
          <h5>Our Services</h5>
          <a href="#" class="footer-link">Plasma Freezers</a>
          <a href="#" class="footer-link">Blood Storage</a>
          <a href="#" class="footer-link">Centrifuge Calibration</a>
          <a href="#" class="footer-link">AMC Contracts</a>
          <a href="#" class="footer-link">Emergency Repairs</a>
        </div>

        <!-- Contact Info -->
        <div class="col-lg-3 col-md-6 footer-column">
          <h5>Contact Us</h5>
          <div class="d-flex mb-3">
            <i class="bi bi-geo-alt-fill text-primary me-3 mt-1"></i>
            <span>123 Medical Equipment Plaza, New Delhi 110001, India</span>
          </div>
          <div class="d-flex mb-3">
            <i class="bi bi-telephone-fill text-primary me-3 mt-1"></i>
            <div>
              <div>+91 98765 43210</div>
              <div>+91 11 2345 6789</div>
            </div>
          </div>
          <div class="d-flex mb-3">
            <i class="bi bi-envelope-fill text-primary me-3 mt-1"></i>
            <span>service@ananyasales.com</span>
          </div>
          <div class="d-flex">
            <i class="bi bi-clock-fill text-primary me-3 mt-1"></i>
            <div>
              <div>Monday-Saturday: 8AM-8PM</div>
              <div>Emergency: 24/7 Support</div>
            </div>
          </div>
        </div>
      </div>

      <hr class="my-4 border-secondary">

      <div class="row align-items-center py-3">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
          <p class="mb-0">&copy; <?php echo date('Y'); ?> Ananya Sales & Service. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <div class="d-flex justify-content-md-end justify-content-center">
            <a href="https://www.meetsumit.xyz" class="footer-link">Developed by Sumit Kumar</a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          window.scrollTo({
            top: target.offsetTop - 80,
            behavior: 'smooth'
          });
          
          // Update active nav link
          document.querySelectorAll('.nav-link').forEach(link => {
            link.classList.remove('active');
          });
          this.classList.add('active');
        }
      });
    });

    // Scroll animation
    function isInViewport(element) {
      const rect = element.getBoundingClientRect();
      return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
      );
    }

    function handleScrollAnimation() {
      const elements = document.querySelectorAll('.scroll-animate');
      elements.forEach(element => {
        if (isInViewport(element) || element.getBoundingClientRect().top < window.innerHeight * 0.8) {
          element.classList.add('animate');
        }
      });
    }

    // Initial check for elements in viewport
    document.addEventListener('DOMContentLoaded', function() {
      handleScrollAnimation();
      
      // Add scroll event listener with throttling
      let ticking = false;
      window.addEventListener('scroll', function() {
        if (!ticking) {
          requestAnimationFrame(function() {
            handleScrollAnimation();
            ticking = false;
          });
          ticking = true;
        }
      });
    });

    // Update active nav link based on scroll position
    window.addEventListener('scroll', function() {
      const sections = document.querySelectorAll('section');
      const navLinks = document.querySelectorAll('.nav-link');
      
      let current = '';
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (scrollY >= (sectionTop - 100)) {
          current = section.getAttribute('id');
        }
      });
      
      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${current}`) {
          link.classList.add('active');
        }
      });
    });
  </script>
</body>
</html>