<?php
// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle  = 'About Us';
$activePage = 'about';
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
      <span class="current">About</span>
    </div>
    <h2>🐾 About INFURNEST</h2>
  </div>
</div>

<div class="container py-5">
  <!-- Our Story -->
  <div class="row align-items-center g-5 mb-5">
    <div class="col-md-6">
      <div class="section-label">Our Story</div>
      <h2 class="section-title" style="margin-bottom:20px;">Born from <span>Love</span> for Pets</h2>
      <p style="color:var(--text-mid);line-height:1.8;margin-bottom:16px;">INFURNEST started in 2020 when two pet parents — frustrated with the lack of quality, affordable pet products in the Philippines — decided to build something better. What began as a small online store has grown into the country's most beloved pet e-commerce destination.</p>
      <p style="color:var(--text-mid);line-height:1.8;margin-bottom:24px;">We work directly with trusted manufacturers and veterinary consultants to ensure every product we carry meets the highest standards of safety, nutrition, and quality. Your pet deserves nothing less.</p>
      <div class="row g-3">
        <div class="col-6"><div style="background:var(--cream);border-radius:12px;padding:18px;text-align:center;"><div style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:var(--amber);">18K+</div><div style="font-size:0.8rem;color:var(--text-light);">Happy Pet Families</div></div></div>
        <div class="col-6"><div style="background:var(--cream);border-radius:12px;padding:18px;text-align:center;"><div style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:var(--amber);">2,400+</div><div style="font-size:0.8rem;color:var(--text-light);">Products Available</div></div></div>
        <div class="col-6"><div style="background:var(--cream);border-radius:12px;padding:18px;text-align:center;"><div style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:var(--amber);">4.9★</div><div style="font-size:0.8rem;color:var(--text-light);">Average Rating</div></div></div>
        <div class="col-6"><div style="background:var(--cream);border-radius:12px;padding:18px;text-align:center;"><div style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:var(--amber);">24/7</div><div style="font-size:0.8rem;color:var(--text-light);">Customer Support</div></div></div>
      </div>
    </div>
    <div class="col-md-6 text-center">
      <div style="font-size:12rem;line-height:1;filter:drop-shadow(0 20px 40px rgba(61,43,31,0.15));">🐶🐱</div>
    </div>
  </div>

  <!-- Our Team -->
  <div class="mb-5">
    <div class="text-center mb-4">
      <div class="section-label">Our Team</div>
      <h2 class="section-title">The People Behind <span>INFURNEST</span></h2>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-sm-6 col-md-4 col-lg-2">
        <div class="team-card">
          <div class="team-avatar">👩‍💼</div>
          <h6 style="font-weight:700;color:var(--brown);">Maria Santos</h6>
          <div style="font-size:0.78rem;color:var(--amber);margin-bottom:8px;">Co-Founder & CEO</div>
          <p style="font-size:0.8rem;color:var(--text-light);">Dog mom of 3. Passionate about quality and animal welfare.</p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-2">
        <div class="team-card">
          <div class="team-avatar">👨‍💻</div>
          <h6 style="font-weight:700;color:var(--brown);">Carlo Reyes</h6>
          <div style="font-size:0.78rem;color:var(--amber);margin-bottom:8px;">Co-Founder & CTO</div>
          <p style="font-size:0.8rem;color:var(--text-light);">Cat dad. Builds tech that makes pet shopping easy.</p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-2">
        <div class="team-card">
          <div class="team-avatar">👩‍⚕️</div>
          <h6 style="font-weight:700;color:var(--brown);">Dr. Ana Cruz</h6>
          <div style="font-size:0.78rem;color:var(--amber);margin-bottom:8px;">Veterinary Advisor</div>
          <p style="font-size:0.8rem;color:var(--text-light);">Licensed vet. Reviews all our food and health products.</p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-2">
        <div class="team-card">
          <div class="team-avatar">👩‍🎨</div>
          <h6 style="font-weight:700;color:var(--brown);">Bea Lim</h6>
          <div style="font-size:0.78rem;color:var(--amber);margin-bottom:8px;">Head of Products</div>
          <p style="font-size:0.8rem;color:var(--text-light);">Curates every item in our store with love.</p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-2">
        <div class="team-card">
          <div class="team-avatar">👨‍📦</div>
          <h6 style="font-weight:700;color:var(--brown);">Mark Dela Cruz</h6>
          <div style="font-size:0.78rem;color:var(--amber);margin-bottom:8px;">Logistics Manager</div>
          <p style="font-size:0.8rem;color:var(--text-light);">Ensures fast and safe delivery of your orders.</p>
        </div>
      </div>
      <div class="col-sm-6 col-md-4 col-lg-2">
        <div class="team-card">
          <div class="team-avatar">👩‍💼</div>
          <h6 style="font-weight:700;color:var(--brown);">Kris Garcia</h6>
          <div style="font-size:0.78rem;color:var(--amber);margin-bottom:8px;">Customer Success</div>
          <p style="font-size:0.8rem;color:var(--text-light);">Your go-to for support and pet care advice.</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Mission & Vision -->
  <div class="row g-4">
    <div class="col-md-6">
      <div style="background:linear-gradient(135deg,var(--brown),var(--brown-mid));border-radius:var(--radius);padding:36px;color:white;">
        <h3 style="font-family:'Playfair Display',serif;font-size:1.4rem;margin-bottom:14px;">🌱 Our Mission</h3>
        <p style="color:rgba(255,255,255,0.75);line-height:1.8;font-size:0.9rem;">To make premium, vet-approved pet products accessible to every Filipino pet parent — with unbeatable prices, fast delivery, and genuine care for animal welfare.</p>
      </div>
    </div>
    <div class="col-md-6">
      <div style="background:linear-gradient(135deg,var(--sage),#4A7A4E);border-radius:var(--radius);padding:36px;color:white;">
        <h3 style="font-family:'Playfair Display',serif;font-size:1.4rem;margin-bottom:14px;">🌍 Our Vision</h3>
        <p style="color:rgba(255,255,255,0.75);line-height:1.8;font-size:0.9rem;">A Philippines where every pet is well-nourished, happy, and loved — and where being a responsible pet parent is made easy and affordable for all.</p>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body>
</html>