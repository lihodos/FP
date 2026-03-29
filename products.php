<?php
session_start();
include "connectdb.php";
 
$query = "SELECT * FROM Product ORDER BY price DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>GETGas — Products</title>
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=Barlow:wght@300;400;500&display=swap" rel="stylesheet">
  <style>
    :root {
      --fire: #ff5e1a;
      --fire-dim: #cc4a12;
      --coal: #111214;
      --steel: #1c1e22;
      --iron: #2a2d33;
      --smoke: #3d4048;
      --ash: #6b7280;
      --silver: #c9cdd6;
      --white: #f4f5f7;
    }
 
    * { box-sizing: border-box; margin: 0; padding: 0; }
 
    body {
      font-family: 'Barlow', sans-serif;
      background-color: var(--coal);
      color: var(--white);
      min-height: 100vh;
      overflow-x: hidden;
    }
 
    /* ─── BACKGROUND ─── */
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      background:
        radial-gradient(ellipse 80% 50% at 50% -10%, rgba(255,94,26,0.12) 0%, transparent 60%),
        repeating-linear-gradient(
          0deg,
          transparent,
          transparent 39px,
          rgba(255,255,255,0.015) 39px,
          rgba(255,255,255,0.015) 40px
        ),
        repeating-linear-gradient(
          90deg,
          transparent,
          transparent 39px,
          rgba(255,255,255,0.015) 39px,
          rgba(255,255,255,0.015) 40px
        );
      pointer-events: none;
      z-index: 0;
    }
 
    /* ─── NAVBAR ─── */
    .navbar {
      position: fixed;
      top: 0; left: 0; right: 0;
      z-index: 100;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 40px;
      height: 64px;
      background: rgba(17,18,20,0.92);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(255,94,26,0.25);
    }
 
    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
    }
 
    .navbar-brand .flame-icon {
      font-size: 22px;
      line-height: 1;
    }
 
    .navbar-brand .brand-text {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 22px;
      font-weight: 800;
      letter-spacing: 2px;
      color: var(--white);
      text-transform: uppercase;
    }
 
    .navbar-brand .brand-text span {
      color: var(--fire);
    }
 
    .navbar-links {
      display: flex;
      align-items: center;
      gap: 4px;
    }
 
    .nav-link {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--ash);
      text-decoration: none;
      padding: 8px 14px;
      border-radius: 4px;
      transition: color 0.2s, background 0.2s;
    }
 
    .nav-link:hover {
      color: var(--white);
      background: rgba(255,255,255,0.06);
    }
 
    .nav-link.active {
      color: var(--fire);
    }
 
    .nav-link.cta {
      background: var(--fire);
      color: var(--white);
      padding: 8px 18px;
    }
 
    .nav-link.cta:hover {
      background: var(--fire-dim);
    }
 
    .nav-divider {
      width: 1px;
      height: 20px;
      background: var(--smoke);
      margin: 0 6px;
    }
 
    /* ─── PAGE WRAPPER ─── */
    .page {
      position: relative;
      z-index: 1;
      padding-top: 64px;
    }
 
    /* ─── HERO STRIP ─── */
    .page-header {
      padding: 52px 40px 36px;
      max-width: 1280px;
      margin: 0 auto;
    }
 
    .page-header .label {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 3px;
      text-transform: uppercase;
      color: var(--fire);
      margin-bottom: 10px;
    }
 
    .page-header h1 {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: clamp(38px, 5vw, 64px);
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 1px;
      line-height: 1;
      color: var(--white);
    }
 
    .page-header h1 span {
      color: var(--fire);
    }
 
    .page-header .subtitle {
      margin-top: 12px;
      font-size: 15px;
      font-weight: 300;
      color: var(--ash);
      max-width: 480px;
    }
 
    .header-line {
      height: 2px;
      background: linear-gradient(to right, var(--fire), transparent);
      max-width: 1280px;
      margin: 0 auto 40px;
    }
 
    /* ─── PRODUCT GRID ─── */
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 24px;
      max-width: 1280px;
      margin: 0 auto;
      padding: 0 40px 80px;
    }
 
    .product-card {
      background: var(--steel);
      border: 1px solid var(--iron);
      border-radius: 8px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;
      animation: fadeUp 0.5s ease both;
    }
 
    .product-card:hover {
      transform: translateY(-4px);
      border-color: rgba(255,94,26,0.4);
      box-shadow: 0 16px 40px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,94,26,0.15);
    }
 
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
 
    .product-card:nth-child(1) { animation-delay: 0.05s; }
    .product-card:nth-child(2) { animation-delay: 0.12s; }
    .product-card:nth-child(3) { animation-delay: 0.19s; }
    .product-card:nth-child(4) { animation-delay: 0.26s; }
 
    .product-img-wrap {
      position: relative;
      background: var(--iron);
      height: 200px;
      overflow: hidden;
    }
 
    .product-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      padding: 20px;
      transition: transform 0.4s ease;
    }
 
    .product-card:hover .product-img-wrap img {
      transform: scale(1.06);
    }
 
    .stock-badge {
      position: absolute;
      top: 12px;
      right: 12px;
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 11px;
      font-weight: 700;
      letter-spacing: 1px;
      text-transform: uppercase;
      padding: 4px 10px;
      border-radius: 3px;
      background: rgba(17,18,20,0.8);
      border: 1px solid;
    }
 
    .stock-badge.in-stock {
      color: #4ade80;
      border-color: rgba(74,222,128,0.3);
    }
 
    .stock-badge.out-of-stock {
      color: #f87171;
      border-color: rgba(248,113,113,0.3);
    }
 
    .product-body {
      padding: 20px 22px;
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }
 
    .product-name {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 20px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: var(--white);
      line-height: 1.2;
    }
 
    .product-meta {
      display: flex;
      gap: 16px;
    }
 
    .meta-item {
      display: flex;
      flex-direction: column;
      gap: 2px;
    }
 
    .meta-label {
      font-size: 10px;
      font-weight: 600;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      color: var(--ash);
    }
 
    .meta-value {
      font-size: 14px;
      font-weight: 500;
      color: var(--silver);
    }
 
    .price-row {
      display: flex;
      align-items: baseline;
      gap: 4px;
    }
 
    .price-amount {
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 30px;
      font-weight: 800;
      color: var(--fire);
      line-height: 1;
    }
 
    .price-currency {
      font-size: 16px;
      font-weight: 600;
      color: var(--fire);
    }
 
    /* ─── ADD TO CART FORM ─── */
    .cart-form {
      margin-top: auto;
      display: flex;
      gap: 8px;
      align-items: center;
    }
 
    .qty-label {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: var(--ash);
      white-space: nowrap;
    }
 
    .qty-input {
      width: 64px;
      padding: 9px 10px;
      background: var(--iron);
      border: 1px solid var(--smoke);
      border-radius: 5px;
      color: var(--white);
      font-size: 14px;
      font-family: 'Barlow', sans-serif;
      text-align: center;
      transition: border-color 0.2s;
    }
 
    .qty-input:focus {
      outline: none;
      border-color: var(--fire);
    }
 
    .btn-cart {
      flex: 1;
      padding: 10px 14px;
      background: var(--fire);
      color: var(--white);
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 1.5px;
      text-transform: uppercase;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.2s, transform 0.1s;
    }
 
    .btn-cart:hover { background: var(--fire-dim); }
    .btn-cart:active { transform: scale(0.97); }
 
    .out-of-stock-tag {
      margin-top: auto;
      text-align: center;
      font-family: 'Barlow Condensed', sans-serif;
      font-size: 13px;
      font-weight: 700;
      letter-spacing: 2px;
      text-transform: uppercase;
      color: #f87171;
      padding: 10px;
      border: 1px solid rgba(248,113,113,0.2);
      border-radius: 5px;
      background: rgba(248,113,113,0.05);
    }
 
    /* ─── RESPONSIVE ─── */
    @media (max-width: 600px) {
      .navbar { padding: 0 16px; }
      .product-grid, .page-header { padding-left: 16px; padding-right: 16px; }
      .navbar-links .nav-link:not(.cta) { display: none; }
    }
  </style>
</head>
<body>
 
<!-- ══════════════ NAVBAR ══════════════ -->
<nav class="navbar">
  <a href="home.php" class="navbar-brand">
    <span class="flame-icon">🔥</span>
    <span class="brand-text">GET<span>GAS</span></span>
  </a>
 
  <div class="navbar-links">
    <?php if (!isset($_SESSION['user_id'])): ?>
      <a href="index.php" class="nav-link">Login</a>
      <a href="signup.php" class="nav-link">Sign Up</a>
      <div class="nav-divider"></div>
    <?php endif; ?>
 
    <a href="home.php" class="nav-link">Home</a>
 
    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="products.php" class="nav-link active">Products</a>
      <a href="cart_t.php" class="nav-link">🛒 Cart</a>
 
      <?php if (!empty($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1): ?>
        <div class="nav-divider"></div>
        <a href="admin.php" class="nav-link cta">Admin Panel</a>
      <?php else: ?>
        <div class="nav-divider"></div>
        <a href="logout.php" class="nav-link">Logout</a>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</nav>
 
<!-- ══════════════ PAGE ══════════════ -->
<div class="page">
  <div class="page-header">
    <div class="label">// Catalog</div>
    <h1>Our <span>Products</span></h1>
    <p class="subtitle">Industrial-grade gas equipment — delivered to your door.</p>
  </div>
  <div class="header-line"></div>
 
  <div class="product-grid">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <div class="product-card">
 
        <div class="product-img-wrap">
          <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
          <?php if ($row['amount'] > 0): ?>
            <span class="stock-badge in-stock">✓ In Stock</span>
          <?php else: ?>
            <span class="stock-badge out-of-stock">Out of Stock</span>
          <?php endif; ?>
        </div>
 
        <div class="product-body">
          <div class="product-name"><?= htmlspecialchars($row['name']) ?></div>
 
          <div class="product-meta">
            <div class="meta-item">
              <span class="meta-label">Size</span>
              <span class="meta-value"><?= htmlspecialchars($row['sizes']) ?></span>
            </div>
            <div class="meta-item">
              <span class="meta-label">Stock</span>
              <span class="meta-value"><?= $row['amount'] ?> units</span>
            </div>
          </div>
 
          <div class="price-row">
            <span class="price-amount"><?= $row['price'] ?></span>
            <span class="price-currency">₪</span>
          </div>
 
          <?php if ($row['amount'] > 0): ?>
            <form class="cart-form" method="post" action="add_to_cart.php">
              <input type="hidden" name="product_id" value="<?= $row['SN'] ?>">
              <span class="qty-label">Qty</span>
              <input class="qty-input" type="number" name="quantity" value="1" min="1" max="<?= $row['amount'] ?>" required>
              <button class="btn-cart" type="submit">Add to Cart</button>
            </form>
          <?php else: ?>
            <div class="out-of-stock-tag">— Unavailable —</div>
          <?php endif; ?>
 
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>
 
</body>
</html>