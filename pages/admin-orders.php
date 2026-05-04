<?php
/**
 * Admin Orders Management
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

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

$pageTitle = 'Orders';
$activePage = 'orders';
$base = '../';
include '../includes/admin_head.php';
?>

<?php include '../includes/admin_navbar.php'; ?>

<!-- Orders Management -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="font-family: 'Playfair Display', serif; color: var(--brown);">Orders Management</h2>
</div>

<!-- Filters -->
<div class="d-flex gap-3 mb-4 flex-wrap">
    <select class="sort-select" style="width: auto;" id="filter-status">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="processing">Processing</option>
        <option value="shipped">Shipped</option>
        <option value="delivered">Delivered</option>
        <option value="cancelled">Cancelled</option>
    </select>
    <input type="date" style="border: 1.5px solid var(--blush); border-radius: 9px; padding: 8px 14px;" id="filter-date">
    <input type="text" placeholder="Search order # or customer..." style="border: 1.5px solid var(--blush); border-radius: 9px; padding: 8px 14px; width: 250px;" id="search-orders">
</div>

<!-- Orders Table -->
<div class="table-admin">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Items</th>
                <th>Total</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="orders-tbody">
            <tr>
                <td colspan="7" class="text-center text-muted">Loading orders...</td>
            </tr>
        </tbody>
    </table>
</div>

</div>
</div>

<!-- Update Status Modal -->
<div class="modal-admin" id="statusModal">
    <div class="modal-content-admin">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 style="font-family: 'Playfair Display', serif; color: var(--brown);">Update Order Status</h4>
            <button onclick="hideStatusModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <form>
            <input type="hidden" id="order-id">
            <div class="form-group-custom">
                <label>Order Number</label>
                <input type="text" id="order-number" disabled>
            </div>
            <div class="form-group-custom">
                <label>Status</label>
                <select id="order-status">
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div class="d-flex gap-3">
                <button type="button" class="btn-primary-custom flex-grow-1" onclick="updateOrderStatus()">
                    <i class="fas fa-save"></i> Update Status
                </button>
                <button type="button" class="btn-outline-custom" onclick="hideStatusModal()" style="border-color: var(--blush); color: var(--brown);">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script>
// Load orders from API
async function loadOrders() {
    try {
        const response = await fetch('../backend/api/admin_api.php?action=orders_list');
        const data = await response.json();
        
        const tbody = document.getElementById('orders-tbody');
        
        if (data.success && data.orders && data.orders.length > 0) {
            tbody.innerHTML = data.orders.map(order => `
                <tr>
                    <td>${order.order_number || 'ORD-' + order.id}</td>
                    <td>${order.customer_name || 'N/A'}</td>
                    <td>${order.items_count || 0} item(s)</td>
                    <td>₱${parseFloat(order.total || 0).toLocaleString()}</td>
                    <td>${order.created_at ? new Date(order.created_at).toLocaleDateString() : 'N/A'}</td>
                    <td><span class="status-badge ${order.status || 'pending'}">${order.status || 'Pending'}</span></td>
                    <td>
                        <button class="action-btn" style="background: none; border: none; color: var(--text-light); cursor: pointer; padding: 4px 8px;" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="action-btn" style="background: none; border: none; color: var(--amber); cursor: pointer; padding: 4px 8px;" title="Update Status" onclick="showStatusModal(${order.id}, '${order.order_number || 'ORD-' + order.id}', '${order.status}')">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No orders found</td></tr>';
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        document.getElementById('orders-tbody').innerHTML = '<tr><td colspan="7" class="text-center text-muted">Error loading orders</td></tr>';
    }
}

function showStatusModal(id, orderNumber, status) {
    document.getElementById('order-id').value = id;
    document.getElementById('order-number').value = orderNumber;
    document.getElementById('order-status').value = status || 'pending';
    document.getElementById('statusModal').classList.add('show');
}

function hideStatusModal() {
    document.getElementById('statusModal').classList.remove('show');
}

async function updateOrderStatus() {
    const id = document.getElementById('order-id').value;
    const status = document.getElementById('order-status').value;
    
    if (!id || !status) {
        alert('Order ID and status are required');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'order_update');
    formData.append('id', id);
    formData.append('status', status);
    
    try {
        const response = await fetch('../backend/api/admin_api.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            hideStatusModal();
            loadOrders();
            alert('Order status updated successfully!');
        } else {
            alert(data.message || 'Error updating order');
        }
    } catch (error) {
        console.error('Error updating order:', error);
        alert('Error updating order');
    }
}

// Filter functions
document.getElementById('filter-status').addEventListener('change', loadOrders);

// Load orders on page load
document.addEventListener('DOMContentLoaded', loadOrders);
</script>

</body>
</html>
