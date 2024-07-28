<?php
session_start();
include "dbFunctions.php";

// Check if the user is already logged in
if (isset($_SESSION['userId'])) {
    header("Location: index.php");
    exit;
}

$message = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // Hash password insecurely

    // Vulnerable query setup (for educational purposes only)
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'"; // SQL Injection vulnerability

    $result = mysqli_query($link, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($link));
    }

    // Check if any rows were returned
    if (mysqli_num_rows($result) > 0) {
        // Fetch the user details
        $row = mysqli_fetch_assoc($result);
        
        // Set session variables
        $_SESSION['userId'] = $row['userId'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['dob'] = $row['dob'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['active'] = $row['active'];

        // Handle "Remember Me" functionality
        if (isset($_POST['remember'])) {
            // Set cookie to expire in 30 days
            setcookie('remember_me_cookie', $username, time() + (30 * 24 * 60 * 60), "/");
        } else {
            // Remove cookie if "Remember Me" is not checked
            setcookie('remember_me_cookie', '', time() - 3600, "/");
        }

        // Redirect to index.php upon successful login
        header("Location: index.php");
        exit;
    } else {
        $message = "Invalid username or password. Please try again.";
    }
}

// Retrieve remembered username from cookie
$rememberedUsername = isset($_COOKIE['remember_me_cookie']) ? htmlspecialchars($_COOKIE['remember_me_cookie']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Import Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Import Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Import Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0; /* Set background color to light gray */
            font-family: 'Poppins', sans-serif; /* Set font family to Poppins */
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto 0; /* Add top margin to the login container */
            background-color: #ffffff; /* Set login container background color to white */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); /* Add a shadow effect */
        }
        .login-container h1 {
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-label {
            color: #495057; /* Set form label color to dark gray */
        }
        .form-control {
            background-color: #f0f0f0; /* Set form control background color to light gray */
            color: #495057; /* Set form control text color to dark gray */
        }
        .form-control:focus {
            background-color: #e9ecef; /* Set form control focus background color to lighter gray */
            color: #495057; /* Set form control focus text color to dark gray */
            border-color: #80bdff;
            box-shadow: 0 0 0 0.25rem rgba(0, 123, 255, 0.25);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .alert {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
<!-- Navbar Content -->
<?php include("navbar.php")?>

<div class="container">
    <div class="login-container">
        <h1>Login</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <?php if(!empty($message)) { ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $message; ?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" placeholder="Enter Username" class="form-control" value="<?php echo $rememberedUsername; ?>" required/>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter Password" class="form-control" required/>
            </div>
            <div class="mb-3">
                <input type="checkbox" name="remember" id="remember" <?php echo isset($_COOKIE['remember_me_cookie']) ? 'checked' : ''; ?>>
                <label for="remember"> Remember Me</label>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
            <br>
            <p class="text-center">Not a member yet? <a href="register.php">Register</a> Now!</p>
        </form>
    </div>
</div>
<!-- Import Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>