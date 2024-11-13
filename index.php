<?php
// Include the database connection file
require 'db.php';

// Initialize the login error message variable
$loginError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password']; // Plaintext password for verification

    // Prepare SQL query to check if the user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    
    // Execute the query and fetch the user
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify password
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct
        header('Location: welcome.php');
    } else {
        // Incorrect username or password
        $loginError = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Project - Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <p class="title">Login</p>
            <form class="form" method="POST" action="index.php">

                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                    <div class="forgot">
                        <a href="#">Forgot Password?</a>
                    </div>
                </div>

                <button class="sign" type="submit">Sign in</button>
            </form>

            <!-- Display success or error message -->
            <?php if (!empty($loginSucc)): ?>
                <div class="success-message">
                    <p><?php echo $loginSucc; ?></p>
                </div>
            <?php elseif (!empty($loginError)): ?>
                <div class="error-message">
                    <p><?php echo $loginError; ?></p>
                </div>
            <?php endif; ?>

            <p class="signup">Don't have an account?
                <a href="./register.php">Sign up</a>
            </p>
        </div>
    </div>
</body>

</html>
