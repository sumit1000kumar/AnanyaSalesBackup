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

// Quote Modal AJAX Handler
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['quote_name'], $_POST['quote_phone'], $_POST['quote_product']) &&
    !isset($_POST['contact_submit']) 
) {
    header('Content-Type: application/json');
    $name = trim($_POST['quote_name']);
    $phone = trim($_POST['quote_phone']);
    $product = trim($_POST['quote_product']);

    // Validate
    if (!$name || !preg_match('/^[0-9]{10}$/', $phone) || !$product) {
        echo json_encode([
            'success' => false,
            'message' => 'Please fill all fields correctly.'
        ]);
        exit;
    }

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
        $mail->Subject = 'Quote Request: ' . $product;
        $mail->isHTML(true);
        $mail->Body = '<b>Product:</b> ' . htmlspecialchars($product) . '<br>' .
                 '<b>Name:</b> ' . htmlspecialchars($name) . '<br>' .
                 '<b>Phone:</b> ' . htmlspecialchars($phone) . '<br>' .
                 '<b>Date:</b> ' . date('F j, Y, g:i a') . '<br>' .
                 '<b>Submitted from:</b> ananyasales.in website';
        $mail->send();
        echo json_encode([
            'success' => true,
            'message' => 'Thank you! Your quote request has been sent.'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo
        ]);
    }
    exit;
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
    ...existing code...
    /* FAQ Section Styles */
        /* Remove Bootstrap default accordion arrow */
        .faq-accordion .accordion-button::after {
            background-image: none !important;
            box-shadow: none !important;
        }
    #faq {
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        margin-bottom: 40px;
        padding-top: 0;
    }
    #faq .section-title {
        margin-bottom: 32px;
    }
    .faq-accordion .accordion-item {
        border: none;
        margin-bottom: 12px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(229,9,20,0.07);
        background: #fafbfc;
        transition: box-shadow 0.2s;
    }
    .faq-accordion .accordion-item.active,
    .faq-accordion .accordion-item:hover {
        box-shadow: 0 4px 24px rgba(229,9,20,0.13);
    }
    .faq-accordion .accordion-header {
        background: none;
    }
    .faq-accordion .accordion-button {
        font-size: 1.13rem;
        font-weight: 600;
        color: #e30613;
        background: #fff;
        border: none;
        box-shadow: none;
        padding: 18px 24px;
        border-radius: 0;
        transition: background 0.2s, color 0.2s;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .faq-accordion .accordion-button.collapsed {
        color: #222;
        background: #fafbfc;
    }
    .faq-accordion .accordion-button:after {
        content: '+';
        font-size: 1.3rem;
        font-weight: bold;
        color: #e30613;
        margin-left: auto;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .faq-accordion .accordion-button:not(.collapsed):after {
        color: #a9030d;
    }
    .faq-accordion .accordion-body {
        background: #fff;
        color: #444;
        font-size: 1.05rem;
        padding: 18px 24px 16px 24px;
        border-top: 1px solid #eee;
    }
    @media (max-width: 700px) {
        #faq {
            border-radius: 0;
            box-shadow: none;
            margin-bottom: 24px;
        }
        .faq-accordion .accordion-button,
        .faq-accordion .accordion-body {
            padding: 14px 12px;
        }
    }

    .topbar-hours { display: inline-block; }
    .topbar-email { display: inline-block; }

    body { margin: 0; }
    .topbar-container { padding-top: 0; padding-bottom: 0; position: relative; margin-top: 0 !important; }
    .top-shape { margin-top: 0 !important; margin-left: auto; display: inline-flex; }

    @media (max-width: 767.98px) {
        .topbar-hours { display: none !important; }
        .top-shape { padding-left: 0.75rem; padding-right: 0.75rem; }
        .top-shape .me-3 { padding-right: 0.5rem; margin-right: 0.5rem; border-right: none; }
    }

    /* If space is very limited, hide the email and show only phone */
    @media (max-width: 480px) {
        .topbar-email { display: none !important; }
        .top-shape { padding-left: 0.5rem; padding-right: 0.5rem; }
        .top-shape p { margin: 0; font-size: 0.95rem; }
    }

    /* Fix: mid-range screens where topbar was overflowing (766px - 902px) */
    @media (min-width: 766px) and (max-width: 902px) {
        /* keep both email and phone visible; tighten spacing and prevent wrapping */
        .topbar-email { display: inline-block !important; }
        .top-shape { padding-left: 0.35rem; padding-right: 0.35rem; gap: 0.35rem; flex-wrap: nowrap; }
        .top-shape .me-3 { padding-right: 0.3rem; margin-right: 0.3rem; border-right: 1px solid rgba(255,255,255,0.18); }
        .top-shape p, .top-shape a { font-size: 0.92rem; white-space: nowrap; }
        .topbar-container { padding-left: 0.5rem; padding-right: 0.5rem; }
        .topbar-email a { display: inline-block; overflow: hidden; text-overflow: ellipsis; max-width: 220px; vertical-align: middle; }
    }
    /* Ensure contact band sits flush right between 765px and 992px */
    @media (min-width: 765px) and (max-width: 992px) {
        .topbar-container { position: relative; }
        .topbar-container .row { min-height: 48px; }
        .top-shape { position: absolute !important; right: 0; top: 0; padding-left: 0.5rem; padding-right: 0.5rem; }
        .top-shape p, .top-shape a { white-space: nowrap; font-size: 0.93rem; }
    }

    .navbar-nav .nav-link {
            position: relative;
            transition: color 0.2s;
        }
        .navbar-nav .nav-link::after {
            content: "";
            display: block;
            position: absolute;
            left: 0;
            bottom: 10px;
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
        </style>
        <style>
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
        .footer-link:hover { color: white; transform: translateX(5px); }
        .social-btn { width: 40px; height: 40px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.1); color: white; transition: all .3s; margin-right:10px; }
        .social-btn:hover { background: var(--primary-color); transform: translateY(-3px); }
        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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

     <!-- Vertical Download Catalog Button Start -->
    <!-- <a href="/path/to/catalog.pdf" class="vertical-download-catalog" title="Download Catalog" target="_blank">
        DOWNLOAD CATALOG
    </a> -->
    <!-- Vertical Download Catalog Button End -->

    <!-- Topbar Start -->
    <div class="container-fluid bg-light ps-3 pe-0 py-0 topbar-container">
        <div class="row gx-0">
            <div class="col-md-6 text-start mb-2 mb-lg-0 d-none d-lg-block">
                                 <div class="d-inline-flex align-items-center">
                                    <small class="py-2 topbar-hours"><i class="far fa-clock text-danger me-2"></i>Mon - Sat : 9.00 am - 8.00 pm | <span style="text-transform: uppercase; font-weight: 550;">24*7 Emergency Support Available</span></small>
                                </div>
            </div>
            <div class="col-md-6 text-end">
                <div class="position-relative d-inline-flex align-items-center bg-danger text-white top-shape px-5">
                    <div class="me-3 pe-3 border-end py-2 topbar-email">
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
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3 py-4 sticky-top flex-nowrap align-items-center justify-content-between" style="z-index:1030;">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/images/logo/logo-noBg.png" alt="Ananya Sales & Service Logo" style="height: 50px; width: auto; margin-right: 10px;">
            <span class="fw-bold text-danger">Ananya Sales & Service</span>
        </a>
        <button class="navbar-toggler border-0" type="button" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars-staggered fs-3 text-danger" style="color: #000;"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto py-0">
                <a href="index.php" class="nav-item nav-link active">Home</a>
                <a href="index.php#about" class="nav-item nav-link">About</a>
                <a href="index.php#services" class="nav-item nav-link">Services</a>
                <a href="products.php" class="nav-item nav-link">Products</a>
                <a href="index.php#contact" class="nav-item nav-link">Contact</a>
            </div>
            <div class="d-flex align-items-center ms-lg-4">
                <a href="auth/login.php" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Portal Login
                </a>
            </div>
        </div>
    </nav>
    <!-- Navbar End -->

    <!-- Slide-in Nav Overlay -->
    <div id="nav-overlay" class="nav-overlay" aria-hidden="true">
        <div class="nav-overlay-content">
            <div class="nav-overlay-header" style="display:flex; justify-content:flex-end;">
                <button id="nav-overlay-close" class="nav-overlay-close" aria-label="Close">&times;</button>
            </div>
            <nav class="nav-overlay-links">
                <a href="index.php">Home</a>
                <a href="index.php#about">About Us</a>
                <a href="products.php">Products</a>
                <a href="index.php#services">Services</a>
                <a href="index.php#contact">Contact Us</a>
            </nav>

            <div class="nav-overlay-portal">
                <a href="auth/login.php" class="btn btn-outline-danger portal-btn d-inline-flex align-items-center justify-content-center"><i class="bi bi-box-arrow-in-right me-2"></i> Portal Login</a>
            </div>

            <div class="nav-overlay-emergency">
                <h4>Emergency Contacts</h4>
                <p><strong>Phone:</strong> +91 81042 93994</p>
            </div>
        </div>
    </div>

    <style>
        .nav-overlay { position: fixed; inset:0; transform: translateX(100%); transition: transform .35s ease-in-out; background: rgba(0,0,0,.25); z-index:2000; display:flex; justify-content:flex-end; backdrop-filter: blur(2px);} 
        .nav-overlay.open { transform: translateX(0); }
        .nav-overlay-content { position: relative; width:100%; max-width:360px; height:100%; background:#fff; padding:16px 18px; display:flex; flex-direction:column; box-shadow: -20px 0 40px rgba(0,0,0,0.15); overflow-y:auto; -webkit-overflow-scrolling:touch; } 
        .nav-overlay-close { position:relative; background:#fff; border:1px solid rgba(0,0,0,0.04); font-size:26px; cursor:pointer; width:44px; height:44px; display:inline-flex; align-items:center; justify-content:center; color:#222; border-radius:8px; box-shadow:0 6px 14px rgba(0,0,0,0.08); margin:4px 0; }
        .nav-overlay-close:hover { background: #fafafa; }
        .nav-overlay-header { padding-bottom:2px; }
        .nav-overlay-links { margin-top:18px; display:flex; flex-direction:column; gap:6px; }
        .nav-overlay-links a { font-size:1.05rem; padding:10px 8px; color:#212529; text-decoration:none; font-weight:700; border-radius:0; position:relative; display:block; transition: color .18s ease; }
        .nav-overlay-links a::after { content: ""; position: absolute; left: 0; bottom: 8px; width: 100%; height: 3px; background: #e30613; transform: scaleX(0); transform-origin: left; transition: transform .22s ease; }
        .nav-overlay-links a:hover::after, .nav-overlay-links a.active::after { transform: scaleX(1); }
        .nav-overlay-links a:hover { color: #e30613; background: transparent; }
        .portal-btn { display:inline-block; padding:10px 16px; border-radius:8px; text-decoration:none; font-weight:700; width:160px; }
        .portal-btn i { font-size:1.05rem; }
        .nav-overlay-emergency { margin-top:auto; padding-top:18px; border-top:1px solid #eee; color:#444; font-size:.95rem; }
        @media (max-width:768px){ .nav-overlay-content { max-width:100%; padding:20px; } .nav-overlay-links a{ font-size:1.1rem; padding:16px 12px; } .portal-btn { width:100%; } }
        @media (min-width:1200px){ .nav-overlay-content { max-width:420px; } }
        @media (max-width:480px){ .nav-overlay-links a{ font-size:1.05rem; } }
    </style>

    <script>
        (function(){
            let overlay = document.getElementById('nav-overlay');
            const closeBtn = document.getElementById('nav-overlay-close');
            const navbar = document.querySelector('.navbar');
            const toggler = document.querySelector('.navbar-toggler');

            // Ensure overlay is a direct child of <body> to avoid stacking-context issues
            if (overlay && overlay.parentElement !== document.body) {
                document.body.appendChild(overlay);
                overlay = document.getElementById('nav-overlay');
            }

            if (overlay) {
                overlay.style.zIndex = '99999';
                overlay.style.background = 'rgba(0,0,0,0.45)';
            }

            function getScrollbarWidth(){ return window.innerWidth - document.documentElement.clientWidth; }
            function openOverlay(){
                if(!overlay) return;
                // avoid layout shift when removing scrollbar by adding equivalent padding
                const sb = getScrollbarWidth();
                if (sb > 0) document.body.style.paddingRight = sb + 'px';
                overlay.classList.add('open');
                overlay.setAttribute('aria-hidden','false');
                document.body.style.overflow='hidden';
                // Ensure any Bootstrap collapse (if present) is hidden to avoid duplicate navs
                try {
                    var navbarCollapse = document.getElementById('navbarCollapse');
                    if (navbarCollapse) {
                        var bsCollapse = bootstrap.Collapse.getOrCreateInstance(navbarCollapse);
                        bsCollapse.hide();
                    }
                } catch (err) {
                    // ignore if bootstrap API not available
                }
            }
            function closeOverlay(){
                if(!overlay) return;
                overlay.classList.remove('open');
                overlay.setAttribute('aria-hidden','true');
                document.body.style.overflow='';
                document.body.style.paddingRight = '';
                // Also ensure Bootstrap collapse is closed and toggler aria state reset
                try {
                    var navbarCollapse = document.getElementById('navbarCollapse');
                    if (navbarCollapse) {
                        var bsCollapse = bootstrap.Collapse.getOrCreateInstance(navbarCollapse);
                        bsCollapse.hide();
                    }
                    if (toggler) toggler.setAttribute('aria-expanded','false');
                } catch (err) {
                    // ignore
                }
            }

            // Open when clicking navbar background (but not links/buttons)
            if(navbar) navbar.addEventListener('click', function(e){ if(e.target.closest('a')||e.target.closest('button')||e.target.closest('input')) return; openOverlay(); });

            // Also open when toggler (hamburger) is clicked — common mobile pattern
            if(toggler) toggler.addEventListener('click', function(e){ e.preventDefault(); openOverlay(); });

            if(closeBtn) closeBtn.addEventListener('click', closeOverlay);
            if(overlay) overlay.addEventListener('click', function(e){ if(e.target===overlay) closeOverlay(); });
            document.addEventListener('keydown', function(e){ if(e.key==='Escape'&&overlay&&overlay.classList.contains('open')) closeOverlay(); });
        })();
    </script>


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
            <!-- <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button> -->
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
                    <a href="https://wa.me/918104293994" class="btn btn-danger py-3 px-5 mt-4 wow zoomIn" data-wow-delay="0.6s" target="_blank">Contact Now</a>
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

    <!-- Products Start -->
    <div class="container-fluid py-5 bg-light" id="products">
        <div class="container">
            <div class="section-title mb-4 text-center">
                <h5 class="position-relative d-inline-block text-primary text-uppercase mb-2" style="font-size: 22px; letter-spacing: 0.5px;">OUR PRODUCTS</h5>
                <span class="d-inline-block align-middle mx-2" style="border-top: 3px solid #e30613; width: 45px; position: relative; top: -8px;"></span>
                <span class="d-inline-block align-middle mx-1" style="border-top: 3px solid #a9030d; width: 15px; position: relative; top: -8px;"></span>
                <h1 class="display-5 mb-0 mt-2" style="font-weight:700;">Featured Blood Bank Equipment</h1>
            </div>
            <div class="row justify-content-center g-4">
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="https://imgs.search.brave.com/GROsIjxqupcRPvHvZAGmOoOOhatzInuALqpGQnLI5A0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly81Lmlt/aW1nLmNvbS9kYXRh/NS9TRUxMRVIvRGVm/YXVsdC8yMDIyLzUv/SEQvSkMvUFcvMjEy/NDQxMi9yZW1pLWJy/LTEyMC1ibG9vZC1i/YW5rLXJlZnJpZ2Vy/YXRvci0yNTB4MjUw/LmpwZw" class="card-img-top" alt="Blood Bank Refrigerator">
                        <div class="card-body">
                            <h5 class="card-title">Blood Bank Refrigerator</h5>
                            <p class="card-text">High-precision temperature control for safe blood storage. Available in multiple capacities.</p>
                            <button class="btn btn-outline-danger mt-2 w-100 get-quote-btn" data-product="Blood Bank Refrigerator">Get Quote</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/plasma-freezer.jpg" class="card-img-top" alt="Plasma Freezer">
                        <div class="card-body">
                            <h5 class="card-title">Plasma Freezer</h5>
                            <p class="card-text">Rapid freezing and stable storage for plasma and blood components. NABH compliant.</p>
                            <button class="btn btn-outline-danger mt-2 w-100 get-quote-btn" data-product="Plasma Freezer">Get Quote</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/platelet-incubator.jpg" class="card-img-top" alt="Platelet Incubator">
                        <div class="card-body">
                            <h5 class="card-title">Platelet Incubator</h5>
                            <p class="card-text">Ensures optimal temperature and agitation for platelet storage. Reliable and energy efficient.</p>
                            <button class="btn btn-outline-danger mt-2 w-100 get-quote-btn" data-product="Platelet Incubator">Get Quote</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="products.php" class="btn btn-danger px-4 py-2 fw-semibold">View All Products</a>
            </div>
        </div>
    </div>

        <!-- Quote Modal -->
        <div class="modal fade" id="quoteModal" tabindex="-1" aria-labelledby="quoteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="quoteForm" method="post">
                        <div class="modal-header">
                            <h5 class="modal-title" id="quoteModalLabel">Request a Quote</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="quoteProduct" class="form-label">Product</label>
                                <input type="text" class="form-control" id="quoteProduct" name="quote_product" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="quoteName" class="form-label">Your Name</label>
                                <input type="text" class="form-control" id="quoteName" name="quote_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="quotePhone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="quotePhone" name="quote_phone" pattern="[0-9]{10}" maxlength="10" minlength="10" inputmode="numeric" required>
                
                            </div>
                            <div id="quoteMsg" class="alert d-none"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Products End -->


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

    <!-- FAQ Start -->
    <div class="container-fluid py-5 bg-white" id="faq">
        <div class="container">
            <div class="section-title mb-4 text-center">
                <h5 class="position-relative d-inline-block text-primary text-uppercase mb-2" style="font-size: 22px; letter-spacing: 0.5px;">FREQUENTLY ASKED QUESTIONS</h5>
                <span class="d-inline-block align-middle mx-2" style="border-top: 3px solid #e30613; width: 45px; position: relative; top: -8px;"></span>
                <span class="d-inline-block align-middle mx-1" style="border-top: 3px solid #a9030d; width: 15px; position: relative; top: -8px;"></span>
                <h1 class="display-5 mb-0 mt-2" style="font-weight:700;">FAQ</h1>
            </div>
                <div class="faq-accordion accordion accordion-flush" id="faqAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse1" aria-expanded="false" aria-controls="faqCollapse1">
                            What is included in a blood bank equipment AMC?
                        </button>
                    </h2>
                    <div id="faqCollapse1" class="accordion-collapse collapse" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">An Annual Maintenance Contract (AMC) generally includes preventive maintenance visits, calibration, performance validation, breakdown support, and replacement of minor components as required.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse2" aria-expanded="false" aria-controls="faqCollapse2">
                            How can I request a quote or service?
                        </button>
                    </h2>
                    <div id="faqCollapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">You can use the quote request modal on our homepage or contact us directly via phone or email for personalized assistance.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse3" aria-expanded="false" aria-controls="faqCollapse3">
                            Do you provide support outside of India?
                        </button>
                    </h2>
                    <div id="faqCollapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">Currently, our services are focused within India. For international inquiries, please contact us directly.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse4" aria-expanded="false" aria-controls="faqCollapse4">
                            What is AMC/CMC?
                        </button>
                    </h2>
                    <div id="faqCollapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">AMC stands for Annual Maintenance Contract and CMC stands for Comprehensive Maintenance Contract. Both ensure your equipment is regularly serviced and maintained.</div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="faq5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse5" aria-expanded="false" aria-controls="faqCollapse5">
                            How quickly can you respond to service requests?
                        </button>
                    </h2>
                    <div id="faqCollapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">We strive to respond within 24 hours for urgent requests. For regular maintenance, appointments are scheduled as per your convenience.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FAQ End -->
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
                                            <h2 style="margin-bottom: 0.5rem;">Contact Our Equipment Experts</h2>
                                            <p class="text-muted mb-3" style="font-size: 0.95rem; line-height: 1.5;">For blood bank equipment supply, spare parts, AMC services, or emergency technical support, our team is ready to assist.</p>
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
                <div class="col-xl-4 col-lg-6 wow slideInUp pt-5" data-wow-delay="0.3s">

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
                                    <option value="equipment_inquiry" <?= (($_POST['contact_subject'] ?? '') === 'equipment_inquiry') ? 'selected' : '' ?>>Blood Bank Equipment Inquiry</option>
                                    <option value="spare_parts" <?= (($_POST['contact_subject'] ?? '') === 'spare_parts') ? 'selected' : '' ?>>Spare Parts Request</option>
                                    <option value="amc_contract" <?= (($_POST['contact_subject'] ?? '') === 'amc_contract') ? 'selected' : '' ?>>AMC / Maintenance Contract</option>
                                    <option value="installation_calibration" <?= (($_POST['contact_subject'] ?? '') === 'installation_calibration') ? 'selected' : '' ?>>Installation & Calibration</option>
                                    <option value="emergency_service" <?= (($_POST['contact_subject'] ?? '') === 'emergency_service') ? 'selected' : '' ?>>Emergency Service Support</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <textarea name="contact_message" class="form-control border-0 bg-light px-4 py-3" rows="5" placeholder="Message" required><?= htmlspecialchars($_POST['contact_message'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-danger w-100 py-3" type="submit" name="contact_submit">Submit Inquiry</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-xl-4 col-lg-12 wow slideInUp" data-wow-delay="0.6s">
                    <iframe class="position-relative rounded w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3771.2430975912953!2d73.09966487400607!3d19.053046452695074!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7e9d872fef37d%3A0x489ed8db73deba28!2sGold%20Crest!5e0!3m2!1sen!2sin!4v1772044776838!5m2!1sen!2sin"
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
                    <p class="mt-3">Leading supplier and service provider of blood bank equipment, spare parts, calibration, and AMC solutions for hospitals and laboratories across India.</p>
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
                    <a href="index.php#home" class="footer-link">Home</a>
                    <a href="index.php#about" class="footer-link">About Us</a>
                    <a href="index.php#services" class="footer-link">Services</a>
                    <a href="products.php" class="footer-link">Products</a>
                    <a href="index.php#testimonials" class="footer-link">Testimonials</a>
                </div>
                <!-- Services -->
                <div class="col-lg-3 col-md-6 footer-column">
                    <h5>Our Services</h5>
                    <a href="#" class="footer-link"><i class="bi bi-chevron-double-right"></i> Blood Storage</a>
                    <a href="#" class="footer-link"><i class="bi bi-chevron-double-right"></i> Centrifuge Calibration</a>
                    <a href="#" class="footer-link"><i class="bi bi-chevron-double-right"></i> AMC Contracts</a>
                    <a href="#" class="footer-link"><i class="bi bi-chevron-double-right"></i> Emergency Repairs</a>
                    <a href="#" class="footer-link"><i class="bi bi-chevron-double-right"></i> Equipments</a>
                </div>
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6 footer-column">
                    <h5>Contact Us</h5>
                    <div class="d-flex mb-3">
                        <i class="bi bi-geo-alt-fill me-3 mt-1" style="color: #FFFFFF;"></i>
                        <span>Flat No. 702, The Gold Crest Society, Navde Colony, Navde, Navi Mumbai - 410208</span>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="bi bi-telephone-fill  me-3 mt-1"  style="color: #FFFFFF;"></i>
                        <div>
                            <div>+91 81042 93994</div>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="bi bi-envelope-fill me-3 mt-1"  style="color: #FFFFFF;"></i>
                        <span>support@ananyasales.in</span>
                    </div>
                    <div class="d-flex">
                        <i class="bi bi-clock-fill me-3 mt-1"  style="color: #FFFFFF;"></i>
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
                        <a href="https://www.knowsumit.in" class="footer-link">Developed by Sumit Kumar</a>
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
        <script>
        // Quote Modal logic
        document.addEventListener('DOMContentLoaded', function() {
            var quoteModal = new bootstrap.Modal(document.getElementById('quoteModal'));
            var quoteProductInput = document.getElementById('quoteProduct');
            var quoteForm = document.getElementById('quoteForm');
            var quoteMsg = document.getElementById('quoteMsg');
            var quotePhone = document.getElementById('quotePhone');
            document.querySelectorAll('.get-quote-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    quoteProductInput.value = btn.getAttribute('data-product');
                    quoteMsg.classList.add('d-none');
                    quoteForm.reset();
                    quoteProductInput.value = btn.getAttribute('data-product');
                    quoteModal.show();
                });
            });
            quoteForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // Enforce 10-digit phone number
                var phone = quotePhone.value.trim();
                if (!/^\d{10}$/.test(phone)) {
                    quoteMsg.classList.remove('d-none', 'alert-success');
                    quoteMsg.classList.add('alert-danger');
                    quoteMsg.textContent = 'Please enter a valid 10-digit phone number.';
                    quotePhone.focus();
                    return;
                }
                var formData = new FormData(quoteForm);
                // Show loading spinner overlay and disable screen
                var overlay = document.createElement('div');
                overlay.id = 'quoteSpinnerOverlay';
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100vw';
                overlay.style.height = '100vh';
                overlay.style.background = 'rgba(0,0,0,0.25)';
                overlay.style.zIndex = '9999';
                overlay.style.display = 'flex';
                overlay.style.alignItems = 'center';
                overlay.style.justifyContent = 'center';
                overlay.innerHTML = '<div class="spinner-border text-danger" role="status" style="width:3rem;height:3rem;"><span class="visually-hidden">Sending...</span></div>';
                document.body.appendChild(overlay);
                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Hide overlay
                    var overlayElem = document.getElementById('quoteSpinnerOverlay');
                    if (overlayElem) overlayElem.remove();
                    quoteMsg.classList.remove('d-none', 'alert-danger', 'alert-success');
                    if (data.success) {
                        quoteMsg.classList.add('alert-success');
                        quoteMsg.textContent = data.message;
                        setTimeout(() => { quoteModal.hide(); }, 1800);
                    } else {
                        quoteMsg.classList.add('alert-danger');
                        quoteMsg.textContent = data.message;
                    }
                })
                .catch(() => {
                    var overlayElem = document.getElementById('quoteSpinnerOverlay');
                    if (overlayElem) overlayElem.remove();
                    quoteMsg.classList.remove('d-none', 'alert-success');
                    quoteMsg.classList.add('alert-danger');
                    quoteMsg.textContent = 'Something went wrong. Please try again.';
                });
            });
        });
        </script>
