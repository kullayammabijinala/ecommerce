<?php
session_start();
session_start(); // Start the session

// Destroy the session
session_unset();
session_destroy();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to the login page
header('Location: login.php');
exit();
?>