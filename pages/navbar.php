<?php
// ============================================================
// NAVBAR TEMPLATE — INFURNEST Premium Pet Shop
// ============================================================
// HOW TO USE:
//   1. Include this file at the top of each page AFTER setting
//      $pageTitle, $activePage, and $base.
//
//   2. Set $base depending on where your page lives:
//
//      ROOT pages (e.g. index.php):
//        $base = '';
//
//      PAGES/ subfolder (e.g. pages/shop.php):
//        $base = '../';
//
//   3. Set $activePage to one of:
//      'home', 'shop', 'about', 'contact', 'services',
//      'faqs', 'privacy', 'terms', 'cart'
//
// EXAMPLE (for pages/shop.php):
//   <?php
//     $pageTitle  = 'Shop';
//     $activePage = 'shop';
//     $base       = '../';
//     include '../includes/navbar.php';
//   ?>
//
// EXAMPLE (for index.php):
//   <?php
//     $pageTitle  = 'Home';
//     $activePage = 'home';
//     $base       = '';
//     include 'includes/navbar.php';
//   ?>
// ============================================================

// Get user session data
$userName  = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'User';
$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($pageTitle) ?> — INFURNEST Premium Pet Shop</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="<?= $base ?>assets/styles.css"/>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar-custom" id="mainNav">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">

      <!-- LOGO -->
      <a class="navbar-brand-logo" href="<?= $base ?>index.php">
        <div class="logo-icon"><i class="fas fa-paw"></i></div>
        IN<span class="accent">FUR</span>NEST
      </a>

      <!-- DESKTOP NAV -->
      <div class="nav-links d-none d-md-flex align-items-center gap-1">
        <a id="nav-home"     href="<?= $base ?>index.php"            <?= $activePage==='home'     ? 'class="active-nav"' : '' ?>>Home</a>
        <a id="nav-shop"     href="<?= $base ?>pages/shop.php"       <?= $activePage==='shop'     ? 'class="active-nav"' : '' ?>>Shop</a>
        <a id="nav-about"    href="<?= $base ?>pages/about.php"      <?= $activePage==='about'    ? 'class="active-nav"' : '' ?>>About</a>
        <a id="nav-contact"  href="<?= $base ?>pages/contact.php"    <?= $activePage==='contact'  ? 'class="active-nav"' : '' ?>>Contact</a>
        <a id="nav-services" href="<?= $base ?>pages/services.php"   <?= $activePage==='services' ? 'class="active-nav"' : '' ?>>Services</a>
        <a id="nav-faqs"     href="<?= $base ?>pages/faqs.php"       <?= $activePage==='faqs'     ? 'class="active-nav"' : '' ?>>FAQs</a>
        <a id="nav-privacy"  href="<?= $base ?>pages/privacy.php"    <?= $activePage==='privacy'  ? 'class="active-nav"' : '' ?>>Privacy</a>
        <a id="nav-terms"    href="<?= $base ?>pages/terms.php"      <?= $activePage==='terms'    ? 'class="active-nav"' : '' ?>>Terms</a>
      </div>

      <!-- RIGHT SIDE -->
      <div class="d-flex align-items-center gap-2">

        <!-- CART — desktop -->
        <a id="nav-cart" class="cart-btn d-none d-sm-flex" href="<?= $base ?>pages/cart.php">
          <i class="fas fa-shopping-bag"></i> Cart
          <span class="cart-count">0</span>
        </a>
        <!-- CART — mobile icon only -->
        <a class="cart-btn d-flex d-sm-none" href="<?= $base ?>pages/cart.php" style="padding:9px 12px;">
          <i class="fas fa-shopping-bag"></i>
          <span class="cart-count">0</span>
        </a>

        <!-- PROFILE / LOGIN -->
        <?php if ($isLoggedIn): ?>
          <a id="nav-dashboard" href="<?= $base ?>pages/dashboard.php" class="btn-primary-custom d-none d-sm-inline">
            <i class="fas fa-user"></i> <?= htmlspecialchars($userName) ?>
          </a>
          <a href="<?= $base ?>pages/logout.php" class="btn-outline-custom d-none d-sm-inline">Logout</a>
        <?php else: ?>
  <a id="nav-login" href="<?= $base ?>account/login.php" class="btn-primary-custom d-none d-sm-inline" style="padding:9px 12px;">
            <i class="fas fa-user"></i> Login
          </a>
        <?php endif; ?>

        <!-- HAMBURGER -->
        <button class="hamburger d-md-none" onclick="toggleMobileMenu()">
          <i class="fas fa-bars"></i>
        </button>
      </div>

    </div>

    <!-- MOBILE MENU -->
    <div class="mobile-menu" id="mobileMenu">
      <a id="mnav-home"     href="<?= $base ?>index.php"            <?= $activePage==='home'     ? 'class="active-nav"' : '' ?>>🏠 Home</a>
      <a id="mnav-shop"     href="<?= $base ?>pages/shop.php"       <?= $activePage==='shop'     ? 'class="active-nav"' : '' ?>>🛍️ Shop</a>
      <a id="mnav-about"    href="<?= $base ?>pages/about.php"      <?= $activePage==='about'    ? 'class="active-nav"' : '' ?>>🐾 About</a>
      <a id="mnav-contact"  href="<?= $base ?>pages/contact.php"    <?= $activePage==='contact'  ? 'class="active-nav"' : '' ?>>✉️ Contact</a>
      <a id="mnav-services" href="<?= $base ?>pages/services.php"   <?= $activePage==='services' ? 'class="active-nav"' : '' ?>>🛠️ Services</a>
      <a id="mnav-faqs"     href="<?= $base ?>pages/faqs.php"       <?= $activePage==='faqs'     ? 'class="active-nav"' : '' ?>>❓ FAQs</a>
      <a id="mnav-cart"     href="<?= $base ?>pages/cart.php"       <?= $activePage==='cart'     ? 'class="active-nav"' : '' ?>>🛒 Cart</a>
      <?php if ($isLoggedIn): ?>
        <a id="mnav-dashboard" href="<?= $base ?>pages/dashboard.php">👤 Dashboard</a>
        <a href="<?= $base ?>pages/logout.php">🚪 Logout</a>
      <?php else: ?>
        <a id="mnav-login" href="<?= $base ?>account/login.php">🔐 Login</a>
      <?php endif; ?>
    </div>

  </div>
</nav>

<div class="toast-container-custom" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base ?>assets/script.js"></script>

<script>
// MOBILE MENU
function toggleMobileMenu() {
  document.getElementById('mobileMenu').classList.toggle('open');
}

// TOAST
function showToast(msg, type = 'success') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = 'toast-custom';
  const color = type === 'success' ? 'var(--amber)' : type === 'error' ? '#E84A2A' : 'var(--sage)';
  toast.style.borderLeftColor = color;
  const icon = type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-times-circle' : 'fa-info-circle';
  toast.innerHTML = `<i class="fas ${icon}" style="color:${color};"></i><span>${msg}</span>`;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}
</script>