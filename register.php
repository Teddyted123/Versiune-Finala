<?php
// Include the database connection file
include 'db_connection.php';

// Check if the $conn variable is defined
if (!$conn) {
    die("Database connection not available.");
}

// Initialize variables to store user input
$username = $password = $email = '';
$error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize user input
    $username = htmlspecialchars(trim($_POST['fullname']));
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

    // Check if all required fields are filled
    if (empty($username) || empty($password) || empty($email)) {
        $error = "All fields are required.";
    } else {
        // Insert user data into the database
        $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);

        // Check if the statement is prepared successfully
        if ($stmt) {
            $stmt->bind_param("sss", $username, $password, $email);

            if ($stmt->execute()) {
                // Registration successful
                header("Location: login.php"); // Redirect to login page
                exit();
            } else {
                // Registration failed
                $error = "Registration failed. Please try again.";
            }

            $stmt->close();
        } else {
            // Statement preparation failed
            $error = "Database error. Please try again later.";
        }
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
                <input type="text" id="fullname" name="fullname" value="<?php echo $username; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
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
