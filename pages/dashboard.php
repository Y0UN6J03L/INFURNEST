<?php
/**
 * User Dashboard for INFURNEST
 * Shows profile, orders, and account management
 * Routes to admin dashboard if user role is admin
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if logged in
if (!isset($_SESSION['user_id'])) {
header('Location: ../account/login.php');
    exit;
}

// Role-based routing: if user is admin, redirect to admin dashboard
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: admin.php');
    exit;
}

$pageTitle = 'Dashboard';
$activePage = 'dashboard';

// User session
$userName = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'User';
$isLoggedIn = isset($_SESSION['user_id']);

$basePath = '../';
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
<link rel="stylesheet" href="<?= $basePath ?>assets/styles.css"/>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $basePath ?>assets/script.js"></script>

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

<!-- Main Content -->
<main class="page-content" style="padding-top: 80px; min-height: 100vh; background: var(--cream);">
    <div class="container py-5">
        
        <!-- Welcome Header -->
        <div class="welcome-header mb-5" style="animation: fadeInDown 0.5s ease-out;">
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--brown); margin-bottom: 8px;">
                Welcome back, <span id="user-name" style="color: var(--amber);"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>!
            </h1>
            <p style="color: var(--text-light);">Manage your account and view your orders</p>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-sm-6 col-lg-2">
                <div class="stat-card-custom" style="background: white; border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(232,146,42,0.1); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-shopping-bag" style="font-size: 1.5rem; color: var(--amber);"></i>
                        </div>
                        <div>
                            <h3 id="order-count" style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--brown); margin: 0;">-</h3>
                            <p style="color: var(--text-light); font-size: 0.8rem; margin: 0;">Total Orders</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="stat-card-custom" style="background: white; border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(122,158,126,0.15); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-heart" style="font-size: 1.5rem; color: var(--sage);"></i>
                        </div>
                        <div>
                            <h3 id="wishlist-count" style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--brown); margin: 0;">-</h3>
                            <p style="color: var(--text-light); font-size: 0.8rem; margin: 0;">Wishlist</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="stat-card-custom" style="background: white; border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(59,130,246,0.1); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-clock" style="font-size: 1.5rem; color: #3B82F6;"></i>
                        </div>
                        <div>
                            <h3 id="pending-count" style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--brown); margin: 0;">-</h3>
                            <p style="color: var(--text-light); font-size: 0.8rem; margin: 0;">Pending</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="stat-card-custom" style="background: white; border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(16,185,129,0.1); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-calendar-check" style="font-size: 1.5rem; color: #10B981;"></i>
                        </div>
                        <div>
                            <h3 id="delivered-count" style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: var(--brown); margin: 0;">-</h3>
                            <p style="color: var(--text-light); font-size: 0.8rem; margin: 0;">Delivered</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="stat-card-custom" style="background: linear-gradient(135deg, var(--amber), #f59e0b); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); color: white;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-wallet" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                        <div>
                            <h3 id="total-spent" style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: white; margin: 0;">₱0</h3>
                            <p style="font-size: 0.8rem; margin: 0; opacity: 0.9;">Total Spent</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-2">
                <div class="stat-card-custom" style="background: linear-gradient(135deg, #3B82F6, #1D4ED8); border-radius: var(--radius); padding: 24px; box-shadow: var(--shadow); color: white;">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-chart-line" style="font-size: 1.5rem; color: white;"></i>
                        </div>
                        <div>
                            <h3 id="avg-order" style="font-family: 'Playfair Display', serif; font-size: 1.8rem; font-weight: 700; color: white; margin: 0;">₱0</h3>
                            <p style="font-size: 0.8rem; margin: 0; opacity: 0.9;">Avg Order</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="row g-4">
            <!-- Recent Orders -->
            <div class="col-lg-8">
                <div class="section-card" style="background: white; border-radius: var(--radius); padding: 30px; box-shadow: var(--shadow);">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 style="font-family: 'Playfair Display', serif; color: var(--brown); margin: 0;">Recent Orders</h3>
                        <a href="orders.php" style="color: var(--amber); text-decoration: none; font-size: 0.9rem;">View All Orders</a>
                    </div>
                    <div id="orders-loading" class="text-center py-4">
                        <i class="fas fa-spinner fa-spin" style="font-size: 1.5rem; color: var(--amber);"></i>
                        <p style="color: var(--text-light); margin-top: 10px;">Loading orders...</p>
                    </div>
                    <div id="orders-empty" class="text-center py-4" style="display: none;">
                        <i class="fas fa-shopping-bag" style="font-size: 3rem; color: var(--text-light); opacity: 0.5;"></i>
                        <p style="color: var(--text-light); margin-top: 15px;">No orders yet</p>
                        <a href="shop.php" class="btn btn-primary-custom" style="display: inline-block; margin-top: 10px;">Start Shopping</a>
                    </div>
                    <div class="table-responsive" id="orders-table" style="display: none;">
                        <table class="table mb-0">
                            <thead>
                                <tr style="border-bottom: 2px solid var(--blush);">
                                    <th style="color: var(--brown); font-weight: 600; padding: 12px 8px;">Order #</th>
                                    <th style="color: var(--brown); font-weight: 600; padding: 12px 8px;">Date</th>
                                    <th style="color: var(--brown); font-weight: 600; padding: 12px 8px;">Total</th>
                                    <th style="color: var(--brown); font-weight: 600; padding: 12px 8px;">Status</th>
                                    <th style="color: var(--brown); font-weight: 600; padding: 12px 8px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="orders-tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions / Profile Summary -->
            <div class="col-lg-4">
                <!-- Profile Card -->
                <div class="section-card" style="background: white; border-radius: var(--radius); padding: 30px; box-shadow: var(--shadow); margin-bottom: 24px;">
                    <h3 style="font-family: 'Playfair Display', serif; color: var(--brown); margin-bottom: 20px;">Account</h3>
                    
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--cream); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-user" style="font-size: 1.5rem; color: var(--amber);"></i>
                        </div>
                        <div>
                            <h5 style="color: var(--brown); margin: 0; font-weight: 600;" id="profile-name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></h5>
                            <p style="color: var(--text-light); margin: 0; font-size: 0.85rem;" id="profile-email"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></p>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="orders.php" class="btn btn-primary-custom d-flex align-items-center justify-content-center">
                            <i class="fas fa-box"></i> My Orders
                        </a>
                        <a href="wishlist.php" class="btn btn-outline-custom d-flex align-items-center justify-content-center">
                            <i class="fas fa-heart"></i> My Wishlist
                        </a>
                        <a href="logout.php" class="btn btn-outline-custom d-flex align-items-center justify-content-center" style="color: #E84A2A; border-color: #E84A2A;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>

                <!-- Need Help -->
                <div class="section-card" style="background: linear-gradient(135deg, var(--brown) 0%, var(--brown-light) 100%); border-radius: var(--radius); padding: 30px; box-shadow: var(--shadow);">
                    <h4 style="color: white; margin-bottom: 12px;"><i class="fas fa-headset" style="color: var(--amber); margin-right: 8px;"></i>Need Help?</h4>
                    <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-bottom: 16px;">Our customer support team is here to assist you with any questions.</p>
                    <a href="contact.php" class="btn" style="background: var(--amber); color: white; width: 100%; display: block; text-align: center; padding: 12px; border-radius: var(--radius); text-decoration: none; font-weight: 600;">
                        Contact Us
                    </a>
                </div>
            </div>
        </div>

    </div>
</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
});

async function loadDashboardData() {
    try {
        // Load profile
        const profileRes = await fetch('<?= $basePath ?>backend/api/user_api.php?action=profile');
        const profileData = await profileRes.json();
        
        if (profileData.success && profileData.user) {
            document.getElementById('user-name').textContent = profileData.user.first_name;
            document.getElementById('profile-name').textContent = profileData.user.name;
            document.getElementById('profile-email').textContent = profileData.user.email;
        }
        
        // Load wishlist count
        const wishlistRes = await fetch('<?= $basePath ?>backend/api/user_api.php?action=wishlist');
        const wishlistData = await wishlistRes.json();
        
        if (wishlistData.success) {
            document.getElementById('wishlist-count').textContent = wishlistData.count || 0;
        }
        
        // Load enhanced stats
        const statsRes = await fetch('<?= $basePath ?>backend/api/orders_api.php?action=dashboard_stats_user');
        const statsData = await statsRes.json();
        
        if (statsData.success && statsData.stats) {
            const stats = statsData.stats;
            document.getElementById('order-count').textContent = stats.total_orders;
            document.getElementById('pending-count').textContent = stats.pending_count;
            document.getElementById('delivered-count').textContent = stats.completed_count;
            
            const totalSpentEl = document.getElementById('total-spent');
            if (totalSpentEl) totalSpentEl.textContent = '₱' + stats.total_spent.toLocaleString();
            const avgEl = document.getElementById('avg-order');
            if (avgEl) avgEl.textContent = '₱' + stats.avg_order_value.toLocaleString();
        }
        
        // Load recent orders table
        await loadOrders();
        
    } catch (error) {
        console.error('Dashboard load error:', error);
    }
}

async function loadOrders() {
    const loadingEl = document.getElementById('orders-loading');
    const emptyEl = document.getElementById('orders-empty');
    const tableEl = document.getElementById('orders-table');
    const tbody = document.getElementById('orders-tbody');
    
    try {
        const response = await fetch('<?= $basePath ?>backend/api/orders_api.php?action=recent&limit=5');
        const data = await response.json();
        
        loadingEl.style.display = 'none';
        
        if (data.success && data.orders && data.orders.length > 0) {
            tableEl.style.display = 'block';
            
            let pendingCount = 0;
            let deliveredCount = 0;
            
            tbody.innerHTML = data.orders.map(order => {
                const status = order.status || 'pending';
                if (status === 'pending' || status === 'processing') pendingCount++;
                if (status === 'delivered') deliveredCount++;
                
                return `
                    <tr style="border-bottom: 1px solid var(--blush);">
                        <td style="padding: 14px 8px;">
                            <span style="font-weight: 600; color: var(--amber);">${order.order_number}</span>
                        </td>
                        <td style="padding: 14px 8px; color: var(--text-light);">
                            ${formatDate(order.created_at)}
                        </td>
                        <td style="padding: 14px 8px; font-weight: 600;">
                            ₱${parseFloat(order.total || 0).toLocaleString()}
                        </td>
                        <td style="padding: 14px 8px;">
                            <span class="status-badge ${status}">${capitalizeFirst(status)}</span>
                        </td>
                        <td style="padding: 14px 8px;">
                            <a href="orders.php?id=${order.id}" style="color: var(--amber); text-decoration: none; font-size: 0.85rem;">
                                View <i class="fas fa-arrow-right"></i>
                            </a>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Update stats from orders if stats API didn't populate them
            document.getElementById('pending-count').textContent = pendingCount;
            document.getElementById('delivered-count').textContent = deliveredCount;
            
            // Get total orders count
            const allOrdersRes = await fetch('<?= $basePath ?>backend/api/orders_api.php?action=list&limit=100');
            const allOrdersData = await allOrdersRes.json();
            if (allOrdersData.success && allOrdersData.pagination) {
                document.getElementById('order-count').textContent = allOrdersData.pagination.total;
            }
            
        } else {
            emptyEl.style.display = 'block';
            document.getElementById('order-count').textContent = '0';
            document.getElementById('pending-count').textContent = '0';
            document.getElementById('delivered-count').textContent = '0';
        }
        
    } catch (error) {
        console.error('Load orders error:', error);
        loadingEl.style.display = 'none';
        emptyEl.style.display = 'block';
    }
}

function formatDate(dateStr) {
    if (!dateStr) return 'N/A';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-PH', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
}

function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}
</script>

</body>
</html>