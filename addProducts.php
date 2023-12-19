<?php
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $nume = $_POST['nume'];
    $imagine = $_POST['imagine'];
    $descriere = $_POST['descriere'];
    $pret = $_POST['pret'];
    $cantitate = $_POST['cantitate'];

    // Insert data into the 'produse' table
    $query = "INSERT INTO produse (nume, imagine, descriere, pret, cantitate) VALUES ('$nume', '$imagine', '$descriere', $pret, $cantitate)";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "Product added successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <h2>Add Product</h2>
    <form method="POST" action="">
        <label for="nume">Product Name:</label>
        <input type="text" name="nume" required>

        <label for="imagine">Image URL:</label>
        <input type="text" name="imagine" required>

        <label for="descriere">Description:</label>
        <input type="text" name="descriere" required>

        <label for="pret">Price:</label>
        <input type="number" name="pret" required>

        <label for="cantitate">Quantity:</label>
        <input type="number" name="cantitate" required>

        <button type="submit">Add Product</button>
    </form>
</body>
</html>
