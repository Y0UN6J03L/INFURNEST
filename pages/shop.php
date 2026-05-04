<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle  = 'Shop';
$activePage = 'shop';
$base       = '/INFURNEST/';          // absolute — never breaks regardless of depth

$userName   = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'User';
$isLoggedIn = isset($_SESSION['user_id']);

$category = $_GET['category'] ?? 'all';
$search   = $_GET['search']   ?? '';
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

<!-- ═══════════════════════════ NAVBAR ═══════════════════════════ -->
<nav class="navbar-custom" id="mainNav">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between">

      <!-- LOGO -->
      <a class="navbar-brand-logo" href="<?= $base ?>index.php">
        <div class="logo-icon"><i class="fas fa-paw"></i></div>
        IN<span class="accent">FUR</span>NEST
      </a>

      <!-- DESKTOP NAV -->
      <div class="nav-links d-none d-md-flex align-items-center gap-1">
        <?php
        $navLinks = [
          'home'     => ['label' => 'Home',     'href' => $base . 'index.php'],
          'shop'     => ['label' => 'Shop',     'href' => $base . 'pages/shop.php'],
          'about'    => ['label' => 'About',    'href' => $base . 'pages/about.php'],
          'contact'  => ['label' => 'Contact',  'href' => $base . 'pages/contact.php'],
          'services' => ['label' => 'Services', 'href' => $base . 'pages/services.php'],
          'faqs'     => ['label' => 'FAQs',     'href' => $base . 'pages/faqs.php'],
          'privacy'  => ['label' => 'Privacy',  'href' => $base . 'pages/privacy.php'],
          'terms'    => ['label' => 'Terms',    'href' => $base . 'pages/terms.php'],
        ];
        foreach ($navLinks as $key => $link):
          $isActive = $activePage === $key ? 'class="active-nav"' : '';
        ?>
          <a id="nav-<?= $key ?>" href="<?= $link['href'] ?>" <?= $isActive ?>><?= $link['label'] ?></a>
        <?php endforeach; ?>
      </div>

      <!-- RIGHT SIDE -->
      <div class="d-flex align-items-center gap-2">

        <!-- CART -->
        <a id="nav-cart" class="cart-btn d-none d-sm-flex" href="<?= $base ?>pages/cart.php">
          <i class="fas fa-shopping-bag"></i> Cart
          <span class="cart-count">0</span>
        </a>
        <a class="cart-btn d-flex d-sm-none" href="<?= $base ?>pages/cart.php" style="padding:9px 12px;">
          <i class="fas fa-shopping-bag"></i>
          <span class="cart-count">0</span>
        </a>

        <!-- PROFILE DROPDOWN -->
        <div class="dropdown d-none d-sm-flex">
          <button class="profile-btn dropdown-toggle" type="button"
                  id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
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
              <li><a class="dropdown-item" href="<?= $base ?>pages/dashboard.php"><i class="fas fa-user"></i> My Account</a></li>
              <li><a class="dropdown-item" href="<?= $base ?>pages/orders.php"><i class="fas fa-box"></i> My Orders</a></li>
              <li><a class="dropdown-item" href="<?= $base ?>pages/wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="<?= $base ?>pages/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            <?php else: ?>
              <li><a class="dropdown-item" href="<?= $base ?>account/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
              <li><a class="dropdown-item" href="<?= $base ?>account/login.php#register"><i class="fas fa-user-plus"></i> Register</a></li>
            <?php endif; ?>
          </ul>
        </div>

        <a class="profile-btn-mobile d-flex d-sm-none"
           href="<?= $isLoggedIn ? $base.'pages/dashboard.php' : $base.'account/login.php' ?>">
          <i class="fas fa-user-circle"></i>
        </a>

        

        <button class="hamburger d-md-none" onclick="toggleMobileMenu()">
          <i class="fas fa-bars"></i>
        </button>
      </div>
    </div>

    <!-- MOBILE MENU -->
    <div class="mobile-menu" id="mobileMenu">
      <a id="mnav-home"     href="<?= $base ?>index.php">🏠 Home</a>
      <a id="mnav-shop"     href="<?= $base ?>pages/shop.php">🛍️ Shop</a>
      <a id="mnav-about"    href="<?= $base ?>pages/about.php">🐾 About</a>
      <a id="mnav-contact"  href="<?= $base ?>pages/contact.php">✉️ Contact</a>
      <a id="mnav-services" href="<?= $base ?>pages/services.php">🛠️ Services</a>
      <a id="mnav-faqs"     href="<?= $base ?>pages/faqs.php">❓ FAQs</a>
      <a id="mnav-cart"     href="<?= $base ?>pages/cart.php">🛒 Cart</a>
      <?php if ($isLoggedIn): ?>
        <a id="mnav-dashboard" href="<?= $base ?>pages/dashboard.php">👤 Dashboard</a>
        <a href="<?= $base ?>pages/logout.php">🚪 Logout</a>
      <?php else: ?>
        <a id="mnav-login" href="<?= $base ?>account/login.php">🔐 Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="toast-container-custom" id="toastContainer"></div>

<!-- ═══════════════════════════ PAGE CONTENT ═══════════════════════════ -->
<div class="page-header">
  <div class="container">
    <div class="breadcrumb-custom">
      <a href="<?= $base ?>index.php">Home</a><span class="sep">/</span>
      <span class="current">Shop</span>
    </div>
    <h2>🛍️ Our Pet Shop</h2>
    <p>Explore our full collection of premium pet products</p>
  </div>
</div>

<div class="container py-4">
  <!-- Search bar -->
  <div class="search-wrap mb-4">
    <input type="text" id="shopSearch" placeholder="Search products..."
           value="<?= htmlspecialchars($search) ?>" oninput="debouncedRender()"/>
    <button onclick="renderShopProducts()"><i class="fas fa-search"></i></button>
  </div>

  <!-- Category tabs -->
  <div class="search-filter-tabs mb-4" id="categoryTabs">
    <button class="filter-tab" data-cat="all"         onclick="shopFilterCategory('all',this)">All</button>
    <button class="filter-tab" data-cat="dog"         onclick="shopFilterCategory('dog',this)">🐕 Dogs</button>
    <button class="filter-tab" data-cat="cat"         onclick="shopFilterCategory('cat',this)">🐈 Cats</button>
    <button class="filter-tab" data-cat="food"        onclick="shopFilterCategory('food',this)">🥩 Food</button>
    <button class="filter-tab" data-cat="toys"        onclick="shopFilterCategory('toys',this)">🎾 Toys</button>
    <button class="filter-tab" data-cat="beds"        onclick="shopFilterCategory('beds',this)">🛏 Beds</button>
    <button class="filter-tab" data-cat="accessories" onclick="shopFilterCategory('accessories',this)">👜 Accessories</button>
    <button class="filter-tab" data-cat="grooming"    onclick="shopFilterCategory('grooming',this)">✂️ Grooming</button>
  </div>

  <div class="row g-4">
    <!-- Sidebar Filters -->
    <div class="col-lg-3 col-md-4">
      <div class="shop-filter-sidebar">
        <h6 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--brown);margin-bottom:20px;">Filters</h6>
        <div class="filter-group">
          <h6>Pet Type</h6>
          <div class="filter-check">
            <label><input type="checkbox" id="fDog" onchange="renderShopProducts()" checked/> 🐕 Dogs</label>
            <label><input type="checkbox" id="fCat" onchange="renderShopProducts()" checked/> 🐈 Cats</label>
          </div>
        </div>
        <div class="filter-group">
          <h6>Price Range</h6>
          <div class="d-flex justify-content-between mb-2">
            <span style="font-size:0.8rem;color:var(--text-light);">₱0</span>
            <span style="font-size:0.8rem;font-weight:600;color:var(--amber);" id="priceLabel">₱5,000</span>
          </div>
          <input type="range" class="price-range" min="0" max="5000" value="5000"
                 id="priceRange" oninput="document.getElementById('priceLabel').textContent='₱'+this.value;debouncedRender()"/>
        </div>
        <div class="filter-group">
          <h6>Rating</h6>
          <div class="filter-check">
            <label><input type="checkbox" id="r5" onchange="renderShopProducts()" checked/> ⭐⭐⭐⭐⭐ 5 Stars</label>
            <label><input type="checkbox" id="r4" onchange="renderShopProducts()" checked/> ⭐⭐⭐⭐ 4+ Stars</label>
            <label><input type="checkbox" id="r3" onchange="renderShopProducts()" checked/> ⭐⭐⭐ 3+ Stars</label>
          </div>
        </div>
        <div class="filter-group">
          <h6>Sort By</h6>
          <select class="sort-select" id="shopSort" onchange="renderShopProducts()">
            <option value="featured">Featured</option>
            <option value="price-asc">Price: Low to High</option>
            <option value="price-desc">Price: High to Low</option>
            <option value="rating">Best Rating</option>
            <option value="name">Name A–Z</option>
          </select>
        </div>
        <button onclick="resetFilters()"
                style="background:var(--cream);border:1.5px solid var(--blush);border-radius:9px;padding:9px;width:100%;font-size:0.82rem;font-weight:600;color:var(--text-mid);cursor:pointer;transition:all 0.2s;"
                onmouseover="this.style.borderColor='var(--amber)'"
                onmouseout="this.style.borderColor='var(--blush)'">🔄 Reset Filters</button>
      </div>
    </div>

    <!-- Products Grid -->
    <div class="col-lg-9 col-md-8">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="results-count" id="shopResultsCount"><strong>0</strong> products found</div>
      </div>
      <!-- Loading skeleton -->
      <div id="shopLoadingState" class="row g-3" style="display:none;">
        <?php for ($i = 0; $i < 6; $i++): ?>
        <div class="col-xl-4 col-sm-6">
          <div style="border-radius:14px;background:var(--cream);padding:16px;height:280px;animation:pulse 1.4s ease-in-out infinite;">
            <div style="background:#e0d6cc;border-radius:10px;height:140px;margin-bottom:12px;"></div>
            <div style="background:#e0d6cc;border-radius:6px;height:14px;width:70%;margin-bottom:8px;"></div>
            <div style="background:#e0d6cc;border-radius:6px;height:12px;width:50%;margin-bottom:8px;"></div>
            <div style="background:#e0d6cc;border-radius:6px;height:18px;width:35%;"></div>
          </div>
        </div>
        <?php endfor; ?>
      </div>
      <div class="row g-3" id="shopProductsGrid"></div>
      <div id="shopPagination" class="d-flex justify-content-center gap-2 mt-4"></div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>

<!-- ═══ Scripts — load order matters: Bootstrap → your libs → page logic ═══ -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base ?>assets/script.js"></script>
<script>window.loggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;</script>
<script src="<?= $base ?>assets/app.js"></script>
<script src="<?= $base ?>assets/products.js"></script>

<script>
// ─── Navbar helpers ───────────────────────────────────────────────────────────
function toggleMobileMenu() {
  document.getElementById('mobileMenu').classList.toggle('open');
}

function showToast(msg, type = 'success') {
  const container = document.getElementById('toastContainer');
  const toast     = document.createElement('div');
  toast.className = 'toast-custom';
  const color     = type === 'success' ? 'var(--amber)' : type === 'error' ? '#E84A2A' : 'var(--sage)';
  toast.style.borderLeftColor = color;
  toast.innerHTML = `<i class="fas ${type==='success'?'fa-check-circle':type==='error'?'fa-times-circle':'fa-info-circle'}" style="color:${color};"></i><span>${msg}</span>`;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

function highlightCurrentNav() {
  const page = window.location.pathname.split('/').pop() || 'index.php';
  const map  = {
    'index.php':'home','shop.php':'shop','about.php':'about','contact.php':'contact',
    'services.php':'services','faqs.php':'faqs','privacy.php':'privacy','terms.php':'terms',
    'cart.php':'cart','login.php':'login','dashboard.php':'dashboard'
  };
  const key = map[page] || 'home';
  document.querySelectorAll('.nav-links a').forEach(a => a.classList.remove('active-nav'));
  document.getElementById('nav-' + key)?.classList.add('active-nav');
  document.querySelectorAll('.mobile-menu a').forEach(a => a.classList.remove('active-nav'));
  document.getElementById('mnav-' + key)?.classList.add('active-nav');
}

// ─── Shop logic ───────────────────────────────────────────────────────────────
const API_BASE = '/INFURNEST/backend/api/products_api.php';

let activeShopCategory = '<?= htmlspecialchars($category) ?>';
let currentPage        = 1;
const PAGE_LIMIT       = 12;
let debounceTimer      = null;

document.addEventListener('DOMContentLoaded', () => {
  highlightCurrentNav();

  // Init Bootstrap dropdowns explicitly — fixes dropdown not opening
  document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
    new bootstrap.Dropdown(el);
  });

  // Highlight active category tab
  document.querySelectorAll('#categoryTabs .filter-tab').forEach(btn => {
    if (btn.dataset.cat === activeShopCategory) btn.classList.add('active');
  });

  renderShopProducts();
});

function debouncedRender() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(renderShopProducts, 350);
}

function shopFilterCategory(cat, btn) {
  activeShopCategory = cat;
  currentPage        = 1;
  document.querySelectorAll('#categoryTabs .filter-tab').forEach(t => t.classList.remove('active'));
  btn?.classList.add('active');
  renderShopProducts();
}

function buildApiUrl() {
  const search    = (document.getElementById('shopSearch')?.value || '').trim();
  const maxPrice  = document.getElementById('priceRange')?.value  || 5000;
  const fDog      = document.getElementById('fDog')?.checked  ?? true;
  const fCat      = document.getElementById('fCat')?.checked  ?? true;
  const sort      = document.getElementById('shopSort')?.value || 'featured';
  const r5        = document.getElementById('r5')?.checked ?? true;
  const r4        = document.getElementById('r4')?.checked ?? true;
  const r3        = document.getElementById('r3')?.checked ?? true;
  let minRating   = (r3 && r4 && r5) ? 0 : r5 ? 5 : r4 ? 4 : r3 ? 3 : 0;

  const params = new URLSearchParams({
    action    : search ? 'search' : 'list',
    page      : currentPage,
    limit     : PAGE_LIMIT,
    sort,
    max_price : maxPrice,
    min_price : 0,
  });

  const petTabs = ['dog', 'cat'];
  if (activeShopCategory !== 'all') {
    petTabs.includes(activeShopCategory)
      ? params.set('pet', activeShopCategory)
      : params.set('category', activeShopCategory);
  } else {
    if (fDog && !fCat) params.set('pet', 'dog');
    if (fCat && !fDog) params.set('pet', 'cat');
  }

  if (minRating > 0) params.set('min_rating', minRating);
  if (search)        params.set('q', search);

  return `${API_BASE}?${params}`;
}

async function renderShopProducts() {
  const grid    = document.getElementById('shopProductsGrid');
  const loading = document.getElementById('shopLoadingState');

  grid.innerHTML       = '';
  loading.style.display = 'flex';
  loading.style.flexWrap = 'wrap';

  try {
    const res = await fetch(buildApiUrl());

    if (!res.ok) {
      showError(`Server error: ${res.status} — check API path`);
      loading.style.display = 'none';
      return;
    }

    const rawText = await res.text();
    let data;
    try { data = JSON.parse(rawText); }
    catch (e) {
      console.error('Products API non-JSON:', rawText);
      showError('Server error loading products.');
      loading.style.display = 'none';
      return;
    }

    loading.style.display = 'none';

    if (!data.success) { showError(data.message || 'Failed to load products.'); return; }

    const products   = data.products   || [];
    const pagination = data.pagination || {};

    document.getElementById('shopResultsCount').innerHTML =
      `<strong>${pagination.total ?? products.length}</strong> product${(pagination.total ?? products.length) !== 1 ? 's' : ''} found`;

    if (products.length === 0) {
      grid.innerHTML = `
        <div class="col-12">
          <div class="no-results">
            <div class="no-results-icon">🔍</div>
            <h5 style="color:var(--brown);">No products found</h5>
            <p style="color:var(--text-light);margin-bottom:16px;">Try adjusting your search or filters</p>
            <button class="btn-primary-custom" onclick="resetFilters()">Reset Filters</button>
          </div>
        </div>`;
      renderPagination(pagination);
      return;
    }

    products.forEach(p => renderProductCard(p, grid));
    renderPagination(pagination);

  } catch (err) {
    loading.style.display = 'none';
    console.error('Shop fetch error:', err);
    showError('Could not connect to the server. Please try again.');
  }
}

function renderPagination(pagination) {
  const wrap = document.getElementById('shopPagination');
  wrap.innerHTML = '';
  if (!pagination || pagination.pages <= 1) return;

  const { page, pages } = pagination;
  const mkBtn = (label, active, onClick) => {
    const btn = document.createElement('button');
    btn.innerHTML = label;
    btn.style.cssText = `padding:7px 15px;border-radius:8px;border:1.5px solid ${active?'var(--amber)':'var(--blush)'};background:${active?'var(--amber)':'var(--cream)'};color:${active?'#fff':'var(--text-mid)'};font-weight:600;cursor:pointer;font-size:0.85rem;`;
    btn.onclick = onClick;
    wrap.appendChild(btn);
  };

  if (page > 1) mkBtn('← Prev', false, () => { currentPage = page - 1; renderShopProducts(); });

  const start = Math.max(1, page - 2);
  const end   = Math.min(pages, page + 2);
  for (let i = start; i <= end; i++) {
    const _i = i;
    mkBtn(i, i === page, () => { currentPage = _i; renderShopProducts(); });
  }

  if (page < pages) mkBtn('Next →', false, () => { currentPage = page + 1; renderShopProducts(); });
}

function showError(msg) {
  document.getElementById('shopLoadingState').style.display = 'none';
  document.getElementById('shopProductsGrid').innerHTML = `
    <div class="col-12">
      <div class="no-results">
        <div class="no-results-icon">⚠️</div>
        <h5 style="color:var(--brown);">Oops!</h5>
        <p style="color:var(--text-light);margin-bottom:16px;">${msg}</p>
        <button class="btn-primary-custom" onclick="renderShopProducts()">Try Again</button>
      </div>
    </div>`;
}

function resetFilters() {
  activeShopCategory = 'all';
  currentPage        = 1;
  document.getElementById('shopSearch').value       = '';
  document.getElementById('priceRange').value       = 5000;
  document.getElementById('priceLabel').textContent = '₱5,000';
  ['fDog','fCat','r5','r4','r3'].forEach(id => document.getElementById(id).checked = true);
  document.getElementById('shopSort').value = 'featured';
  document.querySelectorAll('#categoryTabs .filter-tab').forEach(t => {
    t.classList.toggle('active', t.dataset.cat === 'all');
  });
  renderShopProducts();
}
</script>

<style>
@keyframes pulse {
  0%, 100% { opacity: 1; }
  50%       { opacity: 0.5; }
}
</style>

</body>
</html>