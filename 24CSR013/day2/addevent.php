<?php
// Database connection
$host = "localhost"; // Change if needed
$user = "root"; // Default XAMPP user
$pass = ""; // Default XAMPP password (empty)
$dbname = "kecevent"; // Your database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventname = $conn->real_escape_string($_POST['eventname']);
    $eventdate = $conn->real_escape_string($_POST['eventdate']);
    $eventime = $conn->real_escape_string($_POST['eventime']);
    $reglink = $conn->real_escape_string($_POST['reglink']);
    $hostname = $conn->real_escape_string($_POST['email']); 
    // Handle file upload
    $target_dir = "uploads/"; // Directory where images will be stored
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
    }

    $bannerimage = $target_dir . basename($_FILES["bannerimage"]["name"]);
    move_uploaded_file($_FILES["bannerimage"]["tmp_name"], $bannerimage);

    // Insert data into the database
    $sql = "INSERT INTO events (eventname, eventdate, eventime, bannerimage, reglink, host) 
            VALUES ('$eventname', '$eventdate', '$eventime', '$bannerimage', '$reglink', '$hostname')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Event added successfully!'); window.location.href='indexlogged.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close connection
$conn->close();
?>
