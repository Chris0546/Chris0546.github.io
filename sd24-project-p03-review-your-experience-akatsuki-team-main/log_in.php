<?php
// Start the session so we can store user data (like login info)
session_start();

// Include your database connection (this file should set $db)
require 'db_sign_in.php';

// Make $db available globally in case you're using it outside of functions
global $db;

// Array to store any input errors
$errors = [];

///////////////////////////////////////////////////////////
// HANDLE FORM SUBMISSION WHEN USER CLICKS "Inloggen"
///////////////////////////////////////////////////////////
if (isset($_POST['submit'])) {

    // Sanitize username input (protect against XSS & invalid chars)
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);

    // Password is left raw — we'll hash-check it later with password_verify()
    $password = $_POST['password'];

    // If username field is empty, set error message
    if (empty($username)) {
        $errors['username'] = 'Voer je gebruikersnaam in.'; // "Enter your username"
    }

    // If password field is empty, set error message
    if (empty($password)) {
        $errors['password'] = 'Voer je wachtwoord in.'; // "Enter your password"
    }

    ///////////////////////////////////////////////////////
    // If there are NO errors, we continue with login
    ///////////////////////////////////////////////////////
    if (empty($errors)) {
        try {
            // Prepare SQL query to find user by username
            $query = $db->prepare('SELECT * FROM form WHERE username = :username');
            $query->bindParam(':username', $username);
            $query->execute();

            // Fetch user data from DB (or false if not found)
            $user = $query->fetch(PDO::FETCH_ASSOC);

            // If user exists and password is correct (using password_verify)
            if ($user && password_verify($password, $user['password'])) {

                //  Store user info in session for later use
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;

                // Decode role from DB — might be a JSON array or plain string
                $user_roles = isset($user['role']) ? json_decode($user['role'], true) : [];

                // If decoding fails (means role is a string), make it an array
                if (!is_array($user_roles)) {
                    $user_roles = [$user['role']];
                }

                // Store roles in session
                $_SESSION['roles'] = $user_roles;

                // Redirect user based on their role
                if (in_array('ROLE_ADMIN', $user_roles)) {
                    header('Location: admin_panel.php'); // Admin dashboard
                } else {
                    header('Location: profile.php'); // Regular user profile
                }

                // Always exit after header redirect
                exit;

            } else {
                // Login failed: username doesn't exist or password is wrong
                $errors['login'] = 'Gebruikersnaam of wachtwoord is onjuist.';
            }
        } catch (PDOException $e) {
            //  Log technical error (optional) and show a generic message
            error_log($e->getMessage());
            $errors['login'] = 'Er is een fout opgetreden. Probeer later opnieuw.';
        }
    }
}
?>



<!doctype html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-danger">

<?php
// Check if there is a session variable named 'alert'
// This is usually set when you want to show a message on the next page load
if (isset($_SESSION['alert'])) {
    // Display the alert message on the screen (e.g. "You are now logged out")
    echo $_SESSION['alert'];
    // Remove the alert from session so it only shows ONCE
    unset($_SESSION['alert']);
}
?>


<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow p-4 bg-light p-0" style="width: 100%; max-width: 400px;">
        <h2 class="card-title text-center mb-4">Inloggen</h2>

        <?php if (!empty($errors['login'])): ?>
            <!-- Check if there is a login error and show it in a red alert box -->
            <div class="alert alert-danger" role="alert">
                <?= $errors['login'] ?> <!-- Show login error text stored in $errors['login'] -->
            </div>
        <?php endif; ?>


        <form method="post" novalidate>
            <div class="mb-3">
                <label for="username" class="form-label">Gebruikersnaam</label>
                <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                <div class="invalid-feedback">
                    <?= $errors['username'] ?? '' ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Wachtwoord</label>
                <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" id="password" name="password" required>
                <div class="invalid-feedback">
                    <?= $errors['password'] ?? '' ?>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" name="submit" class="btn btn-primary">Inloggen</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

