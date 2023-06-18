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

if (isset($_GET['product_id'])) {
    // Retrieve the product ID from the URL query parameter
    $product_id = $_GET['product_id'];

    // Set the product ID in the session
    $_SESSION['product_id'] = $product_id;
} else {
    // Redirect to an error page or handle the error as per your requirement
    exit();
}
include "connectdb.php"; // Include database connection code

$sql = "SELECT * FROM feedback WHERE customer_id = :customer_id AND product_id = :product_id"; // Select feedback data based on customer_id and product_id
$stmt = $conn->prepare($sql); // Prepare the query
$stmt->bindParam(':customer_id', $customer_id); // Bind the customer_id parameter
$stmt->bindParam(':product_id', $product_id); // Bind the product_id parameter
$stmt->execute(); // Execute the query
$result = $stmt->fetchAll(); // Fetch all rows

// Set the retrieved product_id in the session
$_SESSION['product_id'] = $product_id;

?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="comment.css">
    <link rel="icon" href="logo.png">
    <title>MENU</title>
</head>
<body>
<header>
    <nav class="navbar">
        <div class="logo-wrapper">
            <img src="logo.png" style="height: 10%; width: 20%;">
            <span><?php echo "HI ".$_SESSION['customer_name']; ?></span>
        </div>
        <div class="menu">
            <ul class="list">
                <li><a href="ndatmain.php">Home</a></li>
                <li><a href="menu.php">Menu</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a href="cart.php">Cart</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
</header>

<?php
    echo "<table style='margin-top:100px;'>"; // Start a table
    echo "<tr><th>Product ID</th><th>Comment</th><th>Rating</th></tr>"; // Table header
if ($stmt->rowCount() > 0) { // Check if any feedback rows are returned

    foreach ($result as $row) { // Loop through the feedback rows
        $_SESSION['product_id'] = $product_id;
        echo "<tr><td>".$row['product_id']."</td><td>".$row['comments']."</td><td>".$row['star_rating']."</td></tr>"; // Display each row in a table row
    }
    echo "</table>"; // End the table
} else {
    echo "No data found."; // Display message if no feedback rows are returned
}
?>

<form name="comments" method="POST" action="insertcomment.php">
    <table>
        <tr>
            <td><textarea id="comments" name="comments" required></textarea></td>
        </tr>
        <tr>
            <td><input type="hidden" name="product_id" value="<?php echo $product_id; ?>"></td>
        </tr>
        <tr>
            <td><button class="" type="submit">Submit</button></td>
        </tr>
    </table>
</form>
<footer>
    <table>
        <hr>
        <tr><th>Menu</th><th>About Us</th></tr>
        <tr><td>Food</td><td>Our Company</td></tr>
        <tr><td>Drinks</td></tr>
    </table>
</footer>
</body>
</html>
