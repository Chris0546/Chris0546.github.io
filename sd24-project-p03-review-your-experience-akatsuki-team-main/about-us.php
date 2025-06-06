
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>About Us - Anime Store</title>
    <link rel="stylesheet" href="./css/style.css" />
    <script src="./js/main.js"></script>
</head>
<body>
<div class="wrapper">
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

    <main class="content">
        <div class="left-section">
            <button onclick="showContent('company')">About Us</button>
            <button onclick="showContent('inspiration')">Our Inspiration</button>

            <div id="company" class="text-box">
                <h2>About Us</h2>
                <p>
                    We are an online store dedicated to anime fans, especially those who love Akatsuki from Naruto.
                    Our store was founded with the idea of providing high-quality Akatsuki-themed clothing, accessories, and more.
                    We started as a small project among friends and have grown into a community-driven business.
                    We believe in delivering the best anime merchandise to our fellow fans!
                </p>
            </div>

            <div id="inspiration" class="text-box" style="display: none;">
                <h2>Our Inspiration</h2>
                <p>
                    Our store draws inspiration from the legendary Akatsuki group in Naruto. Their dark and mysterious style,
                    along with their strong sense of purpose, has influenced our designs. We strive to create products that
                    capture their essence while offering fans a way to express their love for the anime world.
                </p>
            </div>
        </div>

        <div class="right-section">
            <h2>Visit Us</h2>
            <p>Narutostraat 2526 AM, Amsterdam</p>
            <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d243647.17658612567!2d4.728430712879641!3d52.35476008550365!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c609bc67447b0f%3A0x352c1cfb7ccf5a89!2sAmsterdam!5e0!3m2!1sen!2snl!4v1649304902397!5m2!1sen!2snl"
                    width="100%" height="500" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            <blockquote class="quote">
                "Even the strongest of opponents always have a weakness." - Itachi Uchiha
            </blockquote>
        </div>
    </main>

    <footer class="footer">
        <p>&copy; 2025 Anime Store</p>
    </footer>
</div>
</body>
</html>
