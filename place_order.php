<?php
session_start();
include 'db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifică dacă utilizatorul este autentificat
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        $userId = $_SESSION['id'];
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);

        // Începe o tranzacție pentru a asigura consistența datelor în baza de date
        mysqli_begin_transaction($conn);

        try {
            // Adaugă comanda în tabela 'orders'
            $orderInsertQuery = "INSERT INTO orders (user_id) VALUES ($userId)";
            $orderInsertResult = mysqli_query($conn, $orderInsertQuery);

            if (!$orderInsertResult) {
                throw new Exception("Error adding order to database.");
            }

            // Obține ID-ul comenzii recent adăugate
            $orderId = mysqli_insert_id($conn);

            // Golește coșul de cumpărături pentru utilizator
            $clearCartQuery = "DELETE FROM cart WHERE user_id = $userId";
            $clearCartResult = mysqli_query($conn, $clearCartQuery);

            if (!$clearCartResult) {
                throw new Exception("Error clearing cart.");
            }

            // Confirmă tranzacția dacă toate acțiunile au fost executate cu succes
            mysqli_commit($conn);

            // Obține adresa de email și numele utilizatorului din baza de date
            $queryUserData = "SELECT email, username FROM users WHERE id = $userId";
            $resultUserData = mysqli_query($conn, $queryUserData);

            if ($resultUserData && mysqli_num_rows($resultUserData) > 0) {
                $userData = mysqli_fetch_assoc($resultUserData);
                $email = $userData['email'];
                $username = $userData['username'];

                // Trimite confirmarea pe email (folosind PHPMailer)
                sendConfirmationEmail($email, $name, $address);

                // Afișează un mesaj de succes
                echo 'Order placed successfully!';
            } else {
                echo 'Error retrieving user data.';
            }
        } catch (Exception $e) {
            // Rollback la tranzacție în caz de eroare
            mysqli_rollback($conn);
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        echo 'User not logged in.';
    }
} else {
    // Handle invalid request method
    echo 'Invalid request method.';
}

function sendConfirmationEmail($email, $name, $address) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'muscletedd@gmail.com'; // Your Gmail username
        $mail->Password = 'gnjt hkgc gurj ribo'; // Your Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        //Recipients
        $mail->setFrom('muscletedd@gmail.com', 'TrendyCloset'); // Your Gmail address and your name
        $mail->addAddress($email); // User's email
        $mail->addReplyTo('muscletedd@gmail.com', 'TrendyCloset'); // Your Gmail address and your name

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Order Confirmation';
        $mail->Body = "Hello $name,\n\nThank you for placing your order!\n\nYour order will be shipped to:\n$address";

        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: {$mail->ErrorInfo}";
    }
}

?>
