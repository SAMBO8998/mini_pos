<?php
require_once "./includes/auth.php";
include "./config/db.php";
$total_order = $conn->query("SELECT COUNT(*) as total FROM orders");
$result = mysqli_fetch_assoc($total_order);
$result_sale = $conn->query("
    SELECT orders.*, users.username
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
");
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
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
  <div>
    <h2 class="mb-0 fw-bold">Orders</h2>
  </div>
  <div class="d-flex align-items-center gap-2">
    <span class="badge text-bg-primary fs-6"><?= $result['total'] ?> orders</span>
    <a href="pos.php" class="btn btn-primary"><i class="fa-solid fa-cart-shopping me-2"></i>New Sale</a>
  </div>
</div>

<!-- Search -->
<div class="card border-0 shadow-sm mb-3 shadow-md">
    <form class="row g-2">
      <div class="input-group w-50">
        <input id="searchinput" class="form-control border-black  " placeholder="Search Receipt_No and Cashier">
        <a class="btn btn-light border-black" href="orders.php"><i class="fa-solid fa-clock-rotate-left"></i></a>
      </div>
    </form>
  </div>

<!-- Order list -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white p-3">
    <h5 class="mb-0 fw-bold">Order History</h5>
  </div>
  <div class="table-responsive">
    <table class="table table-bordered align-middle text-nowrap border-secondary-subtle">
      <thead>
        <tr>
          <th>id</th>
          <th>Receipt No.</th>
          <th>Cashier</th>
          <th>Total</th>
          <th>Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="orderinput">
        <?php while($rows = $result_sale->fetch_assoc()):?>
        <tr>
          <td class="text-secondary"><?= $rows['id'] ?></td>
          <td><span class="badge text-bg-secondary"><?= $rows['receipt_no'] ?></span></td>
          <td><i class="fa-solid fa-user text-muted me-2"></i><?= $rows['username'] ?></td>
          <td class="fw-bold text-success">$ <?= $rows['total'] ?></td>
          <td class="text-muted small"><?= $rows['created_at'] ?></td>
          <td>
          <a class="btn btn-sm btn-outline-primary" href="./order_view.php?id=<?= $rows['id'] ?>"><i class="fa-solid fa-receipt me-1"></i>View receipt</a>
          </td>
        </tr>
        <?php endwhile; ?>
        <!-- Empty state -->
        <!-- <tr>
          <td colspan="6" class="text-center text-muted py-5">
            <i class="fa-solid fa-receipt fa-2x mb-2 d-block"></i>No orders found.
          </td>
        </tr> -->
      </tbody>
    </table>
  </div>
</div>
</body>
  <script>
    const searchcate = document.getElementById('searchinput');
    const row_s = document.querySelectorAll("#orderinput tr");
    searchcate.addEventListener("input", function() {
      let found = 0;
      const search = searchcate.value.toLowerCase();
      row_s.forEach(function(row) {
        const name = (row.cells[1].textContent + row.cells[2].textContent).toLowerCase();
        if (name.includes(search)) {
          row.style.display = "";
          found++;
        } else {
          row.style.display = "none";
        }
      });
      let products = document.getElementById('noOrder');
      if(found === 0){
        if(products == null){
          document.getElementById('orderinput').innerHTML += '<tr id="noOrder"><td colspan="6" class="bg-warning text-center">No orders found</td></tr>';
        }
      }else{
        if(products != null){
          products.remove();
        }
      }
    })
  </script>
</html>
<?php include "./includes/footer.php"; ?>