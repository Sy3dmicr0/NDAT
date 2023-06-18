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
include "connectdb.php"; // include database connection code

$sql = "SELECT o.product_id, f.food_name, o.quantity, o.table_no, f.price
FROM orders o
JOIN food_data f ON o.product_id = f.product_id
WHERE o.customer_id = $customer_id"; // select data from the customer table based on customer_id
$result = $conn->query($sql); // execute the query
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="mainstyle.css">
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
      <li><a href="logout.php">Log-out</a></li>
    </ul>
  </div>
</nav>
</header>
<?php
if ($result->rowCount() > 0) { // check if any rows are returned
    $total = 0;
    echo "<table style='margin: 80px auto; margin-top: 100px;'>"; // add inline style to center the table
    echo "<tr><th>Nama Makanan</th><th>Jumlah</th></tr>"; // table header
    while ($row = $result->fetch()) { // loop through the rows
        $productTotal = $row['quantity'] * $row['price'];
        $total += $productTotal;

        echo "<tr><td>".$row['food_name']."</td><td>".$row['quantity']."</td></tr>"; // display each row in a table row
    }
    echo "</table>"; // end the table
    echo "Jumlah Harga RM" .$total;
} else {
    echo "No data";
}
?>
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