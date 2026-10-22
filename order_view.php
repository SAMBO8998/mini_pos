<?php
require_once __DIR__ . '/config/db.php'; require_once __DIR__ . '/includes/functions.php'; require_login();
$id=(int)($_GET['id']??0);$s=$pdo->prepare("SELECT o.*,u.full_name FROM orders o JOIN users u ON u.id=o.user_id WHERE o.id=?");$s->execute([$id]);$order=$s->fetch();
if(!$order){http_response_code(404);exit('Order not found.');}
$s=$pdo->prepare("SELECT oi.price,oi.quantity,p.name FROM order_items oi JOIN products p ON p.id=oi.product_id WHERE oi.order_id=?");$s->execute([$id]);$items=$s->fetchAll();
$pageTitle='Receipt';include __DIR__.'/includes/header.php';
?>
<?php if(isset($_GET['new'])): ?><div class="alert alert-success"><i class="fa-solid fa-check me-2"></i>Sale completed successfully. Stock has been updated.</div><?php endif; ?>
<div class="row justify-content-center"><div class="col-lg-8"><div class="card border-0 shadow-sm"><div class="card-body p-4">
<div class="d-flex justify-content-between flex-wrap gap-3 mb-4"><div><h2 class="mb-1">Mini Mart Receipt</h2><div class="text-muted">Receipt: <?= e($order['receipt_no']) ?></div></div><div class="text-md-end"><div><strong>Date:</strong> <?= e($order['created_at']) ?></div><div><strong>Cashier:</strong> <?= e($order['full_name']) ?></div></div></div>
<div class="table-responsive"><table class="table"><thead class="table-light"><tr><th>Product</th><th>Price</th><th>Qty</th><th class="text-end">Subtotal</th></tr></thead><tbody><?php foreach($items as $it): ?><tr><td><?= e($it['name']) ?></td><td><?= money($it['price']) ?></td><td><?= (int)$it['quantity'] ?></td><td class="text-end"><?= money((float)$it['price']*(int)$it['quantity']) ?></td></tr><?php endforeach; ?></tbody><tfoot><tr><th colspan="3" class="text-end">Grand Total</th><th class="text-end fs-5"><?= money($order['total']) ?></th></tr></tfoot></table></div>
<div class="d-flex justify-content-between mt-4"><a href="orders.php" class="btn btn-outline-secondary">Back to Orders</a><button onclick="window.print()" class="btn btn-primary"><i class="fa-solid fa-print me-2"></i>Print Receipt</button></div>
</div></div></div></div>
<style>@media print { nav, .btn, .alert { display:none!important; } body { background:white!important; } .card { box-shadow:none!important; } }</style>
<?php include __DIR__.'/includes/footer.php'; ?>
