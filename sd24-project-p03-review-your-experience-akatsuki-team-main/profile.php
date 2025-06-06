<?php
//Start a new session or resume the existing one
//This allows you to store and access user data (like login status) across different pages
session_start();

//Include your database connection script
// This file should contain your PDO connection and assign it to $db
require 'db_sign_in.php';
global $db; // Make sure the $db variable from the included file is usable here

//Check if user is not logged in (no session user_id set)
// If they are not logged in, redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); //Send user to login page
    exit; //Stop executing the script (important for security)
}

// 🆔 Get the currently logged-in user's ID from the session
$userId = $_SESSION['user_id'];

// Use placeholders (:id) to prevent SQL injection (secure coding)
$userQuery = $db->prepare("SELECT username, email FROM form WHERE id = :id");
//Bind the :id parameter to the actual user ID value from session
$userQuery->bindParam(':id', $userId);
//Execute the prepared query to fetch user info
$userQuery->execute();

//Fetch the result as an associative array (e.g., ['username' => 'Chris', 'email' => 'chris@email.com'])
$user = $userQuery->fetch(PDO::FETCH_ASSOC);

//If no user was found (maybe their ID doesn't exist anymore), show error and exit
if (!$user) {
    echo "Gebruiker niet gevonden."; //"User not found" in Dutch
    exit; //Always exit if there's an issue with fetching critical data
}
//Now get all the reviews made by this user from the 'review' table
$reviewQuery = $db->prepare("SELECT name, comment, image FROM review WHERE user_id = :user_id");
//Bind the user_id in the query to the logged-in user's ID
$reviewQuery->bindParam(':user_id', $userId);
//Execute the review query
$reviewQuery->execute();
//Fetch all reviews as an array of associative arrays
// Example: [ ['name' => 'Pizza Place', 'comment' => 'Great!', 'image' => 'pizza.jpg'], ... ]
$reviews = $reviewQuery->fetchAll(PDO::FETCH_ASSOC);

// ✅ Now you can use $user to show username/email
// 🧾 And use $reviews to loop and show each review
?>




<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn Profiel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-dark text-white py-5">
<header class="bg-danger text-white py-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">

        <!-- Logo -->
        <div class="logo fs-3 fw-bold">AKATSUKI</div>

        <!-- Navigation links -->
        <nav class="d-flex gap-3 align-items-center">
            <a href="index.php" class="text-white text-decoration-none">Home</a>
            <a href="product.php" class="text-white text-decoration-none">Product</a>
            <a href="about-us.php" class="text-white text-decoration-none">About Us</a>
            <a href="contact.php" class="text-white text-decoration-none">Contact</a>

            <!-- Show different buttons based on login status -->
            <?php if (isset($_SESSION['username'])): ?>

                <!-- Show Admin Panel button if user has 'ROLE_ADMIN' -->
                <?php if (in_array('ROLE_ADMIN', $_SESSION['roles'])): ?>
                    <a href="admin_panel.php" class="btn btn-outline-light btn-sm">Admin Panel</a>
                <?php else: ?>
                    <!-- If normal user, show 'Mijn Profiel' -->
                    <a href="profile.php" class="btn btn-outline-light btn-sm">Mijn Profiel</a>
                <?php endif; ?>

                <!-- Logout button for all logged-in users -->
                <a href="log_out.php" class="btn btn-light btn-sm">Uitloggen</a>
            <?php else: ?>
                <!-- If user is not logged in, show login and sign up -->
                <a href="log_in.php" class="btn btn-light btn-sm">Login</a>
                <a href="sign_in.php" class="btn btn-outline-light btn-sm">Sign Up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>


<div class="container">
    <!-- Card with user info -->
    <div class="card bg-secondary text-white shadow-lg p-4 mb-4 rounded-4">
        <<!--Show logged-in user's name safely -->
        <h2 class="mb-3">Welkom, <?= htmlspecialchars($user['username']) ?>!</h2>
        <!--Show user's email securely -->
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>


        <hr class="border-light">

        <!-- Section for user reviews -->
        <h4 class="mt-4"> Jouw Reviews</h4>

        <!-- Check if the user has posted any reviews -->
        <?php if (count($reviews) > 0): ?>
            <div class="row">
                <!-- Loop through each review the user has made -->
                <?php foreach ($reviews as $review): ?>
                    <div class="col-md-6 col-lg-4 mt-3">
                        <!-- Individual review card -->
                        <div class="card bg-light text-dark h-100 shadow-sm rounded-3">
                            <div class="card-body">
                                <!--Show the review's title/name safely -->
                                <h5 class="card-title"><?= htmlspecialchars($review['name']) ?></h5>
                                <!--Show the review comment with line breaks and safe output -->
                                <p class="card-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>


                                <!-- Show image if there is one uploaded with the review -->
                                <?php if (!empty($review['image'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($review['image']) ?>" alt="Review afbeelding" class="img-fluid rounded mt-2" style="max-height: 200px;">
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?> <!-- End of review loop -->
            </div>
        <?php else: ?>
            <!-- If no reviews, show fallback message -->
            <p class="text-light">Je hebt nog geen reviews geplaatst.</p>
        <?php endif; ?>
    </div>

    <!-- Logout button at bottom of profile -->
    <div class="text-end">
        <!-- Logout button takes the user to a logout script -->
        <a href="log_out.php" class="btn btn-danger btn-lg">Uitloggen</a>
    </div>
</div>



</body>

</html>

