<?php
// Start the session if it hasn't been started
session_start();

// Clear all session variables
$_SESSION = array();

// If a session cookie is used, destroy it
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Destroy the session
session_destroy();

// Redirect to the home page
header("Location: water_quality.php");
exit();
?>