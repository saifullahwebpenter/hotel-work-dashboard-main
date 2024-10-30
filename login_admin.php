<?php
include 'inc/header.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Check if the email exists
    $stmt = $conn->prepare("SELECT * FROM admin_credentials WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); 
        // var_dump($row); exit;
        
        // Now verify the password
        if ($row["password"] === $password) {  // Assuming password is stored as plain text (not recommended)
            // Set session variables
            $_SESSION["id"] = $row["id"];
            $_SESSION["profile_photo"] = $row["profile_photo"];
            $_SESSION["name"] = $row["name"];
            $_SESSION["email"] = $row["email"];
            $_SESSION["role"] = $row["role"];
            // $_SESSION["ref_code"] = $row["ref_code"];
            // $_SESSION["credit"] = $row["credit"];
            // $_SESSION["total_balance"] = $row["total_balance"];
            // $_SESSION["today_commission"] = $row["today_commission"];
            // $_SESSION["total_commission"] = $row["total_commission"];

            header("Location: index.php");
            exit;
        } else {
            // Incorrect password
            $_SESSION['error_password'] = "Incorrect password";  // Set password error
            header("Location: login.php");
            exit;
        }
    } else {
        // Incorrect email
        $_SESSION['error_email'] = "Email is wrong";  // Set email error
        header("Location: login.php");
        exit;
    }

    $stmt->close();
}
?>
