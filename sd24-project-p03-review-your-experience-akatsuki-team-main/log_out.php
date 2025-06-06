<?php
session_start(); // Start the session (even if empty)
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Optionally clear session cookie if you want to be extra safe
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

// Redirect to homepage or login
header("Location: index.php");
exit;
?>


