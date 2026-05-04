<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$pageTitle  = 'Checkout';
$activePage = 'checkout';
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

<div class="page-header">
  <div class="container">
    <div class="breadcrumb-custom">
      <a href="/INFURNEST/index.php">Home</a><span class="sep">/</span>
      <a href="/INFURNEST/pages/cart.php">Cart</a><span class="sep">/</span>
      <span class="current">Checkout</span>
    </div>
    <h2>📋 Checkout</h2>
  </div>
</div>

<div class="container py-4">
  <div class="row g-4">
    <div class="col-lg-7">
      <div class="checkout-section">
        <h5>📍 Shipping Information</h5>
        <div class="row g-3">
          <div class="col-sm-6"><div class="form-group-custom"><label>First Name</label><input type="text" id="firstName" placeholder="Juan"/></div></div>
          <div class="col-sm-6"><div class="form-group-custom"><label>Last Name</label><input type="text" id="lastName" placeholder="dela Cruz"/></div></div>
          <div class="col-12"><div class="form-group-custom"><label>Email Address</label><input type="email" id="email" placeholder="juan@example.com"/></div></div>
          <div class="col-12"><div class="form-group-custom"><label>Phone Number</label><input type="tel" id="phone" placeholder="+63 9XX XXX XXXX"/></div></div>
          <div class="col-12"><div class="form-group-custom"><label>Complete Address</label><input type="text" id="address" placeholder="House/Unit No., Street, Barangay"/></div></div>
          <div class="col-sm-6"><div class="form-group-custom"><label>City / Municipality</label><input type="text" id="city" placeholder="Quezon City"/></div></div>
          <div class="col-sm-6"><div class="form-group-custom"><label>Province</label>
            <select id="province">
              <option>Metro Manila</option><option>Cebu</option><option>Davao</option>
              <option>Laguna</option><option>Cavite</option><option>Bulacan</option><option>Other</option>
            </select>
          </div></div>
          <div class="col-sm-6"><div class="form-group-custom"><label>ZIP Code</label><input type="text" id="zip" placeholder="1100"/></div></div>
          <div class="col-sm-6"><div class="form-group-custom"><label>Delivery Type</label>
            <select id="delivery">
              <option>Standard (3-5 days)</option>
              <option>Express (1-2 days)</option>
              <option>Same Day Metro</option>
            </select>
          </div></div>
        </div>
      </div>
      <div class="checkout-section">
        <h5>💳 Payment Method</h5>
        <div class="payment-option selected" onclick="selectPayment(this)">
          <div class="payment-icon">💳</div>
          <div><div style="font-weight:700;font-size:0.88rem;color:var(--brown);">Credit / Debit Card</div><div style="font-size:0.78rem;color:var(--text-light);">Visa, Mastercard, JCB</div></div>
        </div>
        <div class="payment-option" onclick="selectPayment(this)">
          <div class="payment-icon">📱</div>
          <div><div style="font-weight:700;font-size:0.88rem;color:var(--brown);">GCash / Maya</div><div style="font-size:0.78rem;color:var(--text-light);">Mobile wallet payment</div></div>
        </div>
        <div class="payment-option" onclick="selectPayment(this)">
          <div class="payment-icon">🏦</div>
          <div><div style="font-weight:700;font-size:0.88rem;color:var(--brown);">Bank Transfer</div><div style="font-size:0.78rem;color:var(--text-light);">BPI, BDO, UnionBank</div></div>
        </div>
        <div class="payment-option" onclick="selectPayment(this)">
          <div class="payment-icon">💵</div>
          <div><div style="font-weight:700;font-size:0.88rem;color:var(--brown);">Cash on Delivery</div><div style="font-size:0.78rem;color:var(--text-light);">Pay when you receive</div></div>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="order-summary">
        <h5 style="font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--brown);margin-bottom:18px;padding-bottom:12px;border-bottom:1px solid var(--blush);">🧾 Order Summary</h5>
        <div id="checkoutItems"></div>
        <div class="order-row"><span>Subtotal</span><span id="coSubtotal">₱0.00</span></div>
        <div class="order-row"><span>Shipping</span><span style="color:var(--sage);font-weight:600;">FREE</span></div>
        <div class="order-row"><span>Discount</span><span style="color:#E84A2A;">−₱0.00</span></div>
        <div id="promoRow" style="display:none;" class="order-row"><span>Promo Code</span><span style="color:var(--sage);font-weight:600;" id="promoDiscount">−₱0.00</span></div>
        <div class="order-row" style="border-top:2px solid var(--blush);padding-top:14px;"><strong>Total</strong><strong id="coTotal" style="font-size:1.2rem;color:var(--amber-dark);">₱0.00</strong></div>
        <div class="d-flex gap-2 mt-3">
          <input type="text" id="promoCode" placeholder="Promo code" style="flex:1;border:1.5px solid var(--blush);border-radius:9px;padding:9px 12px;font-size:0.85rem;font-family:inherit;outline:none;"/>
          <button onclick="applyPromo()" style="background:var(--sage);border:none;color:white;border-radius:9px;padding:9px 16px;font-weight:600;font-size:0.82rem;cursor:pointer;white-space:nowrap;">Apply</button>
        </div>
        <button class="checkout-btn" onclick="placeOrder()" style="display:block;width:100%;">Place Order 🐾 →</button>
        <p style="font-size:0.75rem;color:var(--text-light);text-align:center;margin-top:10px;">🔒 Your payment info is secure &amp; encrypted</p>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script src="<?= $base ?>assets/script.js"></script>

<script>
function renderCheckout() {
  const cart = getCart();
  const itemsEl = document.getElementById('checkoutItems');
  const total = getCartTotal();

  if (cart.length === 0) {
    itemsEl.innerHTML = `
      <div style="text-align:center;padding:20px;color:var(--text-light);">
        No items in cart. <a href="/INFURNEST/pages/shop.php">Shop now</a>
      </div>`;
  } else {
    itemsEl.innerHTML = cart.map(item => `
      <div style="display:flex;align-items:center;gap:10px;padding:10px 0;border-bottom:1px solid var(--blush);">
        <div style="font-size:2rem;width:48px;height:48px;display:flex;align-items:center;justify-content:center;border-radius:8px;overflow:hidden;background:var(--cream);">
          ${item.imageUrl
            ? `<img src="${item.imageUrl}" alt="${item.name}" style="width:100%;height:100%;object-fit:cover;"/>`
            : item.emoji
          }
        </div>
        <div style="flex:1;">
          <div style="font-size:0.85rem;font-weight:600;color:var(--brown);">${item.name}</div>
          <div style="font-size:0.75rem;color:var(--text-light);">x${item.qty}</div>
        </div>
        <div style="font-weight:700;color:var(--amber-dark);font-size:0.9rem;">
          ₱${(item.price * item.qty).toLocaleString()}
        </div>
      </div>`).join('');
  }

  document.getElementById('coSubtotal').textContent = '₱' + total.toLocaleString();
  document.getElementById('coTotal').textContent    = '₱' + (total >= 999 ? total : total + 99).toLocaleString();
}

function selectPayment(el) {
  document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
  el.classList.add('selected');
}

function applyPromo() {
  const code = document.getElementById('promoCode').value.trim().toUpperCase();
  const validCodes = { 'DOGFOOD20': 0.20, 'CATLUV15': 0.15, 'INFURNEST10': 0.10, 'PETLOVE5': 0.05 };
  if (validCodes[code]) {
    const disc = Math.round(getCartTotal() * validCodes[code]);
    document.getElementById('promoRow').style.display        = 'flex';
    document.getElementById('promoDiscount').textContent     = '−₱' + disc.toLocaleString();
    document.getElementById('coTotal').textContent           = '₱' + (getCartTotal() - disc).toLocaleString();
    showToast(`🎉 Promo code ${code} applied! You saved ₱${disc.toLocaleString()}`, 'success');
  } else {
    showToast('❌ Invalid promo code. Try: DOGFOOD20 or CATLUV15', 'error');
  }
}
async function placeOrder() {
  const cart = getCart();
  if (cart.length === 0) { 
    showToast('Your cart is empty!', 'error'); 
    return; 
  }

  const formData = new FormData();
  formData.append('action', 'create');
  formData.append('cart_data', JSON.stringify(cart));
  formData.append('shipping_name', `${document.getElementById('firstName').value} ${document.getElementById('lastName').value}`.trim());
  formData.append('shipping_phone', document.getElementById('phone').value);
  formData.append('shipping_address', document.getElementById('address').value);
  formData.append('shipping_city', `${document.getElementById('city').value}, ${document.getElementById('province').value}`);
  formData.append('shipping_zip', document.getElementById('zip').value);
  formData.append('payment_method', Array.from(document.querySelectorAll('.payment-option.selected'))
    .map(el => el.textContent.trim())[0]?.split('\n')[0].trim().toLowerCase().replace(' / ', '-') || 'cod');
  formData.append('notes', '');

  const checkoutBtn = document.querySelector('.checkout-btn');
  const originalText = checkoutBtn.innerHTML;
  checkoutBtn.innerHTML = 'Processing... <i class="fas fa-spinner fa-spin"></i>';
  checkoutBtn.disabled = true;

  try {
    const response = await fetch('/INFURNEST/backend/api/orders_api.php', {
      method: 'POST',
      body: formData
    });

    // ✅ Read raw text first, then try to parse
    const rawText = await response.text();

    let data;
    try {
      data = JSON.parse(rawText);
    } catch (parseErr) {
      // The backend returned HTML (PHP error/warning) — log it for debugging
      console.error('Backend returned non-JSON:\n', rawText);
      showToast('Server error. Check console for details.', 'error');
      return;
    }

    if (data.success) {
      showToast('Order placed successfully! 🎉', 'success');
      saveCart([]);
      updateCartCountUI();
      window.location.href = `/INFURNEST/pages/order-success.php?order=${encodeURIComponent(data.order.order_number)}`;
    } else {
      showToast(data.message || 'Order failed. Please try again.', 'error');
      if (data.require_login) {
        window.location.href = '/INFURNEST/pages/login.php?redirect=checkout';
      }
    }
  } catch (error) {
    console.error('Network error:', error);
    showToast('Connection error. Please check your internet.', 'error');
  } finally {
    checkoutBtn.innerHTML = originalText;
    checkoutBtn.disabled = false;
  }
}

document.addEventListener('DOMContentLoaded', renderCheckout);
</script>
</body>
</html>