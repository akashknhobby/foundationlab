<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>KEC Events</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="cr.css">
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
</head>

<body>
    <div class="header">
        <div class="container-fluid bg-dark text-white p-5" style="position: relative;">
            <div class="particle-container">
                <canvas id="particle-canvas"></canvas>
            </div>
            <div class="content">
                <div class="display-1">KEC Events</div>
            </div>
        </div>
        <ul class="nav border">
            <li class="nav-item">
                <a class="nav-link text-dark" href="index.html">Home</a>
            </li>
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                    Account
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="signup.php">Sign Up</a></li>
                    <li><a class="dropdown-item" href="login.php">Log in</a></li>
                </ul>
            </div>
        </ul>
    </div>
    <div class="body">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-9 border p-1">
                    <div class="h3" style="text-align: center;">
                        EVENTS
                    </div>
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

                    $sql = "SELECT * FROM events ORDER BY eventdate ASC";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                            <div class="container">

                                <div class="card"
                                    style="width: 100%; max-height: 500px; overflow: hidden; margin-bottom: 20px;">
                                    <img src="<?php echo $row['bannerimage']; ?>" class="card-img-top" alt="Event Banner"
                                        id="bannerimage" style="max-height: 200px; object-fit: cover;">
                                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                                        <h5 class="card-title" id="eventname">
                                            <?php echo $row['eventname']; ?>
                                        </h5>
                                        <p class="card-text">
                                            <strong>Date:</strong> <span id="eventdate">
                                                <?php echo $row['eventdate']; ?>
                                            </span><br>
                                            <strong>Time:</strong> <span id="eventime">
                                                <?php echo $row['eventime']; ?>
                                            </span>
                                        </p>
                                        <?php
                                        if (isset($_SESSION['email'])) {
                                            $email = $_SESSION['email'];

                                            // Check if the user is already registered for this event
                                            $eventname = $row['eventname'];
                                            $check_sql = "SELECT * FROM entries WHERE email = ? AND eventname = ?";
                                            $stmt = $conn->prepare($check_sql);
                                            $stmt->bind_param("ss", $email, $eventname);
                                            $stmt->execute();
                                            $check_result = $stmt->get_result();

                                            $is_registered = ($check_result->num_rows > 0);
                                            $stmt->close();
                                        } else {
                                            $is_registered = false; // User is not logged in
                                        }
                                        ?>

                                        <form action="register_event.php" method="POST">
                                            <input type="hidden" name="eventname" value="<?php echo $row['eventname']; ?>">
                                            <input type="hidden" name="reglink" value="<?php echo $row['reglink']; ?>">
                                            <button type="submit" class="btn btn-primary" <?php echo $is_registered ? 'disabled' : ''; ?>>
                                                <?php echo $is_registered ? 'Registered' : 'Register'; ?>
                                            </button>
                                        </form>

                                        <form action="register_event.php" method="POST">
                                            <input type="hidden" name="eventname" value="<?php echo $row['eventname']; ?>">
                                            <input type="hidden" name="reglink" value="<?php echo $row['reglink']; ?>">

                                        </form>
                                    </div>
                                </div>
                            </div>

                    <?php
                        }
                    } else {
                        echo "<p>No events found.</p>";
                    }
                    $conn->close();
                    ?>
                </div>
                <div class="col-3 border">
                    <div class="row border">
                        <div class="h3" style="text-align: center;">
                            REGISTERED EVENTS
                        </div>
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

                        if (!isset($_SESSION['email'])) {
                            die("<p>Please log in to view your events.</p>");
                        }

                        $email = $_SESSION['email'];

                        $sql = "SELECT e.eventname, ev.eventdate, ev.eventime 
        FROM entries e 
        JOIN events ev ON e.eventname = ev.eventname 
        WHERE e.email = ?";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $email);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        ?>

                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Event Name</th>
                                    <th>Event Date</th>
                                    <th>Event Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['eventname']); ?></td>
                                        <td><?php echo htmlspecialchars($row['eventdate']); ?></td>
                                        <td><?php echo htmlspecialchars($row['eventime']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <?php
                        $stmt->close();
                        $conn->close();
                        ?>

                    </div>
                    <div class="row border p-3">
                        <div class="h3" style="text-align: center;">
                            CALENDAR
                        </div>
                        <div class="calendar-container">
                            <div class="month-header">
                                <button class="nav-button" onclick="prevMonth()">&#10094;</button>
                                <h2 id="month-year"></h2>
                                <button class="nav-button" onclick="nextMonth()">&#10095;</button>
                            </div>
                            <div class="calendar" id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer" style="text-align: center;">
        <p>&copy; 2023 KEC Events. All rights reserved.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="pr.js"></script>
    <script src="cr.js"></script>
</body>

</html>