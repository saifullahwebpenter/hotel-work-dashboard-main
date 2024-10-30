





<?php
include 'inc/header.php';
$error = '';
// Enable error reporting (for debugging purposes)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection or other necessary files here
// require 'db_connection.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect the form data
    $user_id = $_POST['user_id'] ?? '';

    $member_account = $_POST['member_account'] ?? '';
    $member_phone = $_POST['member_phone'] ?? '';
    $member_level = $_POST['member_level'] ?? '';
    
    $member_status = $_POST['member_status'] ?? '';

    // Example: Update user logic (use prepared statements to avoid SQL injection)
    // Assume you have a function called updateUser that handles the database update
    // $result = updateUser($user_id, $member_account, $member_phone, $member_level, $member_type, $member_status);
    $sql = "UPDATE users SET username = '$member_account', number = '$member_phone', member_level = '$member_level', member_status = '$member_status' WHERE id = '$user_id'"; 
    // var_dump($sql); exit;

    // Execute query
    if ($result = mysqli_query($conn, $sql)) {
        $result = true;
        if ($result) {
            // If the update was successful
            $response = ['success' => true, 'message' => 'User updated successfully!'];
            header('Content-Type: application/json');

    // Send the JSON response
            echo json_encode($response);
            header("Location: member-list.php");
            exit;
        } else {
            // If there was an issue with the update
            $response = ['success' => false, 'message' => 'Failed to update user.'];
        }

   
    }
} else {
    // If the request method is not POST, you can return an error response
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
?>
