<?php
require_once __DIR__ . '/config/db.php'; require_once __DIR__ . '/includes/functions.php'; require_login();
$id=(int)($_GET['id']??0);$editing=$id>0;$error='';
$product=['name'=>'','category_id'=>'','price'=>'','stock'=>''];
if($editing){$s=$pdo->prepare("SELECT * FROM products WHERE id=?");$s->execute([$id]);$found=$s->fetch();if(!$found){header('Location: products.php');exit;}$product=$found;}
$categories=$pdo->query("SELECT id,name FROM categories ORDER BY name")->fetchAll();
if($_SERVER['REQUEST_METHOD']==='POST'){
  $product['name']=trim($_POST['name']??'');$product['category_id']=(int)($_POST['category_id']??0);
  $product['price']=$_POST['price']??'';$product['stock']=$_POST['stock']??'';
  if($product['name']===''||$product['category_id']<1||!is_numeric($product['price'])||(float)$product['price']<0||filter_var($product['stock'],FILTER_VALIDATE_INT)===false||(int)$product['stock']<0){$error='Please enter valid product information.';}
  else {if($editing){$s=$pdo->prepare("UPDATE products SET name=?,category_id=?,price=?,stock=? WHERE id=?");$s->execute([$product['name'],$product['category_id'],$product['price'],$product['stock'],$id]);}
  else{$s=$pdo->prepare("INSERT INTO products(name,category_id,price,stock) VALUES(?,?,?,?)");$s->execute([$product['name'],$product['category_id'],$product['price'],$product['stock']]);}
  header('Location: products.php');exit;}
}
$pageTitle=$editing?'Edit Product':'Add Product';include __DIR__.'/includes/header.php';
?>
<div class="row justify-content-center"><div class="col-lg-7"><div class="card border-0 shadow-sm"><div class="card-body p-4"><h2 class="mb-4"><?= e($pageTitle) ?></h2>
<?php if($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<?php if(!$categories): ?><div class="alert alert-warning">Please <a href="categories.php">create a category</a> first.</div><?php endif; ?>
<form method="post"><div class="mb-3"><label class="form-label">Product name</label><input class="form-control" name="name" maxlength="100" value="<?= e($product['name']) ?>" required></div>
<div class="mb-3"><label class="form-label">Category</label><select class="form-select" name="category_id" required><option value="">Choose category</option><?php foreach($categories as $c): ?><option value="<?= (int)$c['id'] ?>" <?= (int)$product['category_id']===(int)$c['id']?'selected':'' ?>><?= e($c['name']) ?></option><?php endforeach; ?></select></div>
<div class="row"><div class="col-md-6 mb-3"><label class="form-label">Price ($)</label><input class="form-control" type="number" name="price" min="0" step="0.01" value="<?= e($product['price']) ?>" required></div><div class="col-md-6 mb-3"><label class="form-label">Stock quantity</label><input class="form-control" type="number" name="stock" min="0" step="1" value="<?= e($product['stock']) ?>" required></div></div>
<button class="btn btn-primary" <?= !$categories?'disabled':'' ?>>Save Product</button> <a href="products.php" class="btn btn-outline-secondary">Cancel</a></form></div></div></div></div>
<?php include __DIR__.'/includes/footer.php'; ?>
