<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
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
        <div class="container mt-5">
            <h2 class="text-center mb-4">Add Event</h2>
            <form action="addevent.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="eventname" class="form-label">Event Name</label>
                    <input type="text" class="form-control" id="eventname" name="eventname" required>
                </div>
                <div class="mb-3">
                    <label for="eventdate" class="form-label">Event Date</label>
                    <input type="date" class="form-control" id="eventdate" name="eventdate" required>
                </div>
                <div class="mb-3">
                    <label for="eventime" class="form-label">Event Time</label>
                    <input type="time" class="form-control" id="eventime" name="eventime" required>
                </div>
                <div class="mb-3">
                    <label for="bannerimage" class="form-label">Banner Image</label>
                    <input type="file" class="form-control" id="bannerimage" name="bannerimage" required>
                </div>
                <div class="mb-3">
                    <label for="reglink" class="form-label">Registration Link</label>
                    <input type="url" class="form-control" id="reglink" name="reglink">
                </div>
                <input type="hidden" name="email" value="<?php echo $_REQUEST['email']; ?>">
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
    </div>
    <div class="footer" style="text-align: center;">
        <p>&copy; 2025 KEC Events. All rights reserved.</p>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="pr.js"></script>
</body>

</html>