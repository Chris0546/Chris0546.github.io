<?php
// Start the session so we can save data like user ID after registration
session_start();

// Connect to the database (this file sets up the $db variable)
require 'db_sign_in.php';
global $db; // Makes $db accessible globally (even though in most cases not needed if scoped right)

// Custom error messages for form validation
const USERNAME_ERROR = 'Vul je gebruikersnaam in'; // "Fill in your username"
const PASSWORD_ERROR = 'Vul je wachtwoord in';     // "Fill in your password"
const EMAIL_ERROR = 'Vul je email in';              // "Fill in your email"

//////////////////////////////////////
// 🚀 If the form has been submitted, start processing
//////////////////////////////////////
if (isset($_POST['submit'])) {

    // Create empty arrays to store input values and validation errors
    $errors = [];  // For saving errors per field
    $inputs = [];  // For saving cleaned inputs

    // Sanitize form inputs to prevent XSS (Cross-site Scripting)
    $username = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS); // Clean HTML tags etc.
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);   // Clean email
    $password = $_POST['password'];  // Don't sanitize password (we hash it)

    ////////////////////////////
    // Validate user input
    ////////////////////////////

    // Check if username is empty
    if (empty($username)) {
        $errors['name'] = USERNAME_ERROR;
    } else {
        $inputs['name'] = $username; // Save valid input
    }

    // Check if email is empty
    if (empty($email)) {
        $errors['email'] = EMAIL_ERROR;
    } else {
        $inputs['email'] = $email; // Save valid input
    }

    // Check if password is empty (we’ll hash later)
    if (empty($password)) {
        $errors['password'] = PASSWORD_ERROR;
    }

    ////////////////////////////////
    // If no validation errors, continue with DB
    ////////////////////////////////
    try {
        if (count($errors) === 0) {

            // Check if the username or email already exists in DB
            $checkQuery = $db->prepare('SELECT * FROM form WHERE username = :name OR email = :email');
            $checkQuery->bindParam(':name', $inputs['name']);
            $checkQuery->bindParam(':email', $inputs['email']);
            $checkQuery->execute();

            // If a record is found, store it in $existingUser
            $existingUser = $checkQuery->fetch(PDO::FETCH_ASSOC);

            if ($existingUser) {
                // Username already exists
                if ($existingUser['username'] === $inputs['name']) {
                    $errors['name'] = 'Gebruikersnaam is al in gebruik.';
                }
                // Email already exists
                if ($existingUser['email'] === $inputs['email']) {
                    $errors['email'] = 'Emailadres is al in gebruik.';
                }
            } else {
                ////////////////////////////////////
                //  All good — Insert user into DB
                ////////////////////////////////////

                // Securely hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Set default role
                $role = 'ROLE_USER';

                // Prepare INSERT query to add user to DB
                $query = $db->prepare('
                    INSERT INTO form (username, password, email, time_created, role)
                    VALUES (:name, :password, :email, NOW(), :role)
                ');

                // Bind values into the query
                $query->bindParam(':name', $inputs['name']);
                $query->bindParam(':password', $hashedPassword); // Save hashed password
                $query->bindParam(':email', $inputs['email']);
                $query->bindParam(':role', $role);

                // Run the insert query
                $query->execute();

                /////////////////////////////////////////
                // Log the user in immediately (optional)
                /////////////////////////////////////////
                $_SESSION['user_id'] = $db->lastInsertId(); // Save new user's ID in session

                // Redirect to profile/dashboard
                header('Location: profile.php');
                exit;
            }
        }
    } catch (PDOException $e) {
        // Optional: Save DB error for debugging (in session)
        $_SESSION['error'] = $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registratieformulier</title>

    <!-- Bootstrap CSS for layout and styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-dark py-5"> <!-- Dark background + vertical spacing -->

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- Success alert after registration (if set) -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['success']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset ($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Card UI for registration form -->
            <div class="card shadow">
                <div class="card-body bg-light">
                    <h4 class="card-title mb-4">Registreren</h4>

                    <!-- FORM Starts Here -->
                    <form method="post">

                        <!-- Username Input -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Gebruikersnaam</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   value="<?php echo isset($inputs['name']) ? htmlspecialchars($inputs['name']) : ''; ?>">
                            <?php if (isset($errors['name'])): ?>
                                <div class="text-danger"><?php echo $errors['name']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Emailadres</label>
                            <input type="email" id="email" name="email" class="form-control"
                                   value="<?php echo isset($inputs['email']) ? htmlspecialchars($inputs['email']) : ''; ?>">
                            <?php if (isset($errors['email'])): ?>
                                <div class="text-danger"><?php echo $errors['email']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Wachtwoord</label>
                            <input type="password" id="password" name="password" class="form-control">
                            <?php if (isset($errors['password'])): ?>
                                <div class="text-danger"><?php echo $errors['password']; ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submit" name="submit" class="btn btn-primary w-100">Registreren</button>
                    </form>
                    <!-- FORM Ends -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (for modal, alert close buttons, etc.) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

