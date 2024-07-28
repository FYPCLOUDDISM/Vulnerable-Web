<?php
session_start();
include "dbFunctions.php";

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data directly (no escaping)
    $username = $_POST['username'];
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $userId = $_SESSION['userId'];

    // Update user profile in the database
    $updateQuery = "UPDATE users SET username = '$username', name = '$name', dob = '$dob', email = '$email' WHERE userId = $userId";
    $updateResult = mysqli_query($link, $updateQuery);

    if ($updateResult) {
        // Update session variables
        $_SESSION['username'] = $username;
        $_SESSION['name'] = $name;
        $successMessage = "<i class='fas fa-check-circle me-2 success-icon'></i> Profile updated successfully!";
    } else {
        echo "Error updating user profile: " . mysqli_error($link);
        exit();
    }
}

// Fetch user details from the database
$query = "SELECT * FROM users WHERE userId = " . $_SESSION['userId'];
$result = mysqli_query($link, $query);

if (!$result) {
    echo "Error fetching user details: " . mysqli_error($link);
    exit();
}

$row = mysqli_fetch_assoc($result);

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Import Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Import Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Import Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Custom CSS styles */
        body {
            background-color: #f8f9fa; /* Set background color to light gray */
            font-family: 'Poppins', sans-serif; /* Set font family to Poppins */
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Add box shadow for modern look */
            border-radius: 10px; /* Round the corners */
            width: 100%; /* Adjust width to fit container */
            max-width: 550px; /* Limit maximum width */
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>
<body>
<!-- Navbar Section -->
<?php include 'navbar.php'; ?> <!-- Include the navbar.php file -->

<!-- Main Content Section -->
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Profile</h5>
        </div>
        <div class="card-body">
            <?php if (isset($successMessage)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $successMessage; ?>
                </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $row['username']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="<?php echo $row['dob']; ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" required>
                </div>
                <div class="mb-2">
                    <button type="submit" class="btn btn-primary">Edit</button>
                    <a href="userDelete.php?userId=<?php echo $_SESSION['userId']; ?>" class="btn btn-danger">Delete Account</a>
                    <a href="changePassword.php?userId=<?php echo $_SESSION['userId']; ?>" class="btn btn-warning">Change Password</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>