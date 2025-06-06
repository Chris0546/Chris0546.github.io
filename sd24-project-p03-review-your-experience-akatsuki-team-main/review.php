<?php
// Include the database connection for the 'product' table
require 'db_product.php';
// Include the database connection for the 'review' table
require 'db_review.php';
// Make the $db variable accessible globally if needed elsewhere
global $db;

/* -------------------------------------
   1. FETCH ALL PRODUCTS FROM DATABASE
--------------------------------------*/

// Prepare SQL statement to get all products, ordered by category then ID
// This helps us group and sort products cleanly for the UI
$query = $db->prepare('SELECT * FROM `product` ORDER BY category, id ASC');
// Run the query against the database
$query->execute();
// Get all resulting rows as an associative array (column_name => value)
$products = $query->fetchAll(PDO::FETCH_ASSOC);

/* -------------------------------------
   2. FETCH ALL REVIEWS FROM DATABASE
--------------------------------------*/

// Prepare SQL to get every review in the system, most recent first
$reviewStmt = $db->prepare('SELECT * FROM `review` ORDER BY created_at DESC');
// Execute the review query
$reviewStmt->execute();
// Fetch all review rows as an associative array
$allReviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);
// Initialize an empty array to store reviews grouped by product ID
$reviewsByProduct = [];
// Loop through each review and group them under their respective product_id
foreach ($allReviews as $review) {
    // Add the review to the array keyed by product_id
    $reviewsByProduct[$review['product_id']][] = $review;
}

/* -------------------------------------
   3. GROUP PRODUCTS BY CATEGORY
--------------------------------------*/

// Empty array to store products grouped by their category (e.g., posters, shirts, figures)
$grouped = [];

// Loop through each product
foreach ($products as $product) {
    // Use category name as key, and append product to that group
    $grouped[$product['category']][] = $product;
}
?>


<!-- keep the rest of the HTML head and navbar here -->

<div class="container py-5">
    <?php foreach ($grouped as $category => $items): ?>
        <div id="<?= htmlspecialchars(strtolower($category)) ?>">
            <h2 class="category-title"><?= ucfirst($category) ?></h2>

            <div id="carousel-<?= htmlspecialchars(strtolower($category)) ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($items as $index => $product): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <div class="product-card mx-auto" style="max-width: 400px;">
                                <img src="img/<?= htmlspecialchars($product['img']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                                <h5 class="mt-3"><?= htmlspecialchars($product['name']) ?></h5>
                                <p>€<?= htmlspecialchars($product['price']) ?></p>
                                <p><small>Made in: <?= htmlspecialchars($product['made_in']) ?></small></p>

                                <!-- Show reviews if any -->
                                <?php if (!empty($reviewsByProduct[$product['id']])): ?>
                                    <div class="mt-3 text-start">
                                        <strong>Reviews:</strong>
                                        <ul class="list-group mt-2">
                                            <?php foreach ($reviewsByProduct[$product['id']] as $rev): ?>
                                                <li class="list-group-item">
                                                    <strong><?= htmlspecialchars($rev['name']) ?>:</strong>
                                                    <?= htmlspecialchars($rev['comment']) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

                                <!-- Toggle Review Form -->
                                <button class="btn btn-outline-danger mt-3" type="button" data-bs-toggle="collapse" data-bs-target="#reviewForm<?= $product['id'] ?>">
                                    Leave a Review
                                </button>

                                <!-- Review Form -->
                                <div class="collapse mt-3" id="reviewForm<?= $product['id'] ?>">
                                    <form action="submit_review.php" method="post">
                                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">

                                        <div class="mb-2">
                                            <input type="text" class="form-control" name="name" placeholder="Your name" required>
                                        </div>
                                        <div class="mb-2">
                                            <textarea class="form-control" name="comment" rows="3" placeholder="Write your review..." required></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-success">Submit</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Carousel controls -->
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
