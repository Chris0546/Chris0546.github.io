<?php
// Start the session to access session variables like user ID
session_start();
// Include the database connection file to interact with the DB
require 'db_review.php'; // Make sure this file sets up $db (PDO instance)
// Check if the user is logged in by verifying if 'user_id' exists in session
if (!isset($_SESSION['user_id'])) {
    // If not logged in, stop the script and show a message (could also redirect)
    die("Je moet ingelogd zijn om je reviews te bekijken."); // "You must be logged in to view your reviews"
}
// Store the logged-in user's ID from the session in a variable
$userId = $_SESSION['user_id'];
// Prepare an SQL statement to fetch all reviews belonging to this user
$stmt = $db->prepare("
    SELECT * FROM reviews 
    WHERE user_id = :user_id
");
// Bind the user ID parameter securely to the SQL query to avoid SQL injection
$stmt->bindParam(':user_id', $userId);
// Execute the query to get the user's reviews
$stmt->execute();
// Fetch all matched reviews as an associative array (key => value pairs)
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Now $reviews contains all reviews written by the logged-in user, ready to use
?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Mijn Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-light py-5">

<div class="container">
    <h1 class="mb-4">Mijn Reviews</h1>

    <?php if (empty($reviews)): ?>
        <div class="alert alert-warning">Je hebt nog geen reviews geplaatst.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($reviews as $review): ?>
                <?php
                // Initialize an empty string for the product type label
                $productType = '';
                // Check which product ID is set in the review and assign a descriptive label
                if ($review['hoodies_id']) {
                    // If hoodies_id exists, set label to "Hoodie #ID"
                    $productType = 'Hoodie #' . $review['hoodies_id'];
                } elseif ($review['pants_id']) {
                    // If pants_id exists, set label to "Pants #ID"
                    $productType = 'Pants #' . $review['pants_id'];
                } elseif ($review['sweatpants_id']) {
                    // If sweatpants_id exists, set label to "Sweatpants #ID"
                    $productType = 'Sweatpants #' . $review['sweatpants_id'];
                } elseif ($review['tshirt_id']) {
                    // If tshirt_id exists, set label to "T-shirt #ID"
                    $productType = 'T-shirt #' . $review['tshirt_id'];
                } else {
                    // If none of the product IDs exist, label as 'Unknown' (Onbekend)
                    $productType = 'Onbekend';
                }
                ?>

                <!-- Container div for one review card -->
                <div class="col-md-6">
                    <!-- Bootstrap card with styling for dark background and light text -->
                    <div class="card mb-3 bg-secondary text-light">
                        <div class="card-body">
                            <!-- Display the reviewer's name safely to prevent XSS -->
                            <h5 class="card-title"><?php echo htmlspecialchars($review['name']); ?></h5>

                            <!-- Show the product type (like Hoodie #3) -->
                            <h6 class="card-subtitle mb-2 text-light-50"><?php echo $productType; ?></h6>

                            <!-- Display the review comment
                                 - Use htmlspecialchars to escape HTML special chars (avoid XSS)
                                 - Use nl2br() to convert newlines to <br> tags for formatting -->
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
