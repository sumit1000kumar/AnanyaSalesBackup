<?php
// products.php - Product listing page for Ananya Sales & Service
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Ananya Sales & Service | Products</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="assets/images/favicon.ico" rel="icon">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/lib/animate.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .testimonial-section { background: #fff; padding: 60px 0; }
        .carousel-item { min-height: 320px; }
        .testimonial-card { background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(0,0,0,0.07); padding: 32px 24px 24px 24px; max-width: 370px; min-width: 320px; margin: 0 auto; display: flex; flex-direction: column; align-items: flex-start; transition: box-shadow 0.2s; position: relative; }
            /* Vertical Download Catalog Button */
            .vertical-download-catalog {
                position: fixed;
                left: 0;
                top: 18px;
                z-index: 9999;
                background: #111;
                color: #fff;
                border-radius: 0 18px 18px 0;
                box-shadow: 0 2px 8px rgba(0,0,0,0.18);
                font-size: 0.95rem;
                font-weight: 700;
                letter-spacing: 0.12em;
                width: 56px;
                min-height: 90px;
                padding: 8px 0;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: flex-start;
                cursor: pointer;
                transition: background 0.2s, box-shadow 0.2s, width 0.2s;
                border: none;
                box-sizing: border-box;
                text-align: center;
                overflow: hidden;
                gap: 10px;
            }

            .vertical-download-catalog i {
                font-size: 1.3em;
                margin-bottom: 6px;
            }

            .vertical-download-catalog span {
                display: block;
                line-height: 1.1;
                font-size: 1em;
            }

            .vertical-download-catalog .catalog-vertical {
                writing-mode: vertical-rl;
                text-orientation: mixed;
                letter-spacing: 0.08em;
                font-size: 1em;
                font-weight: 700;
                margin-top: 2px;
                margin-bottom: 2px;
            }

            .vertical-download-catalog:hover {
                background: #e30613;
                color: #fff;
                box-shadow: 0 8px 24px rgba(227,6,19,0.22);
            }

            @media (max-width: 900px) {
                .vertical-download-catalog {
                    top: 12px;
                    width: 44px;
                    min-height: 70px;
                    font-size: 0.85rem;
                    border-radius: 0 0 12px 12px;
                }
            }

            @media (max-width: 600px) {
                .vertical-download-catalog {
                    top: 8px;
                    width: 32px;
                    min-height: 48px;
                    font-size: 0.7rem;
                    border-radius: 0 0 8px 8px;
                    padding: 4px 0;
                }
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

    </style>
    <style>
    @media (max-width: 575.98px) {
        .modal-dialog {
            margin: 1.2rem !important;
            max-width: 98vw;
        }
        .modal-content {
            border-radius: 0.7rem;
        }
    }
    @media (max-width: 400px) {
        .modal-dialog {
            margin: 0.3rem !important;
        }
    }
    </style>
</head>
<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-grow text-primary m-1" role="status"><span class="sr-only">Loading...</span></div>
        <div class="spinner-grow text-dark m-1" role="status"><span class="sr-only">Loading...</span></div>
        <div class="spinner-grow text-secondary m-1" role="status"><span class="sr-only">Loading...</span></div>
    </div>
    <!-- Spinner End -->

    <!-- Vertical Download Catalog Button Start -->
    <!-- <a href="assets/images/catalog.pdf" class="vertical-download-catalog" title="Download Catalog" target="_blank" style="text-decoration: none !important;">
           <i class="fa fa-download"></i>
           <span>Download</span>
           <span class="catalog-vertical">Catalog</span>
    </a> -->
    <!-- Vertical Download Catalog Button End -->

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
                <a href="index.php" class="nav-item nav-link">Home</a>
                <a href="index.php#about" class="nav-item nav-link">About</a>
                <a href="index.php#services" class="nav-item nav-link">Services</a>
                <a href="products.php" class="nav-item nav-link active">Products</a>
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

    <!-- Hero Section Start -->
    <section class="hero-section position-relative hero-bg-img" style="color: #fff; padding: 2.5rem 0 2rem; overflow: hidden;">
        <div class="container position-relative z-2">
            <div class="row align-items-center">
                <div class="col-12">
                    <h6 class="mb-2" style="font-weight:600; letter-spacing:1px; opacity:0.85;">OUR PRODUCTS</h6>
                    <h1 class="display-4 fw-bold mb-3" style="font-size:2.2rem;">Blood Bank Equipment & Service Solutions in India</h1>
                    <p class="mb-4" style="max-width:600px; opacity:0.92;">Reliable blood bank refrigerators, plasma freezers, platelet incubators, centrifuges, and component storage systems for hospitals and diagnostic laboratories.</p>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0" style="--bs-breadcrumb-divider: '›';">
                            <li class="breadcrumb-item"><a href="index.php" class="text-white text-decoration-none"><i class="fa fa-home me-1"></i>Home</a></li>
                            <li class="breadcrumb-item active text-white-50" aria-current="page">Products</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div style="position:absolute;inset:0;z-index:1;pointer-events:none;">
            <img src="assets/images/hero-bg-map.png" alt="" style="width:100%;height:100%;object-fit:cover;opacity:0.18;" loading="lazy">
        </div>
    </section>
    <!-- Hero Section End -->
     
    <!-- Products Start -->
    <div class="container-fluid py-5 bg-light" id="products">
        <div class="container">
            <!-- <div class="section-title mb-4 text-center">
                <h5 class="position-relative d-inline-block text-primary text-uppercase mb-2" style="font-size: 22px; letter-spacing: 0.5px;">OUR PRODUCTS</h5>
                <span class="d-inline-block align-middle mx-2" style="border-top: 3px solid #e30613; width: 45px; position: relative; top: -8px;"></span>
                <span class="d-inline-block align-middle mx-1" style="border-top: 3px solid #a9030d; width: 15px; position: relative; top: -8px;"></span>
                <h1 class="display-5 mb-0 mt-2" style="font-weight:700;">Featured Blood Bank Equipment</h1>
            </div> -->
            <div class="row justify-content-center g-4">
                <!-- Product Boxes Start -->
                <!-- Blood Collection Monitor -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Blood-Collection-Monitor.jpg" class="card-img-top product-img-fixed" alt="Blood Collection Monitor" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Blood Collection Monitor</h5>
                            <p class="card-text">Compact, microprocessor-based device for smooth and gentle blood mixing. 3D mixing prevents clot formation.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Blood Collection Monitor">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Blood Collection Monitor">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blood Bag Tube Sealer -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Blood-Bag-Tube-Sealer.jpg" class="card-img-top product-img-fixed" alt="Blood Bag Tube Sealer" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Blood Bag Tube Sealer</h5>
                            <p class="card-text">Seals blood bag tubes quickly and safely using high-frequency dielectric heating.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Blood Bag Tube Sealer">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Blood Bag Tube Sealer">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blood Bag Tube Stripper -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Blood-Bag-Tube-Stripper.jpg" class="card-img-top product-img-fixed" alt="Blood Bag Tube Stripper" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Blood Bag Tube Stripper</h5>
                            <p class="card-text">Ergonomic, stainless steel tool for safe and efficient blood segment preparation.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Blood Bag Tube Stripper">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Blood Bag Tube Stripper">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Single & Double Pan Balance -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Single-Double-Pan-Balance.jpg" class="card-img-top product-img-fixed" alt="Single & Double Pan Balance" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Single & Double Pan Balance</h5>
                            <p class="card-text">Dual pan balance for accurate weighing before centrifuging blood bags. Digital display and high-accuracy sensors.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Single & Double Pan Balance">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Single & Double Pan Balance">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Plasma Extractor -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Plasma-Extractor.jpg" class="card-img-top product-img-fixed" alt="Plasma Extractor" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Plasma Extractor</h5>
                            <p class="card-text">Manual device for extracting plasma from centrifuged blood bags. Spring-loaded acrylic plate ensures uniform pressure.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Plasma Extractor">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Plasma Extractor">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Automatic Donor Couch -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Automatic-Donor-Couch.jpg" class="card-img-top product-img-fixed" alt="Automatic Donor Couch" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Automatic Donor Couch</h5>
                            <p class="card-text">Dual actuator couch for comfortable, safe blood donation. Adjustable positions and soft upholstery.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Automatic Donor Couch">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Automatic Donor Couch">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Platelet Incubator -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Platelet-Incubator.jpg" class="card-img-top product-img-fixed" alt="Platelet Incubator" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Platelet Incubator</h5>
                            <p class="card-text">Maintains stable 22°C for platelet storage. Microprocessor temperature control with alarms.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Platelet Incubator">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Platelet Incubator">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Platelet Agitator -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Platelet-Agitator.jpg" class="card-img-top product-img-fixed" alt="Platelet Agitator" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Platelet Agitator</h5>
                            <p class="card-text">Ensures freshness of platelet concentrates with gentle, continuous agitation. Heavy-duty motor, adjustable shelves.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Platelet Agitator">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Platelet Agitator">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Plasma Thawing Bath -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Plasma-Thawing-Bath.jpg" class="card-img-top product-img-fixed" alt="Plasma Thawing Bath" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Plasma Thawing Bath</h5>
                            <p class="card-text">Quick, accurate thawing of plasma at 37°C. Microprocessor control, audio/visual alarms, and water recirculation.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Plasma Thawing Bath">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Plasma Thawing Bath">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Cryoprecipitate Bath -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Cryoprecipitate-Bath.jpg" class="card-img-top product-img-fixed" alt="Cryoprecipitate Bath" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Cryoprecipitate Bath</h5>
                            <p class="card-text">Thaws cryo and plasma at 4°C to 37°C with high accuracy. Micro-controlled temperature, water recirculation.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Cryoprecipitate Bath">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Cryoprecipitate Bath">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Blood Bank Refrigerator -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Blood-Bank-Refrigerator.webp" class="card-img-top product-img-fixed" alt="Blood Bank Refrigerator" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Blood Bank Refrigerator</h5>
                            <p class="card-text">Designed for safe storage of blood, red cells, and biological products at 2°C–6°C. Microprocessor temperature control, alarms, and chart recorder.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Blood Bank Refrigerator">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Blood Bank Refrigerator">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Cryoprecipitate Freezer -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Cryoprecipitate-Freezer.jpg" class="card-img-top product-img-fixed" alt="Cryoprecipitate Freezer" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Cryoprecipitate Freezer</h5>
                            <p class="card-text">Maintains -50°C to -80°C for cryoprecipitate and plasma preservation. Microprocessor control, alarms, and thick insulation.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Cryoprecipitate Freezer">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Cryoprecipitate Freezer">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Plasma Freezer -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Plasma-Freezer.jpg" class="card-img-top product-img-fixed" alt="Plasma Freezer" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Plasma Freezer</h5>
                            <p class="card-text">Maintains -30°C to -40°C for plasma and sample preservation. Microprocessor control, alarms, and energy-efficient insulation.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Plasma Freezer">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Plasma Freezer">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Refrigerated Centrifuge -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Refrigerated-Centrifuge.jpg" class="card-img-top product-img-fixed" alt="Refrigerated Centrifuge" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Refrigerated Centrifuge</h5>
                            <p class="card-text">Separates blood components with microprocessor control. Digital display for RPM, RCF, temperature, and time.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Refrigerated Centrifuge">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Refrigerated Centrifuge">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Laboratory Incubator -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Laboratory-Incubator.jpg" class="card-img-top product-img-fixed" alt="Laboratory Incubator" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Laboratory Incubator</h5>
                            <p class="card-text">Provides controlled temperature incubation (5°C–60°C). Microprocessor control, alarms, stainless steel chamber, and adjustable shelves.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Laboratory Incubator">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Laboratory Incubator">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Hot Air Oven -->
                <div class="col-md-4 col-sm-6">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/products/Hot-Air-Oven.jpg" class="card-img-top product-img-fixed" alt="Hot Air Oven" onerror="this.onerror=null;this.src='assets/images/products/placeholder.png';">
                        <div class="card-body">
                            <h5 class="card-title">Hot Air Oven</h5>
                            <p class="card-text">For sterilization and drying (ambient +5°C to 250°C). Microprocessor control, stainless steel chamber, and safety cut-off.</p>
                            <div class="d-flex gap-2 mt-2">
                                <button class="btn btn-outline-secondary w-50 view-details-btn" data-product="Hot Air Oven">View Details</button>
                                <button class="btn btn-outline-danger w-50 get-quote-btn" data-product="Hot Air Oven">Get Quote</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Product Boxes End -->
            </div>
            <!-- <div class="text-center mt-4">
                <a href="products.php" class="btn btn-danger px-4 py-2 fw-semibold">View All Products</a>
            </div> -->
        </div>
    </div>

    <!-- Product Details Modal -->
    <div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="productDetailsContent">
                    <!-- Product info will be injected here -->
                </div>
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
                    <a href="#home" class="footer-link">Home</a>
                    <a href="#about" class="footer-link">About Us</a>
                    <a href="#services" class="footer-link">Services</a>
                    <a href="#products" class="footer-link">Products</a>
                    <a href="#testimonials" class="footer-link">Testimonials</a>
                </div>
                <!-- Services -->
                <div class="col-lg-3 col-md-6 footer-column">
                    <h5>Our Services</h5>
                    <a href="#" class="footer-link"><i class="bi bi-chevron-double-right"></i> Plasma Freezers</a>
                    <a href="#" class="footer-link"><i class="bi bi-chevron-double-right"></i> Blood Storage</a>
                    <a href="#" class="footer-link"><i class="bi bi-chevron-double-right"></i> Centrifuge Calibration</a>
                    <a href="#" class="footer-link"><i class="bi bi-chevron-double-right"></i> AMC Contracts</a>
                    <a href="#" class="footer-link"><i class="bi bi-chevron-double-right"></i> Emergency Repairs</a>
                </div>
                <!-- Contact Info -->
                <div class="col-lg-3 col-md-6 footer-column">
                    <h5>Contact Us</h5>
                    <div class="d-flex mb-3">
                        <i class="bi bi-geo-alt-fill text-primary me-3 mt-1"></i>
                        <span>Flat No. 702, The Gold Crest Society, Navde Colony, Navde, Navi Mumbai - 410208</span>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="bi bi-telephone-fill text-primary me-3 mt-1"></i>
                        <div>
                            <!-- <div>+91 98765 43210</div> -->
                            <div>+91 81042 93994</div>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <i class="bi bi-envelope-fill text-primary me-3 mt-1"></i>
                        <span>support@ananyasales.in</span>
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
                var phone = quotePhone.value.trim();
                if (!/^\d{10}$/.test(phone)) {
                    quoteMsg.classList.remove('d-none', 'alert-success');
                    quoteMsg.classList.add('alert-danger');
                    quoteMsg.textContent = 'Please enter a valid 10-digit phone number.';
                    quotePhone.focus();
                    return;
                }
                var formData = new FormData(quoteForm);
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
    <!-- Floating WhatsApp & Call Icons -->
    <style>
        .floating-contact {
            position: fixed;
            right: 22px;
            bottom: 22px;
            z-index: 1050;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .floating-contact a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            background: #25d366;
            color: #fff;
            font-size: 2rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.18);
            transition: transform 0.18s, box-shadow 0.18s;
            text-decoration: none;
        }
        .floating-contact a.call {
            background: #e30613;
        }
        .floating-contact a:hover {
            transform: scale(1.08) translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.22);
        }
        @media (max-width: 600px) {
            .floating-contact { right: 10px; bottom: 10px; }
            .floating-contact a { width: 46px; height: 46px; font-size: 1.5rem; }
        }
    </style>
    <style>
    .hero-bg-img {
        background: linear-gradient(120deg, rgba(179,18,23,0.70) 0%, rgba(229,45,39,0.55) 100%), url('https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80') center center/cover no-repeat;
        position: relative;
    }
    </style>
    <style>
    .product-img-fixed {
        height: 220px;
        object-fit: cover;
        width: 100%;
        background: #f8f8f8;
        border-radius: 0.5rem 0.5rem 0 0;
        transition: none;
        display: block;
    }
    @media (max-width: 991.98px) {
        .col-md-4.col-sm-6 { flex: 0 0 50%; max-width: 50%; }
    }
    @media (max-width: 767.98px) {
        .col-md-4.col-sm-6 { flex: 0 0 100%; max-width: 100%; }
        .card.h-100 { min-height: unset; }
        .product-img-fixed { height: 180px; }
    }
    @media (max-width: 575.98px) {
        .product-img-fixed { height: 140px; }
        .card-body { padding: 1rem; }
        .card-title { font-size: 1.1rem; }
        .card-text { font-size: 0.95rem; }
        .d-flex.gap-2.mt-2 { flex-direction: column; gap: 0.5rem !important; }
        .btn.w-50 { width: 100% !important; }
    }
    </style>
    <div class="floating-contact">
        <a href="https://wa.me/918104293994" target="_blank" rel="noopener" title="Chat on WhatsApp"><i class="fab fa-whatsapp"></i></a>
        <a href="tel:+918104293994" class="call" title="Call Now"><i class="fa fa-phone"></i></a>
    </div>
<script>
// Product info data (fill in as needed)
const productDetailsData = {
    'Blood Collection Monitor': `
        <strong>Blood Collection Monitor (LED/LCD)</strong><br>
        <p>A new generation compact instrument for smooth and gentle mixing of blood. 3D mixing with anticoagulant prevents clot formation. Fully automatic, microprocessor-based, with battery backup and LED/LCD display for volume, weight, flow rate, and time.</p>
        <strong>Features:</strong>
        <ul>
            <li>Microprocessor based control system with accuracy of ±1 ml</li>
            <li>Large LED/LCD display for Set Volume, Process Volume, Weight, Flow rate, Time & status</li>
            <li>Audio and visual alarm for High flow and collection status</li>
            <li>Mixing cycle of 12–14 rpm for gentle mixing</li>
            <li>Pre-selection and target volume setting (1–999 ml)</li>
            <li>Motorized clamping, auto clamp, and pause functions</li>
            <li>Battery backup up to 24 hrs</li>
        </ul>
        <strong>Technical Specification:</strong>
        <ul>
            <li>Model No.: MBCM-04 | MBCM-05</li>
            <li>Mains Voltage: 12–18 V DC</li>
            <li>Max. Volume: Up to 999 ml</li>
            <li>Battery backup: Up to 24 hrs</li>
            <li>Max. Weight: Up to 999 gm</li>
            <li>Dimensions: 230 x 340 x 175 mm</li>
            <li>Net Weight: 4–4.5 kg</li>
            <li>Power: 220–240V, 50 Hz, Single Phase</li>
        </ul>
    `,
    'Blood Bag Tube Sealer': `
        <strong>Blood Bag Tube Sealer</strong><br>
        <p>Designed to seal blood bag tubes by high frequency dielectric heating without causing hemolysis or leakage. Achieves complete sealing in less than 1.5 seconds per tube. Compact, lightweight, and easy to use with automatic operation and LED indicators.</p>
        <strong>Features:</strong>
        <ul>
            <li>Compact & lightweight hand unit</li>
            <li>Suitable for limited workspace or shared use</li>
            <li>High-frequency power melts thermoplastic tubing in ~1.5 seconds</li>
            <li>Automatic operation when tube is placed between electrodes</li>
            <li>LED indicators for Power, Ready, Seal, and Ground</li>
            <li>Battery support (in some models)</li>
        </ul>
        <strong>Technical Specification:</strong>
        <ul>
            <li>Model No.: MBTS-04 | MBTS-05</li>
            <li>Input Voltage: 220–240 VAC</li>
            <li>Radio Frequency: 40.68 MHz</li>
            <li>Sealing Time: 1.5 sec approx.</li>
            <li>Diameter of tube: 3–6 mm</li>
            <li>Power Consumption: 100 VA</li>
            <li>Weight: 3.4–5.0 kg approx.</li>
            <li>Size: 175 x 320 x 130 mm</li>
            <li>Battery Support: NA | 160 Seals</li>
        </ul>
    `,
    'Blood Bag Tube Stripper': `
        <strong>Blood Bag Tube Stripper</strong><br>
        <p>Specifically designed to strip blood segments during collection and processing. Stainless steel, ergonomic, and easy to use with Teflon rollers for smooth stripping.</p>
        <strong>Features:</strong>
        <ul>
            <li>Safe and easy to use</li>
            <li>Anti-rust stainless steel body</li>
            <li>Spring system keeps handles open</li>
            <li>Ergonomic design for good grip</li>
            <li>Teflon rollers with nickel coating</li>
        </ul>
        <strong>Technical Specification:</strong>
        <ul>
            <li>Model No.: MBTS-01</li>
            <li>Material: Stainless steel</li>
            <li>Dimensions: 180 x 650 x 22 mm</li>
            <li>Rollers: Nylon</li>
        </ul>
    `,
    'Single & Double Pan Balance': `
        <strong>Single & Double Pan Balance</strong><br>
        <p>Micro-controlled dual pan balance for accurate weighing before centrifuging blood bags. Digital display, high-accuracy sensors, tare function, and battery backup in a durable, compact design.</p>
        <strong>Features:</strong>
        <ul>
            <li>Stainless steel pans for blood/blood products</li>
            <li>Max weight capacity: 10 kg</li>
            <li>Digital display and high accuracy sensors</li>
            <li>Compact, easy to clean, and durable</li>
            <li>Calibration and tare functions</li>
            <li>Battery backup options</li>
        </ul>
        <strong>Technical Specification:</strong>
        <ul>
            <li>Model No.: MBBS-01 | MBBS-02</li>
            <li>Weighing Capacity: 10 kg</li>
            <li>Accuracy: 0.1 gm</li>
            <li>Power: 100 VA</li>
            <li>Battery Backup: 12V 1.2Ah or 6V 4.5Ah</li>
            <li>Weight: 3.3–4.0 kg approx.</li>
            <li>Size: 305 x 245 x 120 mm</li>
        </ul>
    `,
    // ...repeat for all other products in the same format...
};

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.view-details-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const product = btn.getAttribute('data-product');
            const details = productDetailsData[product] || 'No details available.';
            document.getElementById('productDetailsModalLabel').textContent = product;
            document.getElementById('productDetailsContent').innerHTML = details;
            var modal = new bootstrap.Modal(document.getElementById('productDetailsModal'));
            modal.show();
        });
    });
});
</script>
</body>
</html>
