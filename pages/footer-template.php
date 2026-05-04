<?php
// Page-level footer template - copy this HTML to each page
?>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="navbar-brand-logo mb-3" style="color:white;font-size:1.4rem;">
          <div class="logo-icon"><i class="fas fa-paw"></i></div>
          IN<span style="color:var(--amber-light);">FUR</span>NEST
        </div>
        <p style="font-size:0.82rem;color:rgba(255,255,255,0.6);line-height:1.7;">The Philippines' most trusted online pet store. Delivering love and care to furry families since 2020.</p>
        <div class="d-flex gap-3 mt-3">
          <button onclick="showToast('🐦 Opening Facebook page...','info')" style="background:rgba(255,255,255,0.1);border:none;color:rgba(255,255,255,0.7);width:36px;height:36px;border-radius:8px;cursor:pointer;font-size:0.9rem;" title="Facebook"><i class="fab fa-facebook"></i></button>
          <button onclick="showToast('📸 Opening Instagram page...','info')" style="background:rgba(255,255,255,0.1);border:none;color:rgba(255,255,255,0.7);width:36px;height:36px;border-radius:8px;cursor:pointer;font-size:0.9rem;" title="Instagram"><i class="fab fa-instagram"></i></button>
          <button onclick="showToast('🐦 Opening Twitter/X page...','info')" style="background:rgba(255,255,255,0.1);border:none;color:rgba(255,255,255,0.7);width:36px;height:36px;border-radius:8px;cursor:pointer;font-size:0.9rem;" title="Twitter"><i class="fab fa-twitter"></i></button>
        </div>
      </div>
      <div class="col-6 col-md-2">
        <h5>Shop</h5>
        <a href="pages/shop.php?category=dog">Dog Products</a>
        <a href="pages/shop.php?category=cat">Cat Products</a>
        <a href="pages/shop.php?category=food">Pet Food</a>
        <a href="pages/shop.php?category=toys">Toys</a>
      </div>
      <div class="col-6 col-md-2">
        <h5>Help</h5>
        <a href="pages/contact.php">Contact Us</a>
        <a href="pages/faqs.php">FAQs</a>
        <a href="pages/privacy.php">Privacy Policy</a>
        <a href="pages/terms.php">Terms of Service</a>
      </div>
      <div class="col-md-4">
        <h5>Stay Updated 🐾</h5>
        <p style="font-size:0.82rem;color:rgba(255,255,255,0.6);margin-bottom:12px;">Get exclusive deals and pet care tips in your inbox.</p>
        <div class="d-flex gap-2">
          <input type="email" id="footerEmail" placeholder="Enter your email" style="background:rgba(255,255,255,0.1);border:1.5px solid rgba(255,255,255,0.2);border-radius:8px;padding:9px 13px;color:white;flex:1;font-family:inherit;font-size:0.85rem;outline:none;"/>
          <button onclick="subscribeEmail()" style="background:var(--amber);border:none;color:white;border-radius:8px;padding:9px 16px;cursor:pointer;font-weight:600;font-size:0.85rem;white-space:nowrap;">Subscribe</button>
        </div>
      </div>
    </div>
    <div class="footer-bottom row align-items-center">
      <div class="col-md-6"><p style="font-size:0.8rem;color:rgba(255,255,255,0.45);margin:0;">© 2025 INFURNEST. All rights reserved. Made with ❤️ for pets.</p></div>
      <div class="col-md-6 text-md-end">
        <span style="font-size:0.78rem;color:rgba(255,255,255,0.4);">🔒 Secure Payments &nbsp;·&nbsp; 🐾 Vet Approved &nbsp;·&nbsp; 🚚 Fast Delivery</span>
      </div>
    </div>
  </div>
</footer>

<!-- Add cart count update script -->
<script>
// Update cart count on page load
document.addEventListener('DOMContentLoaded', function() {
  const cartCount = typeof getCartCount === 'function' ? getCartCount() : 0;
  document.querySelectorAll('.cart-count').forEach(el => el.textContent = cartCount);
});

// Subscribe email function
function subscribeEmail() {
  const email = document.getElementById('footerEmail').value;
  if(email && email.includes('@')) {
    showToast('✅ Subscribed successfully!', 'success');
  } else {
    showToast('⚠️ Please enter a valid email', 'error');
  }
}
</script>

</body>
</html>
