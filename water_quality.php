<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WaterQual - Water Quality Analysis</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="water_quality.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <a href="water_quality.php" class="brand-text">WaterQual</a>
        </div>
        <div class="nav-links">
            <a href="water_quality.php"><i class="fas fa-home"></i> Home</a>
            <a href="information.php"><i class="fas fa-info-circle"></i> Information</a>
            <a href="help.php"><i class="fas fa-question-circle"></i> Help Center</a>
            <a href="ebooks.php"><i class="fas fa-book"></i> E-Books</a>
            <a href="blog.php"><i class="fas fa-blog"></i> Blog</a>
        </div>
        <div class="nav-auth">
            <?php if(isset($_SESSION['user_name'])): ?>
                <div class="user-dropdown">
                    <button class="dropbtn">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                    </button>
                    <div class="dropdown-content">
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.html" class="login-btn">Login</a>
                <a href="signup.html" class="signup-btn">Sign In</a>
            <?php endif; ?>
        </div>
    </nav>
    
    <main class="main-content">
        <!-- Hero Section with Video Background -->
        <section class="hero-banner">
            <video autoplay muted loop playsinline>
                <source src="videos/18.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="hero-content">
                <h1>Real-Time Water Insights for a Healthier Tomorrow</h1>
                <p>Monitoring and analyzing water quality for sustainable development</p>
                <a href="information.php" class="cta-button">Explore Data <i class="fas fa-arrow-right"></i></a>
            </div>
        </section>

        <!-- Key Features Section -->
        <section class="info-section">
            <h2 class="section-title">Key Features of Our Water Quality Platform</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-flask"></i>
                    <h3>Water Analysis</h3>
                    <p>Comprehensive water quality testing and analysis</p>
                </div>
                
                <div class="feature-card">
                    <i class="fas fa-chart-line"></i>
                    <h3>Real-time Monitoring</h3>
                    <p>Monitor water parameters in real-time</p>
                </div>
                
                <div class="feature-card">
                    <i class="fas fa-file-alt"></i>
                    <h3>Detailed Reports</h3>
                    <p>Generate detailed water quality reports</p>
                </div>
                
                <div class="feature-card">
                    <i class="fas fa-database"></i>
                    <h3>Data Management</h3>
                    <p>Efficient water quality data management</p>
                </div>
            </div>
        </section>

        <!-- Why Clean Water Matters Section -->
        <section class="info-section">
            <h2 class="section-title">🌍 Why Clean Water Matters</h2>
            <div class="info-content">
                <p>Access to clean and safe water is not just a basic human need — it's essential for survival, health, and environmental balance.</p>
                <p>Contaminated water causes life-threatening diseases and long-term health impacts.</p>
                <p>Clean water is necessary for agriculture, sanitation, and overall development.</p>
                <p>Monitoring water quality ensures the well-being of people, animals, and ecosystems.</p>
            </div>
        </section>

        <!-- Water Facts Section -->
        <section class="info-section">
            <h2 class="section-title">📊 Water Facts That Might Surprise You</h2>
            <div class="info-content">
                <p>🌎 Only 0.3% of the Earth's water is usable by humans.</p>
                <p>🛢️ Just 1 drop of oil can contaminate 25 liters of clean water.</p>
                <p>🚱 Over 2 billion people globally lack access to safe drinking water.</p>
                <p>💧 The average person uses 80-100 gallons of water daily.</p>
                <p>🌧️ Rainwater harvesting is one of the oldest methods of sustainable water use.</p>
            </div>
        </section>

        <!-- Quotes Section -->
        <section class="info-section">
            <h2 class="section-title">💬 Quotes That Make You Think</h2>
            <div class="quotes-container">
                <blockquote>
                    "Thousands have lived without love, not one without water."
                    <cite>— W.H. Auden</cite>
                </blockquote>
                <blockquote>
                    "Water is the driving force of all nature."
                    <cite>— Leonardo da Vinci</cite>
                </blockquote>
                <blockquote>
                    "Clean water, the essence of life and a birthright for everyone."
                    <cite>— Anonymous</cite>
                </blockquote>
                <blockquote>
                    "When the well's dry, we know the worth of water."
                    <cite>Benjamin Franklin</cite>
                </blockquote>
                <blockquote>
                    "No water, no life. No blue, no green."
                    <cite>Sylvia Earle</cite>
                </blockquote>
            </div>
        </section>

        <!-- Video Section -->
        <section class="hero-section">
            <h1>Welcome to Water Gallary</h1>
            <p>A Visual Insight into Global Water Bodies</p>
        </section>
        
        <section class="video-section">
            <div class="video-grid">
                <!-- Video content here -->
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/1.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/2.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/3.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/4.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/5.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/20.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/7.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/19.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <!-- Adding videos 9-18 -->
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/14.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/13.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/11.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
                <div class="video-container">
                    <video muted loop preload="metadata" style="height: 250px;">
                        <source src="videos/12.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Quick Links</h3>
                <a href="water_quality.php"><i class="fas fa-home"></i> Home</a>
                <a href="information.php"><i class="fas fa-info-circle"></i> Information</a>
                <a href="help.php"><i class="fas fa-question-circle"></i> Help Center</a>
                <a href="ebooks.php"><i class="fas fa-book"></i> E-Books</a>
                <a href="blog.php"><i class="fas fa-blog"></i> Blog</a>
            </div>

            <div class="footer-section">
                <h3>Resources</h3>
                <a href="information.php#water-quality"><i class="fas fa-tint"></i> Water Quality Data</a>
                <a href="information.php#air-quality"><i class="fas fa-chart-bar"></i> Air Quality Data</a>
                <a href="information.php#soil-quality"><i class="fas fa-flask"></i> Soil Analysis</a>
                <a href="information.php#radiation-quality"><i class="fas fa-radiation"></i> Radiation Data</a>
                <a href="information.php#environmental-status"><i class="fas fa-leaf"></i> Environmental Status</a>
                <a href="information.php#noise-quality"><i class="fas fa-volume-up"></i> Noise Pollution</a>
                <a href="information.php#lakes-analysis"><i class="fas fa-water"></i> 20 Main River Water Quality</a>
            </div>

            <div class="footer-section">
                <h3>Contact Us</h3>
                <a href="mailto:support@waterqual.com"><i class="fas fa-envelope"></i> support@waterqual.com</a>
                <a href="tel:+91-123-456-7890"><i class="fas fa-phone"></i> +91 123-456-7890</a>
                <a href="#"><i class="fas fa-map-marker-alt"></i> New Delhi, India</a>
                <p><i class="far fa-clock"></i> Mon - Fri, 9:00 - 18:00</p>
            </div>

            <div class="footer-section">
                <h3>Select Language</h3>
                <select class="language-select">
                    <option value="en">English</option>
                    <option value="hi">Hindi</option>
                    <option value="pu">Punjabi</option>
                </select>

                <h3>Follow Us</h3>
                <div class="social-links">
                    <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://x.com/"><i class="fab fa-twitter"></i></a>
                    <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                </div>

                <h3>Mobile App</h3>
                <div class="app-buttons">
                    <a href="https://play.google.com/" class="app-btn">
                        <i class="fas fa-play"></i> Play Store
                    </a>
                    <a href="https://www.apple.com/" class="app-btn">
                        <i class="fab fa-apple"></i> App Store
                    </a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p class="copyright">All rights reserved</p>
            <div class="footer-links">
                <a href="privacy.html"><i class="fas fa-shield-alt"></i> Privacy Policy</a>
                <a href="services.html"><i class="fas fa-file-contract"></i> Terms of Service</a>
                <a href="sitemap.html"><i class="fas fa-sitemap"></i> Sitemap</a>
            </div>
        </div>
    </footer>

    <!-- Add this before closing </body> tag -->
    <div class="chat-icon" id="chatIcon">
        <i class="fas fa-comment-dots"></i>
    </div>

    <div class="chat-container" id="chatContainer">
        <!-- Add this button to your chat header -->
<div class="chat-header">
    <h3>WaterQual Assistant</h3>
    <div class="chat-controls">
        <button id="resetChat" title="Reset Chat"><i class="fas fa-redo"></i></button>
        <button id="closeChat" title="Close Chat"><i class="fas fa-times"></i></button>
    </div>
</div>
        <div class="chat-messages" id="chatMessages">
            <div class="message bot">
                <p>Hello! I'm your WaterQual assistant. How can I help you with water quality today?</p>
            </div>
        </div>
        <div class="chat-input">
            <input type="text" id="userMessage" placeholder="Type your message here...">
            <button id="sendMessage"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>

    <script src="water_quality.js"></script>
</body>
</html>