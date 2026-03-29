<?php
session_start();
include "connectdb.php";

// בדיקה שהמשתמש מחובר
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// בדיקה שהגיעה בקשת POST עם product_id
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['product_id'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    // כמות מהטופס, ברירת מחדל = 1
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    if ($quantity <= 0) {
        echo "<script>alert('Quantity must be at least 1.'); window.location.href='products.php';</script>";
        exit();
    }

    // שליפת כמות מהמלאי
    $query = "SELECT amount FROM Product WHERE SN = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $product_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $amount);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    if ($amount <= 0) {
        echo "<script>alert('This product is out of stock.'); window.location.href='products.php';</script>";
        exit();
    }

    // בדיקה אם המוצר כבר קיים בעגלה
    $check = "SELECT amount_cart FROM cart WHERE SN = ? AND id = ?";
    $stmt = mysqli_prepare($conn, $check);
    mysqli_stmt_bind_param($stmt, "ii", $product_id, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $current_cart_amount = $row['amount_cart'];

        if ($current_cart_amount + $quantity > $amount) {
            echo "<script>alert('You cannot add more than the available stock.'); window.location.href='products.php';</script>";
            exit();
        }

        // עדכון כמות בעגלה
        $update = "UPDATE cart SET amount_cart = amount_cart + ? WHERE SN = ? AND id = ?";
        $stmt = mysqli_prepare($conn, $update);
        mysqli_stmt_bind_param($stmt, "iii", $quantity, $product_id, $user_id);
        mysqli_stmt_execute($stmt);
    } else {
        if ($quantity > $amount) {
            echo "<script>alert('You cannot add more than the available stock.'); window.location.href='products.php';</script>";
            exit();
        }

        // הכנסת מוצר חדש לעגלה
        $insert = "INSERT INTO cart (SN, id, amount_cart) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert);
        mysqli_stmt_bind_param($stmt, "iii", $product_id, $user_id, $quantity);
        mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    header("Location: cart_t.php");
    exit();
} else {
    header("Location: products.php");
    exit();
}
?>
