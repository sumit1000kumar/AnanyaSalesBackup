
    <!-- Top Bar -->
    <div class="top-bar-custom">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <span class="me-4">
                    <i class="bi bi-geo-alt-fill me-1"></i> Nashik, Maharashtra
                </span>
                <a href="tel:+919876543210" class="top-bar-link me-4">
                    <i class="bi bi-telephone-fill me-1"></i> +91 98765 43210
                </a>
                <a href="mailto:service@ananyasales.com" class="top-bar-link">
                    <i class="bi bi-envelope-fill me-1"></i> service@ananyasales.com
                </a>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-4">
                    <i class="bi bi-clock-fill me-1"></i> Mon-Sat: 8AM-8PM | 
                    <a href="/user-login.php" class="top-bar-link">
                        <i class="bi bi-person-circle me-1"></i> Customer Login
                    </a>
                </span>      </span>
                    </span>
                
            </div>
        </div>
    </div>
    <style>
    .top-bar-custom {
        background: var(--gradient-primary);
        color: #fff;
        font-size: 1rem;
        padding: 0.5rem 0;
        border-bottom: none;
        box-shadow: 0 2px 8px rgba(227,6,19,0.07);
    }
    .top-bar-custom .top-bar-link {
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }
    .top-bar-custom .top-bar-link:hover {
        color: var(--accent-color);
        text-decoration: underline;
    }
    .top-bar-custom .emergency-highlight {
        background: rgba(255,255,255,0.15);
        color: #fff;
        font-weight: 700;
        padding: 2px 12px;
        border-radius: 20px;
        letter-spacing: 1px;
        font-size: 0.98rem;
        display: flex;
        align-items: center;
    }
    @media (max-width: 767px) {
        .top-bar-custom {
            font-size: 0.95rem;
            padding: 0.7rem 0;
        }
        .top-bar-custom .container {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        .top-bar-custom .d-flex {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 4px;
        }
        .top-bar-custom .me-4 {
            margin-right: 0.8rem !important;
        }
    }
    </style>


    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container nav-container">
            <a class="navbar-brand navbar-brand-custom" href="#">
                <img src="assets/images/logo/logo-noBg.png" alt="Ananya Sales & Service" class="navbar-logo">
                <span class="brand-name">Ananya Sales & Service</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link nav-link-custom" href="#">Contact</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn emergency-btn text-white" href="tel:+919876543210">
                            <i class="bi bi-telephone me-2"></i>Emergency Call
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <style>
        :root {
            --primary-color: #e30613;
            --secondary-color: #a9030d;
            --accent-color: #ff5964;
            --dark-text: #212529;
            --light-bg: #f8f9fa;
            --gradient-primary: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }
        
        /* Top Bar */
        .top-bar {
            background: var(--gradient-primary);
            color: white;
            padding: 8px 0;
            font-size: 0.9rem;
        }
        
        .top-bar a {
            color: white;
            text-decoration: none;
            transition: opacity 0.3s;
        }
        
        .top-bar a:hover {
            opacity: 0.8;
        }
        
        /* Navbar */
        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .nav-container {
            width: 100%;
        }

        @media (min-width: 1400px) {
    .nav-container
 {
        max-width: 1490px;
    }  
}
        
        .navbar-custom.scrolled {
            padding: 1rem 0;
            background: rgba(255, 255, 255, 0.98);
        }
        
        .navbar-brand-custom {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-logo {
            height: 45px;
            margin-right: 12px;
            transition: transform 0.3s;
        }
        
        .navbar-brand-custom:hover .navbar-logo {
            transform: scale(1.05);
        }
        
        .brand-name {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .nav-link-custom {
            font-weight: 600;
            margin: 0 8px;
            color: var(--dark-text) !important;
            position: relative;
            padding: 8px 16px !important;
            border-radius: 50px;
            transition: all 0.3s;
        }
        
        .nav-link-custom:hover, .nav-link-custom.active {
            color: white !important;
            background: var(--gradient-primary);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(227, 6, 19, 0.3);
        }
        
        .emergency-btn {
            background: var(--gradient-primary);
            border: 1px solid var(--primary-color);
            padding: 10px 24px;
            font-weight: 600;
            border-radius: 0;
            box-shadow: 0 4px 15px rgba(227, 6, 19, 0.3);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            margin-left: 3rem;
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
            background: var(--gradient-primary);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(227, 6, 19, 0.4);
        }
        
        .navbar-toggler {
            border: none;
            padding: 4px 8px;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28227, 6, 19, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }
        
        @media (max-width: 991px) {
            .navbar-collapse {
                background: white;
                padding: 20px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                margin-top: 15px;
            }
            
            .nav-link-custom {
                margin: 5px 0;
                text-align: center;
            }
            
            .emergency-btn {
                margin-top: 10px;
                justify-content: center;
                width: 100%;
            }
        }
    </style>

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
    </script>