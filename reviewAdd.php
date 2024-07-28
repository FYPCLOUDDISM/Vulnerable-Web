<?php
session_start();
include "dbFunctions.php";

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Get the ID from the URL
$theId = isset($_GET['id']) ? $_GET['id'] : '';

// Validate the ID parameter
if (!is_numeric($theId) || $theId <= 0) {
    // Invalid ID, handle error or redirect
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Review</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Custom CSS styles */
        body {
            background-color: #f0f0f0; /* Set background color to light gray */
            font-family: 'Poppins', sans-serif; /* Set font family to Poppins */
        }
        .star-rating {
            font-size: 2rem;
            color: #ccc;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }
        .star-rating:hover,
        .star-rating:hover ~ .star-rating {
            color: #ffdd00;
        }
        .container {
            padding-top: 20px;
            max-width: 1100px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
    </style>
</head>
<body>
<!-- Navbar Section-->
<?php include 'navbar.php'; ?> <!-- Include the navbar.php file -->

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="font-weight:bold; color:black;">Add Review</div>
                <div class="card-body">
                    <form method="POST" action="doReviewAdd.php?id=<?php echo $theId; ?>" id="reviewForm">
                        <div class="mb-3">
                            <label for="username" class="form-label">Your Username:</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label">Your Review:</label>
                            <textarea id="review" name="review" class="form-control" rows="5" required></textarea>
                            <div class="invalid-feedback">Please provide your review.</div>
                        </div>
                        <div class="mb-3">
                            <label for="ratings" class="form-label">Ratings:</label>
                            <div class="star-rating" id="starRating">
                                <span class="star" data-rating="1">&#9733;</span>
                                <span class="star" data-rating="2">&#9733;</span>
                                <span class="star" data-rating="3">&#9733;</span>
                                <span class="star" data-rating="4">&#9733;</span>
                                <span class="star" data-rating="5">&#9733;</span>
                            </div>
                            <input type="hidden" id="selectedRating" name="ratings" value="5"> <!-- Hidden input to store selected rating -->
                            <div class="invalid-feedback">Please select a rating.</div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                            <a href="review.php?id=<?php echo $theId; ?>" class="btn btn-secondary">Go Back to Review</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const stars = document.querySelectorAll('.star-rating .star');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const rating = parseInt(star.getAttribute('data-rating'));
            document.getElementById('selectedRating').value = rating;

            // Reset all stars color
            stars.forEach(s => {
                s.style.color = "#ccc";
            });

            // Highlight selected stars
            for (let i = 0; i < rating; i++) {
                stars[i].style.color = "#ffdd00";
            }
        });
    });
</script>
</body>
</html>