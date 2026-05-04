<?php
// session_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle  = 'Cart';
$activePage = 'cart';
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
      <a href="/INFURNEST/index.php">Home</a><span class="sep">/</span>
      <span class="current">Cart</span>
    </div>
    <h2>🛒 Your Cart</h2>
  </div>
</div>

<div class="container py-4">
  <div id="cartContent"></div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script>
function renderCart() {
  const container = document.getElementById('cartContent');
  const cart = getCart();

  if (cart.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        <div class="empty-icon">🛒</div>
        <h4 style="color:var(--brown);">Your cart is empty</h4>
        <p style="color:var(--text-light);margin-bottom:24px;">Add some products to get started!</p>
        <a class="btn-primary-custom" href="/INFURNEST/pages/shop.php" style="display:inline-flex;">
          <i class="fas fa-store"></i> Browse Products
        </a>
      </div>`;
    return;
  }

  const total = getCartTotal();
  container.innerHTML = `
    <div class="row g-4">
      <div class="col-lg-8">
        <div class="cart-table">
          <table class="table mb-0">
            <thead><tr>
              <th>Product</th>
              <th class="d-none d-sm-table-cell">Price</th>
              <th>Qty</th>
              <th>Total</th>
              <th></th>
            </tr></thead>
            <tbody>
              ${cart.map(item => `
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-3">
                    <div class="cart-item-img">
                      ${item.imageUrl
                        ? `<img src="${item.imageUrl}" alt="${item.name}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;"/>`
                        : item.emoji
                      }
                    </div>
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
                  <div class="quantity-box" style="transform:scale(0.85);transform-origin:left;">
                    <button onclick="changeItemQty(${item.id}, ${item.qty-1})">−</button>
                    <input type="number" value="${item.qty}" min="1" max="99" onchange="changeItemQty(${item.id},this.value)"/>
                    <button onclick="changeItemQty(${item.id}, ${item.qty+1})">+</button>
                  </div>
                </td>
                <td style="font-weight:700;color:var(--amber-dark);">
                  ₱${(item.price*item.qty).toLocaleString()}
                </td>
                <td>
                  <button class="cart-remove" onclick="removeItem(${item.id})">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </td>
              </tr>`).join('')}
            </tbody>
          </table>
        </div>
        <div class="d-flex justify-content-between mt-3">
          <a href="/INFURNEST/pages/shop.php" style="background:var(--cream);border:1.5px solid var(--blush);border-radius:10px;padding:10px 18px;font-size:0.85rem;font-weight:600;color:var(--brown);cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
            <i class="fas fa-arrow-left"></i> Continue Shopping
          </a>
          <button onclick="clearCartAll()" style="background:var(--cream);border:1.5px solid var(--blush);border-radius:10px;padding:10px 18px;font-size:0.85rem;font-weight:600;color:#E84A2A;cursor:pointer;">
            Clear Cart
          </button>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="order-summary">
          <h5 style="font-family:'Playfair Display',serif;color:var(--brown);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--blush);">
            Order Summary
          </h5>
          <div class="order-row">
            <span>${cart.length} item(s)</span>
            <span>₱${total.toLocaleString()}</span>
          </div>
          <div class="order-row">
            <span>Shipping</span>
            <span style="color:var(--sage);font-weight:600;">${total >= 999 ? 'FREE' : '₱99'}</span>
          </div>
          <div class="order-row">
            <span><strong>Total</strong></span>
            <span style="font-size:1.2rem;color:var(--amber-dark);font-weight:800;">
              ₱${(total + (total >= 999 ? 0 : 99)).toLocaleString()}
            </span>
          </div>
          <a class="checkout-btn" href="/INFURNEST/pages/checkout.php">
            Proceed to Checkout <i class="fas fa-arrow-right"></i>
          </a>
          <div style="text-align:center;margin-top:12px;">
            <span style="font-size:0.75rem;color:var(--text-light);">
              🔒 Secure &nbsp;·&nbsp; ✅ Vet Approved &nbsp;·&nbsp; 🚚 Fast Delivery
            </span>
          </div>
        </div>
      </div>
    </div>`;
}

function changeItemQty(id, val) {
  updateCartQty(id, val);
  renderCart();
}

function removeItem(id) {
  removeFromCart(id);
  renderCart();
}

function clearCartAll() {
  saveCart([]);
  updateCartCountUI();
  showToast('🗑️ Cart cleared', 'info');
  renderCart();
}

document.addEventListener('DOMContentLoaded', renderCart);
</script>
</body>
</html>