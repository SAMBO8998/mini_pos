<?php
require_once __DIR__ . '/config/db.php'; require_once __DIR__ . '/includes/functions.php'; require_login();
$q=trim($_GET['q']??'');$sql="SELECT o.*,u.full_name FROM orders o JOIN users u ON u.id=o.user_id";$args=[];
if($q!==''){$sql.=" WHERE o.receipt_no LIKE ? OR u.full_name LIKE ?";$args=["%$q%","%$q%"];}
$sql.=" ORDER BY o.id DESC";$s=$pdo->prepare($sql);$s->execute($args);$orders=$s->fetchAll();
$pageTitle='Orders';include __DIR__.'/includes/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"><h2 class="mb-0">Orders</h2><a href="pos.php" class="btn btn-primary">New Sale</a></div>
<form class="row g-2 mb-3"><div class="col-md-5"><input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Search receipt or cashier"></div><div class="col-auto"><button class="btn btn-outline-primary">Search</button><a class="btn btn-outline-secondary" href="orders.php">Reset</a></div></form>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover mb-0"><thead class="table-light"><tr><th>#</th><th>Receipt No.</th><th>Cashier</th><th>Total</th><th>Date</th><th>Action</th></tr></thead><tbody>
<?php foreach($orders as $o): ?><tr><td><?= (int)$o['id'] ?></td><td><?= e($o['receipt_no']) ?></td><td><?= e($o['full_name']) ?></td><td class="fw-semibold"><?= money($o['total']) ?></td><td><?= e($o['created_at']) ?></td><td><a class="btn btn-sm btn-outline-primary" href="order_view.php?id=<?= (int)$o['id'] ?>">View receipt</a></td></tr><?php endforeach; ?>
<?php if(!$orders): ?><tr><td colspan="6" class="text-center text-muted py-4">No orders found.</td></tr><?php endif; ?></tbody></table></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
