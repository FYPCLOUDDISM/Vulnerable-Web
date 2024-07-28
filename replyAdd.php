<?php
session_start();
include "dbFunctions.php";

// Validate the request method and user session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['userId'])) {
    // Retrieve data from the form without sanitization
    $questionId = intval($_POST['question_id']); // Convert to integer for security
    $reply = $_POST['reply']; // No sanitization here
    $userId = $_SESSION['userId'];

    // Prepare the SQL statement to insert the reply into the database
    $insertReplyQuery = "INSERT INTO replies (quesId, userId, reply) VALUES ($questionId, $userId, '$reply')";
    
    // Execute the SQL query directly (not recommended due to SQL injection risk)
    $result = mysqli_query($link, $insertReplyQuery);

    if ($result) {
        // Redirect back to the forum page after successful reply
        header("Location: forum.php");
        exit();
    } else {
        // Handle the case where the reply insertion fails
        echo "Error: " . mysqli_error($link);
    }
} else {
    // Redirect to the login page if the user is not logged in
    header("Location: login.php");
    exit();
}
?>