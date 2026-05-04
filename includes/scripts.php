<?php
if (!isset($base)) $base = '';
if (!isset($activePage)) $activePage = '';
$v = time(); // cache buster
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= $base ?>assets/app.js?v=<?= $v ?>"></script>
<?php if ($activePage !== 'shop'): ?>
<script src="<?= $base ?>assets/products.js?v=<?= $v ?>"></script>
<?php endif; ?>
<script>
function subscribeEmail() {
  const email = document.getElementById('footerEmail').value.trim();
  if (!email || !email.includes('@')) { showToast('Please enter a valid email!', 'error'); return; }
  showToast('🎉 Subscribed! Welcome to the INFURNEST family!', 'success');
  document.getElementById('footerEmail').value = '';
}
</script>