<?php
session_start();
include "./config/db.php";
include "./includes/header.php";
$category = $conn->query("SELECT * FROM `categories` ORDER BY id DESC");
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  $name = $_POST['name'];
  $category = $_POST['category_id'];
  $price = $_POST['price'];
  $stock = $_POST['stock'];
  $sql = "INSERT INTO `products`(`category_id`, `name`, `price`, `stock`, `created_at`) VALUES ('$category','$name','$price','$stock', now())";
  if(mysqli_query($conn, $sql)){
    header("location: products.php");
  }else{
    echo "not success";
  }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
    <div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm">

      <div class="card-header bg-white p-4 d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
          <i class="fa-solid fa-box-open fa-xl"></i>
        </div>
        <div>
          <h3 class="mb-1 fw-bold">Add Product</h3>
          <p class="text-muted mb-0">Enter product information below</p>
        </div>
      </div>

      <div class="card-body p-4">
        <!-- No category warning -->
        <div class="alert alert-warning border-0">if doesn't have category <a href="./categories.php">Create category</a> first</div>

        <form method="POST">

          <div class="mb-4">
            <label class="form-label fw-semibold">Product name <span class="text-danger">*</span></label>
            <input class="form-control" name="name" maxlength="100" placeholder="Enter product name" required>
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
            <select class="form-select" name="category_id" required>
              <option value="">Choose category</option>
              <?php while ($row =$category->fetch_assoc()): ?>
              <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
              <?php endwhile; ?>
            </select>
          </div>

          <div class="row">
            <div class="col-md-6 mb-4">
              <label class="form-label fw-semibold">Price ($) <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input class="form-control" type="number" name="price" min="0" step="0.01" placeholder="0.00" required>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <label class="form-label fw-semibold">Stock quantity <span class="text-danger">*</span></label>
              <input class="form-control" type="number" name="stock" min="0" step="1" placeholder="Enter quantity" required>
            </div>
          </div>

          <div class="d-flex flex-wrap gap-2 mt-2">
            <button class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Save Product</button>
            <a href="products.php" class="btn btn-outline-secondary"><i class="fa-solid fa-xmark me-1"></i>Cancel</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
<?php include "./includes/footer.php"; ?>