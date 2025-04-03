<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kecevent";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventname = $_POST['eventname'];
    $eventdate = $_POST['eventdate'];
    $description = $_POST['description'];
    
    // Create uploads directory if it doesn't exist
    $target_dir = "gallery_uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Create folder for this event if it doesn't exist
    $event_dir = $target_dir . str_replace(' ', '_', $eventname) . '/';
    if (!file_exists($event_dir)) {
        mkdir($event_dir, 0777, true);
    }
    
    // Process multiple images
    $uploadCount = 0;
    $errorCount = 0;
    
    // Check if files were uploaded
    if(isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        // Count total files
        $countfiles = count($_FILES['images']['name']);
        
        // Loop through all files
        for($i=0; $i < $countfiles; $i++) {
            // Get file info
            $filename = $_FILES['images']['name'][$i];
            $temp_name = $_FILES['images']['tmp_name'][$i];
            $file_size = $_FILES['images']['size'][$i];
            $file_type = $_FILES['images']['type'][$i];
            $file_error = $_FILES['images']['error'][$i];
            
            // Generate unique file name
            $new_filename = time() . '_' . $i . '_' . str_replace(' ', '_', $filename);
            $target_file = $event_dir . $new_filename;
            
            // Check if image file is a actual image
            $check = getimagesize($temp_name);
            if($check !== false) {
                // Check file size (5MB limit)
                if ($file_size > 5000000) {
                    $errorCount++;
                    continue;
                }
                
                // Allow certain file formats
                $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'image/webp');
                if(!in_array($file_type, $allowed_types)) {
                    $errorCount++;
                    continue;
                }
                
                // Upload file
                if (move_uploaded_file($temp_name, $target_file)) {
                    // Insert into database
                    $filepath = $target_file;
                    $stmt = $conn->prepare("INSERT INTO gallery (eventname, eventdate, imagepath, description) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param("ssss", $eventname, $eventdate, $filepath, $description);
                    
                    if ($stmt->execute()) {
                        $uploadCount++;
                    } else {
                        $errorCount++;
                    }
                    
                    $stmt->close();
                } else {
                    $errorCount++;
                }
            } else {
                $errorCount++;
            }
        }
    }
    
    // Set message based on results
    if ($uploadCount > 0) {
        $_SESSION['message'] = "$uploadCount images uploaded successfully.";
        if ($errorCount > 0) {
            $_SESSION['message'] .= " $errorCount images failed to upload.";
        }
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to upload images. Please try again.";
        $_SESSION['message_type'] = "danger";
    }
    
    // Redirect back to gallery
    header("Location: gallery.php");
    exit();
}

$conn->close();
?>