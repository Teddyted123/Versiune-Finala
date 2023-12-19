<?php
include("db_connection.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate the user's credentials directly
    $query = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_data = $result->fetch_assoc();

        if ($user_data && password_verify($password, $user_data['password'])) {
            // Set user session upon successful login
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $user_data['username']; // Use the correct field name
            $_SESSION['id'] = $user_data['id'];

            header("Location: index.php"); // Redirect to your welcome page or dashboard
            exit();
        } else {
            // Invalid credentials
            $error = "Invalid username or password. Please try again.";
        }

        $stmt->close();
    } else {
        // Statement preparation failed
        $error = "Database error. Please try again later.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrendyCloset</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section id="header">
        <a herf="#"><img src="Imagini/logo.png" class="logo" alt=""></a>

        <div>
            <ul id="navbar">
                <li><a class="underline-hover-effect" href="index.php">Home</a></li>
                <li><a class="underline-hover-effect" href="shop.php">Men</a></li>
                <li><a class="underline-hover-effect" href="shop2.php">Women</a></li>
                <li><a class="underline-hover-effect" href="about.php">About</a></li>
                <li><a class="underline-hover-effect" href="contact.php">Contact</a></li>
                <li><a class="underline-hover-effect" href="login.php">Login</a></li>
                <li><a class="underline-hover-effect" href="register.php">Register</a></li>
            </ul>
        </div>
    </section>
    
    <section id="login">
    <div class="login-form">
        <h2>Login</h2>
        <form action="login.php" method="post"> <!-- Specify the method as POST and the action as login.php -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
    </section>
    

    <section id="newsletter" class="section-p1 section-m1">
        <div class="newstext">
            <h4>Sign Up For Newsletters</h4>
            <p>Get e-mail updates about our latest special offers. </p>
        </div>
        <div class="form">
            <input type="text" placeholder="Enter your email address">
            <button class="normal">Sign Up</button>
        </div>
    </section>

    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="Imagini/logo2.png" alt="">
            <h4>Contact</h4>
            <p><strong>Address:</strong> Timisoara, Romania</p>
            <p><strong>Phone:</strong> +40 720 226 382</p>
            <p><strong>Email:</strong> tudor.calugar03@e-uvt.ro</p>
            <div class="follow">
                <h4>Follow us</h4>
                <div class="icon">
                    <i class="fab fa-facebook-f"></i>
                    <i class="fab fa-instagram"></i>
                </div>
            </div>
        </div>

        <div class="col">
            <h4>Information</h4>
            <a href="#">About us</a>
            <a href="#">Contact us</a>
            <a href="#">Terms & Conditions</a>
        </div>
    </footer>

    <script src="script.js"></script>
</body>

</html>