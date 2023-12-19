<?php
// rate_product.php

// Include your database connection
include 'db_connection.php';

// Assuming you receive the product ID and rating in the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['productId'];
    $rating = $_POST['rating'];
    $userId = $_SESSION['user_id']; // Assuming you store the user ID in the session

    // Check if the user has already rated this product
    $checkRatingQuery = "SELECT * FROM ratings WHERE product_id = $productId AND user_id = $userId";
    $checkRatingResult = mysqli_query($conn, $checkRatingQuery);

    if ($checkRatingResult && mysqli_num_rows($checkRatingResult) > 0) {
        // User has already rated, update the existing rating
        $updateQuery = "UPDATE ratings SET rating = $rating WHERE product_id = $productId AND user_id = $userId";
    } else {
        // User has not rated, insert a new rating
        $updateQuery = "INSERT INTO ratings (product_id, user_id, rating) VALUES ($productId, $userId, $rating)";
    }

    $result = mysqli_query($conn, $updateQuery);

    if ($result) {
        // Return a success message or additional data if needed
        echo json_encode(['success' => true]);
    } else {
        // Return an error message if the update fails
        echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
    }
} else {
    // Handle invalid requests
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>
