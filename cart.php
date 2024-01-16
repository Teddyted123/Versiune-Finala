<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connection.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Fetch cart items for the logged-in user
    $userId = $_SESSION['id'];
    $query = "SELECT cart.*, produse.nume, produse.imagine 
              FROM cart 
              JOIN produse ON cart.product_id = produse.id 
              WHERE cart.user_id = $userId";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $cartItems = mysqli_fetch_all($result, MYSQLI_ASSOC);
        mysqli_free_result($result);
    } else {
        // Handle error
        die("Error fetching cart items");
    }
} else {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>

    <script src="/Site PI/jquery3.7.1.js"></script>
    <script src="script_v3.js"></script>
</head>
<body>

    <!-- Your cart content goes here -->
    <h2>Your Cart</h2>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Name</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $grandTotalPrice = 0;

            foreach ($cartItems as $item) {
                $totalItemPrice = $item['quantity'] * $item['price'];
                $grandTotalPrice += $totalItemPrice;

                echo "<tr>
                        <td>{$item['nume']}</td>
                        <td><img src='{$item['imagine']}' alt='{$item['nume']}' style='width: 50px; height: 50px;'></td>
                        <td>{$item['nume']}</td>
                        <td>{$item['selected_size']}</td>
                        <td>{$item['quantity']}</td>
                        <td>{$totalItemPrice} RON</td>
                        <td>
                            <button class='removeFromCart' data-product-id='{$item['product_id']}'>Remove</button>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5"><strong>Total:</strong></td>
                <td><strong><?php echo $grandTotalPrice; ?> RON</strong></td>
            </tr>
        </tfoot>
    </table>
    
    <!-- Formular pentru plasarea comenzii -->
    <section id="order-form">
        <h2>Completează informațiile pentru comandă</h2>
        <form action="place_order.php" method="POST" onsubmit="clearForm()">
            <!-- Adaugă câmpuri ascunse pentru nume și adresă -->
            <input type="hidden" name="name" id="hidden_name" value="">
            <input type="hidden" name="address" id="hidden_address" value="">
            
            <label for="name">Nume:</label>
            <input type="text" id="name" name="name" required>

            <label for="address">Adresă de livrare:</label>
            <textarea id="address" name="address" required></textarea>

            <!-- Alte câmpuri aici în funcție de informațiile pe care vrei să le colectezi -->

            <button type="submit" name="place_order" data-name="name" data-address="address">Place Order</button>
        </form>
    </section>

 <!--   <script>
    function clearForm() {
    // Setează valorile câmpurilor ascunse cu cele din câmpurile vizibile
    document.getElementById('hidden_name').value = document.getElementById('name').value;
    document.getElementById('hidden_address').value = document.getElementById('address').value;
    // Golește câmpurile de nume și adresă după trimiterea formularului
    document.getElementById('name').value = '';
    document.getElementById('address').value = '';
    }
    </script> -->

    
</body>
</html>
