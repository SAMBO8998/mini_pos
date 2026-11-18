<?php
require_once "./includes/auth.php";
include "./config/db.php";
$id = (int)$_GET['id'];
$result = $conn->query("SELECT * FROM `products` WHERE id = $id");
$category = $conn->query("SELECT * FROM `categories`");
$product = mysqli_fetch_assoc($result);
if(isset($_POST['update'])){
   $category_id = (int)$_POST['category'];
   $name = $_POST['name'];
   $price = $_POST['price'];
   $stock = $_POST['stock'];
   $sql = "UPDATE `products` SET `category_id`='$category_id',`name`='$name',`price`='$price',`stock`='$stock' WHERE id = $id";

   if($conn->query($sql)){
      header("location: products.php");
      exit();
   }else{
      echo "Fail try again";
   }
}
include "./includes/header.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
</head>

<body>
   <div class="table border rounded-4 shadow-lg p-5 bg-white w-50">
   <h3 class="text-success fw-bold mb-3">Edit Products</h3>
   <form action="" method="POST">
      <label for="" class="text-danger">Name</label>
      <input value="<?= $product['name'] ?>" name="name" type="text" class="form-control border-black mb-2">

      <label for="" class="text-danger">Category</label>
      <select name="category" class="form-select border-black mb-2" required>
      <option value="">Choose category</option>
      <?php while ($row = $category->fetch_assoc()): ?>
      <!-- <option value=""></option> -->
      <option value="<?= $row['id'] ?>"<?= $row['id'] == $product['category_id'] ? ' selected' : '' ?>><?= $row['name'] ?></option>



      <?php endwhile; ?>
      </select>

      <!-- <input value="<?= $product['category_id'] ?>" name="category" type="text" class="form-control border-black mb-2"> -->
      <label for="" class="text-danger">Price</label>
      <input value="<?= $product['price'] ?>" name="price" type="text" class="form-control border-black mb-2">
      <label for="" class="text-danger">Stock</label>
      <input value="<?= $product['stock'] ?>" name="stock" type="text" class="form-control border-black mb-2"><hr>
      <a href="./products.php" class="btn btn-warning">Cancel</a>
      <button class="btn btn-primary" type="submit" name="update">update</button>

   </form>
   </div>

</body>

</html>
<?php include "./includes/footer.php"; ?>  