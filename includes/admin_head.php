<?php
// Shared head for admin pages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($pageTitle)) $pageTitle = 'Admin';
if (!isset($base)) $base = '../';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?= htmlspecialchars($pageTitle) ?> — INFURNEST Admin</title>

<link rel="preconnect" href="https://fonts.googleapis.com"/>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet"/>
<link rel="stylesheet" href="<?= $base ?>assets/styles.css"/>

<style>
.admin-sidebar {
    background: var(--brown);
    min-height: 100vh;
    padding: 20px;
}
.admin-sidebar a {
    color: rgba(255,255,255,0.7);
    display: block;
    padding: 12px 16px;
    border-radius: 8px;
    text-decoration: none;
    margin-bottom: 4px;
    transition: all 0.2s;
}
.admin-sidebar a:hover, .admin-sidebar a.active {
    background: rgba(255,255,255,0.15);
    color: white;
}
.admin-content {
    padding: 30px;
}
.table-admin {
    background: white;
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: var(--shadow);
}
.table-admin th {
    background: var(--brown);
    color: white;
    padding: 14px 16px;
    font-weight: 600;
}
.table-admin td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--blush);
}
.stat-card {
    background: white;
    border-radius: var(--radius);
    padding: 24px;
    box-shadow: var(--shadow);
}
.stat-card h3 {
    font-family: 'Playfair Display', serif;
    font-size: 2.5rem;
    font-weight: 900;
    color: var(--brown);
    margin-bottom: 4px;
}
.stat-card p {
    color: var(--text-light);
    font-size: 0.85rem;
}
.stat-card .stat-icon {
    font-size: 2rem;
    color: var(--amber);
}
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}
.status-badge.active, .status-badge.delivered {
    background: rgba(122,158,126,0.15);
    color: var(--sage);
}
.status-badge.pending {
    background: rgba(232,146,42,0.15);
    color: var(--amber-dark);
}
.status-badge.processing {
    background: rgba(59,130,246,0.15);
    color: #3B82F6;
}
.status-badge.shipped {
    background: rgba(139,92,43,0.15);
    color: #8B5A2B;
}
.status-badge.cancelled {
    background: rgba(232,74,42,0.15);
    color: #E84A2A;
}
.role-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}
.role-badge.admin {
    background: rgba(232,146,42,0.15);
    color: var(--amber-dark);
}
.role-badge.customer {
    background: rgba(122,158,126,0.15);
    color: var(--sage);
}
.action-btn {
    background: none;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    padding: 4px 8px;
    transition: color 0.2s;
}
.action-btn:hover { color: var(--amber); }
.action-btn.delete:hover { color: #E84A2A; }
.modal-admin {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 9999;
    align-items: center;
    justify-content: center;
}
.modal-admin.show { display: flex; }
.modal-content-admin {
    background: white;
    border-radius: var(--radius);
    padding: 30px;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}
</style>
</head>
<body>
