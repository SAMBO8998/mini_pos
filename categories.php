<?php
require_once __DIR__ . '/config/db.php'; require_once __DIR__ . '/includes/functions.php'; require_login();
if (isset($_GET['delete'])) {
    $id=(int)$_GET['delete'];
    try { $stmt=$pdo->prepare("DELETE FROM categories WHERE id=?"); $stmt->execute([$id]); $_SESSION['flash']='Category deleted.'; }
    catch (PDOException $e) { $_SESSION['flash']='Cannot delete a category that still has products.'; }
    header('Location: categories.php'); exit;
}
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name=trim($_POST['name']??'');
    if ($name!=='') { try {$stmt=$pdo->prepare("INSERT INTO categories(name) VALUES(?)");$stmt->execute([$name]);$_SESSION['flash']='Category added.';} catch(PDOException $e){$_SESSION['flash']='Category name already exists.';} }
    header('Location: categories.php'); exit;
}
$rows=$pdo->query("SELECT c.*, COUNT(p.id) product_count FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id ORDER BY c.id DESC")->fetchAll();
$pageTitle='Categories'; include __DIR__.'/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3"><h2>Categories</h2></div>
<?php if(!empty($_SESSION['flash'])): ?><div class="alert alert-info"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
<div class="row g-3"><div class="col-lg-4"><div class="card shadow-sm border-0"><div class="card-body"><h5>Add Category</h5><form method="post"><label class="form-label">Category name</label><input class="form-control mb-3" name="name" required maxlength="150"><button class="btn btn-primary">Add Category</button></form></div></div></div>
<div class="col-lg-8"><div class="card shadow-sm border-0"><div class="table-responsive"><table class="table table-hover mb-0"><thead class="table-light"><tr><th>ID</th><th>Name</th><th>Products</th><th>Created</th><th>Action</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= (int)$r['id'] ?></td><td><?= e($r['name']) ?></td><td><?= (int)$r['product_count'] ?></td><td><?= e($r['created_at']) ?></td><td><a class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this category?')" href="?delete=<?= (int)$r['id'] ?>">Delete</a></td></tr><?php endforeach; ?>
<?php if(!$rows): ?><tr><td colspan="5" class="text-center text-muted">No categories yet.</td></tr><?php endif; ?></tbody></table></div></div></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
