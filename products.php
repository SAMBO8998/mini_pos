<?php
require_once __DIR__ . '/config/db.php'; require_once __DIR__ . '/includes/functions.php'; require_login();
if(isset($_GET['delete'])) {
  $id=(int)$_GET['delete'];
  try {$s=$pdo->prepare("DELETE FROM products WHERE id=?");$s->execute([$id]);$_SESSION['flash']='Product deleted.';}
  catch(PDOException $e){$_SESSION['flash']='Cannot delete a product that is already used in an order.';}
  header('Location: products.php');exit;
}
$q=trim($_GET['q']??'');
$sql="SELECT p.*,c.name category_name FROM products p JOIN categories c ON c.id=p.category_id";
$args=[];
if($q!==''){$sql.=" WHERE p.name LIKE ? OR c.name LIKE ?";$args=["%$q%","%$q%"];}
$sql.=" ORDER BY p.id DESC";
$stmt=$pdo->prepare($sql);$stmt->execute($args);$rows=$stmt->fetchAll();
$pageTitle='Products';include __DIR__.'/includes/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3"><h2 class="mb-0">Products</h2><a href="product_form.php" class="btn btn-primary"><i class="fa-solid fa-plus me-1"></i>Add Product</a></div>
<?php if(!empty($_SESSION['flash'])): ?><div class="alert alert-info"><?= e($_SESSION['flash']);unset($_SESSION['flash']); ?></div><?php endif; ?>
<form class="row g-2 mb-3"><div class="col-md-5"><input class="form-control" name="q" value="<?= e($q) ?>" placeholder="Search product or category"></div><div class="col-auto"><button class="btn btn-outline-primary">Search</button><a class="btn btn-outline-secondary" href="products.php">Reset</a></div></form>
<div class="card border-0 shadow-sm"><div class="table-responsive"><table class="table table-hover align-middle mb-0"><thead class="table-light"><tr><th>ID</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Created</th><th>Actions</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= (int)$r['id'] ?></td><td><?= e($r['name']) ?></td><td><span class="badge text-bg-secondary"><?= e($r['category_name']) ?></span></td><td><?= money($r['price']) ?></td><td><?php if((int)$r['stock']<=5): ?><span class="text-danger fw-bold"><?= (int)$r['stock'] ?></span><?php else: ?><?= (int)$r['stock'] ?><?php endif; ?></td><td><?= e($r['created_at']) ?></td><td class="text-nowrap"><a class="btn btn-sm btn-outline-primary" href="product_form.php?id=<?= (int)$r['id'] ?>">Edit</a> <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product?')" href="?delete=<?= (int)$r['id'] ?>">Delete</a></td></tr><?php endforeach; ?>
<?php if(!$rows): ?><tr><td colspan="7" class="text-center text-muted py-4">No products found. Add a category first.</td></tr><?php endif; ?></tbody></table></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
