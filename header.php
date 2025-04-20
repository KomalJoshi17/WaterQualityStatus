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
            <a href="water_quality.php" class="brand-text underline">WaterQual</a>
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