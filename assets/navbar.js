/* navbar.js — injects the shared navbar */
function renderNavbar(activePage) {
  const nav = document.createElement('nav');
  nav.className = 'navbar-custom';
  nav.id = 'mainNav';
  nav.innerHTML = `
    <div class="container">
      <div class="d-flex align-items-center justify-content-between">
        <a class="navbar-brand-logo" href="index.html">
          <div class="logo-icon"><i class="fas fa-paw"></i></div>
          IN<span class="accent">FUR</span>NEST
        </a>
        <div class="nav-links d-none d-md-flex align-items-center gap-1">
          <a href="index.php"   id="nav-home"     ${activePage==='home'     ?'class="active-nav"':''}>Home</a>
          <a href="shop.php"    id="nav-shop"     ${activePage==='shop'     ?'class="active-nav"':''}>Shop</a>
          <a href="about.php"   id="nav-about"    ${activePage==='about'    ?'class="active-nav"':''}>About</a>
          <a href="contact.php" id="nav-contact"  ${activePage==='contact'  ?'class="active-nav"':''}>Contact</a>
          <a href="faqs.php"    id="nav-faqs"     ${activePage==='faqs'     ?'class="active-nav"':''}>FAQs</a>
        </div>
        <div class="d-flex align-items-center gap-2">
          <a href="cart.html" class="cart-btn d-none d-sm-flex">
            <i class="fas fa-shopping-bag"></i> Cart
            <span class="cart-count">0</span>
          </a>
          <a href="cart.html" class="cart-btn d-flex d-sm-none" style="padding:9px 12px;">
            <i class="fas fa-shopping-bag"></i>
            <span class="cart-count">0</span>
          </a>
          <button class="hamburger d-md-none" onclick="toggleMobileMenu()"><i class="fas fa-bars"></i></button>
        </div>
      </div>
    </div>
    <div class="mobile-menu" id="mobileMenu">
      <a href="index.html">🏠 Home</a>
      <a href="shop.html">🛍️ Shop</a>
      <a href="about.html">🐾 About</a>
      <a href="contact.html">✉️ Contact</a>
      <a href="faqs.html">❤️ FAQs</a>
      <a href="cart.html">🛒 Cart</a>
      <a href="login.html">🔐 Login</a>
      <a href="dashboard.html">⚙️ Dashboard</a>
    </div>`;
  document.body.prepend(nav);
}

function renderToastContainer() {
  const div = document.createElement('div');
  div.className = 'toast-container-custom';
  div.id = 'toastContainer';
  document.body.appendChild(div);
}