<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = htmlspecialchars($_POST["username_or_email"]);
    $password = $_POST["password"];

    // Check for user in the database
    $sql = "SELECT * FROM users WHERE username = '$username_or_email' OR email = '$username_or_email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $row["password"])) {
            echo "Login successful! Welcome, " . $row["username"];
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}

// Close connection
$conn->close();
?>
