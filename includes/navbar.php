
        <!-- Custom Navbar (Image Style) -->
        <nav class="navbar navbar-expand-lg navbar-custom py-3" style="background: #fff; border-bottom: 1px solid #eaeaea;">
            <div class="container d-flex align-items-center justify-content-between">
                <!-- Logo & Brand -->
                <div class="d-flex align-items-center">
                    <img src="assets/images/logo/logo-noBg.png" alt="Ananya Sales & Service" style="height: 60px; width: auto; margin-right: 18px;">
                    <div>
                        <span style="font-size: 2rem; font-weight: 700; color: #1976d2; letter-spacing: 2px;">ANANYA</span><br>
                        <span style="font-size: 0.95rem; color: #333; letter-spacing: 1px;">SALES & SERVICE</span><br>
                        <span style="font-size: 0.8rem; color: #888; letter-spacing: 1px;">BEST FOR MEDICAL</span>
                    </div>
                </div>
                <!-- Contact Info -->
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center">
                        <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/icons/phone.svg" style="height: 24px; width: 24px; margin-right: 8px;">
                        <div>
                            <span style="font-size: 0.95rem; color: #888;">Number :</span><br>
                            <span style="font-weight: 600; color: #212529;">+91 98765 43210</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/icons/envelope.svg" style="height: 24px; width: 24px; margin-right: 8px;">
                        <div>
                            <span style="font-size: 0.95rem; color: #888;">Email :</span><br>
                            <span style="font-weight: 600; color: #212529;">service@ananyasales.com</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <img src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/icons/geo-alt.svg" style="height: 24px; width: 24px; margin-right: 8px;">
                        <div>
                            <span style="font-size: 0.95rem; color: #888;">Address :</span><br>
                            <span style="font-weight: 600; color: #212529;">123 Medical Equipment Plaza, New Delhi 110001</span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
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


    <!-- ...existing code... -->

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

    <!-- Slide-in Nav Overlay -->
    <div id="nav-overlay" class="nav-overlay" aria-hidden="true">
        <button id="nav-overlay-close" class="nav-overlay-close" aria-label="Close">&times;</button>
        <div class="nav-overlay-content">
            <nav class="nav-overlay-links">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="design/service.html">Services</a>
                <a href="design/about.html">About</a>
                <a href="design/contact.html">Contact</a>
            </nav>

            <div class="nav-overlay-portal">
                <a href="auth/login.php" class="btn portal-btn">Portal Login</a>
            </div>

            <div class="nav-overlay-emergency">
                <h4>Emergency Contacts</h4>
                <p><strong>Phone:</strong> +91 98765 43210</p>
                <p><strong>Email:</strong> service@ananyasales.com</p>
                <p><strong>Address:</strong> 123 Medical Equipment Plaza, New Delhi 110001</p>
            </div>
        </div>
    </div>

    <style>
        /* Nav overlay styles */
        .nav-overlay {
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 100%;
            transform: translateX(100%);
            transition: transform 0.35s ease-in-out;
            background: rgba(0,0,0,0.25);
            z-index: 2000;
            display: flex;
            justify-content: flex-end;
            -webkit-backdrop-filter: blur(2px);
            backdrop-filter: blur(2px);
        }

        .nav-overlay.open {
            transform: translateX(0);
        }

        .nav-overlay-content {
            width: 100%;
            max-width: 480px;
            height: 100%;
            background: #fff;
            box-shadow: -20px 0 40px rgba(0,0,0,0.15);
            padding: 28px;
            display: flex;
            flex-direction: column;
        }

        .nav-overlay-close {
            position: absolute;
            top: 18px;
            right: 18px;
            background: transparent;
            border: none;
            font-size: 32px;
            line-height: 1;
            cursor: pointer;
            color: #333;
        }

        .nav-overlay-links {
            margin-top: 28px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-overlay-links a {
            display: block;
            font-size: 1.25rem;
            padding: 12px 6px;
            color: var(--dark-text);
            text-decoration: none;
            font-weight: 700;
            border-radius: 6px;
        }

        .nav-overlay-links a:hover {
            background: rgba(229, 6, 19, 0.06);
            color: var(--primary-color);
        }

        .nav-overlay-portal {
            margin-top: 18px;
        }

        .portal-btn {
            display: inline-block;
            padding: 12px 18px;
            background: var(--gradient-primary);
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
        }

        .nav-overlay-emergency {
            margin-top: auto;
            padding-top: 18px;
            border-top: 1px solid #eee;
            color: #444;
            font-size: 0.95rem;
        }

        .nav-overlay-emergency h4 {
            margin: 0 0 8px 0;
            font-size: 1rem;
        }

        @media (max-width: 480px) {
            .nav-overlay-content { max-width: 100%; }
            .nav-overlay-links a { font-size: 1.05rem; }
        }
    </style>

    <script>
        // Slide-in overlay open/close
        (function(){
            const overlay = document.getElementById('nav-overlay');
            const closeBtn = document.getElementById('nav-overlay-close');
            const navbar = document.querySelector('.navbar-custom');

            function openOverlay() {
                overlay.classList.add('open');
                overlay.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeOverlay() {
                overlay.classList.remove('open');
                overlay.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            // Open overlay when navbar background (non-link/button) is clicked
            navbar.addEventListener('click', function(e){
                // Ignore clicks on links, buttons, or form controls inside navbar
                if (e.target.closest('a') || e.target.closest('button') || e.target.closest('input')) return;
                openOverlay();
            });

            // Close action
            closeBtn.addEventListener('click', closeOverlay);

            // Close when clicking outside content (on overlay backdrop)
            overlay.addEventListener('click', function(e){
                if (e.target === overlay) closeOverlay();
            });

            // Close on Escape
            document.addEventListener('keydown', function(e){
                if (e.key === 'Escape' && overlay.classList.contains('open')) closeOverlay();
            });
        })();
    </script>