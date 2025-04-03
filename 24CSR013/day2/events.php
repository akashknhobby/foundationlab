<?php
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

$sql = "SELECT * FROM events ORDER BY eventdate DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>

<div class="card" style="width: 100%; max-width: 500px; margin-bottom: 20px;">
    <img src="<?php echo $row['bannerimage']; ?>" class="card-img-top" alt="Event Banner" id="bannerimage">
    <div class="card-body">
        <h5 class="card-title" id="eventname"><?php echo $row['eventname']; ?></h5>
        <p class="card-text">
            <strong>Hosted By:</strong> <span id="eventdate"><?php echo $row['host']; ?></span><br>
            <strong>Date:</strong> <span id="eventdate"><?php echo $row['eventdate']; ?></span><br>
            <strong>Time:</strong> <span id="eventime"><?php echo $row['eventime']; ?></span>
        </p>
        <a href="<?php echo $row['reglink']; ?>" class="btn btn-primary" id="reglink">Register</a>
    </div>
</div>

<?php
    }
} else {
    echo "<p>No events found.</p>";
}
$conn->close();
?>