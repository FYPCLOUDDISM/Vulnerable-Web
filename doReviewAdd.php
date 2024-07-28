<?php
session_start();
include "dbFunctions.php";

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Check if the ID parameter is set and is a valid numeric value
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || $_GET['id'] <= 0) {
    // Handle invalid ID parameter, e.g., redirect to an error page
    header("Location: index.php");
    exit();
}

$theId = (int)$_GET['id']; // Cast to integer to ensure it's numeric

// Initialize message variable
$message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $review = $_POST['review'];
    $ratings = $_POST['ratings'];

    // Sanitize review
    $review = mysqli_real_escape_string($link, trim($review));
    
    // Validate ratings
    $ratings = (int)$ratings; // Cast to integer to ensure it's numeric
    if (empty($review) || !is_numeric($ratings) || $ratings < 1 || $ratings > 5) {
        $message = "<div class='alert alert-danger' role='alert'>Invalid review or rating. Please provide valid input.</div>";
    } else {
        // Insert the review into the database
        $insertQuery = "INSERT INTO reviews (avId, userId, review, rating, datePosted) 
                        VALUES ($theId, {$_SESSION['userId']}, '$review', $ratings, NOW())";

        $result = mysqli_query($link, $insertQuery);

        // Close the connection
        mysqli_close($link);

        // Set message for success
        if ($result) {
            $message = "<div class='alert alert-success text-center' role='alert'>
                            <h5><i class='fas fa-check-circle me-2 success-icon'></i> Your review has been submitted successfully!</h5>
                            <a href='review.php?id=" . urlencode($theId) . "' class='btn btn-success mt-3'>Back to Review Page</a>
                        </div>";
        } else {
            $message = "<div class='alert alert-danger' role='alert'>Failed to submit review. Please try again later.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FYP Antivirus Review</title>
    <!-- Import Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Import Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Import Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Custom CSS styles */
        body {
            background-color: #f0f0f0; /* Set background color to light gray */
            font-family: 'Poppins', sans-serif; /* Set font family to Poppins */
        }
        .card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 100%;
            cursor: pointer; /* Make the card clickable */
        }
        .card img {
            height: 200px; /* Adjust the height as needed */
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
        }
        .modal-content {
            border-radius: 10px;
        }
    </style>
</head>
<body>
<!-- Navbar Section -->
<?php include 'navbar.php'; ?>

<div class="container mt-4 d-flex justify-content-center align-items-center flex-column">
    <?php echo $message; ?>
</div>

<!-- Import Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>