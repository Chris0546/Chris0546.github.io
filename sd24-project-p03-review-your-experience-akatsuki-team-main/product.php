<?php
// Include the database connection file for product-related queries
require 'db_product.php';
// Include the database connection file for review-related queries
require 'db_review.php';
// Declare $db as a global variable so it can be accessed inside functions or elsewhere if needed
global $db;
// Prepare an SQL query to fetch all products from the 'product' table
// Ordering first by category (alphabetically), then by id (ascending order)
// This ordering helps us group products by category neatly later
$query = $db->prepare('SELECT * FROM `product` ORDER BY category, id ASC');
// Execute the prepared query against the database
$query->execute();
// Fetch all results as an associative array (array of arrays with column_name => value)
// This gives us an easy-to-use PHP array with all product info
$products = $query->fetchAll(PDO::FETCH_ASSOC);
// Initialize an empty array to store products grouped by their category
$grouped = [];
// Loop through each product fetched from the database
foreach ($products as $product) {
    // Use the product's 'category' value as a key in the $grouped array
    // Append the current product to the array for its category
    // This groups all products under their categories for easier display logic
    $grouped[$product['category']][] = $product;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Anime Merch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ff0000;
            color: white;
        }
        .product-card {
            background-color: white;
            color: black;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            text-align: center;
        }
        .product-card img {
            width: 100%;
            height: 300px;
            object-fit: contain;
            border-radius: 10px;
        }
        .category-title {
            color: white;
            text-align: center;
            margin-top: 40px;
            margin-bottom: 20px;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 2rem;
        }
        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: black;
            border-radius: 50%;
        }

    </style>
</head>
<body>

<?php session_start(); ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Product</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="product.php">Product</a></li>
                <li class="nav-item"><a class="nav-link" href="about-us.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="profile.php">Profile</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item d-flex align-items-center text-white me-3">
                        👋 Welcome, <strong class="ms-1"><?= htmlspecialchars($_SESSION['username'] ?? 'User') ?></strong>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-danger" href="log_out.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="btn btn-danger me-2" href="log_in.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-light" href="sign_in.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>


<div class="container py-5">
    <?php foreach ($grouped as $category => $items): ?>
        <div id="<?= htmlspecialchars(strtolower($category)) ?>">
            <h2 class="category-title"><?= ucfirst($category) ?></h2>

            <div id="carousel-<?= htmlspecialchars(strtolower($category)) ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($items as $index => $product): ?>
                        <?php
                        // Prepare SQL statement to select all reviews for the current product
                        // It selects comment, reviewer name, rating, creation date from 'review' table
                        // Also left joins with 'users' table to get the username of the reviewer (if available)
                        // Ordering reviews by most recent first (descending order by created_at)
                        $stmt = $db->prepare("
                            SELECT r.comment, r.name, r.rating, r.created_at, u.username 
                            FROM review r 
                            LEFT JOIN users u ON r.user_id = u.id 
                            WHERE r.product_id = :product_id 
                            ORDER BY r.created_at DESC
                        ");

                        // Execute the above prepared statement, binding the current product's id securely to :product_id placeholder
                        $stmt->execute([':product_id' => $product['id']]);
                        // Fetch all matching reviews as associative arrays into $reviews
                        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        // Initialize total rating sum to zero for averaging
                        $total = 0;
                        // Count the number of reviews fetched to calculate average safely
                        $count = count($reviews);
                        // Loop through each review to add up the rating values
                        foreach ($reviews as $r) {
                            $total += $r['rating'];  // Accumulate sum of all ratings
                        }
                        // Calculate the average rating for the current product
                        // Only if there are reviews (count > 0), otherwise set average to 0 to avoid division by zero
                        $average = $count > 0 ? round($total / $count, 1) : 0;
                        ?>


                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <a href="review_form.php?type=<?= urlencode(strtolower($product['category'])) ?>&id=<?= urlencode($product['id']) ?>" style="text-decoration: none;">
                                <div class="product-card mx-auto" style="max-width: 400px;">
                                    <img src="img/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                    <h5 class="mt-3"><?= htmlspecialchars($product['name']) ?></h5>


                                    <p>€<?= htmlspecialchars($product['price']) ?></p>
                                    <p><small>Made in: <?= htmlspecialchars($product['made_in']) ?></small></p>
                                    <p class="text-danger mt-2">➤ Leave a Review</p>
                                    <div class="mb-2">
                                        <?php
                                        $fullStars = floor($average);
                                        $halfStar = ($average - $fullStars) >= 0.5;
                                        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                                        ?>
                                        <div>
                                            <?php for ($i = 0; $i < $fullStars; $i++): ?>
                                                <span style="color: gold; font-size: 1.5rem;">★</span>
                                            <?php endfor; ?>
                                            <?php if ($halfStar): ?>
                                                <span style="color: gold; font-size: 1.5rem;">☆</span>
                                            <?php endif; ?>
                                            <?php for ($i = 0; $i < $emptyStars; $i++): ?>
                                                <span style="color: lightgray; font-size: 1.5rem;">★</span>
                                            <?php endfor; ?>
                                            <span class="ms-2 text-dark fw-bold">(<?= $average ?>/5)</span>
                                        </div>
                                    </div>

                                    <?php if (isset($_SESSION['user_id'])): ?>
                                        <?php
                                         // Again prepare statement to fetch the same reviews for the current product
                                         // This is repeated to display the actual review comments and info below the product card
                                        $stmt = $db->prepare("
                                             SELECT r.comment, r.name, r.rating, r.created_at, u.username 
                                             FROM review r 
                                             LEFT JOIN users u ON r.user_id = u.id 
                                             WHERE r.product_id = :product_id 
                                             ORDER BY r.created_at DESC
                                         ");
                                         // Execute statement with product ID bound to :product_id parameter securely
                                        $stmt->execute([':product_id' => $product['id']]);
                                         // Fetch all reviews into an associative array
                                        $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                         // Reset total and count for calculating average again (could be optimized to avoid repeat)
                                        $total = 0;
                                        $count = count($reviews);
                                         // Sum up all the ratings from the fetched reviews
                                        foreach ($reviews as $r) {
                                            $total += $r['rating'];
                                        }
                                         // Calculate average rating rounded to 1 decimal place, or 0 if no reviews
                                        $average = $count > 0 ? round($total / $count, 1) : 0;
                                        ?>

                                        <div class="text-start bg-white text-black rounded mt-3 p-3">
                                            <h6 class="fw-bold">Reviews:</h6>
                                            <?php if (count($reviews) === 0): ?>
                                                <p class="text-muted">No reviews yet. Be the first to leave one!</p>
                                            <?php else: ?>
                                                <?php foreach ($reviews as $review): ?>
                                                    <div class="mb-2 border-bottom pb-2">
                                                        <strong><?= htmlspecialchars($review['name'] ?? $review['username']) ?>:</strong>
                                                        <small class="text-muted ms-2"><?= date('F j, Y H:i', strtotime($review['created_at'])) ?></small>
                                                        <p class="mb-0"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>

                        </div>
                    <?php endforeach; ?>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?= htmlspecialchars(strtolower($category)) ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?= htmlspecialchars(strtolower($category)) ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<footer class="text-center text-white py-4 bg-dark mt-5">
    &copy; 2025 Anime Store
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
