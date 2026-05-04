<?php
// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle  = 'Contact Us';
$activePage = 'contact';
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
      <span class="current">Contact</span>
    </div>
    <h2>✉️ Get In Touch</h2>
    <p>We'd love to hear from you — or your pet!</p>
  </div>
</div>

<div class="container py-5">
  <div class="row g-5">
    <div class="col-md-5">
      <div class="section-label">Contact Info</div>
      <h2 class="section-title" style="margin-bottom:24px;">We're Here <span>For You</span></h2>
      <div class="d-flex flex-column gap-3">
        <div class="contact-info-card">
          <div class="contact-icon"><i class="fas fa-map-marker-alt"></i></div>
          <div>
            <div style="font-weight:700;color:var(--brown);font-size:0.9rem;">Office Address</div>
            <div style="font-size:0.82rem;color:var(--text-light);margin-top:4px;">123 Katipunan Ave., Quezon City, Metro Manila, Philippines</div>
          </div>
        </div>
        <div class="contact-info-card">
          <div class="contact-icon"><i class="fas fa-phone"></i></div>
          <div>
            <div style="font-weight:700;color:var(--brown);font-size:0.9rem;">Phone</div>
            <div style="font-size:0.82rem;color:var(--text-light);margin-top:4px;">+63 (02) 8888-INFUR · 0917-INFURNEST</div>
          </div>
        </div>
        <div class="contact-info-card">
          <div class="contact-icon"><i class="fas fa-envelope"></i></div>
          <div>
            <div style="font-weight:700;color:var(--brown);font-size:0.9rem;">Email</div>
            <div style="font-size:0.82rem;color:var(--text-light);margin-top:4px;">hello@infurnest.ph · support@infurnest.ph</div>
          </div>
        </div>
        <div class="contact-info-card">
          <div class="contact-icon"><i class="fas fa-clock"></i></div>
          <div>
            <div style="font-weight:700;color:var(--brown);font-size:0.9rem;">Business Hours</div>
            <div style="font-size:0.82rem;color:var(--text-light);margin-top:4px;">Mon–Sat: 8:00 AM – 6:00 PM · Sun: 9:00 AM – 5:00 PM</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div style="background:white;border-radius:var(--radius);padding:36px;box-shadow:var(--shadow);">
        <h5 style="font-family:'Playfair Display',serif;color:var(--brown);margin-bottom:24px;">Send Us a Message 🐾</h5>
        <div class="row g-3">
          <div class="col-sm-6">
            <div class="form-group-custom">
              <label>Your Name</label>
              <input type="text" id="cName" placeholder="Juan dela Cruz"/>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group-custom">
              <label>Email</label>
              <input type="email" id="cEmail" placeholder="juan@example.com"/>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group-custom">
              <label>Subject</label>
              <select id="cSubject">
                <option>General Inquiry</option>
                <option>Order Support</option>
                <option>Product Question</option>
                <option>Wholesale/Bulk</option>
                <option>Partnership</option>
                <option>Complaint</option>
              </select>
            </div>
          </div>
          <div class="col-12">
            <div class="form-group-custom">
              <label>Message</label>
              <textarea id="cMessage" rows="5" placeholder="Tell us how we can help you or your pet..."></textarea>
            </div>
          </div>
        </div>
        <button class="btn-primary-custom w-100 mt-2" onclick="sendContactForm()" style="justify-content:center;padding:14px;font-size:0.95rem;">
          <i class="fas fa-paper-plane"></i> Send Message
        </button>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script>
function sendContactForm() {
  const name    = document.getElementById('cName').value.trim();
  const email   = document.getElementById('cEmail').value.trim();
  const message = document.getElementById('cMessage').value.trim();
  if (!name || !email || !message) {
    showToast('Please fill in all fields!', 'error');
    return;
  }
  showToast(`✅ Message sent! We'll reply within 24 hours, ${name.split(' ')[0]}!`, 'success');
  document.getElementById('cName').value    = '';
  document.getElementById('cEmail').value   = '';
  document.getElementById('cMessage').value = '';
}
</script>
</body>
</html>