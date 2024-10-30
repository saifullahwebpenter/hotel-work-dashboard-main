<?php
include 'inc/header.php'; // Ensure this file correctly sets up the $conn variable

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the ID and status from the POST request
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Prepare the SQL update query
    $sql = "UPDATE recharges SET status = ? WHERE id = ?";

    // Prepare the statement
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $status, $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect back to the previous page
            header("Location: recharge-list.php");
            exit();
        } else {
            // Log and display the error
            error_log("Error updating status: " . $stmt->error);
            echo "Error updating status: " . $stmt->error; // Display the error for debugging
        }

        // Close the statement
        $stmt->close();
    } else {
        // Log if statement preparation fails
        error_log("Failed to prepare the SQL statement: " . $conn->error);
        echo "Failed to prepare the SQL statement: " . $conn->error; // Display the error for debugging
    }

    // Close the database connection
    $conn->close();
}
?>