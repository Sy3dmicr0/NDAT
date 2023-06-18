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
include "connectdb.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql = "INSERT INTO orders (customer_id, product_id, quantity) VALUES (:customer_id, :product_id, :quantity)";
    $stmt = $conn->prepare($sql);

    foreach ($_POST as $item_id => $quantity) {
        if ($quantity >= 1) {
            $stmt2 = $conn->prepare("SELECT product_id FROM food_data WHERE product_id = :item_id");
            $stmt2->bindParam(":item_id", $item_id);
            $stmt2->execute();
            $result = $stmt2->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $product_id = $result['product_id'];
                $stmt->bindParam(":customer_id", $customer_id);
                $stmt->bindParam(":product_id", $product_id);
                $stmt->bindParam(":quantity", $quantity);
                $stmt->execute();
                echo "<font color='blue'>Item berjaya dimasukkan ke dalam pangkalan data...</font>";
            } else {
                echo "ID item tidak sah. Item tidak akan dimasukkan ke dalam pangkalan data.";
            }
        } else {
            echo "Kuantiti untuk item dengan ID $item_id mesti lebih besar daripada atau sama dengan 1. Item tidak akan dimasukkan ke dalam pangkalan data.";
        }
    }

    $_SESSION['message'] = "Item berjaya dimasukkan ke dalam pangkalan data...";
    header('location: cart.php');

    $stmt = null;
    $stmt2 = null;
    $conn = null;
}
?>
