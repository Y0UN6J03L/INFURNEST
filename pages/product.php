<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle  = 'Product Detail';
$activePage = 'shop';
$base       = '/INFURNEST/';          // absolute — never breaks regardless of depth

$userName   = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'User';
$isLoggedIn = isset($_SESSION['user_id']);

$category = $_GET['category'] ?? 'all';
$search   = $_GET['search']   ?? '';
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
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
<link rel="stylesheet" href="../assets/styles.css"/>
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
      <a href="index.php">Home</a><span class="sep">/</span>
      <a href="shop.php">Shop</a><span class="sep">/</span>
      <span class="current" id="detailBreadcrumb">Product</span>
    </div>
    <h2 id="detailPageTitle">Product Detail</h2>
  </div>
</div>

<div class="container py-5" id="productDetailContent">
  <div class="page-loader"><div class="spinner"></div></div>
</div>


<?php include __DIR__ . '/../includes/footer.php'; ?>
<?php include __DIR__ . '/../includes/scripts.php'; ?>
<script>
const productId = <?= $productId ?>;

function switchTab(name, btn) {
  document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
  document.querySelectorAll('.tab-content-panel').forEach(p => p.classList.remove('active'));
  if (btn) btn.classList.add('active');
  document.getElementById('tab-' + name).classList.add('active');
}

function changeQty(delta) {
  const inp = document.getElementById('detailQty');
  let v = parseInt(inp.value) + delta;
  if (v < 1) v = 1;
  if (v > 99) v = 99;
  inp.value = v;
}

function addToCartDetail(id) {
  const qty = parseInt(document.getElementById('detailQty').value) || 1;
  addToCart(id, qty);
}

function setReviewStars(e) {
  const val = parseInt(e.target.dataset.val);
  if (!val) return;
  document.querySelectorAll('#reviewStars span').forEach((s, i) => {
    s.classList.toggle('filled', i < val);
  });
}

function submitReview() {
  const text = document.getElementById('reviewText').value.trim();
  if (!text) { showToast('Please write your review first!', 'error'); return; }
  showToast('✅ Review submitted! Thank you!', 'success');
  document.getElementById('reviewText').value = '';
  document.querySelectorAll('#reviewStars span').forEach(s => s.classList.remove('filled'));
}

function toggleWishlistDetail(id) {
  const added = toggleWishlistItem(id);
  const btn = document.getElementById('detailWishBtn');
  if (btn) {
    btn.innerHTML = added ? '<i class="fas fa-heart"></i>' : '<i class="far fa-heart"></i>';
    btn.style.color = added ? '#E84A2A' : 'var(--text-light)';
    btn.style.borderColor = added ? '#E84A2A' : 'var(--blush)';
    btn.style.background = added ? 'rgba(232,74,42,0.08)' : 'var(--cream)';
  }
}

const API_BASE = '/INFURNEST/backend/api/products_api.php';

async function renderProductDetail(id) {
  document.getElementById('productDetailContent').innerHTML = '<div class="page-loader"><div class="spinner"></div></div>';

  try {
    const res = await fetch(`${API_BASE}?action=single&id=${id}`);
    const data = await res.json();
    
    if (!data.success || !data.product) {
      document.getElementById('productDetailContent').innerHTML = `<div class="text-center py-5"><div style="font-size:5rem;">🔍</div><h4 style="color:var(--brown);">Product not found</h4><a class="btn-primary-custom mt-3" href="shop.php" style="display:inline-flex;">Back to Shop</a></div>`;
      return;
    }

    const p = data.product;
    document.getElementById('detailBreadcrumb').textContent = p.name;
    document.getElementById('detailPageTitle').textContent = p.name;
    document.title = p.name + ' — INFURNEST Premium Pet Shop';

    const stars = '⭐'.repeat(Math.round(p.rating)) + '☆'.repeat(5 - Math.round(p.rating));
    const wishlisted = isWishlisted(id);

    document.getElementById('productDetailContent').innerHTML = `
      <div class="row g-5">
        <div class="col-md-5">
          <div class="detail-img-box">${
            p.imageUrl 
              ? `<img src="${p.imageUrl}" alt="${p.name}" style="width:100%;height:100%;object-fit:cover;border-radius:12px;"/>` 
              : `<span style="font-size:5rem;">${p.emoji}</span>`
          }</div>
          <div class="d-flex gap-2 mt-3 justify-content-center">
            ${
              (p.imageUrl ? [p.imageUrl, p.imageUrl, p.imageUrl] : [p.emoji, p.emoji, p.emoji])
                .map((img,i) => `
                  <div style="background:var(--cream);border-radius:10px;padding:14px;cursor:pointer;border:2px solid ${i===0?'var(--amber)':'var(--blush)'};">${
                    p.imageUrl 
                      ? `<img src="${img}" style="width:40px;height:40px;object-fit:cover;border-radius:6px;"/>` 
                      : img
                  }</div>
                `).join('')
            }
          </div>
        </div>

      <div class="col-md-7">
        <div class="d-flex gap-2 flex-wrap mb-2">
          <span class="badge-pet ${p.pet==='dog'?'badge-dog':'badge-cat'}">${p.pet==='dog'?'🐕 Dog':'🐈 Cat'}</span>
          <span class="badge-pet" style="background:rgba(61,43,31,0.08);color:var(--brown);">📦 ${p.category.charAt(0).toUpperCase()+p.category.slice(1)}</span>
          ${p.badge ? `<span class="product-badge" style="position:static;display:inline-flex;">${p.badge==='bestseller'?'🏆 Best Seller':p.badge==='sale'?'🔥 On Sale':p.badge==='new'?'✨ New Arrival':p.badge}</span>` : ''}
        </div>
        <h3 style="font-family:'Playfair Display',serif;font-size:1.8rem;color:var(--brown);font-weight:900;margin-bottom:10px;">${p.name}</h3>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
          <span style="color:#F5B300;font-size:1.1rem;">${stars}</span>
          <span style="font-size:0.85rem;color:var(--text-light);">${p.reviews} reviews</span>
          <span style="font-size:0.85rem;color:${p.inStock?'var(--sage)':'#E84A2A'};font-weight:600;">${p.inStock?'✅ In Stock':'❌ Out of Stock'}</span>
        </div>
        <div style="margin-bottom:20px;">
          ${p.oldPrice ? `<span style="font-size:1rem;color:var(--text-light);text-decoration:line-through;margin-right:10px;">₱${p.oldPrice.toLocaleString()}</span>` : ''}
          <span style="font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:900;color:var(--amber-dark);">₱${p.price.toLocaleString()}</span>
          ${p.oldPrice ? `<span style="font-size:0.82rem;background:#E84A2A;color:white;border-radius:5px;padding:3px 7px;margin-left:10px;">Save ₱${(p.oldPrice-p.price).toLocaleString()}</span>` : ''}
        </div>
        <p style="color:var(--text-mid);line-height:1.8;margin-bottom:20px;">${p.details}</p>
        <div style="background:var(--cream);border-radius:12px;padding:16px;margin-bottom:22px;">
          <div class="row g-2" style="font-size:0.82rem;">
            <div class="col-6"><span style="color:var(--text-light);">Brand:</span> <strong style="color:var(--brown);">${p.brand}</strong></div>
            <div class="col-6"><span style="color:var(--text-light);">Weight:</span> <strong style="color:var(--brown);">${p.weight}</strong></div>
            <div class="col-6"><span style="color:var(--text-light);">Age Group:</span> <strong style="color:var(--brown);">${p.ageGroup}</strong></div>
            <div class="col-6"><span style="color:var(--text-light);">Pet:</span> <strong style="color:var(--brown);">${p.pet==='dog'?'🐕 Dogs':'🐈 Cats'}</strong></div>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:14px;margin-bottom:20px;flex-wrap:wrap;">
          <label style="font-size:0.82rem;font-weight:600;color:var(--brown);">Quantity:</label>
          <div class="quantity-box">
            <button onclick="changeQty(-1)">−</button>
            <input type="number" id="detailQty" value="1" min="1" max="99"/>
            <button onclick="changeQty(1)">+</button>
          </div>
        </div>
        <div class="d-flex gap-3 flex-wrap">
          <button class="btn-primary-custom" onclick="addToCartDetail(${p.id})" style="flex:1;justify-content:center;"><i class="fas fa-shopping-bag"></i> Add to Cart</button>
          <button onclick="toggleWishlistDetail(${p.id})" id="detailWishBtn" style="background:${wishlisted?'rgba(232,74,42,0.08)':'var(--cream)'};border:1.5px solid ${wishlisted?'#E84A2A':'var(--blush)'};border-radius:12px;padding:12px 18px;cursor:pointer;color:${wishlisted?'#E84A2A':'var(--text-light)'};font-size:1rem;transition:all 0.2s;"><i class="${wishlisted?'fas':'far'} fa-heart"></i></button>
        </div>
        <div class="d-flex gap-3 mt-3 flex-wrap" style="font-size:0.78rem;color:var(--text-light);">
          <span>🚚 Free delivery on orders ₱999+</span>
          <span>🔄 30-day returns</span>
          <span>✅ Vet approved</span>
        </div>
      </div>
    </div>
    <!-- TABS -->
    <div class="mt-5">
      <div style="border-bottom:1.5px solid var(--blush);display:flex;gap:0;overflow-x:auto;">
        <button class="tab-btn active" onclick="switchTab('desc',this)">Description</button>
        <button class="tab-btn" onclick="switchTab('reviews',this)">Reviews (${p.reviews})</button>
        <button class="tab-btn" onclick="switchTab('shipping',this)">Shipping</button>
      </div>
      <div class="tab-content-panel active" id="tab-desc">
        <p style="color:var(--text-mid);line-height:1.8;">${p.desc}</p>
        <ul style="color:var(--text-mid);font-size:0.88rem;line-height:2;margin-top:12px;padding-left:20px;">
          <li>Brand: ${p.brand}</li>
          <li>Net Weight: ${p.weight}</li>
          <li>Suitable for: ${p.ageGroup} ${p.pet}s</li>
          <li>100% vet approved and quality-tested</li>
          <li>Ships from our Metro Manila warehouse</li>
        </ul>
      </div>
      <div class="tab-content-panel" id="tab-reviews">
        <div style="margin-bottom:20px;">
          <div style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:900;color:var(--amber);">${p.rating}.0</div>
          <div style="color:#F5B300;font-size:1.2rem;margin-bottom:4px;">${'⭐'.repeat(p.rating)}</div>
          <div style="font-size:0.82rem;color:var(--text-light);">Based on ${p.reviews} reviews</div>
        </div>
        <div class="review-card"><div class="d-flex justify-content-between"><span class="reviewer">Ana M.</span><span style="color:#F5B300;font-size:0.78rem;">⭐⭐⭐⭐⭐</span></div><div class="review-text">My dog absolutely loves this! Worth every peso. Will definitely re-order!</div></div>
        <div class="review-card"><div class="d-flex justify-content-between"><span class="reviewer">Carlo D.</span><span style="color:#F5B300;font-size:0.78rem;">⭐⭐⭐⭐⭐</span></div><div class="review-text">Great quality and fast delivery. INFURNEST never disappoints. My cat is so happy 🐈</div></div>
        <div class="review-card"><div class="d-flex justify-content-between"><span class="reviewer">Bea R.</span><span style="color:#F5B300;font-size:0.78rem;">⭐⭐⭐⭐</span></div><div class="review-text">Solid product, packaging was excellent. Slight delay in delivery but otherwise perfect.</div></div>
        <div style="margin-top:20px;padding-top:20px;border-top:1px solid var(--blush);">
          <h6 style="font-weight:700;color:var(--brown);margin-bottom:14px;">Write a Review</h6>
          <div class="stars-input" id="reviewStars" onclick="setReviewStars(event)">
            <span data-val="1">★</span><span data-val="2">★</span><span data-val="3">★</span><span data-val="4">★</span><span data-val="5">★</span>
          </div>
          <textarea style="width:100%;border:1.5px solid var(--blush);border-radius:10px;padding:12px;margin-top:10px;font-family:inherit;font-size:0.85rem;outline:none;" rows="3" id="reviewText" placeholder="Share your experience..."></textarea>
          <button class="btn-primary-custom mt-2" onclick="submitReview()"><i class="fas fa-paper-plane"></i> Submit Review</button>
        </div>
      </div>
      <div class="tab-content-panel" id="tab-shipping">
        <div class="row g-3" style="font-size:0.88rem;">
          <div class="col-md-6"><div style="background:var(--cream);border-radius:12px;padding:18px;"><div style="font-size:1.5rem;margin-bottom:8px;">🚚</div><strong style="color:var(--brown);">Standard Delivery (3–5 days)</strong><p style="color:var(--text-light);margin:8px 0 0;line-height:1.7;">Free on orders ₱999+. ₱99 flat rate for smaller orders. Available nationwide.</p></div></div>
          <div class="col-md-6"><div style="background:var(--cream);border-radius:12px;padding:18px;"><div style="font-size:1.5rem;margin-bottom:8px;">⚡</div><strong style="color:var(--brown);">Express Delivery (1–2 days)</strong><p style="color:var(--text-light);margin:8px 0 0;line-height:1.7;">₱149 flat rate. Metro Manila and key cities. Order before 2 PM.</p></div></div>
          <div class="col-md-6"><div style="background:var(--cream);border-radius:12px;padding:18px;"><div style="font-size:1.5rem;margin-bottom:8px;">🏙️</div><strong style="color:var(--brown);">Same-Day Metro (Today)</strong><p style="color:var(--text-light);margin:8px 0 0;line-height:1.7;">₱199 flat rate. Metro Manila only. Order before 11 AM. Guaranteed delivery.</p></div></div>
          <div class="col-md-6"><div style="background:var(--cream);border-radius:12px;padding:18px;"><div style="font-size:1.5rem;margin-bottom:8px;">🔄</div><strong style="color:var(--brown);">Returns & Refunds</strong><p style="color:var(--text-light);margin:8px 0 0;line-height:1.7;">30-day hassle-free returns. Full refund or exchange. Contact us to initiate.</p></div></div>
        </div>
      </div>
    </div>
    <!-- Related Products -->
    <div class="mt-5">
      <h4 style="font-family:'Playfair Display',serif;color:var(--brown);margin-bottom:20px;">You Might Also Like</h4>
      <div class="row g-3" id="relatedProductsGrid"></div>
    </div>`;

  // Render related products
  // Render related products
    const related = products.filter(pr => pr.id !== id && (pr.category === p.category || pr.pet === p.pet)).slice(0, 4);
    const rGrid = document.getElementById('relatedProductsGrid');
    related.forEach(rp => renderProductCard(rp, rGrid));

  } catch (err) {
    console.error('Failed to load product:', err);
    document.getElementById('productDetailContent').innerHTML = `
      <div class="text-center py-5">
        <div style="font-size:5rem;">⚠️</div>
        <h4 style="color:var(--brown);">Something went wrong</h4>
        <p style="color:var(--text-light);">Could not load product details. Please try again.</p>
        <a class="btn-primary-custom mt-3" href="shop.php" style="display:inline-flex;">Back to Shop</a>
      </div>`;
  }
}


document.addEventListener('DOMContentLoaded', () => renderProductDetail(productId));
</script>
</body>
</html>