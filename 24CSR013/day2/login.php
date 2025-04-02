<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            header("Location: indexlogged.php?email=" . urlencode($email));
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No user found with this email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="header">
        <div class="container-fluid bg-dark text-white p-5">
            <div class="content">
                <div class="display-1">KEC Events</div>
            </div>
        </div>
        <ul class="nav border">
            <li class="nav-item">
                <a class="nav-link text-dark" href="index.html">Home</a>
            </li>
            <div class="dropdown">
                <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">Account</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="signup.php">Sign Up</a></li>
                    <li><a class="dropdown-item" href="login.php">Log in</a></li>
                </ul>
            </div>
        </ul>
    </div>
    <div class="body">
        <div class="container border p-4 mt-4">
            <h2 class="text-center">Login</h2>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"> <?php echo $error; ?> </div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
    <div class="footer text-center">
        <p>&copy; 2023 KEC Events. All rights reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="pr.js"></script>
</body>

</html>