<?php
const NAME_REQUIRED = "Name is required";
const EMAIL_REQUIRED = "Email is required";

$errors = [];
$input = [];

if (isset($_POST['submit'])){
    $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_SPECIAL_CHARS);
    echo "Data van het formulier: <br>
    Naam: ". $name . "<br>
    Email: ". $email . "<br>";
    if (isset($_POST['terms'])){
        echo "De algemene voorwaarde zijn geaccepteerd";
    }  else {
        echo "De algemene voorwaarde zijnniet geaccepteerd";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <link rel="stylesheet" href="css/style.css"/>
    <title>Contact - Anime Store</title>
</head>
<body>
<div class="wrapper">
    <!-- Header -->
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

    <main>
        <!-- Contact Form -->
        <section class="contact">
            <h2>Contact Us</h2>
            <form method="post" action="">
                <div class="radio-group">
                    <input type="radio" id="dhr" name="title">
                    <label for="dhr">Dhr</label>
                    <input type="radio" id="mvr" name="title">
                    <label for="mvr">Mvr</label>
                </div>
                <label for="name">Naam:</label>
                <input type="text" id="name" name="name">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
                <button type="submit">Verstuur</button>
            </form>
        </section>

        <!-- Social -->
        <section class="social">
            <h2>Volg ons op Naruto Social</h2>
            <img src="img/naruto.jpg.png" alt="Naruto" class="naruto-img">
            <p>@NarutoUzumaki - "Ik zal Hokage worden!"</p>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Anime Store</p>
    </footer>
</div>
</body>
</html>
