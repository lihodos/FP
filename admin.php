<?php
session_start();
include "connectdb.php";

// רק למנהל
if (!isset($_SESSION['user_id']) || $_SESSION['isAdmin'] != 1) {
    header("Location: index.php");
    exit();
}

// הוספת מוצר חדש
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];
    $sizes = $_POST['sizes'];
    $image = $_POST['image'];

    $query = "INSERT INTO product (name, price, amount, sizes, image)
              VALUES ('$name', $price, $amount, '$sizes', '$image')";
    mysqli_query($conn, $query);
}

// מחיקת מוצר
if (isset($_GET['delete'])) {
    $sn = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM product WHERE SN = $sn");
}

// עדכון מוצר
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {
    $sn = $_POST['sn'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];
    $sizes = $_POST['sizes'];
    $image = $_POST['image'];

    $query = "UPDATE product SET 
              name = '$name', price = $price, amount = $amount,
              sizes = '$sizes', image = '$image'
              WHERE SN = $sn";
    mysqli_query($conn, $query);
}

// טען מוצרים מהמסד
$products = mysqli_query($conn, "SELECT * FROM product");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Panel</title>
  <link rel="stylesheet" href="style.css" />
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="admin-container">
  <h1>Welcome, Admin <?= htmlspecialchars($_SESSION['full_name']) ?>!</h1>

  <!-- ניהול מוצרים -->
  <h2>Product Management</h2>

  <!-- טופס הוספת מוצר חדש -->
  <form method="post">
    <h3>Add Product</h3>
    <input type="text" name="name" placeholder="Product Name" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="number" name="amount" placeholder="Amount" required>
    <input type="text" name="sizes" placeholder="Weight/Size" required>
    <input type="text" name="image" placeholder="Image (e.g. gas.jpg)" required>
    <input type="submit" name="add_product" value="Add Product">
  </form>

  <!-- הצגת כל המוצרים -->
  <h3>Existing Products</h3>
  <table border="1" cellpadding="8" cellspacing="0">
    <tr>
      <th>SN</th>
      <th>Name</th>
      <th>Price</th>
      <th>Amount</th>
      <th>Weight/Size</th>
      <th>Image</th>
      <th>Actions</th>
    </tr>
    <?php 
    $i = 0;
    while ($row = mysqli_fetch_assoc($products)): 
        $i++;
        $imageFile = ($i == 1) ? 'gasCylinder.jpg' : htmlspecialchars($row['image']);
    ?>
      <tr>
        <form method="post">
          <td>
            <?php echo $row['SN']; ?>
            <input type="hidden" name="sn" value="<?php echo $row['SN']; ?>">
          </td>
          <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>"></td>
          <td><input type="number" step="0.01" name="price" value="<?php echo $row['price']; ?>"></td>
          <td><input type="number" name="amount" value="<?php echo $row['amount']; ?>"></td>
          <td><input type="text" name="sizes" value="<?php echo htmlspecialchars($row['sizes']); ?>"></td>
          <td>
            <input type="hidden" name="image" value="<?php echo $imageFile; ?>">
            <img src="images/<?php echo $imageFile; ?>" alt="Product Image" style="width: 100px; height: auto; border-radius: 8px; margin-top: 5px;">
          </td>
          <td>
            <input type="submit" name="edit_product" value="Update">
            <br>
            <a href="admin.php?delete=<?php echo $row['SN']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
          </td>

        </form>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

</body>
</html>
