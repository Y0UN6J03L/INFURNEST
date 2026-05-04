<?php
/**
 * Admin Users Management
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

$pageTitle = 'Users';
$activePage = 'users';
$base = '../';
include '../includes/admin_head.php';
?>

<?php include '../includes/admin_navbar.php'; ?>

<!-- Users Management -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="font-family: 'Playfair Display', serif; color: var(--brown);">Users Management</h2>
    <button class="btn-primary-custom" onclick="showAddUserModal()">
        <i class="fas fa-user-plus"></i> Add User
    </button>
</div>

<!-- Filters -->
<div class="d-flex gap-3 mb-4 flex-wrap">
    <select class="sort-select" style="width: auto;" id="filter-role">
        <option value="">All Roles</option>
        <option value="admin">Admin</option>
        <option value="customer">Customer</option>
    </select>
    <select class="sort-select" style="width: auto;" id="filter-status">
        <option value="">All Status</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>
    <input type="text" placeholder="Search by name or email..." style="border: 1.5px solid var(--blush); border-radius: 9px; padding: 8px 14px; width: 250px;" id="search-users">
</div>

<!-- Users Table -->
<div class="table-admin">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="users-tbody">
            <tr>
                <td colspan="7" class="text-center text-muted">Loading users...</td>
            </tr>
        </tbody>
    </table>
</div>

</div>
</div>

<!-- Update Role Modal -->
<div class="modal-admin" id="roleModal">
    <div class="modal-content-admin">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 style="font-family: 'Playfair Display', serif; color: var(--brown);">Update User Role</h4>
            <button onclick="hideRoleModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <form>
            <input type="hidden" id="user-id">
            <div class="form-group-custom">
                <label>User Name</label>
                <input type="text" id="user-name" disabled>
            </div>
            <div class="form-group-custom">
                <label>Role</label>
                <select id="user-role">
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="d-flex gap-3">
                <button type="button" class="btn-primary-custom flex-grow-1" onclick="updateUserRole()">
                    <i class="fas fa-save"></i> Update Role
                </button>
                <button type="button" class="btn-outline-custom" onclick="hideRoleModal()" style="border-color: var(--blush); color: var(--brown);">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal-admin" id="addUserModal">
    <div class="modal-content-admin">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 style="font-family: 'Playfair Display', serif; color: var(--brown);">Add New User</h4>
            <button onclick="hideAddUserModal()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        <form id="addUserForm">
            <div class="d-flex gap-3">
                <div class="form-group-custom flex-grow-1">
                    <label>First Name <span style="color:#E84A2A">*</span></label>
                    <input type="text" id="new-first-name" placeholder="First name" required>
                </div>
                <div class="form-group-custom flex-grow-1">
                    <label>Last Name</label>
                    <input type="text" id="new-last-name" placeholder="Last name">
                </div>
            </div>
            <div class="form-group-custom">
                <label>Email <span style="color:#E84A2A">*</span></label>
                <input type="email" id="new-email" placeholder="email@example.com" required>
            </div>
            <div class="form-group-custom">
                <label>Phone</label>
                <input type="text" id="new-phone" placeholder="+63 9XX XXX XXXX">
            </div>
            <div class="form-group-custom">
                <label>Password <span style="color:#E84A2A">*</span></label>
                <input type="password" id="new-password" placeholder="Minimum 6 characters" required minlength="6">
            </div>
            <div class="form-group-custom">
                <label>Role</label>
                <select id="new-role">
                    <option value="customer">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div id="add-user-error" style="color:#E84A2A; font-size:0.875rem; margin-bottom:10px; display:none;"></div>
            <div class="d-flex gap-3">
                <button type="button" class="btn-primary-custom flex-grow-1" onclick="submitAddUser()">
                    <i class="fas fa-user-plus"></i> Add User
                </button>
                <button type="button" class="btn-outline-custom" onclick="hideAddUserModal()" style="border-color: var(--blush); color: var(--brown);">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script>
// Load users from API
async function loadUsers() {
    try {
        const response = await fetch('../backend/api/admin_api.php?action=users_list');
        const data = await response.json();
        
        const tbody = document.getElementById('users-tbody');
        
        if (data.success && data.users && data.users.length > 0) {
            tbody.innerHTML = data.users.map(user => `
                <tr>
                    <td>${user.id}</td>
                    <td>${user.first_name || ''} ${user.last_name || ''}</td>
                    <td>${user.email || 'N/A'}</td>
                    <td>${user.phone || 'N/A'}</td>
                    <td><span class="role-badge ${user.role || 'customer'}">${user.role || 'Customer'}</span></td>
                    <td>${user.created_at ? new Date(user.created_at).toLocaleDateString() : 'N/A'}</td>
                    <td>
                        <button style="background: none; border: none; color: var(--text-light); cursor: pointer; padding: 4px 8px;" title="View">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button style="background: none; border: none; color: var(--amber); cursor: pointer; padding: 4px 8px;" title="Edit" onclick="showRoleModal(${user.id}, '${user.first_name || ''} ${user.last_name || ''}', '${user.role}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button style="background: none; border: none; color: #E84A2A; cursor: pointer; padding: 4px 8px;" title="Delete" onclick="deleteUser(${user.id})">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        } else {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No users found</td></tr>';
        }
    } catch (error) {
        console.error('Error loading users:', error);
        document.getElementById('users-tbody').innerHTML = '<tr><td colspan="7" class="text-center text-muted">Error loading users</td></tr>';
    }
}

function showRoleModal(id, name, role) {
    document.getElementById('user-id').value = id;
    document.getElementById('user-name').value = name;
    document.getElementById('user-role').value = role || 'customer';
    document.getElementById('roleModal').classList.add('show');
}

function hideRoleModal() {
    document.getElementById('roleModal').classList.remove('show');
}

async function updateUserRole() {
    const id = document.getElementById('user-id').value;
    const role = document.getElementById('user-role').value;
    
    if (!id) {
        alert('User ID is required');
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'user_update');
    formData.append('id', id);
    formData.append('role', role);
    
    try {
        const response = await fetch('../backend/api/admin_api.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            hideRoleModal();
            loadUsers();
            alert('User updated successfully!');
        } else {
            alert(data.message || 'Error updating user');
        }
    } catch (error) {
        console.error('Error updating user:', error);
        alert('Error updating user');
    }
}

async function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) return;
    
    try {
        const formData = new FormData();
        formData.append('action', 'user_delete');
        formData.append('id', id);
        
        const response = await fetch('../backend/api/admin_api.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        
        if (data.success) {
            loadUsers();
            alert('User deleted successfully!');
        } else {
            alert(data.message || 'Error deleting user');
        }
    } catch (error) {
        console.error('Error deleting user:', error);
        alert('Error deleting user');
    }
}

// Add User Modal
function showAddUserModal() {
    document.getElementById('new-first-name').value = '';
    document.getElementById('new-last-name').value = '';
    document.getElementById('new-email').value = '';
    document.getElementById('new-phone').value = '';
    document.getElementById('new-password').value = '';
    document.getElementById('new-role').value = 'customer';
    document.getElementById('add-user-error').style.display = 'none';
    document.getElementById('addUserModal').classList.add('show');
}

function hideAddUserModal() {
    document.getElementById('addUserModal').classList.remove('show');
}

async function submitAddUser() {
    const firstName = document.getElementById('new-first-name').value.trim();
    const lastName  = document.getElementById('new-last-name').value.trim();
    const email     = document.getElementById('new-email').value.trim();
    const phone     = document.getElementById('new-phone').value.trim();
    const password  = document.getElementById('new-password').value;
    const role      = document.getElementById('new-role').value;
    const errorEl   = document.getElementById('add-user-error');

    if (!firstName) { showAddUserError('First name is required.'); return; }
    if (!email)     { showAddUserError('Email is required.'); return; }
    if (password.length < 6) { showAddUserError('Password must be at least 6 characters.'); return; }

    errorEl.style.display = 'none';

    const formData = new FormData();
    formData.append('action', 'user_create');
    formData.append('first_name', firstName);
    formData.append('last_name', lastName);
    formData.append('email', email);
    formData.append('phone', phone);
    formData.append('password', password);
    formData.append('role', role);

    try {
        const response = await fetch('../backend/api/admin_api.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();

        if (data.success) {
            hideAddUserModal();
            loadUsers();
            alert('User added successfully!');
        } else {
            showAddUserError(data.message || 'Error creating user.');
        }
    } catch (error) {
        console.error('Error creating user:', error);
        showAddUserError('Server error. Please try again.');
    }
}

function showAddUserError(msg) {
    const el = document.getElementById('add-user-error');
    el.textContent = msg;
    el.style.display = 'block';
}

// Filter functions
document.getElementById('filter-role').addEventListener('change', loadUsers);
document.getElementById('filter-status').addEventListener('change', loadUsers);

// Load users on page load
document.addEventListener('DOMContentLoaded', loadUsers);
</script>

</body>
</html>