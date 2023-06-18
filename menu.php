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

$sql = "SELECT * FROM food_data"; // select data from the customer table based on customer_id
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
<br>
<form method="POST" action="insertorder.php" onsubmit="return data_validate();">
    <?php
    if ($result->rowCount() > 0) { // check if any rows are returned
        echo "<table class='menu123'>"; // start a table
        echo "<br><br><br><div><h3>Food & Drinks</h3><hr></div>"; // table header
        while ($row = $result->fetch()) { // loop through the rows
            echo "<tr><td><img style='height:100px;'src='data:image/jpeg;base64," . base64_encode($row['food_image']) . "' /></td></tr>";
            echo "<tr><td>" . $row['food_name'] . "</td><td>RM " . $row['price'] . "0</td></tr>";
            echo '<tr class="gap"><td><input type="number" class="bang" id="ma" name="' . $row['product_id'] . '" min="0"></td><td><a href="comment.php?product_id=' . $row['product_id'] . '" class="comment" id="ma" >Komen</a></td></tr>';
        }
        echo "</table>"; // end the table
        echo '<button class="btnCART" type="submit"><b>Add</b></button>';
    } else {
        echo "No data found."; // display message if no rows are returned
    }
    ?>
</form>
<!-- <script>
   function data_validate() {
    var a = document.getElementById("ma").value;
    var msg = "";

    if (a.length == 0) {
        alert("Make an order");
        return false;
    } else {
        alert("Your order has been taken");
    } 
    return true; // Prevent form submission for this example
}
</script> -->

</body>
<footer>
  <table>
  <hr>
    <tr><th>Menu</th><th>About Us</th></tr>
    <tr><td>Food</td><td>Our Company</td></tr>
    <tr><td>Drinks</td></tr>
  </table>
</footer>
</html>
