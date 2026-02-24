
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

require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/includes/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/includes/PHPMailer/src/Exception.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Contact form processing
$successMsg = $errorMsg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = trim($_POST['contact_name'] ?? '');
    $phone = trim($_POST['contact_phone'] ?? '');
    $subject = trim($_POST['contact_subject'] ?? '');
    $message = trim($_POST['contact_message'] ?? '');

    // Basic validation
    if ($name && preg_match('/^[0-9]{10}$/', $phone) && $subject && $message) {
        // Save to DB
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, phone, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssss", $name, $phone, $subject, $message);
        $dbSaved = $stmt->execute();
        $stmt->close();

        // Send Email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.ananyasales.in';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'support@ananyasales.in';
            $mail->Password   = 'Ananya#135';
            $mail->Port       = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->setFrom('support@ananyasales.in', 'Ananya Sales & Service');
            $mail->addAddress('sumitkumar9012004@gmail.com');
            $mail->Subject = 'New Contact Message: ' . ucfirst($subject);
            $mail->isHTML(true);
            $mail->Body = '<b>Name:</b> ' . htmlspecialchars($name) . '<br>' .
                         '<b>Phone:</b> ' . htmlspecialchars($phone) . '<br>' .
                         '<b>Subject:</b> ' . htmlspecialchars($subject) . '<br>' .
                         '<b>Message:</b><br>' . nl2br(htmlspecialchars($message));
            $mail->send();
            $successMsg = 'Thank you! Your message has been sent. Our team will get back to you shortly.';
        } catch (Exception $e) {
            $errorMsg = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }
        if (!$dbSaved) {
            $errorMsg = 'Message could not be saved to database.';
        }
    } else {
        $errorMsg = 'Please fill all fields correctly.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Ananya Sales & Service | Blood Bank Equipment Specialists</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="keywords" content="blood bank equipment, plasma freezers, centrifuge calibration, AMC, CMC, blood storage, Ananya Sales & Service, medical equipment India">
    <meta name="description" content="Professional Service & Calibration for Blood Bank Equipment. Ananya Sales & Service is a trusted provider of maintenance, calibration, and AMC/CMC contracts for blood banks and hospitals in India.">

    <!-- Favicon -->
    <link href="assets/images/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="assets/lib/animate.min.css" rel="stylesheet">

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Main Stylesheet (local, customized) -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
    /* Attractive Support Section Styles */
    .support-attractive-container {
        width: 100%;
        background: linear-gradient(90deg, #fff 60%, #e30613 100%);
        border-radius: 18px;
        box-shadow: 0 6px 32px rgba(229,9,20,0.10), 0 2px 8px rgba(0,0,0,0.06);
        padding: 0;
        margin: 0 auto 40px auto;
        max-width: 900px;
        transition: box-shadow 0.2s;
    }
    .support-attractive-content {
        display: flex;
        align-items: stretch;
        justify-content: space-between;
        width: 100%;
        min-height: 170px;
    }
    .support-attractive-left {
        flex: 1 1 60%;
        background: transparent;
        padding: 36px 32px 36px 38px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .support-attractive-left h2 {
        font-size: 2rem;
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 12px;
    }
    .support-attractive-left p {
        font-size: 1.13rem;
        color: #444;
        margin-bottom: 0;
    }
    .support-attractive-right {
        flex: 1 1 40%;
        background: #e30613;
        border-radius: 0 18px 18px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-width: 260px;
        padding: 0 32px;
        position: relative;
    }
    .support-attractive-phone {
        font-size: 2.3rem;
        font-weight: 900;
        color: #fff;
        letter-spacing: 2px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 10px;
        text-shadow: 0 2px 12px rgba(0,0,0,0.10);
    }
    .support-attractive-phone i {
        font-size: 2.1rem;
        color: #fff;
        background: #b9000e;
        border-radius: 50%;
        padding: 12px 14px;
        margin-right: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    }
    .support-attractive-label {
        font-size: 1.1rem;
        color: #fff;
        font-weight: 600;
        letter-spacing: 1px;
        background: rgba(255,255,255,0.13);
        border-radius: 1em;
        padding: 4px 18px;
        margin-top: 2px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    }
    @media (max-width: 700px) {
        .support-attractive-content {
            flex-direction: column;
            min-height: unset;
        }
        .support-attractive-left, .support-attractive-right {
            border-radius: 18px 18px 0 0;
            padding: 28px 18px;
            min-width: unset;
            text-align: center;
        }
        .support-attractive-right {
            border-radius: 0 0 18px 18px;
            padding: 24px 18px;
        }
    }
        /* Navbar link hover underline effect */
        .navbar-nav .nav-link {
            position: relative;
            transition: color 0.2s;
        }
        .navbar-nav .nav-link::after {
            content: "";
            display: block;
            position: absolute;
            left: 0;
            bottom: 20px;
            width: 100%;
            height: 3px;
            background: #e53935;
            transform: scaleX(0);
            transition: transform 0.3s;
        }
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after {
            transform: scaleX(1);
        }

        .hero-80vh {
        height: 80vh !important;
        min-height: 400px;
        position: relative;
    }
    .hero-80vh .carousel-inner,
    .hero-80vh .carousel-item,
    .hero-80vh .carousel-caption {
        height: 100% !important;
    }
    .hero-80vh .carousel-item {
        position: relative;
    }
    .hero-80vh .carousel-item > img {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        object-fit: cover;
        z-index: 1;
    }
    .hero-80vh .carousel-caption {
        z-index: 2;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        padding: 0;
    }

    .top-shape::before{
      background: #000000;
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
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="spinner-grow text-dark m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <div class="spinner-grow text-secondary m-1" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->


    <!-- Topbar Start -->
    <div class="container-fluid bg-light ps-5 pe-0 d-none d-lg-block">
        <div class="row gx-0">
            <div class="col-md-6 text-center text-lg-start mb-2 mb-lg-0">
                 <div class="d-inline-flex align-items-center">
                  <small class="py-2"><i class="far fa-clock text-danger me-2"></i>Opening Hours: Mon - Sat : 9.00 am - 8.00 pm, Sunday Closed </small>
                </div>
            </div>
            <div class="col-md-6 text-center text-lg-end">
                <div class="position-relative d-inline-flex align-items-center bg-danger text-white top-shape px-5">
                    <div class="me-3 pe-3 border-end py-2">
                        <p class="m-0"><i class="fa fa-envelope-open me-2"></i><a href="mailto:info@ananyasales.in" class="text-white text-decoration-none">info@ananyasales.in</a></p>
                    </div>
                    <div class="py-2">
                        <p class="m-0"><i class="fa fa-phone-alt me-2"></i><a href="tel:+918104293994" class="text-white text-decoration-none">+91 81042 93994</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-5 py-3 py-lg-0">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/images/logo/logo-noBg.png" alt="Ananya Sales & Service Logo" style="height: 50px; width: auto; margin-right: 10px;">
            <span class="fw-bold text-danger">Ananya Sales & Service</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php" class="nav-item nav-link active">Home</a>
                <a href="#about" class="nav-item nav-link">About</a>
                <a href="#services" class="nav-item nav-link">Services</a>
                <a href="products.php" class="nav-item nav-link">Products</a>
                <a href="#contact" class="nav-item nav-link">Contact</a>
            </div>
            <div class="d-flex align-items-center ms-lg-4">
                <a href="auth/login.php" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Portal Login
                </a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->


    <!-- Carousel Start -->
    <div class="container-fluid p-0 mb-5 hero-80vh">
        <div id="header-carousel" class="carousel slide carousel-fade h-100 position-relative" data-bs-ride="carousel">
            <div class="carousel-inner h-100 w-100 position-relative">
                <div class="carousel-item active">
                    <img class="position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover; z-index:1;" src="https://plus.unsplash.com/premium_photo-1661779739047-c5c27cf8ebac?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Blood Bank Equipment">
                        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100" style="background:rgba(0,0,0,0.5); z-index:2;"></div>
                        <div class="carousel-caption d-flex align-items-center justify-content-center h-100 w-100 p-0 m-0" style="z-index:3; position:relative; left:0; top:0; background:none !important;">
                            <div class="container h-100 d-flex flex-column justify-content-center align-items-start" style="position:relative; z-index:3; background:none !important;">
                                <div class="row">
                                    <div class="col-lg-8 col-md-10 col-12 py-5 ps-lg-4 ps-md-3 ps-2 text-start" style="background:none !important;">
                                        <span class="badge bg-dark bg-opacity-75 text-white fs-6 fw-semibold px-4 py-2 mb-4 shadow-sm" style="font-size:1.05rem; letter-spacing:1px; border-radius:2em; background:#fff !important; color: red !important;"><i class="bi bi-patch-check-fill me-2 text-danger"></i>Trusted Since 2008</span>
                                        <h1 class="fw-bold mb-3 text-white text-start" style="font-size:3.5rem; line-height:1.05; text-shadow:0 2px 12px rgba(0,0,0,0.22); background:none !important;">
                                            Precision Care for <span style="color:#e53935; border-radius:0.25em; padding:0 0.2em; background:none !important;">Blood Bank Equipment</span>
                                        </h1>
                                        <p class="text-white mb-4 fs-5 text-start" style="max-width:700px; text-shadow:0 1px 8px rgba(0,0,0,0.18); font-size:1.25rem; background:none !important;">Specialized maintenance, calibration, and service contracts for critical healthcare equipment. Ensuring reliability when it matters most.</p>
                                        <div class="d-flex flex-wrap gap-3 mb-4" style="background:none !important;">
                                            <span class="badge bg-white bg-opacity-75 text-danger fw-semibold px-3 py-2 border border-0" style="background:none !important;"><i class="bi bi-lightning-charge-fill me-1"></i>24/7 Emergency Service</span>
                                            <span class="badge bg-white bg-opacity-75 text-danger fw-semibold px-3 py-2 border border-0" style="background: none !important;"><i class="bi bi-shield-check me-1"></i>Certified Technicians</span>
                                        </div>
                                        <div class="d-flex flex-wrap gap-3" style="background:none !important;">
                                            <a href="products.php" class="btn btn-danger px-4 py-2 fw-semibold shadow">Check Products</a>
                                            <a href="#contact" class="btn btn-outline-light px-4 py-2 fw-semibold">Schedule a Consultation</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
                <!-- <div class="carousel-item">
                    <img class="position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover; z-index:1;" src="assets/images/carousel-2.jpg" alt="Image">
                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center h-100 w-100" style="z-index:2; position:relative;">
                        <div class="p-3" style="max-width: 900px;">
                            <h5 class="text-white text-uppercase mb-3 animated slideInDown">Keep Your Teeth Healthy</h5>
                            <h1 class="display-1 text-white mb-md-4 animated zoomIn">Take The Best Quality Dental Treatment</h1>
                            <a href="appointment.html" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Appointment</a>
                            <a href="" class="btn btn-secondary py-md-3 px-md-5 animated slideInRight">Contact Us</a>
                        </div>
                    </div>
                </div> -->
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- About Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="section-title mb-4">
                        <h5 class="position-relative d-inline-block text-primary text-uppercase">About Us</h5>
                        <h1 class="display-5 mb-0">Leading Blood Bank Equipment Supplier in India</h1>
                    </div>
                    <h4 class="text-body fst-italic mb-4">Advanced blood bank equipment, genuine spare parts, and certified technical services for hospitals & labs.</h4>
                    <p class="mb-4">We supply high-performance blood bank refrigerators, plasma freezers, platelet incubators, and other critical storage systems for hospitals and laboratories. Along with equipment supply, we provide genuine spare parts, installation, calibration, and Annual Maintenance Contracts (AMC) to ensure reliable and compliant operations.</p>
                    <div class="row g-3">
                        <div class="col-sm-6 wow zoomIn" data-wow-delay="0.3s">
                            <h5 class="mb-3"><i class="fa fa-check-circle text-danger me-3"></i>Certified Technical Engineers</h5>
                            <h5 class="mb-3"><i class="fa fa-check-circle text-danger me-3"></i>Genuine Spare Parts</h5>
                        </div>
                        <div class="col-sm-6 wow zoomIn" data-wow-delay="0.6s">
                            <h5 class="mb-3"><i class="fa fa-check-circle text-danger me-3"></i>Installation & AMC Services</h5>
                            <h5 class="mb-3"><i class="fa fa-check-circle text-danger me-3"></i>Fair Prices</h5>
                        </div>
                    </div>
                    <a href="appointment.html" class="btn btn-danger py-3 px-5 mt-4 wow zoomIn" data-wow-delay="0.6s">Request a Quote</a>
                </div>
                <div class="col-lg-5" style="min-height: 500px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100 rounded wow zoomIn" data-wow-delay="0.9s" src="https://images.unsplash.com/photo-1581093450021-4a7360e9a7b1?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" alt="Blood Bank Equipment" style="object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Service Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="section-title mb-4 text-center">
                <h5 class="position-relative d-inline-block text-primary text-uppercase mb-2" style="font-size: 22px; letter-spacing: 0.5px;">OUR SERVICES</h5>
                <span class="d-inline-block align-middle mx-2" style="border-top: 3px solid #e30613; width: 45px; position: relative; top: -8px;"></span>
                <span class="d-inline-block align-middle mx-1" style="border-top: 3px solid #a9030d; width: 15px; position: relative; top: -8px;"></span>
                <h1 class="display-5 mb-0 mt-2" style="font-weight:700;">Complete Blood Bank Equipment Solutions</h1>
            </div>
            <div class="d-flex justify-content-center gap-4 mb-4 flex-wrap">
                <div class="service-card text-left bg-white rounded shadow-sm p-3" style="width:260px;">
                    <img src="assets/images/blood-bank-supply-service-1.png" alt="Blood Bank Equipment Supply" style="width:100%;height:140px;object-fit:cover;border-radius:8px;">
                    <h3 class="mt-3 mb-1" style="font-size:22px;font-weight:600;text-align:left;">Blood Bank Equipment Supply</h3>
                    <p class="mb-0" style="font-size:16px;text-align:left;">Advanced refrigerators, plasma freezers, platelet incubators, and storages</p>
                </div>
                <div class="service-card text-left bg-white rounded shadow-sm p-3" style="width:260px;">
                    <img src="assets/images/installation-commissioning-service.png" alt="Installation & Commissioning" style="width:100%;height:140px;object-fit:cover;border-radius:8px;">
                    <h3 class="mt-3 mb-1" style="font-size:22px;font-weight:600;text-align:left;">Installation & Commissioning</h3>
                    <p class="mb-0" style="font-size:16px;text-align:left;">Professional setup, validation, and performance testing</p>
                </div>
                <div class="service-card text-left bg-white rounded shadow-sm p-3" style="width:260px;">
                    <img src="assets/images/Genuine-spare-parts-supply-service.png" alt="Genuine Spare Parts Supply" style="width:100%;height:140px;object-fit:cover;border-radius:8px;">
                    <h3 class="mt-3 mb-1" style="font-size:22px;font-weight:600;text-align:left;">Genuine Spare Parts Supply</h3>
                    <p class="mb-0" style="font-size:16px;text-align:left;">Original components for reliable and compliant operation</p>
                </div>
                <div class="service-card text-left bg-white rounded shadow-sm p-3" style="width:260px;">
                    <img src="assets/images/AMC-Breakdown-Service.png" alt="AMC & Breakdown Services" style="width:100%;height:140px;object-fit:cover;border-radius:8px;">
                    <h3 class="mt-3 mb-1" style="font-size:22px;font-weight:600;text-align:left;">AMC & Breakdown Services</h3>
                    <p class="mb-0" style="font-size:16px;text-align:left;">Annual Maintenance Contracts and rapid technical support</p>
                </div>
            </div>
            <div class="support-attractive-container mb-5">
                <div class="support-attractive-content">
                    <div class="support-attractive-left">
                        <h2>Need Immediate Technical Support?</h2>
                        <p>Reliable blood bank equipment service and spare parts assistance across India.</p>
                    </div>
                    <div class="support-attractive-right">
                        <div class="support-attractive-phone">
                            <!-- <i class="fa fa-phone-alt"></i> -->
                             +91 81042 93994
                        </div>
                        <div class="support-attractive-label">Call Now</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service End -->


    <!-- Testimonials Start -->
    <style>
    .testimonial-section {
        background: #fff;
        padding: 60px 0;
    }
    .carousel-item {
        min-height: 320px;
    }
    .testimonial-card {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        padding: 32px 24px 24px 24px;
        max-width: 370px;
        min-width: 320px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        transition: box-shadow 0.2s;
        position: relative;
    }
    .testimonial-stars {
        color: #ffb400;
        font-size: 1.3rem;
        margin-bottom: 12px;
    }
    .testimonial-text {
        font-size: 1.08rem;
        color: #222;
        margin-bottom: 24px;
        min-height: 80px;
    }
    .testimonial-profile {
        display: flex;
        align-items: center;
        margin-top: auto;
    }
    .testimonial-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        border: 3px solid #e11d48;
        margin-right: 16px;
        object-fit: cover;
    }
    .testimonial-name {
        font-weight: 700;
        font-size: 1.1rem;
        color: #222;
        margin-bottom: 2px;
    }
    .testimonial-title {
        font-size: 1rem;
        color: #888;
    }
    /* Carousel navigation button styles */
    .carousel-control-prev, .carousel-control-next {
        width: 48px;
        height: 48px;
        top: 50%;
        transform: translateY(-50%);
        background: #e30613;
        border-radius: 50%;
        opacity: 0.85;
        border: none;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    }
    .carousel-control-prev-icon, .carousel-control-next-icon {
        filter: none;
        background-size: 60% 60%;
        background-color: transparent;
    }
    .carousel-control-prev:hover, .carousel-control-next:hover {
        opacity: 1;
        background: #b9000e;
    }
    @media (max-width: 900px) {
        .carousel-item {
            min-height: 0;
        }
        .testimonial-card {
            min-width: 0;
            width: 100%;
        }
        .carousel-control-prev, .carousel-control-next {
            width: 36px;
            height: 36px;
        }
    }
    </style>
    <div class="testimonial-section" id="testimonials">
        <div class="container">
            <div class="section-title mb-4 text-center">
                <h5 class="position-relative d-inline-block text-primary text-uppercase mb-2" style="font-size: 22px; letter-spacing: 0.5px;">TESTIMONIALS</h5>
                <span class="d-inline-block align-middle mx-2" style="border-top: 3px solid #e30613; width: 45px; position: relative; top: -8px;"></span>
                <span class="d-inline-block align-middle mx-1" style="border-top: 3px solid #a9030d; width: 15px; position: relative; top: -8px;"></span>
                <h1 class="display-5 mb-0 mt-2" style="font-weight:700;">What Our Clients Say</h1>
            </div>
            <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="d-flex justify-content-center align-items-center w-100">
                            <div class="testimonial-card">
                                <div class="testimonial-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="testimonial-text">"Ananya's technicians resolved our plasma freezer issue within 2 hours of calling. Their expertise is unmatched and their emergency service is truly reliable."</div>
                                <div class="testimonial-profile">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Dr. Sharma" class="testimonial-avatar">
                                    <div>
                                        <div class="testimonial-name">Dr. Sharma</div>
                                        <div class="testimonial-title">Delhi Blood Bank</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="d-flex justify-content-center align-items-center w-100">
                            <div class="testimonial-card">
                                <div class="testimonial-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="testimonial-text">"Their AMC program has saved us thousands in unexpected repair costs. The preventive maintenance approach ensures our equipment is always in optimal condition."</div>
                                <div class="testimonial-profile">
                                    <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Ms. Patel" class="testimonial-avatar">
                                    <div>
                                        <div class="testimonial-name">Ms. Patel</div>
                                        <div class="testimonial-title">Mumbai Medical Center</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="d-flex justify-content-center align-items-center w-100">
                            <div class="testimonial-card">
                                <div class="testimonial-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <div class="testimonial-text">"The calibration certificates provided meet all NABH requirements. Their documentation is thorough and has made our regulatory compliance much easier."</div>
                                <div class="testimonial-profile">
                                    <img src="https://randomuser.me/api/portraits/men/65.jpg" alt="Mr. Kumar" class="testimonial-avatar">
                                    <div>
                                        <div class="testimonial-name">Mr. Kumar</div>
                                        <div class="testimonial-title">Bangalore Hospital</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
    <!-- Testimonials End -->

    <!-- Contact Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-xl-4 col-lg-6 wow slideInUp" data-wow-delay="0.1s">
                                        <!-- Improved Contact Card Design -->
                                        <style>
                                            .contact-card {
                                                background: #fff;
                                                border-radius: 18px;
                                                box-shadow: 0 4px 24px rgba(0,0,0,0.07);
                                                padding: 2.5rem 2rem;
                                                max-width: 340px;
                                                margin: auto;
                                            }
                                            .contact-header {
                                                display: flex;
                                                align-items: center;
                                                margin-bottom: 1.2rem;
                                            }
                                            .contact-title {
                                                color: #2563eb;
                                                font-weight: 700;
                                                letter-spacing: 1px;
                                                font-size: 1rem;
                                                text-transform: uppercase;
                                            }
                                            .contact-divider {
                                                flex: 1;
                                                height: 2px;
                                                background: linear-gradient(90deg, #e11d48 60%, transparent 100%);
                                                margin-left: 1rem;
                                                border-radius: 1px;
                                            }
                                            .contact-card h2 {
                                                font-size: 2rem;
                                                font-weight: 800;
                                                margin: 0 0 2rem 0;
                                                color: #22223b;
                                                line-height: 1.2;
                                            }
                                            .contact-info {
                                                display: flex;
                                                flex-direction: column;
                                                gap: 1.5rem;
                                            }
                                            .contact-item {
                                                display: flex;
                                                align-items: flex-start;
                                                gap: 1rem;
                                            }
                                            .contact-icon {
                                                color: #e11d48;
                                                font-size: 1.7rem;
                                                margin-top: 2px;
                                                width: 2rem;
                                                height: 2rem;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                            }
                                            .contact-detail {
                                                color: #6b7280;
                                                font-size: 1rem;
                                                font-weight: 400;
                                            }
                                            @media (max-width: 500px) {
                                                .contact-card {
                                                    padding: 1.2rem 0.7rem;
                                                    max-width: 100%;
                                                }
                                                .contact-card h2 {
                                                    font-size: 1.3rem;
                                                }
                                            }
                                        </style>
                                        <div class="contact-card">
                                            <div class="contact-header">
                                                <span class="contact-title">CONTACT US</span>
                                                <span class="contact-divider"></span>
                                            </div>
                                            <h2>Feel Free To<br>Contact Us</h2>
                                            <div class="contact-info">
                                                <div class="contact-item">
                                                    <span class="contact-icon">
                                                        <!-- Location SVG -->
                                                        <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C7.03 2 3 6.03 3 11c0 5.25 7.25 11 9 11s9-5.75 9-11c0-4.97-4.03-9-9-9zm0 13.5c-2.48 0-4.5-2.02-4.5-4.5S9.52 6.5 12 6.5s4.5 2.02 4.5 4.5-2.02 4.5-4.5 4.5z" fill="#e11d48"/></svg>
                                                    </span>
                                                    <div>
                                                        <strong>Our Office</strong>
                                                        <div class="contact-detail">Navde, Navi Mumbai</div>
                                                    </div>
                                                </div>
                                                <div class="contact-item">
                                                    <span class="contact-icon">
                                                        <!-- Email SVG -->
                                                        <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 2v.01L12 13 4 6.01V6h16zm-16 12V8l8 5 8-5v10H4z" fill="#e11d48"/></svg>
                                                    </span>
                                                    <div>
                                                        <strong>Email Us</strong>
                                                        <div class="contact-detail">info@ananyasales.in</div>
                                                    </div>
                                                </div>
                                                <div class="contact-item">
                                                    <span class="contact-icon">
                                                        <!-- Phone SVG -->
                                                        <svg width="24" height="24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M6.62 10.79a15.05 15.05 0 006.59 6.59l2.2-2.2a1 1 0 011.11-.27c1.21.49 2.53.76 3.88.76a1 1 0 011 1v3.5a1 1 0 01-1 1C7.61 22 2 16.39 2 10.5a1 1 0 011-1h3.5a1 1 0 011 1c0 1.35.27 2.67.76 3.88a1 1 0 01-.27 1.11l-2.2 2.2z" fill="#e11d48"/></svg>
                                                    </span>
                                                    <div>
                                                        <strong>Call Us</strong>
                                                        <div class="contact-detail">+91 81042 93994</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                </div>
                <div class="col-xl-4 col-lg-6 wow slideInUp" data-wow-delay="0.3s">

                    <?php if ($successMsg): ?>
                        <div class="alert alert-success"> <?= $successMsg ?> </div>
                    <?php elseif ($errorMsg): ?>
                        <div class="alert alert-danger"> <?= $errorMsg ?> </div>
                    <?php endif; ?>
                    <form method="post" autocomplete="off">
                        <div class="row g-3">
                            <div class="col-12">
                                <input type="text" name="contact_name" class="form-control border-0 bg-light px-4" placeholder="Your Name" style="height: 55px;" required value="<?= htmlspecialchars($_POST['contact_name'] ?? '') ?>">
                            </div>
                            <div class="col-12">
                                <input type="tel" name="contact_phone" class="form-control border-0 bg-light px-4" placeholder="Your Phone Number" style="height: 55px;" pattern="[0-9]{10}" maxlength="10" minlength="10" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)" required value="<?= htmlspecialchars($_POST['contact_phone'] ?? '') ?>">
                            </div>
                            <div class="col-12">
                                <select name="contact_subject" class="form-control border-0 bg-light px-4" style="height: 55px;" required>
                                    <option value="" disabled <?= empty($_POST['contact_subject']) ? 'selected' : '' ?>>Select Subject</option>
                                    <option value="service" <?= (($_POST['contact_subject'] ?? '') === 'service') ? 'selected' : '' ?>>Service Request</option>
                                    <option value="purchase" <?= (($_POST['contact_subject'] ?? '') === 'purchase') ? 'selected' : '' ?>>Product Purchase</option>
                                    <option value="complaint" <?= (($_POST['contact_subject'] ?? '') === 'complaint') ? 'selected' : '' ?>>Complaint</option>
                                    <option value="consultation" <?= (($_POST['contact_subject'] ?? '') === 'consultation') ? 'selected' : '' ?>>Consultation</option>
                                    <option value="other" <?= (($_POST['contact_subject'] ?? '') === 'other') ? 'selected' : '' ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <textarea name="contact_message" class="form-control border-0 bg-light px-4 py-3" rows="3" placeholder="Message" required><?= htmlspecialchars($_POST['contact_message'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-danger w-100 py-3" type="submit" name="contact_submit">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-4 col-lg-12 wow slideInUp" data-wow-delay="0.6s">
                    <iframe class="position-relative rounded w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15084.844682350982!2d73.0912470366346!3d19.05445089646613!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7e9f07164197b%3A0xcf882f74c583ad1a!2sNavde%2C%20Taloja%2C%20Navi%20Mumbai%2C%20Maharashtra%20410208!5e0!3m2!1sen!2sin!4v1771923820659!5m2!1sen!2sin"
                        frameborder="0" style="min-height: 400px; border:0;" allowfullscreen="" aria-hidden="false"
                        tabindex="0"></iframe>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->

    
  <!-- Footer -->
  <footer class="main-footer pt-5" style="background: black !important; color: white; position: relative; overflow: hidden;">
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

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-danger btn-lg-square rounded back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/wow/wow.min.js"></script>
    <script src="assets/lib/easing.min.js"></script>
    <script src="assets/lib/waypoints.min.js"></script>

    <!-- Main Javascript -->
    <script src="assets/js/main.js"></script>
</body>

</html>