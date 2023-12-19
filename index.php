<?php
session_start();
include 'db_connection.php';

// Fetch men's clothes from the database
$query = "SELECT * FROM produse WHERE id IN (1, 2, 7, 14)";
$result = mysqli_query($conn, $query);

// Check for errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
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
              
                

                
                <?php
                // Check if the user is logged in
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
                    echo '<li><a class="underline-hover-effect" href="cart.php"><i class="far fa-shopping-bag"></i></a></li>';
                    echo '<li><a class="underline-hover-effect" href="logout.php">Logout</a></li>';
                    
                } else {
                    echo '<li><a class="underline-hover-effect" href="login.php">Login</a></li>';
                    echo '<li><a class="underline-hover-effect" href="register.php">Register</a></li>';
                }
                ?>

            </ul>
        </div>
    </section>

    

    <section id="hero">
        <h4>Trade-in-offer</h4>
        <h2>Super value deals</h2>
        <h1>On all products</h1>
        <p>Save more with cupons & up to 70% off!</p>
        <button onclick="window.location.href='allproducts.php';">Shop now</button>
    </section>

    <section id="feature" class="section-p1">
        <div class="fe-box">
            <img src="Imagini/features/f1.png" alt="">
            <h6>Free shipping</h6>
        </div>

        <div class="fe-box">
            <img src="Imagini/features/f2.png" alt="">
            <h6>Online Order</h6>
        </div>

        <div class="fe-box">
            <img src="Imagini/features/f4.png" alt="">
            <h6>Promotions</h6>
        </div>

        <div class="fe-box">
            <img src="Imagini/features/f6.png" alt="">
            <h6>24/7 Support</h6>
        </div>

    </section>

    <section id="product1" class="section-p1">
        <h2>Best Sellers</h2>
        <p>Winter Collection</p>

        <div class="pro-container">
            <?php
            // Loop through men's clothes
            while ($product = mysqli_fetch_assoc($result)) {
                ?>
                <!-- Add your star rating logic here -->
                <div class="pro" onclick="window.location.href='product.php?id=<?php echo $product['id']; ?>';">
                    <img src="<?php echo $product['imagine']; ?>" alt="">
                    <div class="des">
                        <!-- Remove the brand information -->
                        <h5><?php echo $product['nume']; ?></h5>
                        <div class="star">
                            <?php
                            $rating = $product['rating']; // Assuming you have a 'rating' field in your database
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $rating) {
                                    echo '<i class="fas fa-star"></i>';
                                } else {
                                    echo '<i class="far fa-star"></i>';
                                }
                            }
                            ?>
                        </div>
                        <h4><?php echo $product['pret']; ?> RON</h4>
                    </div>
                </div>
            <?php } ?>
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
                <a href="https://www.facebook.com/teddy.ted.71066/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/teddyted_02/" target="_blank"><i class="fab fa-instagram"></i></a>
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