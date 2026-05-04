<?php
/**
 * Customer Order Tracking Page for INFURNEST
 */

if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle  = 'My Orders — INFURNEST Premium Pet Shop';
$activePage = 'dashboard';
$base       = '/INFURNEST/';
$basePath   = '../';

$orderNumber = isset($_GET['order_number']) ? trim($_GET['order_number']) : '';
$orderId     = null;

$userName  = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'User';
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="<?= $base ?>assets/styles.css"/>
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

<main class="page-content" style="padding-top: 80px; min-height: 100vh; background: var(--cream);">
  <div class="container py-5">

    <div class="welcome-header mb-5">
      <h1 style="font-family:'Playfair Display',serif;font-size:2.5rem;color:var(--brown);margin-bottom:8px;">
        <?= ($orderId || $orderNumber) ? 'Track Order' : 'My Orders' ?>
      </h1>
      <p style="color:var(--text-light);">
        <?= ($orderId || $orderNumber) ? 'Track your order status and delivery progress' : 'View your order history and track deliveries' ?>
      </p>
    </div>

    <!-- Quick Track Form -->
    <div class="section-card mb-5" style="background:white;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);">
      <h3 style="font-family:'Playfair Display',serif;color:var(--brown);margin-bottom:20px;">
        <i class="fas fa-search" style="color:var(--amber);margin-right:10px;"></i>Track Order by Number
      </h3>
      <div class="row g-3">
        <div class="col-md-8">
          <div class="input-group-custom">
            <input type="text" id="trackInput" placeholder="Enter order number (e.g., INF-20241201-ABC123)"
                   class="form-control-custom" style="font-size:1.1rem;">
            <button class="btn-primary-custom" onclick="trackOrder()">
              <i class="fas fa-search"></i> Track
            </button>
          </div>
        </div>
        <div class="col-md-4 d-flex align-items-center">
          <div style="color:var(--text-light);font-size:0.9rem;">
            <i class="fas fa-info-circle" style="color:var(--sage);"></i>
            No account needed for tracking
          </div>
        </div>
      </div>
    </div>

    <?php if ($isLoggedIn): ?>
    <!-- Orders List -->
    <div class="section-card mb-5" style="background:white;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 style="font-family:'Playfair Display',serif;color:var(--brown);margin:0;">Order History</h3>
        <div class="d-flex gap-2">
          <select id="statusFilter" class="form-select-custom" style="width:auto;">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
      </div>
      <div id="orders-loading" class="text-center py-4">
        <i class="fas fa-spinner fa-spin" style="font-size:1.5rem;color:var(--amber);"></i>
        <p style="color:var(--text-light);">Loading orders...</p>
      </div>
      <div id="orders-container"></div>
    </div>
    <?php endif; ?>

    <!-- Order Details View -->
    <div id="order-details-container" style="display:none;">
      <div class="section-card" style="background:white;border-radius:var(--radius);padding:30px;box-shadow:var(--shadow);">
        <div class="d-flex justify-content-between align-items-start mb-4">
          <div>
            <h2 style="font-family:'Playfair Display',serif;color:var(--brown);" id="order-title">Order Details</h2>
            <div id="order-number-display" style="font-size:1.2rem;color:var(--amber);font-weight:600;margin-top:5px;"></div>
          </div>
          <button class="btn btn-outline-custom" onclick="showOrdersList()" style="border-color:var(--blush);">
            ← Back to Orders
          </button>
        </div>

        <div class="order-timeline mb-5">
          <div id="status-timeline"></div>
        </div>

        <div class="row g-4">
          <div class="col-lg-8">
            <h4 style="color:var(--brown);margin-bottom:20px;">Order Items</h4>
            <div id="order-items-list" class="table-responsive"></div>
          </div>
          <div class="col-lg-4">
            <h4 style="color:var(--brown);margin-bottom:20px;">Summary</h4>
            <div id="order-summary" style="background:var(--cream);border-radius:var(--radius-sm);padding:20px;"></div>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

<?php include '../includes/footer.php'; ?>

<!-- Scripts — paths relative to /INFURNEST/ root -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base ?>assets/script.js"></script>
<script src="<?= $base ?>assets/app.js?v=<?= time() ?>"></script>
<script src="<?= $base ?>assets/products.js?v=<?= time() ?>"></script>

<script>
// ─── API base (always correct regardless of which /pages/ file calls it) ───
const API_BASE = '/INFURNEST/backend/api/orders_api.php';

let currentOrderData = null;

function toggleMobileMenu() {
  document.getElementById('mobileMenu').classList.toggle('open');
}

function showToast(msg, type = 'success') {
  const container = document.getElementById('toastContainer');
  const toast = document.createElement('div');
  toast.className = 'toast-custom';
  const color = type === 'success' ? 'var(--amber)' : type === 'error' ? '#E84A2A' : 'var(--sage)';
  toast.style.borderLeftColor = color;
  toast.innerHTML = `<i class="fas ${type==='success'?'fa-check-circle':type==='error'?'fa-times-circle':'fa-info-circle'}" style="color:${color};"></i><span>${msg}</span>`;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

document.addEventListener('DOMContentLoaded', function () {
  <?php if ($orderId || $orderNumber): ?>
    trackOrderByParam();
  <?php else: ?>
    <?php if ($isLoggedIn): ?>
      loadOrdersList();
      document.getElementById('statusFilter').addEventListener('change', loadOrdersList);
    <?php endif; ?>
  <?php endif; ?>
});

async function trackOrder() {
  const orderNumber = document.getElementById('trackInput').value.trim();
  if (!orderNumber) { showToast('Please enter order number', 'error'); return; }
  await loadOrderDetails(null, orderNumber);
}

async function trackOrderByParam() {
  <?php if ($orderId): ?>
    await loadOrderDetails(<?= $orderId ?>);
  <?php elseif ($orderNumber): ?>
    document.getElementById('trackInput').value = '<?= htmlspecialchars($orderNumber) ?>';
    await loadOrderDetails(null, '<?= htmlspecialchars($orderNumber) ?>');
  <?php endif; ?>
}

async function loadOrdersList() {
  const loadingEl  = document.getElementById('orders-loading');
  const container  = document.getElementById('orders-container');
  loadingEl.style.display = 'block';
  container.innerHTML = '';

  try {
    const status   = document.getElementById('statusFilter').value;
    const url      = `${API_BASE}?action=list${status ? '&status=' + encodeURIComponent(status) : ''}`;
    const response = await fetch(url);
    const rawText  = await response.text();

    let data;
    try { data = JSON.parse(rawText); }
    catch (e) { console.error('Orders API non-JSON:', rawText); showToast('Server error loading orders', 'error'); return; }

    loadingEl.style.display = 'none';

    if (data.success && data.orders.length > 0) {
      container.innerHTML = `
        <div class="table-responsive">
          <table class="table mb-0">
            <thead>
              <tr style="border-bottom:2px solid var(--blush);">
                <th style="color:var(--brown);">Order #</th>
                <th style="color:var(--brown);">Date</th>
                <th style="color:var(--brown);">Items</th>
                <th style="color:var(--brown);">Total</th>
                <th style="color:var(--brown);">Status</th>
                <th style="color:var(--brown);">Action</th>
              </tr>
            </thead>
            <tbody>
              ${data.orders.map(order => `
                <tr style="border-bottom:1px solid var(--blush);">
                  <td style="padding:16px 12px;font-weight:600;color:var(--amber);">${order.order_number}</td>
                  <td style="padding:16px 12px;color:var(--text-light);">${formatDate(order.created_at)}</td>
                  <td style="padding:16px 12px;">${order.item_count || 1} item${order.item_count > 1 ? 's' : ''}</td>
                  <td style="padding:16px 12px;font-weight:600;">₱${parseFloat(order.total).toLocaleString()}</td>
                  <td style="padding:16px 12px;"><span class="status-badge ${order.status}">${capitalizeFirst(order.status)}</span></td>
                  <td style="padding:16px 12px;">
                    <button class="btn btn-primary-custom btn-sm" onclick="loadOrderDetails(${order.id})">
                      <i class="fas fa-eye"></i> View
                    </button>
                  </td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        </div>
        ${data.pagination && data.pagination.total > 10 ? `
          <div class="d-flex justify-content-between align-items-center mt-4">
            <div style="color:var(--text-light);">Showing ${data.orders.length} of ${data.pagination.total} orders</div>
            <button class="btn btn-outline-custom btn-sm" onclick="loadMoreOrders()">Load More</button>
          </div>` : ''}
      `;
    } else {
      container.innerHTML = `
        <div class="text-center py-5">
          <i class="fas fa-shopping-bag" style="font-size:4rem;color:var(--text-light);opacity:0.5;"></i>
          <h5 style="color:var(--brown);margin-top:16px;">No orders found</h5>
          <p style="color:var(--text-light);">Start shopping to place your first order</p>
          <a href="/INFURNEST/pages/shop.php" class="btn btn-primary-custom mt-3">Start Shopping</a>
        </div>`;
    }
  } catch (error) {
    console.error('Load orders error:', error);
    loadingEl.style.display = 'none';
    container.innerHTML = '<div class="text-center py-5"><p class="text-danger">Error loading orders</p></div>';
  }
}

async function loadOrderDetails(id, orderNumber = null) {
  try {
    const url      = id
      ? `${API_BASE}?action=single&id=${id}`
      : `${API_BASE}?action=track&order_number=${encodeURIComponent(orderNumber)}`;
    const response = await fetch(url);
    const rawText  = await response.text();

    let data;
    try { data = JSON.parse(rawText); }
    catch (e) { console.error('Order detail API non-JSON:', rawText); showToast('Server error loading order', 'error'); return; }

    if (data.success && data.order) {
      currentOrderData = data.order;
      showOrderDetails(data.order);
    } else {
      showToast(data.message || 'Order not found', 'error');
    }
  } catch (error) {
    console.error('Load order error:', error);
    showToast('Error loading order details', 'error');
  }
}

function showOrderDetails(order) {
  const ordersSection = document.getElementById('orders-container');
  if (ordersSection) ordersSection.style.display = 'none';
  document.getElementById('order-details-container').style.display = 'block';
  document.getElementById('order-number-display').textContent = order.order_number;

  // Timeline
  const statusOrder = ['pending', 'processing', 'shipped', 'delivered'];
  document.getElementById('status-timeline').innerHTML = statusOrder.map(status => {
    const isActive    = order.status === status;
    const isCompleted = statusOrder.indexOf(order.status) > statusOrder.indexOf(status);
    return `
      <div class="timeline-item ${isActive ? 'active' : ''} ${isCompleted ? 'completed' : ''}">
        <div class="timeline-icon"><i class="fas ${getStatusIcon(status)}"></i></div>
        <div class="timeline-content">
          <span class="status-label">${capitalizeFirst(status)}</span>
          <small>${getStatusDescription(status)}</small>
        </div>
      </div>`;
  }).join('');

  // Items
  document.getElementById('order-items-list').innerHTML = `
    <div style="max-height:400px;overflow-y:auto;">
      ${order.items.map(item => `
        <div class="order-item d-flex gap-3 p-3 border-bottom">
          <div style="width:60px;height:60px;background:var(--cream);border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-paw" style="font-size:1.5rem;color:var(--blush);"></i>
          </div>
          <div class="flex-grow-1">
            <h6 style="margin:0;color:var(--brown);">${item.product_name}</h6>
            <p style="color:var(--text-light);margin:4px 0 0 0;font-size:0.9rem;">
              ${item.quantity} × ₱${parseFloat(item.price).toLocaleString()}
            </p>
          </div>
          <div style="text-align:right;"><strong>₱${parseFloat(item.total).toLocaleString()}</strong></div>
        </div>`).join('')}
    </div>`;

  // Summary
  document.getElementById('order-summary').innerHTML = `
    <div class="mb-3">
      <div class="d-flex justify-content-between"><span>Subtotal</span><span>₱${parseFloat(order.subtotal).toLocaleString()}</span></div>
      <div class="d-flex justify-content-between"><span>Shipping</span><span>₱${parseFloat(order.shipping_fee).toLocaleString()}</span></div>
    </div>
    <div class="border-top pt-3" style="border-color:var(--blush) !important;">
      <div class="d-flex justify-content-between" style="font-size:1.2rem;font-weight:700;color:var(--brown);">
        <span>Total</span><span>₱${parseFloat(order.total).toLocaleString()}</span>
      </div>
    </div>
    <div class="mt-3 pt-3 border-top" style="border-color:var(--blush) !important;font-size:0.85rem;">
      <div class="d-flex justify-content-between mb-2">
        <span>Payment:</span><span>${capitalizeFirst(order.payment_method || 'COD')}</span>
      </div>
      <div class="d-flex justify-content-between">
        <span>Ship To:</span><span>${order.shipping_name ?? ''}</span>
      </div>
    </div>`;
}

function showOrdersList() {
  document.getElementById('order-details-container').style.display = 'none';
  const ordersSection = document.getElementById('orders-container');
  if (ordersSection) { ordersSection.style.display = 'block'; loadOrdersList(); }
}

function getStatusIcon(status) {
  return { pending:'fa-clock', processing:'fa-cogs', shipped:'fa-truck', delivered:'fa-check-circle', cancelled:'fa-times-circle' }[status] || 'fa-question-circle';
}
function getStatusDescription(status) {
  return { pending:'Order received', processing:'Preparing your order', shipped:'On the way', delivered:'Order delivered', cancelled:'Order cancelled' }[status] || '';
}
function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('en-PH', { year:'numeric', month:'short', day:'numeric', hour:'2-digit', minute:'2-digit' });
}
function capitalizeFirst(str) {
  return str ? str.charAt(0).toUpperCase() + str.slice(1) : '';
}
</script>

<style>
.order-timeline { display:flex; flex-direction:column; gap:20px; margin-bottom:30px; }
.timeline-item { display:flex; align-items:center; opacity:0.5; transition:all 0.3s ease; }
.timeline-item.active, .timeline-item:hover { opacity:1; }
.timeline-item.completed { opacity:0.7; }
.timeline-icon { width:50px; height:50px; background:var(--cream); border:3px solid var(--blush); border-radius:50%; display:flex; align-items:center; justify-content:center; margin-right:20px; position:relative; z-index:2; }
.timeline-item.active .timeline-icon { background:var(--amber); border-color:var(--amber); color:white; }
.timeline-item.completed .timeline-icon { background:var(--sage); border-color:var(--sage); color:white; }
.timeline-content { flex-grow:1; }
.status-label { display:block; font-weight:600; color:var(--brown); font-size:1rem; }
.status-badge { padding:6px 12px; border-radius:20px; font-size:0.8rem; font-weight:600; text-transform:capitalize; }
.status-badge.pending    { background:rgba(232,146,42,0.1);  color:var(--amber);      border:1px solid var(--amber); }
.status-badge.processing { background:rgba(122,158,126,0.1); color:var(--sage);       border:1px solid var(--sage); }
.status-badge.shipped    { background:rgba(59,130,246,0.1);  color:#3B82F6;           border:1px solid #3B82F6; }
.status-badge.delivered  { background:rgba(122,189,126,0.2); color:var(--sage-dark);  border:1px solid var(--sage-dark); }
.status-badge.cancelled  { background:rgba(232,74,42,0.1);   color:#E84A2A;           border:1px solid #E84A2A; }
.input-group-custom { display:flex; gap:10px; }
.form-control-custom { border:2px solid var(--blush); border-radius:var(--radius); padding:14px 18px; font-size:1rem; transition:all 0.2s; flex:1; }
.form-control-custom:focus { border-color:var(--amber); box-shadow:0 0 0 3px rgba(232,146,42,0.1); outline:none; }
@media (max-width:768px) {
  .timeline-item { flex-direction:column; text-align:center; gap:12px; }
  .timeline-icon { margin-right:0; margin-bottom:10px; }
}
</style>

</body>
</html>