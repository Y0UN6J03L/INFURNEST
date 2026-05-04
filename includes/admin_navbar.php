<?php
// Check if user is logged in and is admin
if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.php');
    exit;
}

$activePage = $activePage ?? 'admin';
?>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="admin-sidebar d-none d-md-block" style="width:260px;">
        <h4 style="color:white;font-family:'Playfair Display',serif;margin-bottom:24px;padding:0 16px;">
            <i class="fas fa-cog"></i> Admin
        </h4>
        <a href="../pages/admin.php" class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="../pages/admin-products.php" class="<?= $activePage === 'products' ? 'active' : '' ?>">
            <i class="fas fa-box"></i> Products
        </a>
        <a href="../pages/admin-orders.php" class="<?= $activePage === 'orders' ? 'active' : '' ?>">
            <i class="fas fa-shopping-cart"></i> Orders
        </a>
        <a href="../pages/admin-users.php" class="<?= $activePage === 'users' ? 'active' : '' ?>">
            <i class="fas fa-users"></i> Users
        </a>
        <hr style="border-color:rgba(255,255,255,0.1);margin:20px 0;">
        <a href="../index.php"><i class="fas fa-store"></i> View Store</a>
        <a href="../pages/logout.php"><i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
    
    <!-- Main Content -->
    <div class="flex-grow-1 admin-content" style="background:var(--cream);">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="font-family:'Playfair Display',serif;color:var(--brown);"><?= $pageTitle ?></h2>
            <div>
                <span style="color:var(--text-light);">Welcome, Admin</span>
            </div>
        </div>
