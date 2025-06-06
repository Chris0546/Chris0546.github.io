<?php
// Start session to access the logged-in user info
session_start();

// Include database connection for reviews
require 'db_review.php';
global $db;

// Only run the code when the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get and sanitize all inputs from form
    $productId = $_POST['product_id'] ?? null;     // The product being reviewed
    $name = trim($_POST['name'] ?? '');            // Name of the reviewer
    $comment = trim($_POST['comment'] ?? '');      // Review content
    $rating = $_POST['rating'] ?? 5;               // Star rating (default to 5 if missing)
    $userId = $_SESSION['user_id'] ?? null;        // Logged-in user ID (from session)

    // Extra safety check: make sure rating is between 1 and 5
    $rating = max(1, min(5, (int)$rating));

    // Final check: make sure required fields aren't empty
    if ($productId && $name && $comment && $userId) {

        // Prepare SQL to insert full review with rating and timestamp
        $stmt = $db->prepare("INSERT INTO review (user_id, product_id, name, comment, rating, created_at) 
                              VALUES (:user_id, :product_id, :name, :comment, :rating, NOW())");

        // Execute the query with the actual values
        $stmt->execute([
            ':user_id' => $userId,
            ':product_id' => $productId,
            ':name' => $name,
            ':comment' => $comment,
            ':rating' => $rating
        ]);

        // After submitting, send the user back to the product page
        header("Location: product.php?id=$productId");
        exit;
    } else {
        // If required values are missing, you could redirect back with error
        echo "Vul alle verplichte velden in."; // "Please fill in all required fields"
    }
}
?>

