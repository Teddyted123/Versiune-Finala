<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];
    $userId = $_SESSION['id'];

    $query = "DELETE FROM cart WHERE user_id = $userId AND product_id = $productId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo 'Product removed from cart successfully!';
    } else {
        echo 'Error removing product from cart. Please try again.';
    }
}
?>
