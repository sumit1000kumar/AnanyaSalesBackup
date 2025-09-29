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
  <!-- <link rel="manifest" href="assets/images/favicon/site.webmanifest"> -->
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
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      font-family: 'Open Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: var(--dark-text);
      position: relative;
    }

    /* Animated background elements */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: 
        radial-gradient(circle at 20% 80%, rgba(227, 6, 19, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255, 89, 100, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(169, 3, 13, 0.03) 0%, transparent 50%);
      pointer-events: none;
      z-index: -1;
    }
    
    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, rgba(227, 6, 19, 0.95), rgba(169, 3, 13, 0.95)), 
                  url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      color: white;
      padding: 4rem 0;
      margin-bottom: 3rem;
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
      height: 80px;
      width: auto;
      margin-right: 20px;
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.3));
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }
    
    @media (min-width: 768px) {
      .hero-section {
        padding: 6rem 0;
        margin-bottom: 4rem;
      }
    }
    
    /* Service Cards */
    .service-card {
      border: none;
      border-radius: 20px;
      box-shadow: var(--card-shadow);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      height: 100%;
      background: linear-gradient(145deg, #ffffff, #f0f0f0);
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
      transform: translateY(-15px) scale(1.02);
      box-shadow: 0 20px 40px rgba(227, 6, 19, 0.2);
    }

    .service-card:hover .service-icon {
      transform: scale(1.2) rotate(10deg);
      background: var(--gradient-primary);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    
    .service-icon {
      font-size: 3rem;
      color: var(--primary-color);
      margin-bottom: 1rem;
      transition: all 0.3s ease;
      display: inline-block;
    }
    
    @media (min-width: 768px) {
      .service-icon {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
      }
    }
    
    /* Buttons */
    .btn-primary {
      background: var(--gradient-primary);
      border: none;
      padding: 0.75rem 2rem;
      font-weight: 600;
      font-size: 1rem;
      border-radius: 50px;
      box-shadow: 0 4px 15px rgba(227, 6, 19, 0.3);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-primary::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s;
    }

    .btn-primary:hover::before {
      left: 100%;
    }
    
    @media (min-width: 768px) {
      .btn-primary {
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
      }
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

    .btn-outline-light {
      border: 2px solid rgba(255, 255, 255, 0.8);
      border-radius: 50px;
      padding: 0.75rem 2rem;
      font-weight: 600;
      transition: all 0.3s ease;
      backdrop-filter: blur(10px);
    }

    .btn-outline-light:hover {
      background: rgba(255, 255, 255, 0.2);
      border-color: white;
      transform: translateY(-2px);
    }
    
    /* Typography */
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Montserrat', sans-serif;
      font-weight: 700;
      color: var(--dark-text);
    }
    
    h1 {
      font-size: 2rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }
    
    h2 {
      font-size: 2.5rem;
      margin-bottom: 1.5rem;
      position: relative;
      display: inline-block;
    }

    h2::after {
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
    
    @media (min-width: 768px) {
      h1 {
        font-size: 3rem;
      }
      h2 {
        font-size: 3rem;
        margin-bottom: 2rem;
      }
    }
    
    .lead {
      font-size: 1.1rem;
      color: var(--dark-text);
      opacity: 0.8;
    }
    
    @media (min-width: 768px) {
      .lead {
        font-size: 1.3rem;
      }
    }
    
    /* Sections */
    section {
      padding: 3rem 0;
      position: relative;
    }
    
    @media (min-width: 768px) {
      section {
        padding: 4rem 0;
      }
    }

    /* Enhanced section backgrounds */
    .bg-light {
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
      position: relative;
    }

    .bg-light::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: 
        radial-gradient(circle at 10% 20%, rgba(227, 6, 19, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 90% 80%, rgba(255, 89, 100, 0.05) 0%, transparent 50%);
      pointer-events: none;
    }
    
    /* Feature Box */
    .feature-box {
      background: linear-gradient(145deg, #ffffff, #f8f9fa);
      border-radius: 20px;
      padding: 2.5rem 2rem;
      box-shadow: var(--card-shadow);
      margin-bottom: 2rem;
      text-align: center;
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .feature-box::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, transparent 30%, rgba(227, 6, 19, 0.05) 50%, transparent 70%);
      transform: rotate(45deg);
      transition: all 0.3s ease;
      opacity: 0;
    }

    .feature-box:hover::before {
      opacity: 1;
      animation: rotate 2s linear infinite;
    }

    @keyframes rotate {
      from { transform: rotate(45deg); }
      to { transform: rotate(405deg); }
    }

    .feature-box:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(227, 6, 19, 0.15);
    }
    
    /* Client Portal */
    .client-portal {
      background: var(--gradient-primary);
      color: white;
      padding: 4rem 0;
      position: relative;
      overflow: hidden;
    }

    .client-portal::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
    
    /* Footer */
    .main-footer {
      background: linear-gradient(135deg, #1a1a1a 0%, var(--dark-text) 100%);
      color: white;
      position: relative;
      overflow: hidden;
      padding: 3rem 0 2rem 0;
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

    .main-footer::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.02'%3E%3Cpath d='M20 20c0-11.046-8.954-20-20-20v20h20z'/%3E%3C/g%3E%3C/svg%3E");
      pointer-events: none;
    }
    
    .text-white-50 {
      color: rgba(255, 255, 255, 0.7) !important;
    }
    
    .hover-text-white:hover {
      color: white !important;
      transform: translateX(5px);
      transition: all 0.3s ease;
    }
    
    /* Responsive adjustments */
    .responsive-img {
      width: 100%;
      height: auto;
      margin-top: 2rem;
      border-radius: 20px;
      box-shadow: var(--card-shadow);
      transition: all 0.3s ease;
    }

    .responsive-img:hover {
      transform: scale(1.05);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }
    
    @media (min-width: 768px) {
      .responsive-img {
        margin-top: 0;
      }
    }
    
    /* Button groups */
    .btn-group-responsive {
      flex-direction: column;
      gap: 1rem;
    }
    
    @media (min-width: 576px) {
      .btn-group-responsive {
        flex-direction: row;
        gap: 1.5rem;
      }
    }
    
    /* Testimonial cards */
    .testimonial-card {
      margin-bottom: 2rem;
      transition: all 0.3s ease;
    }

    .testimonial-card:hover {
      transform: translateY(-5px);
    }

    .star-rating {
      margin-bottom: 1rem;
    }

    .star-rating i {
      transition: all 0.3s ease;
    }

    .testimonial-card:hover .star-rating i {
      transform: scale(1.2);
    }
    
    /* Footer columns */
    .footer-column {
      margin-bottom: 3rem;
    }
    
    @media (min-width: 768px) {
      .footer-column {
        margin-bottom: 0;
      }
    }

    /* Install App Button */
    #installApp {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 1000;
      padding: 12px 24px;
      background: var(--gradient-primary);
      color: white;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      font-size: 16px;
      font-weight: 600;
      box-shadow: 0 8px 25px rgba(227, 6, 19, 0.3);
      transition: all 0.3s ease;
    }

    #installApp:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 35px rgba(227, 6, 19, 0.4);
    }

    /* Loading animation for cards */
    .card-loading {
      animation: cardLoad 0.6s ease-out forwards;
    }

    @keyframes cardLoad {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Scroll animations */
    .scroll-animate {
      opacity: 0;
      transform: translateY(30px);
      transition: all 0.6s ease;
    }

    .scroll-animate.animate {
      opacity: 1;
      transform: translateY(0);
    }

    /* Enhanced social buttons */
    .social-btn {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .social-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: var(--gradient-primary);
      border-radius: 50%;
      transform: scale(0);
      transition: transform 0.3s ease;
    }

    .social-btn:hover::before {
      transform: scale(1);
    }

    .social-btn i {
      position: relative;
      z-index: 1;
    }
  </style>
  
  <!-- web push notifications api - pushalert.co -->
<!-- <script type="text/javascript">
        (function(d, t) {
                var g = d.createElement(t),
                s = d.getElementsByTagName(t)[0];
                g.src = "https://cdn.pushalert.co/integrate_004ca555b3f92956ab9cf37d5144df74.js";
                s.parentNode.insertBefore(g, s);
        }(document, "script"));
</script> -->
</head>
<body>
  <button id="installApp" style="display:none;">Install App</button>

  <!-- Hero Section -->
  <section class="hero-section text-center">
    <div class="container">
      <div class="hero-content">
        <h1 class="fw-bold mb-4 text-white"> 
          <img src="assets/images/logo/logo-noBg.png" alt="Ananya Sales & Service Logo" class="hero-logo" >
          Ananya Sales & Service
        </h1>
        <p class="lead mb-5 text-white">Specialized Maintenance & Calibration for Blood Bank Equipment</p>
        <div class="d-flex btn-group-responsive justify-content-center">
          <a href="auth/login.php" class="btn btn-primary btn-lg">
            <i class="bi bi-box-arrow-in-right me-2"></i>Portal Login
          </a>
          <a href="tel:+919876543210" class="btn btn-outline-light btn-lg">
            <i class="bi bi-telephone me-2"></i>Emergency Service
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="container scroll-animate">
    <div class="text-center mb-5">
      <h2>Our Specialized Services</h2>
      <p class="lead">Precision calibration and maintenance for critical blood bank equipment</p>
    </div>
    
    <div class="row g-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card text-center p-4 card-loading">
          <div class="service-icon">
            <i class="bi bi-snow"></i>
          </div>
          <h4 class="text-primary mb-3">Plasma Freezers</h4>
          <p class="mb-3">Precision calibration and maintenance for optimal temperature control</p>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card text-center p-4 card-loading">
          <div class="service-icon">
            <i class="bi bi-droplet"></i>
          </div>
          <h4 class="text-primary mb-3">Blood Storage</h4>
          <p class="mb-3">Comprehensive servicing of blood storage refrigerators and systems</p>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card text-center p-4 card-loading">
          <div class="service-icon">
            <i class="bi bi-arrow-repeat"></i>
          </div>
          <h4 class="text-primary mb-3">Component Centrifuge</h4>
          <p class="mb-3">Expert calibration and repair services for blood separation equipment</p>
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card text-center p-4 card-loading">
          <div class="service-icon">
            <i class="bi bi-thermometer-snow"></i>
          </div>
          <h4 class="text-primary mb-3">Walking Chambers</h4>
          <p class="mb-3">Maintenance and temperature validation for blood bank walk-ins</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Service Contracts Section -->
  <section class="bg-light">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <h2 class="mb-3">Service Contracts</h2>
          <p class="lead mb-4">Ensure uninterrupted operation with our comprehensive maintenance contracts</p>
          <ul class="list-unstyled mb-4">
            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Annual Maintenance Contracts (AMC)</li>
            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Comprehensive Maintenance Contracts (CMC)</li>
            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Emergency breakdown support</li>
            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Preventive maintenance programs</li>
            <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Regulatory compliance documentation</li>
          </ul>
          <div class="alert alert-info">
            <i class="bi bi-info-circle-fill me-2"></i> All contracts include priority service and discounted rates.
          </div>
          <a href="tel:+919876543210" class="btn btn-primary mt-3">
            <i class="bi bi-telephone me-2"></i>Request a Quote
          </a>
        </div>
        <div class="col-lg-6">
          <img src="https://images.unsplash.com/photo-1581093450021-4a7360e9a7b1?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
               alt="Blood bank equipment maintenance" 
               class="img-fluid rounded shadow responsive-img">
        </div>
      </div>
    </div>
  </section>

  <!-- Why Choose Us Section -->
  <section class="container">
    <div class="text-center mb-4">
      <h2>Why Choose Ananya Sales & Service?</h2>
      <p class="lead">Trusted by blood banks across the region</p>
    </div>
    
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-box">
          <i class="bi bi-award-fill display-5  mb-3" style="color: var(--primary-color);"></i>
          <h4   style="color: var(--primary-color);">Certified Technicians</h4>
          <p>Our team holds manufacturer certifications and specialized training in blood bank equipment</p>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="feature-box">
          <i class="bi bi-speedometer2 display-5  mb-3" style="color: var(--primary-color);"></i>
          <h4   style="color: var(--primary-color);">Rapid Response</h4>
          <p>24/7 emergency service to minimize equipment downtime in critical situations</p>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="feature-box">
          <i class="bi bi-file-earmark-medical-fill display-5  mb-3" style="color: var(--primary-color);"></i>
          <h4   style="color: var(--primary-color);">Compliance Ready</h4>
          <p>Detailed service reports meeting all regulatory requirements for blood bank equipment</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Instrument Calibration Services Section -->
  <section class="container">
    <div class="text-center mb-4">
      <h2>Instrument Calibration Services</h2>
      <p class="lead">Precision calibration for accurate and reliable equipment performance</p>
    </div>
    
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card service-card h-100">
          <div class="card-body p-3">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                <i class="bi bi-tools display-5" style="color: var(--primary-color);"></i>
              </div>
              <h4 class="mb-0"  style="color: var(--primary-color);">AMC Calibration Service</h4>
            </div>
            <p>Our Annual Maintenance Contract includes comprehensive calibration services to ensure your blood bank instruments maintain optimal accuracy throughout the year.</p>
            <ul class="list-unstyled mb-3">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Regular scheduled calibrations</li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> NABL accredited procedures</li>
              <li class="bi bi-check-circle-fill text-success me-2"></i> Traceable certification</li>
            </ul>
            <a href="#" class="btn btn-outline-primary">Learn More</a>
          </div>
        </div>
      </div>
      
      <div class="col-md-6">
        <div class="card service-card h-100">
          <div class="card-body p-3">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-primary bg-opacity-10 p-2 rounded me-3">
                <i class="bi bi-clipboard2-pulse display-5" style="color: var(--primary-color);"></i>
              </div>
              <h4 class="mb-0"  style="color: var(--primary-color);">Comprehensive Calibration</h4>
            </div>
            <p>We calibrate all critical blood bank equipment including:</p>
            <div class="row">
              <div class="col-6">
                <ul class="list-unstyled">
                  <li class="mb-2"><i class="bi bi-check text-accent me-2"></i> Temperature monitors</li>
                  <li class="mb-2"><i class="bi bi-check text-accent me-2"></i> Centrifuges</li>
                  <li class="mb-2"><i class="bi bi-check text-accent me-2"></i> Blood agitators</li>
                </ul>
              </div>
              <div class="col-6">
                <ul class="list-unstyled">
                  <li class="mb-2"><i class="bi bi-check text-accent me-2"></i> Platelet incubators</li>
                  <li class="mb-2"><i class="bi bi-check text-accent me-2"></i> Blood bank refrigerators</li>
                  <li class="mb-2"><i class="bi bi-check text-accent me-2"></i> Plasma freezers</li>
                </ul>
              </div>
            </div>
            <a href="#" class="btn btn-outline-primary mt-3">View Full List</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Client Portal Section -->
  <section class="client-portal">
    <div class="container text-center">
      <h2 class="mb-3 text-white">Client Portal</h2>
      <p class="lead mb-4 text-white">Access your service history, request support, and manage contracts</p>
      <div class="d-flex btn-group-responsive justify-content-center">
        <a href="auth/login.php" class="btn btn-light btn-lg">
          <i class="bi bi-box-arrow-in-right me-2"></i>Login
        </a>
        <a href="auth/register.php" class="btn btn-outline-light btn-lg">
          <i class="bi bi-person-plus me-2"></i>Register
        </a>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="container scroll-animate">
    <div class="text-center mb-5">
      <h2>What Our Clients Say</h2>
      <p class="lead">Trusted by leading healthcare institutions</p>
    </div>
    
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card service-card p-4 testimonial-card">
          <div class="star-rating mb-3">
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
          <p class="mb-4">"Ananya's technicians resolved our plasma freezer issue within 2 hours of calling. Their expertise is unmatched."</p>
          <div class="d-flex align-items-center mt-3">
            <i class="bi bi-person-circle text-primary me-3" style="font-size: 2rem;"></i>
            <div>
              <h6 class="mb-0 text-primary">Dr. Sharma</h6>
              <small class="text-muted">Delhi Blood Bank</small>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card service-card p-4 testimonial-card">
          <div class="star-rating mb-3">
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
          <p class="mb-4">"Their AMC program has saved us thousands in unexpected repair costs. Highly recommended for blood banks."</p>
          <div class="d-flex align-items-center mt-3">
            <i class="bi bi-person-circle text-primary me-3" style="font-size: 2rem;"></i>
            <div>
              <h6 class="mb-0 text-primary">Ms. Patel</h6>
              <small class="text-muted">Mumbai Medical Center</small>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card service-card p-4 testimonial-card">
          <div class="star-rating mb-3">
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-half text-warning"></i>
          </div>
          <p class="mb-4">"The calibration certificates provided meet all NABH requirements. One less thing to worry about."</p>
          <div class="d-flex align-items-center mt-3">
            <i class="bi bi-person-circle text-primary me-3" style="font-size: 2rem;"></i>
            <div>
              <h6 class="mb-0 text-primary">Mr. Kumar</h6>
              <small class="text-muted">Bangalore Hospital</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="main-footer">
    <div class="container py-4">
      <div class="row g-4">
        <!-- Company Info -->
        <div class="col-lg-4 col-md-6 footer-column">
          <div class="d-flex align-items-center mb-3">
            <h4 class="mb-0 text-white">Ananya Sales & Service</h4>
          </div>
          <p class="text-white-50">Specialists in blood bank equipment maintenance, calibration, and service contracts.</p>
          
          <div class="d-flex gap-3 mt-4">
            <a href="#" class="btn btn-outline-light social-btn">
              <i class="bi bi-facebook"></i>
            </a>
            <a href="#" class="btn btn-outline-light social-btn">
              <i class="bi bi-linkedin"></i>
            </a>
            <a href="#" class="btn btn-outline-light social-btn">
              <i class="bi bi-instagram"></i>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div class="col-lg-2 col-md-6 footer-column">
          <h5 class="text-white mb-3">Quick Links</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="auth/login.php" class="text-white-50 text-decoration-none hover-text-white"><i class="bi bi-chevron-right me-1"></i> Admin Login</a></li>
            <li class="mb-2"><a href="auth/login.php" class="text-white-50 text-decoration-none hover-text-white"><i class="bi bi-chevron-right me-1"></i> Client Login</a></li>
          </ul>
        </div>

        <!-- Services -->
        <div class="col-lg-3 col-md-6 footer-column">
          <h5 class="text-white mb-3">Our Services</h5>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-text-white"><i class="bi bi-chevron-right me-1"></i> Plasma Freezers</a></li>
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-text-white"><i class="bi bi-chevron-right me-1"></i> Blood Storage</a></li>
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-text-white"><i class="bi bi-chevron-right me-1"></i> Centrifuge Calibration</a></li>
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-text-white"><i class="bi bi-chevron-right me-1"></i> AMC Contracts</a></li>
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none hover-text-white"><i class="bi bi-chevron-right me-1"></i> Emergency Repairs</a></li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div class="col-lg-3 col-md-6 footer-column">
          <h5 class="text-white mb-3">Contact Us</h5>
          <ul class="list-unstyled text-white-50">
            <li class="mb-3 d-flex">
              <i class="bi bi-geo-alt-fill text-primary me-2 mt-1"></i>
              <span>123 Medical Equipment Plaza, New Delhi 110001, India</span>
            </li>
            <li class="mb-3 d-flex">
              <i class="bi bi-telephone-fill text-primary me-2 mt-1"></i>
              <div>
                <div>+91 98765 43210</div>
                <div>+91 11 2345 6789</div>
              </div>
            </li>
            <li class="mb-3 d-flex">
              <i class="bi bi-envelope-fill text-primary me-2 mt-1"></i>
              <span>service@ananyasales.com</span>
            </li>
            <li class="d-flex">
              <i class="bi bi-clock-fill text-primary me-2 mt-1"></i>
              <div>
                <div>Monday-Saturday: 8AM-8PM</div>
                <div>Emergency: 24/7 Support</div>
              </div>
            </li>
          </ul>
        </div>
      </div>

      <hr class="my-4 border-secondary">

      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
          <p class="mb-0 text-white-50">&copy; <?php echo date('Y'); ?> Ananya Sales & Service. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <div class="d-flex justify-content-md-end justify-content-center gap-3">
            <a href="https://www.meetsumit.xyz" class="text-white-50 hover-text-white text-decoration-none">Developed by <span class="text-warning">Sumit Kumar</span></a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <script>
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('/sw.js')
    .then(reg => console.log('✅ Service Worker registered:', reg.scope))
    .catch(err => console.error('❌ SW registration failed:', err));
}
</script>
  
<script>
  let deferredPrompt;
const installBtn = document.getElementById('installApp');

window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;
  installBtn.style.display = 'block';

  installBtn.addEventListener('click', () => {
    installBtn.style.display = 'none';
    deferredPrompt.prompt();
  });
});
</script>

<script>
// Scroll Animation
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

// Staggered card animation
function animateCards() {
  const cards = document.querySelectorAll('.card-loading');
  cards.forEach((card, index) => {
    setTimeout(() => {
      card.style.animationDelay = `${index * 0.1}s`;
      card.classList.add('animate');
    }, index * 100);
  });
}

// Enhanced button hover effects
function addButtonEffects() {
  const buttons = document.querySelectorAll('.btn-primary, .btn-outline-primary');
  buttons.forEach(button => {
    button.addEventListener('mouseenter', function() {
      this.style.transform = 'translateY(-2px) scale(1.05)';
    });
    
    button.addEventListener('mouseleave', function() {
      this.style.transform = 'translateY(0) scale(1)';
    });
  });
}

// Parallax effect for hero section
function addParallaxEffect() {
  const hero = document.querySelector('.hero-section');
  if (hero) {
    window.addEventListener('scroll', () => {
      const scrolled = window.pageYOffset;
      const rate = scrolled * -0.5;
      hero.style.transform = `translate3d(0, ${rate}px, 0)`;
    });
  }
}

// Smooth scroll for anchor links
function addSmoothScroll() {
  const links = document.querySelectorAll('a[href^="#"]');
  links.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
}

// Initialize all animations and effects
document.addEventListener('DOMContentLoaded', function() {
  // Initial check for elements in viewport
  handleScrollAnimation();
  
  // Animate cards on load
  setTimeout(animateCards, 500);
  
  // Add button effects
  addButtonEffects();
  
  // Add parallax effect
  addParallaxEffect();
  
  // Add smooth scroll
  addSmoothScroll();
  
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
  
  // Add loading animation to page
  document.body.style.opacity = '0';
  document.body.style.transition = 'opacity 0.5s ease-in-out';
  setTimeout(() => {
    document.body.style.opacity = '1';
  }, 100);
});

// Add intersection observer for better performance
if ('IntersectionObserver' in window) {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  });

  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.scroll-animate').forEach(el => {
      observer.observe(el);
    });
  });
}
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>