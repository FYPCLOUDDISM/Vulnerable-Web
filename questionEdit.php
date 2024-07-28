<?php
session_start();
include "dbFunctions.php";

// Check if the user is logged in
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit();
}

// Check if the question ID is provided in the URL
if (!isset($_GET['quesId'])) {
    echo "Question ID not provided.";
    exit();
}

// Get the question ID from the URL
$questionId = $_GET['quesId'];

// Fetch the question details from the database
$query = "SELECT * FROM questions WHERE quesId = $questionId";
$result = mysqli_query($link, $query);

if (!$result) {
    echo "Error fetching question details: " . mysqli_error($link);
    exit();
}

$question = mysqli_fetch_assoc($result);

// Check if the logged-in user is the author of the question
if ($_SESSION['userId'] != $question['userId']) {
    echo "You are not authorized to edit this question.";
    exit();
}

// Initialize variables for success container
$successMessage = "";

// If form is submitted for question edit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editQuestion'])) {
    $newQuestion = $_POST['question'];

    // Update the question in the database
    $updateQuery = "UPDATE questions SET question = '$newQuestion' WHERE quesId = $questionId";
    $updateResult = mysqli_query($link, $updateQuery);

    if (!$updateResult) {
        echo "Error updating question: " . mysqli_error($link);
        exit();
    } else {
        $successMessage = "Question updated successfully!";
    }
}

mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Question</title>
    <!-- Import Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Import Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Import Google Fonts (Poppins) -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Custom CSS styles */
        body {
            background-color: #f0f0f0;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            max-height: 400px;
            width: 100%;
        }
        .card-body {
            padding: 30px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            font-weight: bold;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }
        .card-title {
            margin-bottom: 10px;
        }
        .card-text {
            margin-bottom: 15px;
            line-height: 1.5;
        }
        .ratings {
            margin-top: auto;
            margin-bottom: 0;
        }
        .edit-delete-buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .success-container {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
            padding: 10px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            width: 100%;
        }
        .success-icon {
            font-size: 24px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
<!-- Navbar Section-->
<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="font-weight:bold; color:black;">Edit Question</div>
                <div class="card-body">
                    <?php if ($successMessage): ?>
                        <div class="success-container">
                            <i class="fas fa-check-circle me-2 success-icon"></i><?php echo $successMessage; ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="question" class="form-label">Your Question:</label>
                            <textarea id="question" name="question" class="form-control" rows="5" required><?php echo $question['question']; ?></textarea>
                            <div class="invalid-feedback">Please provide your question.</div>
                        </div>
                        <div class="text-center">
                            <button type="submit" name="editQuestion" class="btn btn-primary">Edit</button>
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