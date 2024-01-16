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
    <script src="/Site PI/jquery3.7.1.js"></script>
    <script src="script_v3.js"></script>
    
    
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
            <div class="star-rating" data-product-id="<?php echo $productId; ?>">
            <!-- Rating Section -->
        <div class="rating-section" data-product-id="<?php echo $productId; ?>">
            <div class="star-rating" data-product-id="<?php echo $productId; ?>">
                <?php
                // Generare stele pentru ratingul mediu
                for ($i = 1; $i <= 5; $i++) {
                    $starClass = ($i <= $averageRating) ? 'rated' : '';
                    echo '<span class="star ' . $starClass . '" data-value="' . $i . '" style="color: #ccc; font-size: 24px; cursor: pointer;">&#9733;</span>';
                }
                ?>
            </div>
        </div>
        </div>
            <h2><?php echo $productPrice; ?> RON</h2>

            <form id="addToCartForm" action="add_to_cart.php" method="POST">
                <select name="selected_size" required>
                    <option value="" disabled selected>Select size</option>
                    <option value="XXL">XXL</option>
                    <option value="XL">XL</option>
                    <option value="L">L</option>
                    <option value="M">M</option>
                    <option value="S">S</option>
                    <option value="XS">XS</option>
                </select>

            <input type="number" name="quantity" value="1" min="1">
            <button class="normal addToCart" data-product-id="<?php echo $productId; ?>">Add to Cart</button>
            </form>
            
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


<script>
$(document).ready(function () {
    // Handle star hover effect
    $('.star-rating .star').hover(function () {
        var currentStar = parseInt($(this).attr('data-value'));
        $(this).css('color', 'yellow'); // Change color for the hovered star
        $(this).prevAll('.star').css('color', 'yellow'); // Change color for previous stars
    }, function () {
        $(this).parent().find('.star').css('color', '#ccc'); // Reset all stars to default color
    });

    // Handle star click event
    $('.star-rating .star').click(function () {
        var ratingValue = parseInt($(this).attr('data-value'));
        var productId = $(this).parent().attr('data-product-id');

        // Update the UI based on the selected rating
        $(this).parent().find('.star').css('color', '#ccc'); // Reset all stars to default color
        for (var i = 1; i <= ratingValue; i++) {
            $(this).parent().find('.star[data-value="' + i + '"]').css('color', 'yellow'); // Change color for selected stars
        }

        // Send the AJAX request to the server
        $.ajax({
            url: 'rate_product.php',
            method: 'POST',
            data: {
                product_id: productId,
                rating: ratingValue
            },
            success: function (response) {
                alert(response);
            }
        });

        

    });
});
</script>

</body>
</html>
