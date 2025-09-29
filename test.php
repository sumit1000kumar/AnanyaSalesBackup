<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ananya Sales & Service</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: #e30613;
            --secondary-color: #a9030d;
            --accent-color: #ff5964;
            --dark-text: #212529;
            --light-bg: #f8f9fa;
            --gradient-primary: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            --gradient-dark: linear-gradient(135deg, #1a1a1a, #2d2d2d);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--light-bg);
            color: var(--dark-text);
            overflow-x: hidden;
        }
        
        .brand-name {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link-custom {
            font-weight: 600;
            margin: 0 6px;
            color: var(--dark-text) !important;
            position: relative;
            padding: 10px 18px !important;
            border-radius: 50px;
            transition: all 0.3s;
            font-size: 1.05rem;
        }
        
        .nav-link-custom:hover, .nav-link-custom.active {
            color: white !important;
            background: var(--gradient-primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(227, 6, 19, 0.3);
        }
        
        .emergency-btn {
            background: var(--gradient-primary);
            border: none;
            padding: 12px 24px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(227, 6, 19, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            font-size: 1.05rem;
        }
        
        .emergency-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .emergency-btn:hover::before {
            left: 100%;
        }
        
        .emergency-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(227, 6, 19, 0.4);
        }
        
        /* Hero Section */
        .hero-section {
            min-height: calc(100vh - 130px);
            background: var(--gradient-dark);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
            margin-top: 0;
        }
        
        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(227, 6, 19, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 89, 100, 0.1) 0%, transparent 50%),
                url('https://images.unsplash.com/photo-1582719471384-894fbb16e074?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            opacity: 0.3;
            z-index: 1;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
            padding: 2rem 0;
        }
        
        .hero-badge {
            display: inline-block;
            background: var(--gradient-primary);
            color: white;
            padding: 10px 22px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1.2rem;
            box-shadow: 0 4px 15px rgba(227, 6, 19, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .hero-title {
            font-size: 3.2rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.2rem;
            color: white;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .hero-title span {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 2.2rem;
            max-width: 550px;
            line-height: 1.5;
        }
        
        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-bottom: 2.2rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: white;
            display: block;
            line-height: 1;
            margin-bottom: 0.3rem;
        }
        
        .stat-label {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1.2rem;
            flex-wrap: wrap;
        }
        
        .btn-primary-custom {
            background: var(--gradient-primary);
            border: none;
            padding: 14px 32px;
            font-weight: 600;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(227, 6, 19, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            font-size: 1.1rem;
        }
        
        .btn-primary-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-primary-custom:hover::before {
            left: 100%;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(227, 6, 19, 0.4);
        }
        
        .btn-outline-light-custom {
            border: 2px solid rgba(255, 255, 255, 0.4);
            color: white;
            background: transparent;
            padding: 14px 32px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
        }
        
        .btn-outline-light-custom:hover {
            background: rgba(255, 255, 255, 0.15);
            border-color: white;
            transform: translateY(-3px);
        }
        
        .hero-image {
            position: relative;
            text-align: center;
        }
        
        .floating-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        
        .floating-card img {
            max-width: 100%;
            border-radius: 10px;
        }
        
        .card-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            background: var(--gradient-primary);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(227, 6, 19, 0.3);
        }
        
        .feature-highlight {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.15);
            padding: 8px 16px;
            border-radius: 50px;
            margin-right: 10px;
            margin-bottom: 10px;
            font-size: 1rem;
            font-weight: 500;
        }
        
        .feature-highlight i {
            color: var(--accent-color);
            margin-right: 8px;
            font-size: 1.1rem;
        }
        
        /* Responsive Design */
        @media (max-width: 991px) {
            .hero-title {
                font-size: 2.8rem;
            }
            
            .hero-stats {
                justify-content: center;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .hero-image {
                margin-top: 2.5rem;
            }
        }
        
        @media (max-width: 767px) {
            
            
            .hero-title {
                font-size: 2.4rem;
            }
            
            .hero-subtitle {
                font-size: 1.2rem;
            }
            
            .hero-stats {
                gap: 1.5rem;
            }
            
            .stat-number {
                font-size: 2.2rem;
            }
            
            .stat-label {
                font-size: 0.95rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn-primary-custom, .btn-outline-light-custom {
                width: 100%;
                justify-content: center;
                font-size: 1.05rem;
            }
            
            .feature-highlight {
                font-size: 0.95rem;
                padding: 7px 14px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-bg"></div>
        
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <div class="hero-badge">
                            <i class="bi bi-award-fill me-2"></i>Trusted Since 2008
                        </div>
                        
                        <h1 class="hero-title">
                            Precision Care for <span>Blood Bank</span> Equipment
                        </h1>
                        
                        <p class="hero-subtitle">
                            Specialized maintenance, calibration, and service contracts for critical healthcare equipment. Ensuring reliability when it matters most.
                        </p>
                        
                        <div class="mb-4">
                            <span class="feature-highlight">
                                <i class="bi bi-check-circle-fill"></i>24/7 Emergency Service
                            </span>
                            <span class="feature-highlight">
                                <i class="bi bi-check-circle-fill"></i>Certified Technicians
                            </span>
                            <span class="feature-highlight">
                                <i class="bi bi-check-circle-fill"></i>NABL Accredited
                            </span>
                        </div>
                        
                        <div class="hero-stats">
                            <div class="stat-item">
                                <span class="stat-number" data-count="500">0</span>
                                <span class="stat-label">Clients Served</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number" data-count="15">0</span>
                                <span class="stat-label">Years Experience</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number" data-count="98">0</span>
                                <span class="stat-label">% Satisfaction</span>
                            </div>
                        </div>
                        
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="hero-image">
                        <div class="floating-card position-relative">
                            <div class="card-badge">
                                <i class="bi bi-lightning-charge-fill me-1"></i>24/7 Support
                            </div>
                            <img src="https://images.unsplash.com/photo-1581093450021-4a7360e9a7b1?ixlib=rb-1.2.1&auto=format&fit=crop&w=600&q=80" 
                                 alt="Blood Bank Equipment" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Active nav link highlighting
        document.querySelectorAll('.nav-link-custom').forEach(link => {
            link.addEventListener('click', function() {
                document.querySelectorAll('.nav-link-custom').forEach(l => l.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Animated counter for stats
        function animateCounter() {
            const counters = document.querySelectorAll('.stat-number');
            const speed = 200;
            
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-count');
                const count = +counter.innerText;
                const increment = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(() => animateCounter(), 1);
                } else {
                    counter.innerText = target;
                }
            });
        }
        
        // Start animation when page loads
        window.addEventListener('load', () => {
            setTimeout(animateCounter, 500);
        });
    </script>
</body>
</html>