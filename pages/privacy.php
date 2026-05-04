<?php
// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle  = 'Data Privacy Policy';
$activePage = 'privacy';
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
      <span class="current">Data Privacy Policy</span>
    </div>
    <h2>🔒 Data Privacy Policy</h2>
  </div>
</div>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <div class="policy-content" style="background:white;border-radius:var(--radius);padding:40px;box-shadow:var(--shadow);">

        <p style="color:var(--text-light);font-size:0.85rem;margin-bottom:24px;">Last updated: January 2025</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">1. Introduction</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">INFURNEST ("we," "our," or "us") respects your privacy. This Data Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or make a purchase.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">2. Information We Collect</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:12px;">We collect the following types of information:</p>
        <ul style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;padding-left:20px;">
          <li>Personal identification information (Name, Email, Phone number)</li>
          <li>Shipping and billing addresses</li>
          <li>Payment information (processed securely)</li>
          <li>Pet-related preferences</li>
          <li>Browsing behavior and cookies</li>
        </ul>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">3. How We Use Your Information</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:12px;">We use your information to:</p>
        <ul style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;padding-left:20px;">
          <li>Process and fulfill your orders</li>
          <li>Provide customer support</li>
          <li>Send order updates and notifications</li>
          <li>Improve our services and website</li>
          <li>Comply with legal obligations</li>
        </ul>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">4. Data Security</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">We implement appropriate security measures to protect your personal information. All payment data is encrypted using SSL technology. However, no method of transmission over the internet is 100% secure.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">5. Your Rights</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:12px;">Under the Data Privacy Act of the Philippines, you have the right to:</p>
        <ul style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;padding-left:20px;">
          <li>Access your personal data</li>
          <li>Correct inaccurate data</li>
          <li>Request deletion of your data</li>
          <li>Object to processing</li>
          <li>Data portability</li>
        </ul>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">6. Cookies</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">We use cookies to enhance your browsing experience. You can set your browser to refuse cookies, but some features may not function properly.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">7. Third-Party Services</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">We may share data with third-party service providers (payment processors, delivery partners) only as necessary to fulfill your orders. They are obligated to protect your data.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">8. Children's Privacy</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">Our services are not intended for children under 13. We do not knowingly collect information from children.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">9. Changes to This Policy</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">We may update this policy periodically. We will notify you of any material changes.</p>

        <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin:24px 0 12px;">10. Contact Us</h4>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">If you have questions about this policy, please contact us at privacy@infurnest.com or call +639123456789.</p>

        <hr style="margin:32px 0;border-color:var(--blush);">
        <p style="color:var(--text-light);font-size:0.85rem;">By using our website, you consent to this Data Privacy Policy.</p>

      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body>
</html>