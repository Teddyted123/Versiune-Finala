<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifică dacă 'product_id' este setat în $_POST
    if (!isset($_POST['product_id'])) {
        die('Product ID is not set.');
    }

    // Verifică dacă utilizatorul este autentificat
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $userId = $_SESSION['id'];
        $productId = $_POST['product_id'];
        $selectedSize = $_POST['selected_size'];
        $quantity = $_POST['quantity'];

        // Verifică dacă produsul cu aceeași mărime există deja în coș
        $existingProductQuery = "SELECT id, quantity FROM cart WHERE user_id = $userId AND product_id = $productId AND selected_size = '$selectedSize'";
        $existingProductResult = mysqli_query($conn, $existingProductQuery);

        if ($existingProductResult) {
            $existingProduct = mysqli_fetch_assoc($existingProductResult);

            if ($existingProduct) {
                // Produsul există deja în coș, actualizăm cantitatea
                $newQuantity = $existingProduct['quantity'] + $quantity;
                $updateQuery = "UPDATE cart SET quantity = $newQuantity WHERE id = {$existingProduct['id']}";
                $updateResult = mysqli_query($conn, $updateQuery);

                if ($updateResult) {
                    echo 'Product quantity updated in cart successfully!';
                } else {
                    // Handle error
                    echo 'Error updating product quantity in cart. Please try again.';
                }
            } else {
                // Produsul nu există în coș, îl adăugăm
                $query = "SELECT pret FROM produse WHERE id = $productId";
                $result = mysqli_query($conn, $query);

                if ($result && $product = mysqli_fetch_assoc($result)) {
                    $price = $product['pret'];

                    // Adaugă produsul în coș
                    $insertQuery = "INSERT INTO cart (user_id, product_id, quantity, price, selected_size) 
                                    VALUES ($userId, $productId, $quantity, $price, '$selectedSize')";
                    $insertResult = mysqli_query($conn, $insertQuery);

                    if ($insertResult) {
                        echo 'Product added to cart successfully!';
                    } else {
                        // Handle error
                        echo 'Error adding product to cart. Please try again.';
                    }
                } else {
                    // Handle error
                    echo 'Error fetching product details.';
                }
            }

            // Eliberăm rezultatul interogării
            mysqli_free_result($existingProductResult);
        } else {
            // Handle error
            echo 'Error fetching existing product details.';
        }
    } else {
        // Redirect to login page if not logged in
        echo 'User not logged in.';
    }
} else {
    // Handle invalid request method
    die('Invalid request method.');
}
?>
