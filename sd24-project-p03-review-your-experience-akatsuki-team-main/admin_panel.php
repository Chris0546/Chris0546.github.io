<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db_sign_in.php';
require 'db_product.php';
global $db;

// Check if user is logged in and is admin
if (!isset($_SESSION['roles']) || !in_array('ROLE_ADMIN', $_SESSION['roles'])) {
    header('Location: log_in.php');
    exit;
}

// Delete user if delete is triggered
if (isset($_GET['delete'])) {
    $userId = (int)$_GET['delete'];
    $stmt = $db->prepare("DELETE FROM form WHERE id = :id");
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $_SESSION['alert'] = "<div class='alert alert-success'>Gebruiker verwijderd.</div>";
    header("Location: admin_panel.php");
    exit;
}

// Fetch all users
$stmt = $db->query("SELECT id, username, email, role, time_created FROM form ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Check if admin
if (!isset($_SESSION['roles']) || !in_array('ROLE_ADMIN', $_SESSION['roles'])) {
    header('Location: log_in.php');
    exit;
}

// Add product
// Add product
$success = '';
$errors = [];

if (isset($_POST['add_product'])) {
    $name        = $_POST['name'] ?? '';
    $price       = $_POST['price'] ?? '';
    $category_id = $_POST['category_id'] ?? null; // Foreign key!
    $made_in     = $_POST['made_in'] ?? '';
    $size = !empty($_POST['size']) ? $_POST['size'] : null;
    $imgName     = '';

    if (!$name || !$price || !$category_id || !$made_in) {
        $errors[] = "Alle velden zijn verplicht.";
    } elseif (!isset($_FILES['img']) || $_FILES['img']['error'] !== 0) {
        $errors[] = "Fout bij het uploaden van de afbeelding.";
    } else {
        $uploadDir = __DIR__ . '/uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imgName = basename($_FILES['img']['name']);
        $targetFilePath = $uploadDir . $imgName;

        if (move_uploaded_file($_FILES['img']['tmp_name'], $targetFilePath)) {
            $stmt = $db->prepare("INSERT INTO product (name, price, category_id, made_in, img, size) 
                                  VALUES (:name, :price, :category_id, :made_in, :img, :size)");
            $stmt->execute([
                ':name' => $name,
                ':price' => $price,
                ':category_id' => $category_id,
                ':made_in' => $made_in,
                ':img' => $imgName,
                ':size' => $size
            ]);
            $success = "Product succesvol toegevoegd.";
        } else {
            $errors[] = "Kon de afbeelding niet opslaan.";
        }
    }
}


// Delete product if triggered
if (isset($_GET['delete_product'])) {
    $productId = (int)$_GET['delete_product'];

    // First get the image filename to delete from uploads folder
    $stmt = $db->prepare("SELECT img FROM product WHERE id = :id");
    $stmt->execute([':id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $imagePath = __DIR__ . '/uploads/' . $product['img'];

        // Delete image file if it exists
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Now delete from DB
        $stmt = $db->prepare("DELETE FROM product WHERE id = :id");
        $stmt->execute([':id' => $productId]);

        $_SESSION['alert'] = "<div class='alert alert-success'>Product verwijderd.</div>";
    } else {
        $_SESSION['alert'] = "<div class='alert alert-danger'>Product niet gevonden.</div>";
    }

    header("Location: admin_panel.php");
    exit;
}


// Fetch all products
$stmt = $db->query("SELECT * FROM product ORDER BY id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-danger">
<header>
    <div class="logo">AKATSUKI</div>

    <nav>
        <a href="index.php">Home</a>
        <a href="product.php">Product</a>
        <a href="about-us.php">About Us</a>
        <a href="contact.php">Contact</a>

        <?php if (isset($_SESSION['username'])): ?>
            <span class="welcome-msg"> Welkom, <?= htmlspecialchars($_SESSION['username']) ?></span>
            <a href="log_out.php" class="btn auth-btn">Uitloggen</a>
        <?php else: ?>
            <a href="log_in.php" class="btn auth-btn">Login</a>
            <a href="sign_in.php" class="btn auth-btn">Sign Up</a>
        <?php endif; ?>
    </nav>
</header>

<div class="container mt-5">
    <h1 class="mb-4 text-center">Admin Paneel</h1>

    <?php
    if (isset($_SESSION['alert'])) {
        echo $_SESSION['alert'];
        unset($_SESSION['alert']);
    }
    ?>

    <div class="mb-3 text-end">
        <a href="log_out.php" class="btn btn-light">Uitloggen</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Gebruikersnaam</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Aangemaakt op</th>
            <th>Acties</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars(is_array(json_decode($user['role'], true)) ? implode(', ', json_decode($user['role'], true)) : $user['role']) ?></td>
                <td><?= htmlspecialchars($user['time_created']) ?></td>
                <td>
                    <!-- Future: Add edit button -->
                    <a href="admin_panel.php?delete=<?= $user['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">Verwijder</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Product Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="mb-4 text-center">Admin Productbeheer</h1>

    <?php if ($success): ?>
        <div class="alert alert-success"> <?= $success ?> </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <style>
        .form-wrapper {
            background: #f0f4f8;
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .form-wrapper h4 {
            color: #333;
            font-weight: bold;
            margin-bottom: 1.5rem;
        }

        .form-control:focus {
            border-color: #6c63ff;
            box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25);
        }

        .form-select:focus {
            border-color: #6c63ff;
            box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25);
        }

        .btn-success {
            background-color: #28a745;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        label {
            font-weight: 500;
            color: #555;
        }
    </style>

    <form method="post" enctype="multipart/form-data" class="form-wrapper mb-4">
        <h4>Nieuw Product Toevoegen</h4>
        <div class="row g-3">
            <div class="col-md-4">
                <label>Productnaam</label>
                <input type="text" name="name" class="form-control" placeholder="Productnaam" required>
            </div>
            <div class="col-md-2">
                <label>Prijs</label>
                <input type="number" name="price" class="form-control" placeholder="Prijs" step="0.01" required>
            </div>
            <div class="col-md-3">
                <label>Categorie</label>
                <select name="category_id" class="form-select" required>
                    <?php
                    $types = $db->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <option value="">Selecteer Categorie</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= $type['category'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label>Gemaakt in</label>
                <input type="text" name="made_in" required>
            </div>
            <div class="col-md-2">
                <label>Size</label>
                <input type="text" name="size" required>
            </div>
            <div class="col-md-6">
                <label>Product Afbeelding</label>
                <input type="file" name="img" class="form-control" accept="image/*" required>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" name="add_product" class="btn btn-success w-100">Toevoegen</button>
            </div>
        </div>
    </form>



    <h4 class="mb-3">Bestaande Producten</h4>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Naam</th>
            <th>Prijs</th>
            <th>Categorie</th>
            <th>Gemaakt in</th>
            <th>Afbeelding</th>
            <th>Acties</th> <!-- New column -->
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>€<?= htmlspecialchars($product['price']) ?></td>
                <td><?= htmlspecialchars($product['category']) ?></td>
                <td><?= htmlspecialchars($product['made_in']) ?></td>
                <td><img src="uploads/<?= htmlspecialchars($product['img']) ?>"
                         alt="<?= htmlspecialchars($product['name']) ?>" style="max-width:100px; height:auto;"></td>
                <td>
                    <a href="admin_panel.php?delete_product=<?= $product['id'] ?>"
                       class="btn btn-sm btn-danger"
                       onclick="return confirm('Weet je zeker dat je dit product wilt verwijderen?');">
                        Verwijder
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>

</body>
</html>


