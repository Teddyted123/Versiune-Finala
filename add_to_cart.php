<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];
    $selectedSize = $_POST['selectedSize']; // Retrieve the selected size

    // Validate the product ID and size (you can add more validation as needed)

    // Add the product to the cart (you can store cart data in a session variable)
    $_SESSION['cart'][] = ['id' => $productId, 'size' => $selectedSize];

    // Return a success message or additional data if needed
    echo json_encode(['success' => true]);
} else {
    // Handle invalid requests
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
