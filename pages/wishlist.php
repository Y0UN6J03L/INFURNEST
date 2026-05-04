<?php
// pages/wishlist.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$pageTitle  = 'Wishlist';
    $activePage = 'shop';
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

<!-- Navbar (copied from cart.php) -->
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
// Common utils (from cart.php)
function toggleMobileMenu() {
  document.getElementById('mobileMenu').classList.toggle('open');
}

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
    'dashboard.php': 'dashboard',
    'wishlist.php': 'wishlist'  // Add for active nav
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

function showToast(msg, type='success') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = 'toast-custom';

  const color = type === 'success' ? 'var(--amber)' : type === 'error' ? '#E84A2A' : 'var(--sage)';
  toast.style.borderLeftColor = color;

  toast.innerHTML = `<i class="fas ${type==='success'?'fa-check-circle':type==='error'?'fa-times-circle':'fa-info-circle'}" style="color:${color}"></i><span>${msg}</span>`;

  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

// Reuse global cart functions from assets/script.js (avoid redeclare)
function addToWishlistCart(productId, qty=1) {
  if (typeof addToCart === 'function') {
    addToCart(productId, qty);
  } else {
    showToast('Cart function not loaded', 'error');
  }
}

// Wishlist API Base
const API_BASE = '<?= $base ?>backend/api/user_api.php';

// Load & render wishlist
async function renderWishlist() {
  const container = document.getElementById('wishlistContent');
  
  try {
    const res = await fetch(`${API_BASE}?action=wishlist`);
    const data = await res.json();
    
    if (!data.success) {
      showToast('Error loading wishlist', 'error');
      return;
    }
    
    const wishlist = data.wishlist || [];
    
    if (wishlist.length === 0) {
      container.innerHTML = `
        <div class="empty-state">
          <div class="empty-icon">💖</div>
          <h4 style="color:var(--brown);">Your wishlist is empty</h4>
          <p style="color:var(--text-light);margin-bottom:24px;">Save items you love for later!</p>
          <a class="btn-primary-custom" href="<?= $base ?>pages/shop.php">
            <i class="fas fa-store"></i> Browse Products
          </a>
        </div>`;
      return;
    }
    
    // Calculate total
    const total = wishlist.reduce((sum, item) => sum + item.price, 0);
    
    container.innerHTML = `
      <div class="row g-4">
        <div class="col-lg-8">
          <div class="cart-table">
            <table class="table mb-0">
              <thead><tr>
                <th>Product</th>
                <th class="d-none d-sm-table-cell">Price</th>
                <th>Action</th>
                <th></th>
              </tr></thead>
              <tbody>
                ${wishlist.map(item => `
                <tr>
                  <td>
                    <div class="d-flex align-items-center gap-3">
                      <div class="cart-item-img">${item.emoji}</div>
                      <div>
                        <div class="cart-item-name">${item.name}</div>
                        <span class="badge-pet ${item.pet==='dog'?'badge-dog':'badge-cat'}" style="font-size:0.68rem;padding:2px 7px;">
                          ${item.pet==='dog'?'🐕':'🐈'} ${item.pet}
                        </span>
                      </div>
                    </div>
                  </td>
                  <td class="d-none d-sm-table-cell" style="font-weight:600;color:var(--brown);">
                    ₱${item.price.toLocaleString()}
                  </td>
                  <td>
                    <button class="btn-primary-custom btn-sm" onclick="addToWishlistCart(${item.id})" style="padding:6px 12px;">
                      <i class="fas fa-shopping-bag"></i> Add to Cart
                    </button>
                  </td>
                  <td>
                    <button class="cart-remove" onclick="removeFromWishlist(${item.id})">
                      <i class="fas fa-trash-alt"></i>
                    </button>
                  </td>
                </tr>`).join('')}
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-between mt-3">
            <a href="<?= $base ?>pages/shop.php" style="background:var(--cream);border:1.5px solid var(--blush);border-radius:10px;padding:10px 18px;font-size:0.85rem;font-weight:600;color:var(--brown);cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
              <i class="fas fa-arrow-left"></i> Continue Shopping
            </a>
            <button onclick="clearWishlist()" style="background:var(--cream);border:1.5px solid #E84A2A;border-radius:10px;padding:10px 18px;font-size:0.85rem;font-weight:600;color:#E84A2A;cursor:pointer;">
              Clear Wishlist
            </button>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="order-summary">
            <h5 style="font-family:'Playfair Display',serif;color:var(--brown);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--blush);">
              Wishlist Summary
            </h5>
            <div class="order-row">
              <span>${wishlist.length} item(s)</span>
              <span>₱${total.toLocaleString()}</span>
            </div>
            <div class="order-row total-row">
              <span><strong>Wishlist Total</strong></span>
              <span style="font-size:1.2rem;color:var(--amber-dark);font-weight:800;">
                ₱${total.toLocaleString()}
              </span>
            </div>
            <a class="btn-primary-custom" href="<?= $base ?>pages/cart.php">
              View Cart <i class="fas fa-shopping-bag"></i>
            </a>
            <div style="text-align:center;margin-top:12px;">
              <span style="font-size:0.75rem;color:var(--text-light);">
                💝 Saved for later &nbsp;·&nbsp; 🛒 Move to cart anytime
              </span>
            </div>
          </div>
        </div>
      </div>`;
      
  } catch (err) {
    container.innerHTML = `<div class="empty-state"><div class="empty-icon">⚠️</div><h4>Error loading wishlist</h4><p>Try refreshing the page.</p></div>`;
    console.error('Wishlist load error:', err);
  }
}

// Remove single item
async function removeFromWishlist(productId) {
  try {
    const formData = new FormData();
    formData.append('action', 'remove_wishlist');
    formData.append('product_id', productId);
    
    const res = await fetch(API_BASE, {
      method: 'POST',
      body: formData
    });
    const data = await res.json();
    
    if (data.success) {
      showToast('Removed from wishlist', 'success');
      renderWishlist();  // Refresh
    } else {
      showToast(data.message || 'Remove failed', 'error');
    }
  } catch (err) {
    showToast('Error removing item', 'error');
    console.error(err);
  }
}

// Clear all
async function clearWishlist() {
  if (!confirm('Clear entire wishlist?')) return;
  
  const wishlist = await (await fetch(API_BASE + '?action=wishlist')).json();
  if (wishlist.wishlist?.length === 0) return;
  
  // Remove all one by one (or optimize with bulk if API supports)
  for (const item of wishlist.wishlist) {
    await removeFromWishlist(item.id);
  }
  renderWishlist();
}

// Init
document.addEventListener('DOMContentLoaded', () => {
  highlightCurrentNav();
  renderWishlist();
});
</script>

<div class="page-header">
  <div class="container">
    <div class="breadcrumb-custom">
      <a href="/INFURNEST/index.php">Home</a><span class="sep">/</span>
      <a href="/INFURNEST/pages/dashboard.php">Account</a><span class="sep">/</span>
      <span class="current">Wishlist</span>
    </div>
    <h2>💖 Your Wishlist</h2>
  </div>
</div>

<div class="container py-4">
  <div id="wishlistContent"></div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>

</body>
</html>

