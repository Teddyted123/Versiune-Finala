<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifică dacă utilizatorul este autentificat
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $userId = $_SESSION['id'];
        $productId = $_POST['product_id'];
        $rating = $_POST['rating'];

        // Verifică dacă utilizatorul a evaluat deja acest produs
        $checkRatingQuery = "SELECT * FROM ratings WHERE user_id = ? AND product_id = ?";
        $stmtCheckRating = mysqli_prepare($conn, $checkRatingQuery);
        mysqli_stmt_bind_param($stmtCheckRating, 'ii', $userId, $productId);
        mysqli_stmt_execute($stmtCheckRating);
        $checkRatingResult = mysqli_stmt_get_result($stmtCheckRating);

        if (mysqli_num_rows($checkRatingResult) > 0) {
            // Utilizatorul a evaluat deja produsul, așa că facem un UPDATE
            $updateRatingQuery = "UPDATE ratings SET rating = ? WHERE user_id = ? AND product_id = ?";
            $stmtUpdateRating = mysqli_prepare($conn, $updateRatingQuery);
            mysqli_stmt_bind_param($stmtUpdateRating, 'iii', $rating, $userId, $productId);
            mysqli_stmt_execute($stmtUpdateRating);
        } else {
            // Utilizatorul nu a evaluat încă produsul, așa că facem un INSERT
            $insertRatingQuery = "INSERT INTO ratings (user_id, product_id, rating) VALUES (?, ?, ?)";
            $stmtInsertRating = mysqli_prepare($conn, $insertRatingQuery);
            mysqli_stmt_bind_param($stmtInsertRating, 'iii', $userId, $productId, $rating);
            mysqli_stmt_execute($stmtInsertRating);
        }

        // Calculează ratingul mediu și actualizează tabela produselor
        $averageRatingQuery = "SELECT AVG(rating) AS average FROM ratings WHERE product_id = ?";
        $stmtAverageRating = mysqli_prepare($conn, $averageRatingQuery);
        mysqli_stmt_bind_param($stmtAverageRating, 'i', $productId);
        mysqli_stmt_execute($stmtAverageRating);
        $averageRatingResult = mysqli_stmt_get_result($stmtAverageRating);

        if ($averageRatingResult && $averageRatingData = mysqli_fetch_assoc($averageRatingResult)) {
            $averageRating = round($averageRatingData['average']);
            $updateProductRatingQuery = "UPDATE produse SET rating = ? WHERE id = ?";
            $stmtUpdateProductRating = mysqli_prepare($conn, $updateProductRatingQuery);
            mysqli_stmt_bind_param($stmtUpdateProductRating, 'ii', $averageRating, $productId);
            mysqli_stmt_execute($stmtUpdateProductRating);
        }

        echo 'Rating updated successfully!';
    } else {
        echo 'Utilizatorul nu este autentificat.';
    }
} else {
    echo 'Cerere invalidă.';
}
?>
