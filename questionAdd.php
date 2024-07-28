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
    // Validate form data
    $userId = $_SESSION['userId'];
    $question = trim($_POST['question']); // Trim whitespace from the beginning and end of the input

    // Perform server-side validation
    if (empty($question)) {
        $error = "Please provide your question.";
    } elseif (strlen($question) > 255) {
        $error = "Question length exceeds the limit.";
    } else {
        // Vulnerable query setup (for educational purposes only)
        // Directly include user input in the SQL query
        $query = "INSERT INTO questions (userId, question) VALUES ('$userId', '$question')";
        $result = mysqli_query($link, $query);

        if ($result) {
            // Set success message
            $success = "Question added successfully!";
        } else {
            // Handle the error
            $error = "Error adding question.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Custom CSS styles */
        body {
            background-color: #f0f0f0; /* Set background color to light gray */
            font-family: 'Poppins', sans-serif; /* Set font family to Poppins */
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
                <div class="card-header" style="font-weight:bold;">Add Question</div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error; ?> <!-- Output without escaping -->
                        </div>
                    <?php elseif(isset($success)): ?>
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2 success-icon"></i><?php echo $success; ?> <!-- Output without escaping -->
                        </div>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="mb-3">
                            <label for="username" class="form-label">Your Username:</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo isset($_SESSION['username']) ? $_SESSION['username'] : ''; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="question" class="form-label">Your Question:</label>
                            <textarea id="question" name="question" class="form-control" rows="5" required></textarea>
                            <div class="invalid-feedback">Please provide your question.</div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Submit Question</button>
                            <a href="forum.php" class="btn btn-secondary ms-3">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
