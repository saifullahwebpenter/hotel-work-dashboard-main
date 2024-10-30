
<?php
include 'inc/header.php';
// Enable error reporting (for debugging only)
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Replace with your actual database connection code


if (isset($_POST['user_id'])) {
    $userId = $_POST['user_id'];

    // Query to fetch user data
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'User not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}
?>