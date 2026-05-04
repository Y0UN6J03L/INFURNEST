// ==================== PRODUCT DATA ====================
window.products = window.products || [];
if (!window.products.length) { window.products = [
{ id:1, name:'Royal Canin Adult Dog Food', emoji:'🥘', category:'food', pet:'dog', price:1299, oldPrice:1599, rating:5, reviews:124, badge:'bestseller', desc:'Complete balanced nutrition for adult dogs. Scientifically formulated for optimal health.', details:'Supports healthy digestion, a shiny coat, and strong muscles. Suitable for all breeds.', brand:'Royal Canin', weight:'2kg', ageGroup:'Adult', imageUrl:null, inStock:true },
{ id:2, name:'Interactive Feather Cat Toy', emoji:'🪶', category:'toys', pet:'cat', price:349, oldPrice:null, rating:5, reviews:87, badge:'new', desc:'Retractable feather wand toy. Keeps cats mentally stimulated and physically active.', details:'Telescopic rod extends up to 90cm. Feather attachments are replaceable.', brand:'PetPlay', weight:'150g', ageGroup:'All Ages', imageUrl:null, inStock:true },
{ id:3, name:'Cozy Donut Dog Bed', emoji:'🛏', category:'beds', pet:'dog', price:2199, oldPrice:2799, rating:4, reviews:56, badge:'sale', desc:'Ultra-plush donut-shaped dog bed. Self-warming design for deep, restful sleep.', details:'Machine washable cover. Available in brown, grey, and cream. Supports joints.', brand:'PawDreams', weight:'1.2kg', ageGroup:'All Sizes', imageUrl:null, inStock:true },
  { id:4, name:'Cat Dental Treats', emoji:'🦷', category:'food', pet:'cat', price:299, oldPrice:null, rating:4, reviews:43, badge:'new', desc:'Chicken-flavored dental chews that clean teeth and freshen breath naturally.', details:'Vet-recommended formula. 60 treats per bag. Reduces tartar buildup.', brand:'Whiskas Care', weight:'200g', ageGroup:'Adult', imageUrl:null, inStock:true },
  { id:5, name:'GPS Dog Collar Tracker', emoji:'📡', category:'accessories', pet:'dog', price:3499, oldPrice:3999, rating:5, reviews:92, badge:'bestseller', desc:'Real-time GPS tracking collar. Monitor your dog\'s location via smartphone app.', details:'Waterproof, rechargeable, works nationwide. Compatible with iOS & Android.', brand:'PetTrack', weight:'80g', ageGroup:'All Breeds', imageUrl:null, inStock:true },
  { id:6, name:'Cat Grooming Brush Set', emoji:'✂️', category:'grooming', pet:'cat', price:649, oldPrice:null, rating:4, reviews:31, badge:'', desc:'Professional 3-piece grooming set. Deshedding brush, comb, and nail clipper.', details:'Ergonomic handles. Stainless steel blades. Suitable for short and long coats.', brand:'GloomGroom', weight:'300g', ageGroup:'All Ages', imageUrl:null, inStock:true },
  { id:7, name:'Orthopedic Cat Bed', emoji:'😴', category:'beds', pet:'cat', price:1799, oldPrice:2299, rating:5, reviews:68, badge:'sale', desc:'Memory foam orthopedic bed with removable, washable cover. Perfect for senior cats.', details:'High-density memory foam relieves pressure points. Waterproof inner lining.', brand:'PawDreams', weight:'900g', ageGroup:'All Ages', inStock:true },
  { id:8, name:'Natural Dog Shampoo', emoji:'🛁', category:'grooming', pet:'dog', price:459, oldPrice:null, rating:4, reviews:29, badge:'', desc:'Oatmeal & aloe vera formula. Gentle on sensitive skin. Dermatologist-tested.', details:'pH-balanced, sulfate-free, no artificial fragrances. 500ml bottle.', brand:'PurePaws', weight:'550g', ageGroup:'All Ages', inStock:true },
  { id:9, name:'Dog Rope Chew Toy Set', emoji:'🪢', category:'toys', pet:'dog', price:399, oldPrice:null, rating:4, reviews:47, badge:'new', desc:'Set of 5 durable cotton rope toys. Promotes dental health and satisfies chewing instincts.', details:'100% natural cotton, no synthetic dyes. Machine washable. Great for tug-of-war.', brand:'RopePlay', weight:'500g', ageGroup:'All Ages', inStock:true },
  { id:10, name:'Premium Cat Wet Food Pack', emoji:'🍖', category:'food', pet:'cat', price:799, oldPrice:999, rating:5, reviews:108, badge:'sale', desc:'12-pack of gourmet wet food in tuna, chicken, and salmon flavors. No preservatives.', details:'Real meat first ingredient. High protein, low carb. Suitable for all life stages.', brand:'Felix Gourmet', weight:'1.4kg', ageGroup:'Adult', inStock:true },
  { id:11, name:'Cat Window Perch', emoji:'🪟', category:'accessories', pet:'cat', price:1149, oldPrice:null, rating:4, reviews:38, badge:'', desc:'Suction-cup mounted window seat. Lets cats enjoy a birds-eye view in comfort.', details:'Holds up to 15kg. UV-resistant fabric. Easy installation, no tools needed.', brand:'CatView', weight:'700g', ageGroup:'All Ages', inStock:true },
  { id:12, name:'Dog Training Clicker Kit', emoji:'🔔', category:'accessories', pet:'dog', price:299, oldPrice:null, rating:5, reviews:64, badge:'new', desc:'Professional training clicker with wrist strap + 50-page training guide included.', details:'Loud, clear click sound. Ergonomic design. Works for basic and advanced training.', brand:'SmartPet', weight:'100g', ageGroup:'All Ages', inStock:true },
  { id:13, name:'Cat Scratching Tower', emoji:'🏰', category:'accessories', pet:'cat', price:2899, oldPrice:3499, rating:5, reviews:91, badge:'bestseller', desc:'5-level cat tower with scratching posts, hammock, and dangling toys. Saves your furniture.', details:'Sisal rope scratching posts. Stable base. Easy assembly. Accommodates multiple cats.', brand:'CatKingdom', weight:'5.2kg', ageGroup:'All Ages', inStock:true },
  { id:14, name:'Puppy Starter Pack', emoji:'🎁', category:'food', pet:'dog', price:1599, oldPrice:1999, rating:5, reviews:55, badge:'sale', desc:'Complete starter kit for new puppies. Includes food, toys, collar, and care guide.', details:'Age-appropriate nutrition. Soft puppy toys. Adjustable collar. PDF care guide.', brand:'INFURNEST', weight:'2.5kg', ageGroup:'Puppy', inStock:true },
  { id:15, name:'Automatic Pet Water Fountain', emoji:'⛲', category:'accessories', pet:'dog', price:1899, oldPrice:null, rating:4, reviews:73, badge:'', desc:'Circulating water fountain keeps water fresh and oxygenated. 2.5L capacity.', details:'Ultra-quiet pump. 3-stage filtration. LED water level indicator. BPA-free.', brand:'HydraPet', weight:'800g', ageGroup:'All Ages', inStock:true },
  { id:16, name:'Cat Litter Premium Clay', emoji:'🪣', category:'accessories', pet:'cat', price:549, oldPrice:null, rating:4, reviews:36, badge:'', desc:'Clumping clay litter with activated charcoal odor control. 7kg bag.', details:'99% dust-free. Fast clumping formula. 4-week odor protection. Unscented.', brand:'CleanPaws', weight:'7kg', ageGroup:'All Ages', inStock:true },
]; }

// ==================== CART & WISHLIST (sessionStorage) ====================
function getCart() {
  try { return JSON.parse(sessionStorage.getItem('infurnest_cart') || '[]'); } catch(e) { return []; }
}
function saveCart(cart) {
  sessionStorage.setItem('infurnest_cart', JSON.stringify(cart));
}
function getWishlist() {
  try { return JSON.parse(sessionStorage.getItem('infurnest_wishlist') || '[]'); } catch(e) { return []; }
}
function saveWishlist(wl) {
  sessionStorage.setItem('infurnest_wishlist', JSON.stringify(wl));
}
function getCartCount() {
  return getCart().reduce((s, c) => s + c.qty, 0);
}
function getCartTotal() {
  return getCart().reduce((s, c) => s + c.price * c.qty, 0);
}
async function addToCart(id, qty = 1) {
  const p = window.products.find(pr => pr.id === id);
  if (!p) return;
  let cart = getCart();
  const existing = cart.find(c => c.id === id);
  if (existing) existing.qty += qty;
  else cart.push({ ...p, qty });
  saveCart(cart);
  updateCartCountUI();
  showToast(`🛒 ${p.name} added to cart!`, 'success');
  
  // Auto-remove from wishlist if logged in
  const isLoggedIn = window.loggedIn || false;
  if (isLoggedIn) {
    const API_BASE = '/INFURNEST/backend/api/user_api.php';
    try {
      const formData = new FormData();
      formData.append('action', 'remove_wishlist');
      formData.append('product_id', id);
      await fetch(API_BASE, {method: 'POST', body: formData});
    } catch (err) {
      console.log('Auto-remove from wishlist failed (non-critical)');
    }
  } else {
    // LocalStorage: remove from wishlist
    let wl = getWishlist();
    wl = wl.filter(item => item.id !== id);
    saveWishlist(wl);
  }
}
function removeFromCart(id) {
  let cart = getCart().filter(c => c.id !== id);
  saveCart(cart);
  updateCartCountUI();
  showToast('🗑️ Item removed from cart', 'info');
}
function updateCartQty(id, val) {
  let cart = getCart();
  const item = cart.find(c => c.id === id);
  if (item) item.qty = Math.max(1, parseInt(val) || 1);
  saveCart(cart);
}
async function toggleWishlistItem(id) {
  const isLoggedIn = window.loggedIn || false;  // Set from PHP in pages
  if (!isLoggedIn) {
    // Fallback localStorage
    const p = window.products.find(pr => pr.id === id);
    if (!p) return false;
    let wl = getWishlist();
    const idx = wl.findIndex(w => w.id === id);
    if (idx > -1) {
      wl.splice(idx, 1);
      saveWishlist(wl);
      showToast('💔 Removed from wishlist', 'info');
      return false;
    } else {
      wl.push(p);
      saveWishlist(wl);
      showToast(`❤️ ${p.name} added to wishlist!`, 'success');
      return true;
    }
  }

  const API_BASE = '/INFURNEST/backend/api/user_api.php';
  try {
    const checkRes = await fetch(`${API_BASE}?action=check_wishlist&product_id=${id}`);
    const checkData = await checkRes.json();
    
    let action = 'add_wishlist';
    let message = 'Added to wishlist ❤️';
    let state = true;
    if (checkData.success && checkData.in_wishlist) {
      action = 'remove_wishlist';
      message = 'Removed from wishlist 💔';
      state = false;
    }
    
    const formData = new FormData();
    formData.append('action', action);
    formData.append('product_id', id);
    const res = await fetch(API_BASE, {method: 'POST', body: formData});
    const data = await res.json();
    
    if (data.success) {
      showToast(message, 'success');
      return state;
    } else {
      showToast(data.message || 'Failed', 'error');
    }
  } catch (err) {
    console.error('Wishlist API:', err);
    showToast('Network error', 'error');
  }
  return null;
}
function isWishlisted(id) {
  return !!getWishlist().find(w => w.id === id);
}

// ==================== UI HELPERS ====================
function updateCartCountUI() {
  const count = getCartCount();
  document.querySelectorAll('.cart-count').forEach(el => el.textContent = count);
}

function showToast(msg, type = 'success') {
  let container = document.getElementById('toastContainer');
  if (!container) {
    container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container-custom';
    document.body.appendChild(container);
  }
  const toast = document.createElement('div');
  toast.className = 'toast-custom';
  const color = type === 'success' ? 'var(--amber)' : type === 'error' ? '#E84A2A' : 'var(--sage)';
  toast.style.borderLeftColor = color;
  toast.innerHTML = `<i class="fas ${type==='success'?'fa-check-circle':type==='error'?'fa-times-circle':'fa-info-circle'}" style="color:${color};font-size:1rem;"></i><span>${msg}</span>`;
  container.appendChild(toast);
  setTimeout(() => {
    toast.style.animation = 'slideOut 0.3s ease forwards';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

function toggleMobileMenu() {
  document.getElementById('mobileMenu').classList.toggle('open');
}

function renderProductCard(p, container) {
  const stars = '⭐'.repeat(p.rating);
  const wishlisted = isWishlisted(p.id);
  const col = document.createElement('div');
  col.className = 'col-6 col-md-4 col-lg-3';
  col.innerHTML = `
    <div class="product-card" onclick="window.location.href='product.php?id=${p.id}'">
      <div class="product-img">
      ${p.badge ? `<span class="product-badge ${p.badge==='sale'?'sale':p.badge==='new'?'new':''}">${p.badge==='bestseller'?'🏆 Best Seller':p.badge==='sale'?'🔥 Sale':p.badge==='new'?'✨ New':p.badge}</span>` : ''}
      <button class="wishlist-btn ${wishlisted?'active':''}" onclick="event.stopPropagation();handleWishlistToggle(${p.id},this)">
        <i class="${wishlisted?'fas':'far'} fa-heart"></i>
      </button>
      ${p.imageUrl
        ? `<img src="${p.imageUrl}" alt="${p.name}" style="width:100%;height:100%;object-fit:cover;border-radius:12px;"/>`
        : `<span style="font-size:3rem;">${p.emoji}</span>`
      }
    </div>
      <div class="product-body">
        <div class="product-pet-tag">${p.pet==='dog'?'🐕 Dog':'🐈 Cat'}</div>
        <div class="product-name">${p.name}</div>
        <div class="product-desc">${p.desc}</div>
        <div class="product-stars">${stars} <span>(${p.reviews})</span></div>
        <div class="product-price-row">
          <div class="product-price">
            ${p.oldPrice ? `<span class="old-price">₱${p.oldPrice.toLocaleString()}</span>` : ''}
            ₱${p.price.toLocaleString()}
          </div>
          <button class="add-cart-btn" onclick="event.stopPropagation();addToCart(${p.id})">
            <i class="fas fa-plus"></i> Add
          </button>
        </div>
      </div>
    </div>`;
  container.appendChild(col);
}

function handleWishlistToggle(id, btn) {
  const added = toggleWishlistItem(id);
  if (btn) {
    btn.innerHTML = added ? '<i class="fas fa-heart"></i>' : '<i class="far fa-heart"></i>';
    btn.classList.toggle('active', added);
  }
}

// ==================== INIT ====================
document.addEventListener('DOMContentLoaded', () => {
  updateCartCountUI();
});