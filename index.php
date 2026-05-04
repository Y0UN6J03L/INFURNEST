<?php
// Start session properly - use config if available, otherwise fallback
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Include config to get session functions (optional - if file exists)
$configFile = __DIR__ . '/backend/includes/config.php';
if (file_exists($configFile)) {
    require_once $configFile;
    // Run startSession to enforce lifetime and regenerate ID
    if (function_exists('startSession')) {
        startSession();
    }
}

// User session - check both session keys for compatibility
$userName = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'User';
$isLoggedIn = isset($_SESSION['user_id']);

$pageTitle = 'Home';
$activePage = 'home';
$base = '';

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
      <a class="navbar-brand-logo" href="<?= $basePath ?>index.php">
        <div class="logo-icon"><i class="fas fa-paw"></i></div>
        IN<span class="accent">FUR</span>NEST
      </a>

<!-- DESKTOP NAV -->
<div class="nav-links d-none d-md-flex align-items-center gap-1">
  <?php
    $navLinks = [
  'home'     => ['label' => 'Home',     'href' => $basePath . 'index.php'],
  'shop'     => ['label' => 'Shop',     'href' => $basePath . 'pages/shop.php'],
  'about'    => ['label' => 'About',    'href' => $basePath . 'pages/about.php'],
  'contact'  => ['label' => 'Contact',  'href' => $basePath . 'pages/contact.php'],
  'services' => ['label' => 'Services', 'href' => $basePath . 'pages/services.php'],
  'faqs'     => ['label' => 'FAQs',     'href' => $basePath . 'pages/faqs.php'],
  'privacy'  => ['label' => 'Privacy',  'href' => $basePath . 'pages/privacy.php'],
  'terms'    => ['label' => 'Terms',    'href' => $basePath . 'pages/terms.php'],
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
        <a id="nav-cart" class="cart-btn d-none d-sm-flex" href="<?= $basePath ?>pages/cart.php">
          <i class="fas fa-shopping-bag"></i> Cart
          <span class="cart-count">0</span>
        </a>

        <a class="cart-btn d-flex d-sm-none" href="<?= $basePath ?>pages/cart.php" style="padding:9px 12px;">
          <i class="fas fa-shopping-bag"></i>
          <span class="cart-count">0</span>
        </a>

<!-- PROFILE DROPDOWN -->
        <div class="dropdown d-none d-sm-flex">
          <button class="profile-btn dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <?php if ($isLoggedIn): ?>
              <span class="user-avatar-logged-in"><i class="fas fa-user-circle"></i></span>
            <?php else: ?>
              <i class="fas fa-user-circle"></i>
            <?php endif; ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <?php if ($isLoggedIn): ?>
              <li class="dropdown-header text-start px-3">
                <small class="text-muted">Welcome back!</small><br>
                <strong><?= htmlspecialchars($userName) ?></strong>
              </li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= $basePath ?>pages/dashboard.php"><i class="fas fa-user"></i> My Account</a></li>
              <li><a class="dropdown-item" href="<?= $basePath ?>pages/orders.php"><i class="fas fa-box"></i> My Orders</a></li>
              <li><a class="dropdown-item" href="<?= $basePath ?>pages/wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="<?= $basePath ?>pages/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php else: ?>
              <li class="dropdown-header text-start px-3">
                <small class="text-muted">Guest User</small>
              </li>
              <li><a class="dropdown-item" href="<?= $basePath ?>account/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
              <li><a class="dropdown-item" href="<?= $basePath ?>pages/register.php"><i class="fas fa-user-plus"></i> Register</a></li>
            <?php endif; ?>
          </ul>
        </div>

        <a class="profile-btn-mobile d-flex d-sm-none" href="<?php echo $isLoggedIn ? $basePath.'pages/dashboard.php' : $basePath.'pages/login.php'; ?>">
          <?php if ($isLoggedIn): ?>
            <span class="user-avatar-logged-in"><i class="fas fa-user-circle"></i></span>
          <?php else: ?>
            <i class="fas fa-user-circle"></i>
          <?php endif; ?>
        </a>



        <!-- MOBILE BUTTON -->
        <button class="hamburger d-md-none" onclick="toggleMobileMenu()">
          <i class="fas fa-bars"></i>
        </button>
      </div>

    </div>

<!-- MOBILE MENU -->
    <div class="mobile-menu" id="mobileMenu">
  <a id="mnav-home"     href="<?= $basePath ?>index.php">🏠 Home</a>
  <a id="mnav-shop"     href="<?= $basePath ?>pages/shop.php">🛍️ Shop</a>
  <a id="mnav-about"    href="<?= $basePath ?>pages/about.php">🐾 About</a>
  <a id="mnav-contact"  href="<?= $basePath ?>pages/contact.php">✉️ Contact</a>
  <a id="mnav-services" href="<?= $basePath ?>pages/services.php">🛠️ Services</a>
  <a id="mnav-faqs"     href="<?= $basePath ?>pages/faqs.php">❓ FAQs</a>
  <a id="mnav-cart"     href="<?= $basePath ?>pages/cart.php">🛒 Cart</a>

  <?php if ($isLoggedIn): ?>
    <a id="mnav-dashboard" href="<?= $basePath ?>pages/dashboard.php">👤 Dashboard</a>
    <a href="<?= $basePath ?>pages/logout.php">🚪 Logout</a>
  <?php else: ?>
    <a id="mnav-login" href="<?= $basePath ?>account/login.php">🔐 Login</a>
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

// INIT
document.addEventListener('DOMContentLoaded', () => {
  highlightCurrentNav();
});
</script>

<!-- ========== HERO ========== -->
<section class="hero">
  <span class="hero-paw p1">🐾</span>
  <span class="hero-paw p2">🐾</span>
  <span class="hero-paw p3">🐾</span>
  <div class="container py-4">
    <div class="row align-items-center g-4">
      <div class="col-lg-6 col-md-7">
        <div class="hero-badge">🐶🐱 Premium Pet Essentials</div>
        <h1>Everything Your <span class="highlight">Furry Friend</span> Needs</h1>
        <p>Discover handpicked products for dogs and cats — from gourmet treats and stylish accessories to cozy beds and wellness essentials. Because your pet deserves the best.</p>
        <div class="d-flex gap-3 flex-wrap">
          <a class="btn-primary-custom" href="<?= $base ?>pages/shop.php"><i class="fas fa-store"></i> Shop Now</a>
          <a class="btn-outline-custom" href="<?= $base ?>pages/about.php"><i class="fas fa-play-circle"></i> Learn More</a>
        </div>
        <div class="hero-stats">
          <div class="stat-item"><div class="num">2,400+</div><div class="lbl">Products</div></div>
          <div class="stat-item"><div class="num">18K+</div><div class="lbl">Happy Pets</div></div>
          <div class="stat-item"><div class="num">4.9★</div><div class="lbl">Rating</div></div>
        </div>
      </div>
      <div class="col-lg-6 col-md-5 d-none d-md-block">
        <div class="hero-image-area">🐕</div>
      </div>
    </div>
  </div>
</section>

<!-- ========== SEARCH ========== -->
<section class="search-section">
  <div class="container">
    <div class="search-wrap">
      <input type="text" id="heroSearch" placeholder="Search for dog food, cat toys, accessories…" onkeydown="if(event.key==='Enter')doSearch()"/>
      <button onclick="doSearch()"><i class="fas fa-search"></i></button>
    </div>
    <div class="search-filter-tabs mt-2">
      <button class="filter-tab active" onclick="goShop('all')">All Products</button>
      <button class="filter-tab" onclick="goShop('dog')">🐕 Dogs</button>
      <button class="filter-tab" onclick="goShop('cat')">🐈 Cats</button>
      <button class="filter-tab" onclick="goShop('food')">🥩 Food</button>
      <button class="filter-tab" onclick="goShop('toys')">🎾 Toys</button>
      <button class="filter-tab" onclick="goShop('beds')">🛏 Beds</button>
      <button class="filter-tab" onclick="goShop('accessories')">👜 Accessories</button>
      <button class="filter-tab" onclick="goShop('grooming')">✂️ Grooming</button>
    </div>
  </div>
</section>

<!-- ========== CATEGORIES ========== -->
<section class="py-5">
  <div class="container">
    <div class="row align-items-center mb-4">
      <div class="col">
        <div class="section-label">Browse By</div>
        <h2 class="section-title">Shop by <span>Category</span></h2>
      </div>
      <div class="col-auto">
        <a class="btn-primary-custom" href="<?= $base ?>pages/shop.php">View All <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
    <div class="row g-3">
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="cat-card" onclick="window.location.href='<?= $base ?>pages/shop.php?category=dog'">
          <span class="cat-badge">Hot</span>
          <span class="cat-icon">🐕</span>
          <div class="cat-name">Dogs</div>
          <div class="cat-count">420+ items</div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="cat-card" onclick="window.location.href='<?= $base ?>pages/shop.php?category=cat'">
          <span class="cat-icon">🐈</span>
          <div class="cat-name">Cats</div>
          <div class="cat-count">380+ items</div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="cat-card" onclick="window.location.href='<?= $base ?>pages/shop.php?category=food'">
          <span class="cat-icon">🥩</span>
          <div class="cat-name">Food</div>
          <div class="cat-count">290+ items</div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="cat-card" onclick="window.location.href='<?= $base ?>pages/shop.php?category=toys'">
          <span class="cat-badge">New</span>
          <span class="cat-icon">🎾</span>
          <div class="cat-name">Toys</div>
          <div class="cat-count">180+ items</div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="cat-card" onclick="window.location.href='<?= $base ?>pages/shop.php?category=beds'">
          <span class="cat-icon">🛏</span>
          <div class="cat-name">Beds</div>
          <div class="cat-count">95+ items</div>
        </div>
      </div>
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="cat-card" onclick="window.location.href='<?= $base ?>pages/shop.php?category=grooming'">
          <span class="cat-icon">✂️</span>
          <div class="cat-name">Grooming</div>
          <div class="cat-count">140+ items</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== FEATURED PRODUCTS ========== -->
<section class="py-4 pb-5" style="background:#FFF8F0;">
  <div class="container">
    <div class="row align-items-center mb-4">
      <div class="col">
        <div class="section-label">Top Picks</div>
        <h2 class="section-title">Featured <span>Products</span></h2>
      </div>
      <div class="col-auto">
        <a class="btn-primary-custom" href="<?= $base ?>pages/shop.php">See All <i class="fas fa-arrow-right"></i></a>
      </div>
    </div>
    <div class="row g-3" id="featuredProducts"></div>
  </div>
</section>

<!-- ========== PROMO BANNERS ========== -->
<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-6">
        <div class="promo-banner">
          <div class="section-label" style="color:rgba(255,255,255,0.7);">Limited Offer</div>
          <h3>20% Off All Dog Food</h3>
          <p>Premium nutrition for your furry companion. Use code below at checkout.</p>
          <div class="promo-code">DOGFOOD20</div>
          <br/>
          <a class="btn-primary-custom" href="<?= $base ?>pages/shop.php?category=food" style="background:white;color:var(--sage);box-shadow:none;">Shop Dog Food <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
      <div class="col-md-6">
        <div class="promo-banner" style="background:linear-gradient(135deg,#8B5A2B,#C4751A);">
          <div class="section-label" style="color:rgba(255,255,255,0.7);">New Arrivals</div>
          <h3>Cat Luxury Collection</h3>
          <p>Indulge your feline with our new premium accessories range.</p>
          <div class="promo-code">CATLUV15</div>
          <br/>
          <a class="btn-primary-custom" href="<?= $base ?>pages/shop.php?category=cat" style="background:white;color:var(--amber-dark);box-shadow:none;">Shop Cats <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ========== WHY US ========== -->
<section class="py-5" style="background:#FFF8F0;">
  <div class="container">
    <div class="text-center mb-5">
      <div class="section-label">Why INFURNEST</div>
      <h2 class="section-title">Trusted by <span>18,000+</span> Pet Parents</h2>
    </div>
    <div class="row g-4 text-center">
      <div class="col-6 col-md-3">
        <div style="font-size:3rem;margin-bottom:12px;">🚚</div>
        <h6 style="font-weight:700;color:var(--brown);">Free Delivery</h6>
        <p style="font-size:0.82rem;color:var(--text-light);">On orders over ₱999. Same-day metro delivery available.</p>
      </div>
      <div class="col-6 col-md-3">
        <div style="font-size:3rem;margin-bottom:12px;">✅</div>
        <h6 style="font-weight:700;color:var(--brown);">Vet-Approved</h6>
        <p style="font-size:0.82rem;color:var(--text-light);">All products reviewed by licensed veterinarians.</p>
      </div>
      <div class="col-6 col-md-3">
        <div style="font-size:3rem;margin-bottom:12px;">🔄</div>
        <h6 style="font-weight:700;color:var(--brown);">Easy Returns</h6>
        <p style="font-size:0.82rem;color:var(--text-light);">30-day hassle-free returns. No questions asked.</p>
      </div>
      <div class="col-6 col-md-3">
        <div style="font-size:3rem;margin-bottom:12px;">💬</div>
        <h6 style="font-weight:700;color:var(--brown);">24/7 Support</h6>
        <p style="font-size:0.82rem;color:var(--text-light);">Our pet care team is always here to help you.</p>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>

<script>
function doSearch() {
  const val = document.getElementById('heroSearch').value.trim();
  if (val) window.location.href = '<?= $base ?>pages/shop.php?search=' + encodeURIComponent(val);
}
function goShop(category) {
  window.location.href = '<?= $base ?>pages/shop.php?category=' + category;
}
async function renderFeatured() {
  const container = document.getElementById('featuredProducts');
  try {
    const res  = await fetch('/INFURNEST/backend/api/products_api.php?action=featured&limit=8');
    const data = await res.json();
    if (data.success && data.products.length) {
      data.products.forEach(p => renderProductCard(p, container));
    } else {
      // fallback to local array
      window.products.filter(p => p.badge === 'bestseller' || p.rating === 5)
        .slice(0, 8).forEach(p => renderProductCard(p, container));
    }
  } catch(e) {
    // fallback to local array
    window.products.filter(p => p.badge === 'bestseller' || p.rating === 5)
      .slice(0, 8).forEach(p => renderProductCard(p, container));
  }
}
document.addEventListener('DOMContentLoaded', renderFeatured);
</script>
