<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="top-buttons">
  <?php if (!isset($_SESSION['user_id'])): ?>
    <a href="index.php" class="nav-btn">Login</a>
    <a href="signup.php" class="nav-btn">Sign Up</a>
  <?php endif; ?>

  <a href="home.php" class="nav-btn">Home</a>

  <?php if (isset($_SESSION['user_id'])): ?>
    <a href="products.php" class="nav-btn">Products</a>
    <a href="cart_t.php" class="nav-btn">Cart</a>
    <a href="logout.php" class="nav-btn">Logout</a>

    <?php if (!empty($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1): ?>
      <a href="admin.php" class="nav-btn">Admin</a>
    <?php endif; ?>
  <?php endif; ?>
</div>
