<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>KEC Events - Gallery</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <div class="display-1">KEC Events Gallery</div>
            </div>
        </div>
        <ul class="nav border">
            <li class="nav-item">
                <a class="nav-link text-dark" href="indexlogged.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="gallery.php">Gallery</a>
            </li>
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                    Account
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item disabled" href="signup.php">Sign Up</a></li>
                    <li><a class="dropdown-item disabled" href="login.php">Log in</a></li>
                    <li><a class="dropdown-item" href="index.php">Log out</a></li>
                </ul>
            </div>
            <li class="nav-item">
                <a class="nav-link text-dark" href="crevent.php?email=<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>"> Host an Event</a>
            </li>
        </ul>
    </div>
    
    <div class="body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 border p-3">
                    <div class="h3 text-center mb-4">EVENT GALLERY</div>
                    
                    <!-- Gallery Display Section -->
                    <div class="row">
                        <?php
                        // Database connection
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "kecevent";
                        
                        $conn = new mysqli($servername, $username, $password, $dbname);
                        
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }
                        
                        // Get unique events with images
                        $sql = "SELECT DISTINCT eventname, eventdate FROM gallery ORDER BY eventdate DESC";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $eventname = $row['eventname'];
                                $eventdate = $row['eventdate'];
                                
                                // Get images for this event
                                $imagesql = "SELECT id, imagepath, description FROM gallery WHERE eventname = ? ORDER BY uploaded_at";
                                $stmt = $conn->prepare($imagesql);
                                $stmt->bind_param("s", $eventname);
                                $stmt->execute();
                                $imageresult = $stmt->get_result();
                                
                                if ($imageresult->num_rows > 0) {
                                    // Start a new event section
                                    echo '<div class="col-md-6 mb-4">';
                                    echo '<div class="card h-100">';
                                    echo '<div class="card-header">';
                                    echo '<h5>' . htmlspecialchars($eventname) . '</h5>';
                                    echo '<p class="text-muted mb-0">Date: ' . htmlspecialchars($eventdate) . '</p>';
                                    echo '</div>';
                                    echo '<div class="card-body">';
                                    
                                    // Create a unique carousel ID for this event
                                    $carouselId = 'carousel-' . str_replace(' ', '-', strtolower($eventname));
                                    
                                    // Create Bootstrap Carousel
                                    echo '<div id="' . $carouselId . '" class="carousel slide" data-bs-ride="carousel">';
                                    
                                    // Carousel indicators
                                    echo '<div class="carousel-indicators">';
                                    $imageCount = $imageresult->num_rows;
                                    for ($i = 0; $i < $imageCount; $i++) {
                                        echo '<button type="button" data-bs-target="#' . $carouselId . '" data-bs-slide-to="' . $i . '"';
                                        if ($i === 0) echo ' class="active"';
                                        echo '></button>';
                                    }
                                    echo '</div>';
                                    
                                    // Carousel inner
                                    echo '<div class="carousel-inner">';
                                    $firstImage = true;
                                    while ($imgrow = $imageresult->fetch_assoc()) {
                                        echo '<div class="carousel-item ' . ($firstImage ? 'active' : '') . '">';
                                        echo '<img src="' . htmlspecialchars($imgrow['imagepath']) . '" class="d-block w-100" alt="Event Image" style="height: 300px; object-fit: cover;">';
                                        if (!empty($imgrow['description'])) {
                                            echo '<div class="carousel-caption d-none d-md-block">';
                                            echo '<p><kbd>' . htmlspecialchars($imgrow['description']) . '</kbd></p>';
                                            echo '</div>';
                                        }
                                        echo '</div>';
                                        $firstImage = false;
                                    }
                                    echo '</div>';
                                    
                                    // Carousel controls
                                    echo '<button class="carousel-control-prev" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="prev">';
                                    echo '<span class="carousel-control-prev-icon" aria-hidden="true"></span>';
                                    echo '<span class="visually-hidden">Previous</span>';
                                    echo '</button>';
                                    echo '<button class="carousel-control-next" type="button" data-bs-target="#' . $carouselId . '" data-bs-slide="next">';
                                    echo '<span class="carousel-control-next-icon" aria-hidden="true"></span>';
                                    echo '<span class="visually-hidden">Next</span>';
                                    echo '</button>';
                                    
                                    echo '</div>'; // End carousel
                                    echo '</div>'; // End card-body
                                    echo '</div>'; // End card
                                    echo '</div>'; // End col
                                }
                                $stmt->close();
                            }
                        } else {
                            echo '<div class="col-12 text-center">';
                            echo '<p>No images have been uploaded yet.</p>';
                            echo '</div>';
                        }
                        
                        $conn->close();
                        ?>
                    </div>
                    
                    <!-- Upload Images Accordion/Dropdown at bottom of page -->
                    <?php if(isset($_SESSION['email'])): ?>
                    <div class="accordion mt-4 mb-4" id="uploadAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="uploadHeading">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#uploadCollapse" aria-expanded="false" aria-controls="uploadCollapse">
                                    Upload New Images
                                </button>
                            </h2>
                            <div id="uploadCollapse" class="accordion-collapse collapse" aria-labelledby="uploadHeading" data-bs-parent="#uploadAccordion">
                                <div class="accordion-body">
                                    <form action="upload_gallery.php" method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="eventname" class="form-label">Event Name</label>
                                            <select class="form-select" id="eventname" name="eventname" required>
                                                <option value="">Select an event</option>
                                                <?php
                                                // Database connection
                                                $servername = "localhost";
                                                $username = "root";
                                                $password = "";
                                                $dbname = "kecevent";
                                                
                                                $conn = new mysqli($servername, $username, $password, $dbname);
                                                
                                                if ($conn->connect_error) {
                                                    die("Connection failed: " . $conn->connect_error);
                                                }
                                                
                                                // Get events
                                                $sql = "SELECT eventname, eventdate FROM events ORDER BY eventdate DESC";
                                                $result = $conn->query($sql);
                                                
                                                if ($result->num_rows > 0) {
                                                    while($row = $result->fetch_assoc()) {
                                                        echo '<option value="' . htmlspecialchars($row['eventname']) . '" data-date="' . htmlspecialchars($row['eventdate']) . '">' . 
                                                            htmlspecialchars($row['eventname']) . ' (' . htmlspecialchars($row['eventdate']) . ')</option>';
                                                    }
                                                }
                                                $conn->close();
                                                ?>
                                            </select>
                                        </div>
                                        <input type="hidden" name="eventdate" id="eventdate">
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Select Images (Multiple)</label>
                                            <input class="form-control" type="file" id="images" name="images[]" multiple required accept="image/*">
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Upload Images</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="footer" style="text-align: center;">
        <p>&copy; 2025 KEC Events. All rights reserved.</p>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="pr.js"></script>
    <script src="cr.js"></script>
    
    <script>
        // Script to automatically set event date when event is selected
        document.getElementById('eventname').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const eventDate = selectedOption.getAttribute('data-date');
            document.getElementById('eventdate').value = eventDate;
        });

        // Display success/error messages
        <?php if(isset($_SESSION['message'])): ?>
        window.onload = function() {
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show';
            alertDiv.role = 'alert';
            alertDiv.innerHTML = `
                <?php echo $_SESSION['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            document.querySelector('.h3.text-center.mb-4').after(alertDiv);
            
            // Auto dismiss after 5 seconds
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 5000);
        };
        <?php
        // Clear the message after displaying it
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
        ?>
        <?php endif; ?>
    </script>
</body>

</html>