/* ============================================================
   INFURNEST — Shared Script
   ============================================================ */

// ── PRODUCT DATA ──────────────────────────────────────────────
const products = [
  { id:1, name:"Royal Canin Adult Dog", category:"food", pet:"dog", price:1299, oldPrice:1599, rating:5, reviews:128, emoji:"🥩", badge:"Sale", desc:"Premium balanced nutrition for adult dogs. Supports digestive health and maintains ideal body weight.", featured:true },
  { id:2, name:"Whiskas Tuna Cat Food", category:"food", pet:"cat", price:89, oldPrice:null, rating:4.8, reviews:94, emoji:"🐟", badge:null, desc:"Delicious tuna flavor that cats love. Complete nutrition for everyday feeding.", featured:true },
  { id:3, name:"Kong Classic Dog Toy", category:"toys", pet:"dog", price:649, oldPrice:799, rating:5, reviews:211, emoji:"🎾", badge:"Bestseller", desc:"The ultimate dog toy. Fill with treats to keep your dog entertained for hours.", featured:true },
  { id:4, name:"Luxury Cat Tree Tower", category:"beds", pet:"cat", price:3499, oldPrice:4200, rating:4.9, reviews:67, emoji:"🏰", badge:"New", desc:"Multi-level cat tower with cozy perches, scratching posts, and hanging toys.", featured:true },
  { id:5, name:"Dog Harness (Medium)", category:"accessories", pet:"dog", price:549, oldPrice:null, rating:4.7, reviews:88, emoji:"🦺", badge:null, desc:"Comfortable, escape-proof harness for medium breeds. Soft mesh lining prevents chafing.", featured:true },
  { id:6, name:"Cat Grooming Kit", category:"grooming", pet:"cat", price:399, oldPrice:499, rating:4.6, reviews:52, emoji:"✂️", badge:"Sale", desc:"Complete 5-piece grooming set: brush, comb, nail clipper, scissors, and deshedding tool.", featured:true },
  { id:7, name:"Purina Pro Plan Dog", category:"food", pet:"dog", price:1899, oldPrice:null, rating:4.9, reviews:175, emoji:"🍖", badge:null, desc:"Science-backed formula with real chicken as the #1 ingredient.", featured:false },
  { id:8, name:"Orthopedic Dog Bed", category:"beds", pet:"dog", price:2199, oldPrice:2799, rating:5, reviews:43, emoji:"🛏", badge:"Sale", desc:"Memory foam orthopedic bed ideal for senior dogs or breeds prone to joint issues.", featured:false },
  { id:9, name:"Interactive Feather Wand", category:"toys", pet:"cat", price:199, oldPrice:null, rating:4.8, reviews:136, emoji:"🪶", badge:"New", desc:"Retractable feather wand with crinkle sound that triggers cats' natural hunting instincts.", featured:false },
  { id:10, name:"Stainless Steel Bowl Set", category:"accessories", pet:"dog", price:349, oldPrice:null, rating:4.5, reviews:61, emoji:"🥣", badge:null, desc:"Set of 2 anti-slip stainless steel bowls. Dishwasher safe and bacteria-resistant.", featured:false },
  { id:11, name:"Cat Litter (10L)", category:"accessories", pet:"cat", price:299, oldPrice:380, rating:4.6, reviews:99, emoji:"🪣", badge:"Sale", desc:"Ultra-clumping silica gel litter. Odor control for up to 4 weeks.", featured:false },
  { id:12, name:"Dog Shampoo & Conditioner", category:"grooming", pet:"dog", price:249, oldPrice:null, rating:4.7, reviews:77, emoji:"🧴", badge:null, desc:"Tearless, hypoallergenic formula with oatmeal and aloe. Safe for puppies.", featured:false },
  { id:13, name:"Cat Carrier Backpack", category:"accessories", pet:"cat", price:1799, oldPrice:2200, rating:4.8, reviews:55, emoji:"🎒", badge:"New", desc:"Airline-approved bubble window backpack. Ventilated and spacious for cats up to 8kg.", featured:false },
  { id:14, name:"Puppy Starter Kit", category:"accessories", pet:"dog", price:999, oldPrice:1299, rating:4.9, reviews:88, emoji:"🐶", badge:"Sale", desc:"Everything your new puppy needs: collar, leash, bowl set, toy, and training pads.", featured:false },
  { id:15, name:"Cozy Cat Cave Bed", category:"beds", pet:"cat", price:899, oldPrice:null, rating:4.7, reviews:41, emoji:"🏕", badge:null, desc:"Soft felt cave bed that gives cats the enclosed, secure feeling they love.", featured:false },
  { id:16, name:"Freeze-Dried Dog Treats", category:"food", pet:"dog", price:449, oldPrice:null, rating:5, reviews:203, emoji:"🍗", badge:"Bestseller", desc:"Single-ingredient chicken breast treats. No preservatives, additives, or fillers.", featured:false },
];

// ── CART & WISHLIST STATE ──────────────────────────────────────
let cart = JSON.parse(localStorage.getItem('infurnest_cart') || '[]');
let wishlist = JSON.parse(localStorage.getItem('infurnest_wishlist') || '[]');

function saveCart() { localStorage.setItem('infurnest_cart', JSON.stringify(cart)); }
function saveWishlist() { localStorage.setItem('infurnest_wishlist', JSON.stringify(wishlist)); }

function getCartCount() { return cart.reduce((sum, item) => sum + item.qty, 0); }

function updateCartBadge() {
  const count = getCartCount();
  document.querySelectorAll('.cart-count').forEach(el => el.textContent = count);
}

// ── TOAST ──────────────────────────────────────────────────────
function showToast(message, type = 'success') {
  const container = document.getElementById('toastContainer');
  if (!container) return;
  const toast = document.createElement('div');
  toast.className = `toast-item ${type}`;
  toast.innerHTML = message;
  container.appendChild(toast);
  setTimeout(() => toast.remove(), 3000);
}

// ── ADD TO CART ────────────────────────────────────────────────
function addToCart(productId) {
  const product = products.find(p => p.id === productId);
  if (!product) return;
  const existing = cart.find(i => i.id === productId);
  if (existing) {
    existing.qty++;
  } else {
    cart.push({ ...product, qty: 1 });
  }
  saveCart();
  updateCartBadge();
  showToast(`🛒 <strong>${product.name}</strong> added to cart!`, 'success');
}

// ── TOGGLE WISHLIST ────────────────────────────────────────────
function toggleWishlist(productId) {
  const product = products.find(p => p.id === productId);
  if (!product) return;
  const idx = wishlist.findIndex(i => i.id === productId);
  if (idx === -1) {
    wishlist.push(product);
    saveWishlist();
    showToast(`❤️ <strong>${product.name}</strong> added to wishlist!`, 'success');
  } else {
    wishlist.splice(idx, 1);
    saveWishlist();
    showToast(`💔 <strong>${product.name}</strong> removed from wishlist.`, 'info');
  }
  // Refresh wishlist buttons on page
  document.querySelectorAll(`[data-wish="${productId}"]`).forEach(btn => {
    btn.classList.toggle('active', wishlist.some(i => i.id === productId));
  });
}

function isInWishlist(id) { return wishlist.some(i => i.id === id); }

// ── PRODUCT CARD HTML ──────────────────────────────────────────
function productCardHTML(p, onclick = '') {
  const wished = isInWishlist(p.id);
  const stars = '★'.repeat(Math.floor(p.rating)) + (p.rating % 1 >= 0.5 ? '½' : '');
  return `
    <div class="product-card" onclick="${onclick || `goToProduct(${p.id})`}">
      <div class="product-img">
        ${p.badge ? `<span class="product-badge ${p.badge === 'New' ? 'new' : ''}">${p.badge}</span>` : ''}
        <button class="product-wishlist-btn ${wished ? 'active' : ''}" data-wish="${p.id}"
          onclick="event.stopPropagation();toggleWishlist(${p.id})">
          <i class="${wished ? 'fas' : 'far'} fa-heart"></i>
        </button>
        <span>${p.emoji}</span>
      </div>
      <div class="product-info">
        <div class="product-category">${p.pet === 'dog' ? '🐕 Dog' : '🐈 Cat'} · ${p.category}</div>
        <div class="product-name">${p.name}</div>
        <div class="product-rating">${stars} <span>(${p.reviews})</span></div>
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <span class="product-price">₱${p.price.toLocaleString()}</span>
            ${p.oldPrice ? `<span class="old-price ms-2">₱${p.oldPrice.toLocaleString()}</span>` : ''}
          </div>
        </div>
        <button class="add-to-cart-btn" onclick="event.stopPropagation();addToCart(${p.id})">
          <i class="fas fa-plus"></i> Add to Cart
        </button>
      </div>
    </div>`;
}

// ── NAVIGATION ─────────────────────────────────────────────────
function goToProduct(id) {
  localStorage.setItem('infurnest_view_product', id);
  window.location.href = 'product.html';
}

// ── NAVBAR ACTIVE STATE ────────────────────────────────────────
function setActiveNav(page) {
  document.querySelectorAll('.nav-links a').forEach(a => a.classList.remove('active-nav'));
  const el = document.getElementById('nav-' + page);
  if (el) el.classList.add('active-nav');
}

// ── MOBILE MENU ────────────────────────────────────────────────
function toggleMobileMenu() {
  const menu = document.getElementById('mobileMenu');
  if (menu) menu.classList.toggle('open');
}

// ── INIT ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  updateCartBadge();
});