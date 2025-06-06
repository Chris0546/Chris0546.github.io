<?php
// Start the PHP session so we can store user data (like username) across pages
session_start();
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel="stylesheet" href="css/style.css">
    <title>Anime Store</title>
</head>
<body>
<header>
    <!-- Website Logo -->
    <div class="logo">AKATSUKI</div>

    <!-- Navigation links -->
    <nav>
        <a href="index.php">Home</a>
        <a href="product.php">Product</a>
        <a href="about-us.php">About Us</a>
        <a href="contact.php">Contact</a>
        <a href="profile.php">Profile</a>

        <!-- Check if user is logged in using PHP -->
        <?php if (isset($_SESSION['username'])): ?>
            <!-- Show welcome message + logout button if logged in -->
            <span class="welcome-msg">Welkom, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="log_out.php" class="btn auth-btn">Uitloggen</a>
        <?php else: ?>
            <!-- Show login and sign-up if NOT logged in -->
            <a href="log_in.php" class="btn auth-btn">Login</a>
            <a href="sign_in.php" class="btn auth-btn">Sign Up</a>
        <?php endif; ?>
    </nav>
</header>


<div class="container mt-5">
    <div class="card shadow rounded-4 p-4">
        <!-- Section title and toggle button -->
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Gebruikersstatus</h5>
            <button class="btn btn-outline-dark btn-sm" onclick="toggleUserInfo()">
                <!-- Button to show/hide user info -->
                <a href="profile.php"> Toon ingelogde gebruiker </a>
            </button>
        </div>

        <!-- Hidden info box that shows user login status -->
        <div id="user-info" class="mt-4 d-none fade">
            <?php if (isset($_SESSION['username'])): ?>
                <!-- If user is logged in, show this green alert -->
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Je bent ingelogd als: <strong class="ms-1"><?= htmlspecialchars($_SESSION['username']) ?></strong>
                </div>
            <?php else: ?>
                <!-- If not logged in, show a yellow warning -->
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Niemand is momenteel ingelogd. <a href="log_in.php" class="ms-2">Log in</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- JS for toggling -->
<script>
    function toggleUserInfo() {
        const info = document.getElementById('user-info');
        info.classList.toggle('d-none');
        info.classList.toggle('show'); // adds fade-in if you want to animate it
    }
</script>

<!-- Don't forget Bootstrap Icons (add this in your <head> or before </body>) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">



<section class="hero">
    <!-- Big, bold intro banner -->
    <h1>Unleash Your Inner Shinobi</h1>
    <p>Exclusive Anime-Inspired Apparel</p>
    <a href="#" class="btn">Shop Now</a>
</section>


<section class="products">
    <!-- Section title -->
    <h2>Featured Drops</h2>

    <!-- Grid layout for featured product cards -->
    <div class="product-grid">
        <!-- Product 1 -->
        <div class="product-card">
            <img src="img/image1.jpg" alt="Product 1">
            <h3>Akatsuki Hoodie</h3>
            <p>$45.00</p>
        </div>

        <!-- Product 2 -->
        <div class="product-card">
            <img src="img/naruto_tshirt.png" alt="Product 2">
            <h3>Naruto Tshirt</h3>
            <p>$30.00</p>
        </div>

        <!-- Product 3 -->
        <div class="product-card">
            <img src="img/uchiha_pants.jpg" alt="Product 3">
            <h3>Uchiha Pants</h3>
            <p>$50.00</p>
        </div>
    </div>
</section>


<footer>
    <p>&copy; 2025 Akatsuki Apparel. All rights reserved.</p>
</footer>
</body>
</html>