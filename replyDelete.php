<?php
session_start();
include "dbFunctions.php";

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Check if the reply ID is provided in the URL
if (!isset($_GET['replyId'])) {
    echo "Reply ID not provided.";
    exit();
}

// Get the reply ID from the URL
$replyId = $_GET['replyId'];

// Fetch the reply details from the database
$query = "SELECT * FROM replies WHERE replyId = $replyId";
$result = mysqli_query($link, $query);

if (!$result) {
    echo "Error fetching reply details: " . mysqli_error($link);
    exit();
}

$reply = mysqli_fetch_assoc($result);

// Check if the logged-in user is the author of the reply
if ($_SESSION['userId'] != $reply['userId']) {
    echo "You are not authorized to delete this reply.";
    exit();
}

// If confirmed for deletion
if (isset($_POST['confirm'])) {
    // Delete the reply from the database
    $deleteQuery = "DELETE FROM replies WHERE replyId = $replyId";
    $deleteResult = mysqli_query($link, $deleteQuery);

    if (!$deleteResult) {
        echo "Error deleting reply: " . mysqli_error($link);
        exit();
    }

    // Redirect back to the forum page
    header("Location: forum.php");
    exit();
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Reply</title>
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
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding-top: 50px;
        }
        .card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer; /* Make the card clickable */
            max-width: 500px;
            margin: 0 auto;
        }
        .card-header {
            background-color: #dc3545;
            color: #fff;
            font-weight: bold;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .card-body {
            padding: 30px;
        }
        .btn-confirm {
            margin-right: 10px;
        }
    </style>
</head>
<body>
<!-- Navbar Section-->
<?php include 'navbar.php'; ?> <!-- Include the navbar.php file -->

<div class="container">
    <div class="card">
        <div class="card-header" style="color:black;">Delete Reply</div>
        <div class="card-body">
            <p>Are you sure you want to delete this reply?</p>
            <form method="post">
                <button type="submit" class="btn btn-danger btn-confirm" name="confirm">Yes, Delete Reply</button>
                <a href="forum.php" class="btn btn-primary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<!-- Import Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>