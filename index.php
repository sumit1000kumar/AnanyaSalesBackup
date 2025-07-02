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
  <link rel="icon" href="../assets/images/favicon/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" sizes="180x180" href="../assets/images/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon/favicon-16x16.png">
  <link rel="manifest" href="../assets/images/favicon/site.webmanifest">

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
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    body {
      background-color: var(--light-bg);
      font-family: 'Open Sans', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: var(--dark-text);
    }
    
    /* Hero Section */
    .hero-section {
      background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
      background-size: cover;
      background-position: center;
      color: white;
      padding: 3rem 0;
      margin-bottom: 2rem;
    }
    
    @media (min-width: 768px) {
      .hero-section {
        padding: 5rem 0;
        margin-bottom: 3rem;
      }
    }
    
    /* Service Cards */
    .service-card {
      border: none;
      border-radius: 8px;
      box-shadow: var(--card-shadow);
      transition: transform 0.3s;
      height: 100%;
      background-color: white;
      margin-bottom: 1.5rem;
    }
    
    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .service-icon {
      font-size: 2rem;
      color: var(--primary-color);
      margin-bottom: 0.75rem;
    }
    
    @media (min-width: 768px) {
      .service-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
      }
    }
    
    /* Buttons */
    .btn-primary {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
      padding: 0.5rem 1.25rem;
      font-weight: 500;
      font-size: 0.9rem;
    }
    
    @media (min-width: 768px) {
      .btn-primary {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
      }
    }
    
    .btn-primary:hover {
      background-color: var(--secondary-color);
      border-color: var(--secondary-color);
    }
    
    .btn-outline-primary {
      color: var(--primary-color);
      border-color: var(--primary-color);
    }
    
    .btn-outline-primary:hover {
      background-color: var(--primary-color);
      color: white;
    }
    
    /* Typography */
    h1, h2, h3, h4, h5, h6 {
      font-family: 'Montserrat', sans-serif;
      font-weight: 600;
      color: var(--dark-text);
    }
    
    h1 {
      font-size: 1.75rem;
    }
    
    h2 {
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    
    @media (min-width: 768px) {
      h1 {
        font-size: 2.5rem;
      }
      h2 {
        font-size: 2rem;
        margin-bottom: 1.5rem;
      }
    }
    
    .lead {
      font-size: 1rem;
      color: var(--dark-text);
    }
    
    @media (min-width: 768px) {
      .lead {
        font-size: 1.25rem;
      }
    }
    
    /* Sections */
    section {
      padding: 2rem 0;
    }
    
    @media (min-width: 768px) {
      section {
        padding: 3rem 0;
      }
    }
    
    /* Feature Box */
    .feature-box {
      background-color: white;
      border-radius: 8px;
      padding: 1.5rem;
      box-shadow: var(--card-shadow);
      margin-bottom: 1.5rem;
      text-align: center;
    }
    
    /* Client Portal */
    .client-portal {
      background-color: var(--primary-color);
      color: white;
      padding: 2rem 0;
    }
    
    /* Footer */
    .main-footer {
      background-color: var(--dark-text);
      color: white;
      position: relative;
      overflow: hidden;
      padding: 2rem 0;
    }
    
    .main-footer::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 4px;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }
    
    .text-white-50 {
      color: rgba(255, 255, 255, 0.8);
    }
    
    .hover-text-white:hover {
      color: white !important;
    }
    
    /* Responsive adjustments */
    .responsive-img {
      width: 100%;
      height: auto;
      margin-top: 1.5rem;
    }
    
    @media (min-width: 768px) {
      .responsive-img {
        margin-top: 0;
      }
    }
    
    /* Button groups */
    .btn-group-responsive {
      flex-direction: column;
      gap: 0.75rem;
    }
    
    @media (min-width: 576px) {
      .btn-group-responsive {
        flex-direction: row;
      }
    }
    
    /* Testimonial cards */
    .testimonial-card {
      margin-bottom: 1.5rem;
    }
    
    /* Footer columns */
    .footer-column {
      margin-bottom: 2rem;
    }
    
    @media (min-width: 768px) {
      .footer-column {
        margin-bottom: 0;
      }
    }
  </style>
</head>
<body>
  <!-- Hero Section -->
  <section class="hero-section text-center">
    <div class="container">
        <h1 class="fw-bold mb-3 text-white"> <img src="assets/images/logo/logo.jpg" alt="Ananya Sales & Service Logo" style="height:64px;width:auto; margin-right: 15px;" >Ananya Sales & Service</h1>
      <p class="lead mb-4 text-white">Specialized Maintenance & Calibration for Blood Bank Equipment</p>
      <div class="d-flex btn-group-responsive justify-content-center">
        <a href="auth/login.php" class="btn btn-primary btn-lg">
          <i class="bi bi-box-arrow-in-right me-2"></i>Portal Login
        </a>
        <a href="tel:+919876543210" class="btn btn-outline-light btn-lg">
          <i class="bi bi-telephone me-2"></i>Emergency Service
        </a>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <section class="container">
    <div class="text-center mb-4">
      <h2>Our Specialized Services</h2>
      <p class="lead">Precision calibration and maintenance for critical blood bank equipment</p>
    </div>
    
    <div class="row g-4">
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card text-center p-3">
          <div class="service-icon">
            <i class="bi bi-snow"></i>
          </div>
          <h4 class="text-primary">Plasma Freezers</h4>
          <p class="mb-3">Precision calibration and maintenance for optimal temperature control</p>
          <!-- <a href="#" class="text-accent">Learn more <i class="bi bi-arrow-right"></i></a> -->
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card text-center p-3">
          <div class="service-icon">
            <i class="bi bi-droplet"></i>
          </div>
          <h4 class="text-primary">Blood Storage</h4>
          <p class="mb-3">Comprehensive servicing of blood storage refrigerators and systems</p>
          <!-- <a href="#" class="text-accent">Learn more <i class="bi bi-arrow-right"></i></a> -->
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card text-center p-3">
          <div class="service-icon">
            <i class="bi bi-arrow-repeat"></i>
          </div>
          <h4 class="text-primary">Component Centrifuge</h4>
          <p class="mb-3">Expert calibration and repair services for blood separation equipment</p>
          <!-- <a href="#" class="text-accent">Learn more <i class="bi bi-arrow-right"></i></a> -->
        </div>
      </div>
      
      <div class="col-12 col-sm-6 col-lg-3">
        <div class="card service-card text-center p-3">
          <div class="service-icon">
            <i class="bi bi-thermometer-snow"></i>
          </div>
          <h4 class="text-primary">Walking Chambers</h4>
          <p class="mb-3">Maintenance and temperature validation for blood bank walk-ins</p>
          <!-- <a href="#" class="text-accent">Learn more <i class="bi bi-arrow-right"></i></a> -->
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
  <section class="container">
    <div class="text-center mb-4">
      <h2>What Our Clients Say</h2>
      <p class="lead">Trusted by leading healthcare institutions</p>
    </div>
    
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card service-card p-3 testimonial-card">
          <div class="d-flex mb-3">
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
          <p>"Ananya's technicians resolved our plasma freezer issue within 2 hours of calling. Their expertise is unmatched."</p>
          <div class="d-flex align-items-center mt-3">
            <i class="bi bi-person-circle text-primary me-2" style="font-size: 1.5rem;"></i>
            <div>
              <h6 class="mb-0 text-primary">Dr. Sharma</h6>
              <small class="text-muted">Delhi Blood Bank</small>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card service-card p-3 testimonial-card">
          <div class="d-flex mb-3">
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning"></i>
          </div>
          <p>"Their AMC program has saved us thousands in unexpected repair costs. Highly recommended for blood banks."</p>
          <div class="d-flex align-items-center mt-3">
            <i class="bi bi-person-circle text-primary me-2" style="font-size: 1.5rem;"></i>
            <div>
              <h6 class="mb-0 text-primary">Ms. Patel</h6>
              <small class="text-muted">Mumbai Medical Center</small>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-md-4">
        <div class="card service-card p-3 testimonial-card">
          <div class="d-flex mb-3">
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-fill text-warning me-1"></i>
            <i class="bi bi-star-half text-warning"></i>
          </div>
          <p>"The calibration certificates provided meet all NABH requirements. One less thing to worry about."</p>
          <div class="d-flex align-items-center mt-3">
            <i class="bi bi-person-circle text-primary me-2" style="font-size: 1.5rem;"></i>
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
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle p-2">
              <i class="bi bi-facebook"></i>
            </a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle p-2">
              <i class="bi bi-linkedin"></i>
            </a>
            <a href="#" class="btn btn-sm btn-outline-light rounded-circle p-2">
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

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>