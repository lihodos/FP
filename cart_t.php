<?php
session_start();
include "connectdb.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['user_id'];

// עדכון כמות
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_qty'])) {
    $productId = $_POST['product_id'];
    $newQty = max(0, (int)$_POST['amount_cart']); // מונע שליליות

    // בדיקת מלאי
    $checkStock = mysqli_query($conn, "SELECT amount FROM product WHERE SN = $productId");
    $row = mysqli_fetch_assoc($checkStock);

    if ($row && $newQty <= $row['amount']) {
        // עדכון העגלה
        if ($newQty == 0) {
            mysqli_query($conn, "DELETE FROM cart WHERE id = $userId AND SN = $productId");
        } else {
            mysqli_query($conn, "UPDATE cart SET amount_cart = $newQty WHERE id = $userId AND SN = $productId");
        }
    } else {
        echo "<script>alert('Not enough stock available.');</script>";
    }
}

// אישור קנייה
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_purchase'])) {
    $cartItems = mysqli_query($conn, "SELECT cart.SN, cart.amount_cart, product.amount FROM cart 
                                      JOIN product ON cart.SN = product.SN 
                                      WHERE cart.id = $userId");

    $canPurchase = true;
    while ($item = mysqli_fetch_assoc($cartItems)) {
        if ($item['amount_cart'] > $item['amount']) {
            $canPurchase = false;
            break;
        }
    }

    if ($canPurchase) {
        mysqli_data_seek($cartItems, 0); // reset pointer
        while ($item = mysqli_fetch_assoc($cartItems)) {
            $newAmount = $item['amount'] - $item['amount_cart'];
            mysqli_query($conn, "UPDATE product SET amount = $newAmount WHERE SN = " . $item['SN']);
        }

        mysqli_query($conn, "DELETE FROM cart WHERE id = $userId");
        echo "<script>alert('Purchase successful!');</script>";
    } else {
        echo "<script>alert('Not enough stock for some items.');</script>";
    }
}

// קבלת עגלה
$query = "SELECT cart.SN, cart.amount_cart, product.name, product.price, product.amount AS stock, product.image 
          FROM cart 
          JOIN product ON cart.SN = product.SN 
          WHERE cart.id = $userId";

$result = mysqli_query($conn, $query);
?>

<link rel="stylesheet" href="style.css">
<?php include 'navbar.php'; ?>

<h1>Your Shopping Cart</h1>
<div class="product-list">
<?php
$total = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $subtotal = $row['price'] * $row['amount_cart'];
    $total += $subtotal;

    echo "<div class='product'>";
    echo "<img src='images/" . $row['image'] . "' alt='" . $row['name'] . "'>";
    echo "<h2>" . $row['name'] . "</h2>";
    echo "<p>Price: " . $row['price'] . " ₪</p>";
    echo "<p>In Stock: " . $row['stock'] . "</p>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='product_id' value='" . $row['SN'] . "'>";
    echo "<input type='number' name='amount_cart' value='" . $row['amount_cart'] . "' min='0'>";
    echo "<input type='submit' name='update_qty' value='Update'>";
    echo "</form>";
    echo "<p>Subtotal: $subtotal ₪</p>";
    echo "</div>";
}
?>
</div>

<?php if ($total > 0): ?>
    <div style="text-align:center; margin: 20px;">
        <h3>Total: <?php echo $total; ?> ₪</h3>
        <form method="post">
            <input type="submit" name="confirm_purchase" value="Confirm Purchase">
        </form>
    </div>
<?php else: ?>
    <h3 style="text-align:center; margin: 20px;">Your cart is empty.</h3>
<?php endif; ?>
