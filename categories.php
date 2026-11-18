<?php
session_start();
include "./config/db.php";
include "./includes/header.php";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $category = trim($_POST['category']);
  $sql = "INSERT INTO `categories`(`name`, `created_at`) VALUES ('$category', now())";
  if (mysqli_query($conn, $sql)) {
    $_SESSION['swal'] = true;
    header("location: categories.php");
    exit();
  } else {
    $status = "error";
    echo "not success" . mysqli_error($conn);
  }
}
// show data to table
$result = $conn->query("SELECT * FROM `categories` order by id DESC");

// sum catagory
$total_cate = $conn->query("SELECT COUNT(*) FROM categories")->fetch_row()[0];  //[0] យកតម្លៃទីមួយ

//sum product_by_category
$result = $conn->query("
    SELECT categories.*, COUNT(products.id) AS total_product
    FROM categories
    LEFT JOIN products ON categories.id = products.category_id
    GROUP BY categories.id
    ORDER BY categories.id DESC
");
?>
<!-- alert -->
<?php if (isset($_SESSION['swal'])): unset($_SESSION['swal']); ?>
  <script>
    Swal.fire({
      icon: "success",
      title: 'Category added',
      timer: 800,
      showConfirmButton: false
    });
  </script>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>

<body>
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
      <h2 class="mb-0 fw-bold">Categories</h2>
    </div>
    <span class="badge text-bg-primary fs-6"><?= $total_cate ?> : Categories</span>
  </div>
  <div class="row g-4">
    <!-- Add category -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white p-3 d-flex align-items-center gap-3">
          <div class="bg-primary bg-opacity-10 text-primary rounded p-2 px-3">
            <i class="fa-solid fa-tags"></i>
          </div>
          <h5 class="mb-0 fw-bold">Add Category</h5>
        </div>
        <div class="card-body p-3">
          <form method="POST">
            <label class="form-label fw-semibold">Category name <span class="text-danger">*</span></label>
            <input type="text" class="form-control mb-3 border-black" name="category" placeholder="" maxlength="150" required>
            <button class="btn btn-primary w-100"><i class="fa-solid fa-plus me-2"></i>Add Category</button>
          </form>
        </div>
      </div>
    </div>

    <!-- Category list -->
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white p-3">
          <h5 class="mb-0 fw-bold">All Categories</h5>
        </div>
        <div class="input-group">
        <input type="text" id="searchinput" class="form-control border-black" placeholder="Search category name...">
        <a href="./categories.php" class="btn btn-light border-black fs-5"><i class="fa-solid fa-clock-rotate-left"></i></a>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered align-middle text-nowrap border-secondary-subtle">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th class="text-center">Products</th>
                <th>Created</th>
                <th>Updated_at</th>
                <th class="text-end">Action</th>
              </tr>
            </thead>
            <tbody id="categorybody">
              <?php while ($rows = $result->fetch_assoc()): ?>
                <tr>
                  <td class="text-muted"><?= $rows['id'] ?></td>
                  <td class="fw-semibold"><?= $rows['name'] ?></td>
                  <td class="text-center"><span class="badge text-bg-secondary"><?= $rows['total_product'] ?></span></td>
                  <td class="text-muted small"><?= $rows['created_at'] ?></td>
                  <td class="text-muted small"><?= $rows['updated_at'] ?></td>
                  <td class="text-end text-nowrap">
                    <a class="btn btn-sm btn-outline-primary" href="./edit_category.php?id=<?= $rows['id'] ?>"><i class="fa-solid fa-pen"></i>Edit</a>
                    <a class="btn btn-sm btn-outline-danger" href="./delete_category.php?id=<?= $rows['id'] ?>"
                      onclick="return confirm('Are you sure')"><i class="fa-solid fa-trash"></i>Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
              <tr id="noCategory" style="display: none;">
              <td colspan="6" class="text-center">No category found</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- search -->
  <script>
    document.getElementById("searchinput").addEventListener("input", function(){
    let search = this.value.toLowerCase();
    let found = 0;
    document.querySelectorAll("#categorybody tr:not(#noCategory)").forEach(function (row){
        let text = row.cells[1].textContent.toLowerCase();
        if (text.includes(search)) {
            row.style.display = "";
            found++;
        } else {
            row.style.display = "none";
        }
    });
    if(found === 0){
      document.getElementById('noCategory').style.display = "";
    }else{
      document.getElementById('noCategory').style.display = "none";
    }
});
  </script>
</body>
</html>
<?php include "./includes/footer.php"; ?>