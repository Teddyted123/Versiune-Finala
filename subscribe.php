<?php
echo "Form submitted"; // Debugging line to check form submission

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate and sanitize the email address
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    // Check if the email is valid
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

        // Connect to the database (without specific credentials)
        $conn = new mysqli("localhost", "", "", "produse_db");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        echo "Connected successfully";

        // Prepare and execute SQL query to insert email into the database
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email); // 's' indicates a string type
        $stmt->execute();

        // Check if the insertion was successful
        if ($stmt->affected_rows > 0) {
            echo "Thank you for subscribing!";
        } else {
            echo "Error: Unable to subscribe. Please try again later.";
        }

        // Close connections and statements
        $stmt->close();
        $conn->close();
    } else {
        echo "Invalid email address!";
    }
}
?>
