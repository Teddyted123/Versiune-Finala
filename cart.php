<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to the login page if the user is not logged in
    header('Location: login.php');
    exit;
}

// Fetch cart items for the logged-in user
$userId = $_SESSION['id']; // Assuming 'id' is the user ID field in your users table
$query = "SELECT c.*, p.nume AS product_name 
          FROM cart c
          JOIN produse p ON c.product_id = p.id
          WHERE c.user_id = $userId";
$result = mysqli_query($conn, $query);

// Check for errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Fetch cart items as an associative array
$cartItems = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Free result set
mysqli_free_result($result);

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <!-- Add your stylesheets and other head elements here -->

    <!-- Add your scripts here -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="script.js"></script>
</head>
<body>
    <!-- Your HTML and styling for displaying cart items go here -->
    <h1>Your Cart</h1>

    <?php
    // Display cart items
    foreach ($cartItems as $item) {
        echo '<div>';
        echo '<p>Product: ' . $item['nume'] . '</p>';
        echo '<p>Size: ' . $item['size'] . '</p>'; 
        echo '<p>Price: ' . $item['pret'] . ' RON</p>';
        // Add more details as needed
        echo '</div>';
    }
    ?>

    <!-- Add your cart summary, total, and checkout button here -->

    <div>
        <h2>Cart Summary</h2>
        <?php
        $totalPrice = array_sum(array_column($cartItems, 'pret'));
        echo '<p>Total Price: ' . $totalPrice . ' RON</p>';
        ?>
    </div>

    <form action="place_order.php" method="post">
        <input type="submit" value="Place Order">
    </form>

</body>
</html>
