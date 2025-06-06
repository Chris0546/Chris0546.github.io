<?php
// Start the session (needed to check if user is logged in and use session data)
session_start();
// Include DB connection for reviews and products
require 'db_review.php';
require 'db_product.php';

// Check if user is logged in — if not, stop here
if (!isset($_SESSION['user_id'])) {
    // Exit with message if not logged in
    die("Je moet ingelogd zijn om een review te plaatsen.");
}

// Get the product type and ID from the URL (?type=...&id=...)
$type = $_GET['type'] ?? null;  // e.g., "poster", "shirt"
$id = $_GET['id'] ?? null;      // e.g., "3" (product ID)

// Stop script if type or ID are missing
if (!$type || !$id) {
    die("Ongeldige productgegevens.");
}

// Grab user data from the session
$userId = $_SESSION['user_id'];          // Logged-in user ID
$name = $_SESSION['username'] ?? '';     // Username (if set)
$comment = '';                           // Default comment is empty

/* ======================================================
    If this page is submitted via POST (user sent form)
======================================================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Get review text from form
    $comment = $_POST['comment'] ?? '';

    // Get rating value (1-5 stars)
    $rating = $_POST['rating'] ?? null;

    // Validate: Rating must be 1 to 5
    if (!$rating || $rating < 1 || $rating > 5) {
        die("Ongeldige beoordeling.");
    }

    // Try to insert review into DB
    try {
        // Prepare SQL insert query with placeholders
        $stmt = $db->prepare("INSERT INTO review (product_id, user_id, name, comment, rating) 
                              VALUES (:product_id, :user_id, :name, :comment, :rating)");

        // Bind values safely to avoid SQL injection
        $stmt->bindParam(':product_id', $id);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':rating', $rating);

        // Execute the insert
        $stmt->execute();

    } catch (PDOException $e) {
        // If something goes wrong, show error
        die("Fout bij het opslaan van je review: " . $e->getMessage());
    }

    // Review successfully submitted — show a success alert on redirect
    $_SESSION['alert'] = '<div class="alert alert-success mt-3">Review geplaatst!</div>';

    // Redirect back to product page so user doesn’t resubmit on refresh
    header("Location: product.php?type=$type&id=$id");
    exit;
}

/* ======================================================
   📦 Load all reviews for this product from DB
======================================================= */
$reviewStmt = $db->prepare("
    SELECT r.*, u.username
    FROM review r
    JOIN users u ON r.user_id = u.id
    WHERE r.product_id = :product_id
    ORDER BY r.created_at DESC
");

// Bind product ID and execute query
$reviewStmt->bindParam(':product_id', $id);
$reviewStmt->execute();

// Fetch all reviews for this product
$reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Laat een Review achter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #fff;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            font-size: 2.5rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s;
        }

        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: gold;
        }

        .card-review {
            background-color: #1e1e1e;
            border: 1px solid #333;
        }

        .card-review h5 {
            font-weight: bold;
        }

        .card-review small {
            color: #aaa;
        }

        .form-container {
            background-color: #f8f9fa;
            color: #000;
            border-radius: 10px;
            padding: 2rem;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <h1 class="mb-4">Laat een Review achter</h1>

    <?php if (isset($_SESSION['alert'])): echo $_SESSION['alert']; unset($_SESSION['alert']); endif; ?>

    <form method="POST" class="form-container shadow">
        <div class="mb-3">
            <label for="comment" class="form-label fw-bold">Jouw Review</label>
            <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold d-block">Geef een rating:</label>
            <div class="star-rating d-flex flex-row-reverse justify-content-start">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" id="star<?= $i ?>" name="rating" value="<?= $i ?>" required>
                    <label for="star<?= $i ?>">&#9733;</label>
                <?php endfor; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-danger fw-bold px-4">Review Plaatsen</button>
    </form>

    <hr class="my-5">

    <h2 class="mb-4">Alle Reviews</h2>
    <?php if (empty($reviews)): ?>
        <p class="text-muted">🚫 Nog geen recensies voor dit product.</p>
    <?php else: ?>
        <?php foreach ($reviews as $review): ?>
            <div class="card card-review mb-3 p-3 text-light">
                <div class="card-body">
                    <h5 class="card-title mb-1"><?= htmlspecialchars($review['username']) ?></h5>
                    <small class="d-block mb-2"><?= date('d-m-Y H:i', strtotime($review['created_at'])) ?></small>
                    <p class="card-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                    <p class="text-warning">Rating: <?= str_repeat("⭐", $review['rating']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
    // Optional: Make the stars highlight dynamically on hover
    const stars = document.querySelectorAll('.star-rating label');
    stars.forEach((star, index) => {
        star.addEventListener('mouseover', () => {
            stars.forEach((s, i) => {
                s.style.color = i <= index ? 'gold' : '#ccc';
            });
        });

        star.addEventListener('mouseout', () => {
            const checked = document.querySelector('.star-rating input:checked');
            stars.forEach((s, i) => {
                s.style.color = (checked && i >= (5 - checked.value)) ? 'gold' : '#ccc';
            });
        });
    });
</script>
</body>
</html>
