<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Redirect to the login page or handle the situation when the user is not logged in
    header('Location: login.php');
    exit;
}

// Fetch cart items for the logged-in user
$userId = $_SESSION['user_id']; // Adjust this based on your actual session variable name
$cartItems = $_SESSION['cart'];

// Insert order into the orders table
$query = "INSERT INTO orders (user_id) VALUES ($userId)";
mysqli_query($conn, $query);

// Get the order ID
$orderId = mysqli_insert_id($conn);

// Insert cart items into the order_items table
foreach ($cartItems as $productId) {
    $query = "INSERT INTO order_items (order_id, product_id) VALUES ($orderId, $productId)";
    mysqli_query($conn, $query);
}

// Clear the cart
$_SESSION['cart'] = [];

// Close the connection
mysqli_close($conn);

// Redirect to a thank you page or display a success message
header('Location: thank_you.php');
exit;
?>
