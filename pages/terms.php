<?php
// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle  = 'Terms of Service';
$activePage = 'terms';
$base       = '../';

// User session
$userName = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'User';
$isLoggedIn = isset($_SESSION['user_id']);

// Detect base path dynamically
$depth = substr_count(str_replace($_SERVER['DOCUMENT_ROOT'], '', __DIR__), '/');
$basePath = str_repeat('../', max(0, $depth - 1));
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
<link rel="stylesheet" href="<?= $base ?>assets/session-styles.css"/>
</head>

<body>

<nav class="navbar-custom" id="mainNav">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">

<!-- LOGO -->
      <a class="navbar-brand-logo" href="<?= $basePath ?>../index.php">
        <div class="logo-icon"><i class="fas fa-paw"></i></div>
        IN<span class="accent">FUR</span>NEST
      </a>

<!-- DESKTOP NAV -->
<div class="nav-links d-none d-md-flex align-items-center gap-1">
  <?php
    $navLinks = [
  'home'     => ['label' => 'Home',     'href' => $basePath . '../index.php'],
  'shop'     => ['label' => 'Shop',     'href' => $basePath . '../pages/shop.php'],
  'about'    => ['label' => 'About',    'href' => $basePath . '../pages/about.php'],
  'contact'  => ['label' => 'Contact',  'href' => $basePath . '../pages/contact.php'],
  'services' => ['label' => 'Services', 'href' => $basePath . '../pages/services.php'],
  'faqs'     => ['label' => 'FAQs',     'href' => $basePath . '../pages/faqs.php'],
  'privacy'  => ['label' => 'Privacy',  'href' => $basePath . '../pages/privacy.php'],
  'terms'    => ['label' => 'Terms',    'href' => $basePath . '../pages/terms.php'],
];

    foreach ($navLinks as $key => $link):
      $isActive = $activePage === $key ? 'class="active-nav"' : '';
  ?>
    <a id="nav-<?= $key ?>" href="<?= $link['href'] ?>" <?= $isActive ?>>
      <?= $link['label'] ?>
    </a>
  <?php endforeach; ?>
</div>

      <!-- RIGHT SIDE -->
      <div class="d-flex align-items-center gap-2">

<!-- CART -->
        <a id="nav-cart" class="cart-btn d-none d-sm-flex" href="<?= $basePath ?>../pages/cart.php">
          <i class="fas fa-shopping-bag"></i> Cart
          <span class="cart-count">0</span>
        </a>

        <a class="cart-btn d-flex d-sm-none" href="<?= $basePath ?>../pages/cart.php" style="padding:9px 12px;">
          <i class="fas fa-shopping-bag"></i>
          <span class="cart-count">0</span>
        </a>

<!-- PROFILE DROPDOWN -->
        <div class="dropdown d-none d-sm-flex">
          <button class="profile-btn dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <?php if ($isLoggedIn): ?>
              <li class="dropdown-header text-start px-3">
                <small class="text-muted">Welcome back!</small><br>
                <strong><?= htmlspecialchars($userName) ?></strong>
              </li>
              <li><a class="dropdown-item" href="<?= $basePath ?>../pages/dashboard.php"><i class="fas fa-user"></i> My Account</a></li>
              <li><a class="dropdown-item" href="<?= $basePath ?>../pages/orders.php"><i class="fas fa-box"></i> My Orders</a></li>
              <li><a class="dropdown-item" href="<?= $basePath ?>../pages/wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= $basePath ?>../pages/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php else: ?>
              <li><a class="dropdown-item" href="<?= $basePath ?>../account/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
              <li><a class="dropdown-item" href="<?= $basePath ?>../account/login.php#register"><i class="fas fa-user-plus"></i> Register</a></li>
            <?php endif; ?>
          </ul>
        </div>

        <a class="profile-btn-mobile d-flex d-sm-none" href="<?php echo $isLoggedIn ? $basePath.'../pages/dashboard.php' : $basePath.'../account/login.php'; ?>">
          <i class="fas fa-user-circle"></i>
        </a>



        <!-- MOBILE BUTTON -->
        <button class="hamburger d-md-none" onclick="toggleMobileMenu()">
          <i class="fas fa-bars"></i>
        </button>
      </div>

    </div>

<!-- MOBILE MENU -->
    <div class="mobile-menu" id="mobileMenu">
  <a id="mnav-home"     href="<?= $basePath ?>../index.php">🏠 Home</a>
  <a id="mnav-shop"     href="<?= $basePath ?>../pages/shop.php">🛍️ Shop</a>
  <a id="mnav-about"    href="<?= $basePath ?>../pages/about.php">🐾 About</a>
  <a id="mnav-contact"  href="<?= $basePath ?>../pages/contact.php">✉️ Contact</a>
  <a id="mnav-services" href="<?= $basePath ?>../pages/services.php">🛠️ Services</a>
  <a id="mnav-faqs"     href="<?= $basePath ?>../pages/faqs.php">❓ FAQs</a>
  <a id="mnav-cart"     href="<?= $basePath ?>../pages/cart.php">🛒 Cart</a>

  <?php if ($isLoggedIn): ?>
    <a id="mnav-dashboard" href="<?= $basePath ?>../pages/dashboard.php">👤 Dashboard</a>
    <a href="<?= $basePath ?>../pages/logout.php">🚪 Logout</a>
  <?php else: ?>
    <a id="mnav-login" href="<?= $basePath ?>../account/login.php">🔐 Login</a>
  <?php endif; ?>
</div>

  </div>
</nav>

<div class="toast-container-custom" id="toastContainer"></div>

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="<?= $base ?>assets/script.js"></script>

<script>
// MOBILE MENU
function toggleMobileMenu() {
  document.getElementById('mobileMenu').classList.toggle('open');
}

// NAV HIGHLIGHT
function highlightCurrentNav() {
  const path = window.location.pathname.split('/').pop() || 'index.php';

  const map = {
    'index.php': 'home',
    'shop.php': 'shop',
    'about.php': 'about',
    'contact.php': 'contact',
    'services.php': 'services',
    'faqs.php': 'faqs',
    'privacy.php': 'privacy',
    'terms.php': 'terms',
    'cart.php': 'cart',
    'login.php': 'login',
    'dashboard.php': 'dashboard'
  };

  const page = map[path] || 'home';

  // desktop
  document.querySelectorAll('.nav-links a').forEach(a => a.classList.remove('active-nav'));
  const d = document.getElementById('nav-' + page);
  if (d) d.classList.add('active-nav');

  // mobile
  document.querySelectorAll('.mobile-menu a').forEach(a => a.classList.remove('active-nav'));
  const m = document.getElementById('mnav-' + page);
  if (m) m.classList.add('active-nav');
}

// TOAST
function showToast(msg, type='success') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = 'toast-custom';

  const color = type === 'success' ? 'var(--amber)' : type === 'error' ? '#E84A2A' : 'var(--sage)';
  toast.style.borderLeftColor = color;

  toast.innerHTML = `<i class="fas ${type==='success'?'fa-check-circle':type==='error'?'fa-times-circle':'fa-info-circle'}" style="color:${color};"></i><span>${msg}</span>`;

  container.appendChild(toast);

  setTimeout(() => {
    toast.remove();
  }, 3000);
}

// Navbar Dropdown Fix - Force Bootstrap init
const dropdownElement = document.querySelector('#profileDropdown');
if (dropdownElement && typeof bootstrap !== 'undefined') {
  const dropdown = new bootstrap.Dropdown(dropdownElement);
}

// INIT
document.addEventListener('DOMContentLoaded', () => {
  highlightCurrentNav();
  
 
});
</script>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb-custom">
      <a href="../index.php">Home</a><span class="sep">/</span>
      <span class="current">Terms of Service</span>
    </div>
    <h2>📜 Terms of Service</h2>
  </div>
</div>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="policy-content" style="background:white;border-radius:var(--radius);padding:40px;box-shadow:var(--shadow);">

        <p style="color:var(--text-light);font-size:0.85rem;margin-bottom:24px;">Last updated: January 2025</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">1. Acceptance of Terms</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">By accessing and using INFURNEST website, you accept and agree to be bound by the terms and provisions of this agreement.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">2. Use License</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:12px;">Permission is granted to temporarily use INFURNEST website for personal, non-commercial use only.</p>
        <ul style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;padding-left:20px;">
          <li>You may not modify or copy the materials</li>
          <li>You may not use the materials for any commercial purpose</li>
          <li>You may not transfer the materials to another person</li>
          <li>You may not attempt to decompile any software</li>
        </ul>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">3. Account Registration</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:12px;">To make a purchase, you must create an account. You agree to:</p>
        <ul style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;padding-left:20px;">
          <li>Provide accurate and complete information</li>
          <li>Maintain the security of your account</li>
          <li>Notify us immediately of any unauthorized use</li>
          <li>Be responsible for all activities under your account</li>
        </ul>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">4. Orders and Payment</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:12px;">When you place an order:</p>
        <ul style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;padding-left:20px;">
          <li>You agree to pay the total amount including shipping</li>
          <li>Prices are subject to change without notice</li>
          <li>We reserve the right to refuse or cancel any order</li>
          <li>Payment must be received before shipment</li>
        </ul>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">5. Shipping and Delivery</h4>
        <ul style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;padding-left:20px;">
          <li>Delivery times are estimates only</li>
          <li>Free shipping on orders over ₱999</li>
          <li>Shipping fees apply for orders below ₱999</li>
          <li>Risk passes to buyer upon delivery</li>
        </ul>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">6. Returns and Refunds</h4>
        <ul style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;padding-left:20px;">
          <li>30-day return policy for unused items</li>
          <li>Items must be in original packaging</li>
          <li>Refunds are processed within 5-7 business days</li>
          <li>Return shipping costs may apply</li>
        </ul>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">7. Product Information</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">We strive to display products accurately. However, colors may vary due to monitor settings. We reserve the right to limit quantities.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">8. Disclaimer</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">The materials on INFURNEST are provided "as is." We make no warranties, expressed or implied, and hereby disclaims all warranties.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">9. Limitation of Liability</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">INFURNEST shall not be liable for any damages arising out of the use or inability to use the materials on this website.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">10. Governing Law</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">These terms and conditions are governed by the laws of the Philippines. Any disputes will be resolved in Philippine courts.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">11. Contact Information</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">For questions about these Terms of Service, please contact us at support@infurnest.com or +639123456789.</p>

        <hr style="margin:32px 0;border-color:var(--blush);">
        <p style="color:var(--text-light);font-size:0.85rem;">By using our website, you agree to these Terms of Service.</p>

      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body>
</html>