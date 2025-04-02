<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    echo json_encode([]);
    exit;
}

$email = $_SESSION['email'];

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kecevent";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT e.eventname, ev.eventdate, ev.eventime 
        FROM entries e 
        JOIN events ev ON e.eventname = ev.eventname 
        WHERE e.email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[$row['eventdate']] = $row['eventname'];
}

$stmt->close();
$conn->close();

echo json_encode($events);
