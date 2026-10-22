<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_login();
$pageTitle = 'Dashboard';
$products = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$categories = (int)$pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
$orders = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$revenue = (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM orders")->fetchColumn();
$recent = $pdo->query("SELECT o.id,o.receipt_no,o.total,o.created_at,u.full_name FROM orders o JOIN users u ON u.id=o.user_id ORDER BY o.id DESC LIMIT 8")->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4"><div><h2 class="mb-1">Dashboard</h2><p class="text-muted mb-0">Welcome to your Mini Mart management system.</p></div><a href="pos.php" class="btn btn-primary"><i class="fa-solid fa-cart-shopping me-2"></i>New Sale</a></div>
<div class="row g-3 mb-4">
<?php foreach ([['Products',$products,'fa-box','primary'],['Categories',$categories,'fa-tags','success'],['Orders',$orders,'fa-receipt','warning'],['Total Revenue',money($revenue),'fa-sack-dollar','info']] as $c): ?>
<div class="col-6 col-xl-3"><div class="card border-0 shadow-sm h-100"><div class="card-body d-flex justify-content-between align-items-center"><div><div class="text-muted small"><?= e($c[0]) ?></div><h3 class="mb-0"><?= e($c[1]) ?></h3></div><div class="rounded-circle bg-<?= e($c[3]) ?> bg-opacity-10 text-<?= e($c[3]) ?> p-3"><i class="fa-solid <?= e($c[2]) ?> fa-lg"></i></div></div></div></div>
<?php endforeach; ?>
</div>
<div class="card border-0 shadow-sm"><div class="card-header bg-white d-flex justify-content-between"><strong>Recent Orders</strong><a href="orders.php">View all</a></div><div class="table-responsive"><table class="table table-hover mb-0"><thead class="table-light"><tr><th>Receipt</th><th>Cashier</th><th>Total</th><th>Date</th><th></th></tr></thead><tbody>
<?php if (!$recent): ?><tr><td colspan="5" class="text-center text-muted py-4">No orders yet.</td></tr><?php endif; ?>
<?php foreach ($recent as $r): ?><tr><td><?= e($r['receipt_no']) ?></td><td><?= e($r['full_name']) ?></td><td><?= money($r['total']) ?></td><td><?= e($r['created_at']) ?></td><td><a class="btn btn-sm btn-outline-primary" href="order_view.php?id=<?= (int)$r['id'] ?>">Details</a></td></tr><?php endforeach; ?>
</tbody></table></div></div>
<?php include __DIR__ . '/includes/footer.php'; ?>
