<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ensure content-type is JSON
header('Content-Type: application/json');

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel-work";

$conn = new mysqli($servername, $username, $password, $dbname);

// Handle connection errors
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}


$input = json_decode(file_get_contents('php://input'), true);

// Debugging: Write the input to a log file for verification
file_put_contents('debug_log.txt', "Received data: " . print_r($input, true), FILE_APPEND);

$user_id = isset($input['user_id']) ? $input['user_id'] : null;
$amount = isset($input['amount']) ? floatval($input['amount']) : 0;
$transaction_type = isset($input['transaction_type']) ? $input['transaction_type'] : null;

$response = [];

// Check if user ID is provided
if ($user_id === null) {
    $response['error'] = "User ID is missing.";
    echo json_encode($response);
    exit;
} else {
    // Fetch current total_balance
    $query = "SELECT total_balance FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists and fetch balance
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $total_balance = isset($row['total_balance']) ? floatval($row['total_balance']) : null;

            // Verify balance was retrieved
            if ($total_balance !== null) {
                // Adjust balance based on transaction type
                if ($transaction_type === 'System Increase') {
                    $total_balance += $amount;
                } elseif ($transaction_type === 'System Deduction') {
                    $total_balance -= $amount;
                }

                // Update the total_balance in users table
                $updateQuery = "UPDATE users SET total_balance = ? WHERE id = ?";
                $updateStmt = $conn->prepare($updateQuery);
                if ($updateStmt) {
                    $updateStmt->bind_param("di", $total_balance, $user_id);

                    if ($updateStmt->execute()) {
                        // Insert transaction record
                        $insertQuery = "INSERT INTO transactions (user_id, transaction_type, amount) VALUES (?, ?, ?)";
                        $insertStmt = $conn->prepare($insertQuery);
                        if ($insertStmt) {
                            $insertStmt->bind_param("isd", $user_id, $transaction_type, $amount);

                            if ($insertStmt->execute()) {
                                $response['success'] = true;
                            } else {
                                $response['error'] = "Failed to insert transaction record.";
                            }
                            $insertStmt->close();
                        } else {
                            $response['error'] = "Failed to prepare insert statement.";
                        }
                    } else {
                        $response['error'] = "Failed to update total balance.";
                    }
                    $updateStmt->close();
                } else {
                    $response['error'] = "Failed to prepare update statement.";
                }
            } else {
                $response['error'] = "Failed to retrieve total balance.";
            }
        } else {
            $response['error'] = "User not found.";
        }
        $stmt->close();
    } else {
        $response['error'] = "Failed to prepare select statement.";
    }
}

// Close database connection
$conn->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
