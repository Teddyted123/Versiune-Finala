<?php
session_start();
include 'db_connection.php';

// Assuming you have the product ID in the URL, adjust accordingly
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product details and average rating from the database
    $query = "SELECT produse.*, AVG(ratings.rating) AS average_rating 
              FROM produse 
              LEFT JOIN ratings ON produse.id = ratings.product_id 
              WHERE produse.id = $productId";
    $result = mysqli_query($conn, $query);

    if ($result && $product = mysqli_fetch_assoc($result)) {
        $productName = $product['nume'];
        $productImage = $product['imagine'];
        $productDescription = $product['descriere'];
        $productPrice = $product['pret'];
        $averageRating = round($product['average_rating']); // Round to the nearest integer
        // Add more fields as needed

        // Free result set
        mysqli_free_result($result);
    } else {
        // Handle error or redirect to a 404 page
        die("Product not found");
    }
} else {
    // Handle error or redirect to a 404 page
    die("Product ID not provided");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrendyCloset - <?php echo $productName; ?></title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .rated {
            color: gold;
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <section id="header">
        <a href="#"><img src="Imagini/logo.png" class="logo" alt=""></a>

        <div>
            <ul id="navbar">
                <li><a class="underline-hover-effect" href="index.php">Home</a></li>
                <li><a class="underline-hover-effect" href="shop.php">Men</a></li>
                <li><a class="underline-hover-effect" href="shop2.php">Women</a></li>
                <li><a class="underline-hover-effect" href="about.html">About</a></li>
                <li><a class="underline-hover-effect" href="contact.html">Contact</a></li>

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

    <!-- Product Details Section -->
    <section id="prodetails" class="section-p1">
        <div class="single-pro-image">
            <img src="<?php echo $productImage; ?>" width="100%" id="MainImg" alt="<?php echo $productName; ?>">
        </div>
        <div class="single-pro-details">
            <h4><?php echo $productName; ?></h4>
            <div class="star" id="ratingStars" data-product-id="<?php echo $productId; ?>">
                <?php
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $averageRating) {
                        echo '<i class="fas fa-star rated"></i>';
                    } else {
                        echo '<i class="far fa-star"></i>';
                    }
                }
                ?>
            </div>
            <h2><?php echo $productPrice; ?> RON</h2>
            <select name="selected_size" required>
              <option value="" disabled selected>Select size</option>
              <option value="XXL">XXL</option>
              <option value="XL">XL</option>
              <option value="L">L</option>
              <option value="M">M</option>
              <option value="S">S</option>
              <option value="XS">XS</option>
            </select>

            <input type="number" value="1">
            <button class="normal addToCart" data-product-id="<?php echo $product['id']; ?>" data-product-size="selected_size">Add to Cart</button>
            <h4>Product Details</h4>
            <span><?php echo $productDescription; ?></span>
        </div>
    </section>

    <!-- Newsletter Section -->
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

    <!-- Footer Section -->
    <footer class="section-p1">
        <div class="col">
            <!-- Your footer content goes here -->
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
            <!-- Your footer content goes here -->
            <h4>Information</h4>
            <a href="#">About us</a>
            <a href="#">Contact us</a>
            <a href="#">Terms & Conditions</a>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    $(document).ready(function () {
    <?php
    // Check if the user is logged in
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        echo '
        $(".addToCart").click(function () {
            var productId = $(this).data("product-id");

            $.ajax({
                type: "POST",
                url: "add_to_cart.php",
                data: {
                    productId: productId
                },
                dataType: "json",
                success: function (response) {
                    if (response.success) {
                        // Optional: Handle success, e.g., show a confirmation message
                        console.log("Product added to cart successfully");
                    } else {
                        // Optional: Handle error, e.g., show an error message
                        console.error("Error adding product to cart: " + response.error);
                    }
                },
                error: function () {
                    // Optional: Handle AJAX error
                    console.error("Error adding product to cart");
                }
            });
        });
        ';
    }
    ?>
});

    </script>

</body>
</html>
