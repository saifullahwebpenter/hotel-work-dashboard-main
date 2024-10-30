<?php include 'inc/header.php' ?>

    <div class="login-container">
        <div class="login-form">
            <h2>Change Password</h2>
            <form action="update-password.php" method="POST">
                <div class="input-group">
                    <label for="email">Old Password</label>
                    <input type="password" name="old_password" id="old_password" placeholder="Enter your old password" required>
                </div>
                <div class="input-group">
                    <label for="password">New Password</label>
                    <input type="password" name="new_password" id="new_password" placeholder="Enter your new password" required>
                </div>
                <div class="input-group">
                    <button type="submit" class="login-btn">Update</button>
                </div>
            </form>
        </div>
    </div>


    <?php


    // Display email-specific alert
    if (isset($_SESSION['error_email'])) {
        echo '<script>alert("' . $_SESSION['error_email'] . '");</script>';
        unset($_SESSION['error_email']);  // Clear the session variable
    }

    // Display password-specific alert
    if (isset($_SESSION['error_password'])) {
        echo '<script>alert("' . $_SESSION['error_password'] . '");</script>';
        unset($_SESSION['error_password']);  // Clear the session variable
    }
    ?>
</body>
</html>