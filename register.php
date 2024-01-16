<?php
session_start();

include("db_connection.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Path to Composer autoload

// Verificăm dacă utilizatorul a fost înregistrat cu succes și email-ul a fost trimis
if (isset($_SESSION['registration_success'])) {
    echo "
        <script>
            alert('You are registered successfully. Please check your email for verification.');
        </script>";
    unset($_SESSION['registration_success']); // Eliberăm sesiunea pentru a nu afișa mesajul de succes la reîncărcarea paginii
}

// Verificăm dacă formularul a fost trimis
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = stripslashes($_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $username);
    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($conn, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($conn, $password);
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(50));

    // Insert user data into the database
    $query = "INSERT into `users` (username, password, email, token, isEmailVerified)
              VALUES ('$username', '$password_hashed', '$email', '$token', '0')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Send email verification
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'muscletedd@gmail.com'; // Your Gmail username
            $mail->Password = 'gnjt hkgc gurj ribo'; // Your Gmail app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            //Recipients
            $mail->setFrom('muscletedd@gmail.com', 'TrendyCloset'); // Your Gmail address and your name
            $mail->addAddress($email, $username); // User's email and username
            $mail->addReplyTo('muscletedd@gmail.com', 'TrendyCloset'); // Your Gmail address and your name

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body = "Click the following link to verify your email: <a href='http://localhost/Site%20PI/verify.php?token=$token'>Verify Email</a>";

            $mail->send();

            // Setăm variabila de sesiune pentru a afișa mesajul de succes
            $_SESSION['registration_success'] = true;
            header("Location: register.php"); // Redirectăm pentru a evita re-trimiterea formularului la reîncărcarea paginii
            exit();
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "Please enter some valid information!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrendyCloset - Register</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<section id="header">
    <a href="#"><img src="Imagini/logo.png" class="logo" alt=""></a>

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
    <div class="register-form">
        <h2>Register</h2>
        <form method="post" action="register.php">
            <div class="form-group">
                <label for="fullname">Username:</label>
                <input type="text" id="fullname" name="fullname">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>

        <?php
        // Display errors if any
        if (!empty($error)) {
            echo '<p class="error">' . $error . '</p>';
        }
        ?>
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
