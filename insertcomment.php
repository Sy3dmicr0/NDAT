<?php
session_start(); // start the session

if (isset($_SESSION['customer_id'])) {
    // customer ID is set, do something with it
    $customer_id = $_SESSION['customer_id'];
} else {
    // customer ID is not set, redirect to login page
    header('Location: login.php');
    exit();
}

include "connectdb.php"; // Include your database connection file

// Check if the required session key 'product_id' is set
if (isset($_SESSION['product_id'])) {
    // Retrieve the product ID from the session
    $product_id = $_SESSION['product_id'];

    // Retrieve the comment value from the form
    $comments = isset($_POST['comments']) ? $_POST['comments'] : '';

    // Check if comments field is not empty
    if (!empty($comments)) {
        $stmt = $conn->prepare("INSERT INTO feedback (product_id, customer_id, comments, star_rating) VALUES (:product_id, :customer_id, :comments, :star_rating)");

        // Retrieve the star rating from the form input, if available
        $star_rating = isset($_POST['star_rating']) ? $_POST['star_rating'] : null;

        // Bind the parameters
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":customer_id", $customer_id);
        $stmt->bindParam(":comments",  $comments);
        $stmt->bindParam(":star_rating",  $star_rating);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<font color='blue'>Data successfully inserted...</font>";
            header('Location: comment.php'); // Redirect to another page after successful insertion
            exit(); // Exit the script to prevent further execution
        } else {
            echo "<font color='red'>Statement error...</font>";
        }
} else {
    echo "Product ID not found in session.";
}}
?>
