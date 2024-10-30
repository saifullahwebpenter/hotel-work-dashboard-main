<?php include 'inc/header.php' ?>

    <div class="login-container">
        <div class="login-form">
            <h2>Login</h2>
            <form action="login_admin.php" method="POST">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Enter your password" required>
                </div>
                <div class="input-group">
                    <button type="submit" class="login-btn">Login</button>
                </div>
                <!-- <div class="extra-links">
                    <a href="#">Forgot your password?</a>
                    <p>Don't have an account? <a href="signup.html">Sign Up</a></p>
                </div> -->
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