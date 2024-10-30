<?php
include 'inc/header.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['id'];
    $old_password = $_POST["old_password"];
    $new_password = $_POST["new_password"];

    $sql = "SELECT password FROM admin_credentials WHERE id = '$user_id'";

    // Execute query
    $result = mysqli_query($conn, $sql);

    // Check if results were returned
    if (mysqli_num_rows($result) > 0) {
        // Fetch the password from the database
        $row = mysqli_fetch_assoc($result); 
        $pass = $row['password']; 

        // Check if the old password matches the one in the database
        if ($old_password != $pass) {
            echo '<script>alert("Old password is incorrect. Please try again.");</script>';
        } else {
            // SQL update query
            $sql = "UPDATE admin_credentials SET password = '$new_password' WHERE id = '$user_id'";

            // Execute query
            if (mysqli_query($conn, $sql)) {
                // Set success message in session
                $_SESSION['password_change_success'] = "Password updated successfully!";
                // Redirect to profile.php
                header("Location: index.php");
                exit();
            } else {
                echo '<script>alert("Error updating password: ' . mysqli_error($conn) . '");</script>';
            }
        }
    }
}
?>
