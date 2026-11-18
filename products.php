<?php
require_once "./includes/auth.php";
include "./config/db.php";
$result = $conn->query("SELECT * FROM `products` ORDER BY id DESC");
$result = $conn->query("
    SELECT products.*, categories.name AS category_name
    FROM products
    LEFT JOIN categories ON categories.id = products.category_id
    ORDER BY products.id DESC
");
include './includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h2 class="mb-0">Products</h2>
    <a href="product_form.php" class="btn btn-primary">+ Add Product</a>
  </div>

  <!-- Search -->
  <form class="col-6 mb-3">
    <div class="col-md-5 input-group">
      <input type="text" id="searchproducts" class="form-control border-black" placeholder="Seach name products and category...">
      <a class="btn btn-light border-black fs-5" href="products.php"><i class="fa-solid fa-clock-rotate-left"></i></a>
    </div>
  </form>

  <!-- Product list -->
  <div class="card border-0 shadow-sm">
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-nowrap border-secondary-subtle">
        <thead>
          <tr>
            <th>ID</th>
            <th>Product_Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Created</th>
            <th>Updated_at</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="productbody">
          <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= $row['name'] ?></td>
              <td><span class="badge text-bg-primary"><?= $row['category_name'] ?? 'No category' ?></span></td>
              <td><?= $row['price'] ?></td>
              <td><?= $row['stock'] ?></td>
              <td><?= $row['created_at'] ?></td>
              <td><?= $row['updated_at'] ?></td>
              <td class="text-nowrap">
                <a class="btn btn-sm btn-outline-primary" href="./edit_products.php?id=<?= $row['id'] ?>"><i class="fa-solid fa-pen"></i>Edit</a>
                <a class="btn btn-sm btn-outline-danger" href="./delete_products.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure')"><i class="fa-solid fa-trash"></i>Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
            <tr id="noproduct" style="display: none;">
            <td colspan="8" class="text-center bg-warning-subtle">No category found</td>
            </tr>
        </tbody>
      </table>
    </div>
  </div>
</body>
<script>
    document.getElementById("searchproducts").addEventListener("input", function(){
    let search = this.value.toLowerCase();
    let found = 0;
    document.querySelectorAll("#productbody tr:not(#noproduct)").forEach(function (row){
        let text = (row.cells[1].textContent + row.cells[2].textContent).toLowerCase();
        if (text.includes(search)) {
            row.style.display = "";
            found++;
        } else {
            row.style.display = "none";
        }
    });
    if(found === 0){
      document.getElementById('noproduct').style.display = "";
    }else{
      document.getElementById('noproduct').style.display = "none";
    }
});
</script>
</html>
