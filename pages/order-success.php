<?php
$pageTitle = 'Order Placed!';
$activePage = '';
$orderNum = isset($_GET['order']) ? htmlspecialchars($_GET['order']) : '#INF-00001';
?>

<?php
// Headernav code
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($base)) $base = '';

// User session
$activePage = $activePage ?? '';
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
<link rel="stylesheet" href="<?= $base ?>../assets/styles.css"/>
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
<script src="<?= $base ?>../assets/script.js"></script>

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
document.addEventListener('DOMContentLoaded', async () => {
  highlightCurrentNav();
  
  const orderNum = '<?= addslashes($orderNum) ?>';
  if (orderNum) {
    await loadOrderDetails(orderNum);
  }
});

async function loadOrderDetails(orderNumber) {
  const loadingEl = document.getElementById('order-loading');
  const contentEl = document.getElementById('order-success-content');
  
  try {
    const response = await fetch(`/INFURNEST/backend/api/orders_api.php?action=track&order_number=${encodeURIComponent(orderNumber)}`);
    const data = await response.json();
    
    if (data.success && data.order) {
      showOrderSuccess(data.order);
      loadingEl.style.display = 'none';
      contentEl.style.display = 'block';
    } else {
      // Fallback to static
      loadingEl.style.display = 'none';
      contentEl.style.display = 'block';
      document.getElementById('orderNumber').textContent = orderNumber;
    }
  } catch (error) {
    console.error('Load order error:', error);
    // Fallback
    loadingEl.style.display = 'none';
    contentEl.style.display = 'block';
    document.getElementById('orderNumber').textContent = orderNumber;
  }
}

function showOrderSuccess(order) {
  // Update order number
  document.getElementById('orderNumber').textContent = order.order_number;
  
  // Show items summary
  const itemsSummary = document.createElement('div');
  itemsSummary.innerHTML = `
    <div style="font-size:0.9rem;color:var(--text-light);margin-bottom:12px;">
      ${order.items.length} item${order.items.length > 1 ? 's' : ''} | Total: ₱${parseFloat(order.total).toLocaleString()}
    </div>
  `;
  document.querySelector('.success-icon').insertAdjacentElement('afterend', itemsSummary.firstElementChild);
  
  // Status indicator
  const statusEl = document.createElement('div');
  statusEl.style.cssText = 'background:rgba(122,158,126,0.1);color:var(--sage);padding:8px 16px;border-radius:25px;font-size:0.9rem;font-weight:600;margin:16px auto;display:inline-block;';
  statusEl.textContent = order.status.toUpperCase();
  document.getElementById('orderNumber').parentNode.parentNode.appendChild(statusEl);
  
  showToast(`Order #${order.order_number} confirmed!`, 'success');
}
</script>

<div class="container py-5">
  <div class="text-center" style="max-width:500px;margin:0 auto;">
    <div style="background:white;border-radius:24px;padding:50px 40px;box-shadow:var(--shadow);">
      <div class="success-icon">🎉</div>
      <h2 style="font-family:'Playfair Display',serif;font-size:2rem;color:var(--brown);margin:16px 0 10px;">Order Placed!</h2>
      <p style="color:var(--text-light);margin-bottom:6px;">Your furry friend's goodies are on their way 🐾</p>
      <p style="font-size:0.85rem;color:var(--text-light);margin-bottom:28px;">Order confirmation has been sent to your email.</p>
<div id="order-loading" class="text-center py-5">
        <i class="fas fa-spinner fa-spin" style="font-size:2rem;color:var(--amber);"></i>
        <p style="color:var(--text-light);">Loading order details...</p>
      </div>
      <div id="order-success-content" style="display:none;">
        <div style="background:var(--cream);border-radius:12px;padding:18px;margin-bottom:24px;">
          <div style="font-size:0.78rem;color:var(--text-light);margin-bottom:4px;">Order Number</div>
          <div style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--amber);" id="orderNumber"><?= htmlspecialchars($orderNum) ?></div>
        </div>
      </div>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a class="btn-primary-custom" href="../index.php"><i class="fas fa-home"></i> Back to Home</a>
        <a class="btn-outline-custom" href="shop.php" style="color:var(--brown);border-color:var(--blush);">Continue Shopping</a>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
</body>
</html>