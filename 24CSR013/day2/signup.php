<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password hashing

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        $error = "Registration successful. <a href='login.php'>Login here</a>";
    } else {
        $error = "Registration Failed! User already Exists.";
    }
}
?>

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
        <div class="container border p-4 mt-4">
            
        <h2 class="text-center">Sign Up</h2>
            <form action="signup.php" method="POST">
                <div class="mb-3 mt-3">
                    <label for="name" class="form-label">Full Name:</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter full name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>
        </div>
        <?php if (!empty($error)): ?>
                <div class="mt-3 container alert alert-dark alert-dismissible fade show" role="alert">
                    <b><?php echo $error; ?></b>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
    </div>
    <div class="footer" style="text-align: center;">
        <p>&copy; 2023 KEC Events. All rights reserved.</p>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="pr.js"></script>
</body>

</html>