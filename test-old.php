<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ananya Sales & Service - Hero Section</title>
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
        
        .hero-section {
            min-height: 90vh;
            background: var(--gradient-dark);
            position: relative;
            display: flex;
            align-items: center;
            overflow: hidden;
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
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(227, 6, 19, 0.3);
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
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
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 2.5rem;
            max-width: 600px;
        }
        
        .hero-stats {
            display: flex;
            gap: 2rem;
            margin-bottom: 3rem;
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
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .hero-buttons {
            display: flex;
            gap: 1rem;
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
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            background: transparent;
            padding: 14px 32px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .btn-outline-light-custom:hover {
            background: rgba(255, 255, 255, 0.1);
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
            50% { transform: translateY(-20px); }
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
            padding: 8px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(227, 6, 19, 0.3);
        }
        
        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.7);
            text-align: center;
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0) translateX(-50%); }
            40% { transform: translateY(-10px) translateX(-50%); }
            60% { transform: translateY(-5px) translateX(-50%); }
        }
        
        .feature-highlight {
            display: inline-flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 50px;
            margin-right: 10px;
            margin-bottom: 10px;
        }
        
        .feature-highlight i {
            color: var(--accent-color);
            margin-right: 8px;
        }
        
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-stats {
                justify-content: center;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .hero-image {
                margin-top: 3rem;
            }
        }
        
        @media (max-width: 576px) {
            .hero-title {
                font-size: 2rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .hero-stats {
                gap: 1rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
     
  <?php include 'includes/navbar.php'; ?>
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
                        
                        <!-- <div class="hero-buttons">
                            <a href="#services" class="btn btn-primary-custom text-white">
                                <i class="bi bi-tools me-2"></i>Our Services
                            </a>
                            <a href="tel:+919876543210" class="btn btn-outline-light-custom">
                                <i class="bi bi-telephone me-2"></i>Emergency Call
                            </a>
                        </div> -->
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
        
        <div class="scroll-indicator">
            <div>Scroll to explore</div>
            <i class="bi bi-chevron-down"></i>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
        
        // Parallax effect for background
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-section');
            const rate = scrolled * -0.5;
            
            hero.style.transform = `translate3d(0, ${rate}px, 0)`;
        });
    </script>
</body>
</html>