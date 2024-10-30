<!-- Auth -->
<?php

// Check if the user is logged in by checking if the session variable 'user_id' exists
if (!isset($_SESSION['email'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit(); // Make sure to exit to prevent further code execution
}
