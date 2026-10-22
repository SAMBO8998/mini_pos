<?php
require_once __DIR__ . '/config/db.php'; require_once __DIR__ . '/includes/functions.php'; require_login();
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $quantities=$_POST['qty']??[];
  $items=[];
  foreach($quantities as $pid=>$qty){$pid=(int)$pid;$qty=filter_var($qty,FILTER_VALIDATE_INT);if($pid>0&&$qty!==false&&$qty>0)$items[$pid]=$qty;}
  if(!$items){$error='Choose at least one product and quantity.';}
  else{
    try{
      $pdo->beginTransaction();$total=0;$validated=[];
      $get=$pdo->prepare("SELECT id,name,price,stock FROM products WHERE id=? FOR UPDATE");
      foreach($items as $pid=>$qty){$get->execute([$pid]);$p=$get->fetch();if(!$p)throw new Exception('A product could not be found.');if($qty>(int)$p['stock'])throw new Exception('Not enough stock for '.$p['name'].'. Available: '.$p['stock']);$validated[]=['product'=>$p,'qty'=>$qty];$total+=(float)$p['price']*$qty;}
      $receipt='MM-'.date('Ymd-His').'-'.random_int(100,999);
      $s=$pdo->prepare("INSERT INTO orders(receipt_no,user_id,total) VALUES(?,?,?)");$s->execute([$receipt,$_SESSION['user']['id'],$total]);$orderId=(int)$pdo->lastInsertId();
      $itemInsert=$pdo->prepare("INSERT INTO order_items(order_id,product_id,price,quantity) VALUES(?,?,?,?)");
      $stockUpdate=$pdo->prepare("UPDATE products SET stock=stock-? WHERE id=?");
      foreach($validated as $v){$itemInsert->execute([$orderId,$v['product']['id'],$v['product']['price'],$v['qty']]);$stockUpdate->execute([$v['qty'],$v['product']['id']]);}
      $pdo->commit();header('Location: order_view.php?id='.$orderId.'&new=1');exit;
    }catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error=$e->getMessage();}
  }
}
$q=trim($_GET['q']??'');$sql="SELECT p.id,p.name,p.price,p.stock,c.name category_name FROM products p JOIN categories c ON c.id=p.category_id WHERE p.stock>0";$args=[];
if($q!==''){$sql.=" AND (p.name LIKE ? OR c.name LIKE ?)";$args=["%$q%","%$q%"];}
$sql.=" ORDER BY p.name";$s=$pdo->prepare($sql);$s->execute($args);$products=$s->fetchAll();
$pageTitle='POS / Sell';include __DIR__.'/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3"><div><h2 class="mb-1">Point of Sale</h2><p class="text-muted mb-0">Select products and enter quantities.</p></div><a href="orders.php" class="btn btn-outline-secondary">Order History</a></div>
<?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="get" class="row g-2 mb-3"><div class="col-md-5"><input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Search product or category"></div><div class="col-auto"><button class="btn btn-outline-primary">Search</button><a class="btn btn-outline-secondary" href="pos.php">Reset</a></div></form>
<form method="post"><div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>Product</th><th>Category</th><th>Price</th><th>In stock</th><th style="width:150px">Quantity to sell</th></tr></thead><tbody>
<?php foreach($products as $p): ?><tr><td class="fw-semibold"><?= e($p['name']) ?></td><td><?= e($p['category_name']) ?></td><td><?= money($p['price']) ?></td><td><?= (int)$p['stock'] ?></td><td><input class="form-control qty-input" type="number" name="qty[<?= (int)$p['id'] ?>]" min="0" max="<?= (int)$p['stock'] ?>" value="0" data-price="<?= e($p['price']) ?>"></td></tr><?php endforeach; ?>
<?php if(!$products): ?><tr><td colspan="5" class="text-center text-muted py-4">No products in stock. Add products first.</td></tr><?php endif; ?></tbody></table></div><div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3"><div><span class="text-muted">Estimated total:</span> <strong class="fs-4" id="estimated-total">$0.00</strong><div class="small text-muted">Total is recalculated on the server when you submit.</div></div><button class="btn btn-success btn-lg" <?= !$products?'disabled':'' ?>><i class="fa-solid fa-cash-register me-2"></i>Complete Sale</button></div></div></form>
<script>
const inputs = document.querySelectorAll('.qty-input');
function updateTotal(){let total=0;inputs.forEach(input=>{total+=(Number(input.value)||0)*Number(input.dataset.price||0)});document.getElementById('estimated-total').textContent='$'+total.toFixed(2);}
inputs.forEach(input=>input.addEventListener('input',updateTotal));
</script>
<?php include __DIR__.'/includes/footer.php'; ?>
