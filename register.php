<?php
// Include the database connection file
require 'db.php';

// Initialize success and error message variables
$loginSucc = "";
$registerError = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve and sanitize form data
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $username = htmlspecialchars($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Prepare an SQL query to insert user data
    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, username, password) VALUES (:firstname, :lastname, :username, :password)");
    
    // Bind parameters and execute the query
    try {
        $stmt->execute([
            ':firstname' => $firstname,
            ':lastname' => $lastname,
            ':username' => $username,
            ':password' => $password,
        ]);
        $loginSucc = "Registration successful!";
    } catch (PDOException $e) {
        // Check if the error code is 23000 for duplicate entry
        if ($e->getCode() == 23000) {
            $registerError = "Username is already taken. Please choose a different username.";
        } else {
            $registerError = "An error occurred. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Project - Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="form-container">
            <p class="title">Register</p>
            <form class="form" method="POST" action="register.php">

                <div class="input-group">
                    <label for="firstname">First Name</label>
                    <input type="text" name="firstname" id="firstname" required>
                </div>

                <div class="input-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" name="lastname" id="lastname" required>
                </div>

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

                <button class="sign" type="submit">Register Now</button>
            </form>

            <!-- Display success or error message -->
            <?php if (!empty($loginSucc)): ?>
                <div class="success-message">
                    <p><?php echo $loginSucc; ?></p>
                </div>
            <?php elseif (!empty($registerError)): ?>
                <div class="error-message">
                    <p><?php echo $registerError; ?></p>
                </div>
            <?php endif; ?>

            <p class="signup">Have an account?
                <a href="./index.php">Login</a>
            </p>
        </div>
    </div>
</body>

</html>
