<?php
session_start();
if (!isset($_SESSION['email'])) {
    echo "You must be logged in to register for events.";
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kecevent";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['eventname']) && isset($_POST['reglink'])) {
    $email = $_SESSION['email'];
    $eventname = $conn->real_escape_string($_POST['eventname']);
    $reglink = $_POST['reglink'];

    // Insert into entries table
    $sql = "INSERT INTO entries (email, eventname) VALUES ('$email', '$eventname')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: $reglink"); // Redirect to event registration link
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
