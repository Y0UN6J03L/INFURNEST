<?php
/**
 * Admin Dashboard for INFURNEST
 * Only accessible by admin users
 */

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Check if user is admin - redirect to regular dashboard if not
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$pageTitle = 'Dashboard';
$base = '../';
include '../includes/admin_head.php';
?>

<?php include '../includes/admin_navbar.php'; ?>
        
<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h3 id="stats-products">-</h3>
                    <p>Total Products</p>
                </div>
                <div class="stat-icon"><i class="fas fa-box"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h3 id="stats-orders">-</h3>
                    <p>Total Orders</p>
                </div>
                <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h3 id="stats-users">-</h3>
                    <p>Total Users</p>
                </div>
                <div class="stat-icon"><i class="fas fa-users"></i></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between">
                <div>
                    <h3 id="stats-revenue">-</h3>
                    <p>Total Revenue</p>
                </div>
                <div class="stat-icon"><i class="fas fa-peso-sign"></i></div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 style="font-family: 'Playfair Display', serif; color: var(--brown);">Recent Orders</h4>
        <a href="admin-orders.php" style="color: var(--amber); text-decoration: none; font-size: 0.85rem;">View All</a>
    </div>
    <div class="table-admin">
        <table class="table mb-0" id="recent-orders-table">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="orders-tbody">
                <tr>
                    <td colspan="5" class="text-center text-muted">Loading orders...</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4">
    <div class="col-md-4">
        <a href="admin-products.php" class="btn-primary-custom w-100 justify-content-center">
            <i class="fas fa-plus"></i> Add Product
        </a>
    </div>
    <div class="col-md-4">
        <a href="admin-orders.php" class="btn-outline-custom w-100 justify-content-center" style="background: var(--brown); border-color: var(--brown);">
            <i class="fas fa-list"></i> Manage Orders
        </a>
    </div>
    <div class="col-md-4">
        <a href="admin-users.php" class="btn-outline-custom w-100 justify-content-center" style="background: var(--brown); border-color: var(--brown);">
            <i class="fas fa-user-plus"></i> Manage Users
        </a>
    </div>
</div>

</div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script>
// Fetch dashboard stats from API
async function loadDashboardStats() {
    try {
        const response = await fetch('../backend/api/admin_api.php?action=dashboard_stats');
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('stats-products').textContent = data.stats.products || 0;
            document.getElementById('stats-orders').textContent = data.stats.orders || 0;
            document.getElementById('stats-users').textContent = data.stats.users || 0;
            document.getElementById('stats-revenue').textContent = '₱' + (data.stats.revenue || 0).toLocaleString();
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// Fetch recent orders
async function loadRecentOrders() {
    try {
        const response = await fetch('../backend/api/admin_api.php?action=orders_list');
        const data = await response.json();
        
        const tbody = document.getElementById('orders-tbody');
        
        if (data.success && data.orders && data.orders.length > 0) {
            const recentOrders = data.orders.slice(0, 5);
            
            tbody.innerHTML = recentOrders.map(order => `
                <tr>
                    <td>${order.order_number || 'ORD-' + order.id}</td>
                    <td>${order.customer_name || 'N/A'}</td>
                    <td>${order.created_at ? new Date(order.created_at).toLocaleDateString() : 'N/A'}</td>
                    <td>₱${parseFloat(order.total || 0).toLocaleString()}</td>
                    <td><span class="status-badge ${order.status || 'pending'}">${order.status || 'Pending'}</span></td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">No orders found</td></tr>';
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        document.getElementById('orders-tbody').innerHTML = '<tr><td colspan="5" class="text-center text-muted">Error loading orders</td></tr>';
    }
}

// Load data on page load
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardStats();
    loadRecentOrders();
});
</script>

</body>
</html>
