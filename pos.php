<?php
require_once "./includes/auth.php";
include "./config/db.php";
$total = 0;
$items = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  // ===== ផ្នែកទី 1: ពិនិត្យស្តុក និងគណនាតម្លៃសរុប =====
  foreach($_POST['qty'] as $product_id => $quanlity){
    if($quanlity <= 0) continue;
    $row = $conn->query("SELECT price, stock FROM products where id = $product_id")->fetch_assoc();
    if($quanlity > $row['stock']){
      exit ("Not enough stock");
    }
    $total += $row['price'] * $quanlity;
    $items[$product_id] = ['qty' => $quanlity, 'price' => $row['price']];
  }
  if($total <= 0){
    echo '<a href="./pos.php">Back to sale</a>';
    echo "<hr>";
    exit("Please select products");
  }
  // ===== ផ្នែកទី 2: បង្កើតការលក់ insert to (order) =====
  $user_id = $_SESSION['user_id'];
  date_default_timezone_set('Asia/Phnom_Penh');
  $now = date("Ymd-His");   //Year,month,day,hours,minutes,second ករណីuserinsertពេលតែមួយ $receipt_no = "POS-" . date("Ymd-His") . "-" . random_int(100, 999);
  $receipt_no = "POS-$now";
  $conn->query("INSERT INTO orders (receipt_no, user_id, total) VALUES ('$receipt_no','$user_id','$total')");
  $order_id = $conn->insert_id;
  // ===== ផ្នែកទី 3: រក្សាទុកទំនិញ និងកាត់ស្តុក =====
  foreach($items as $product_id => $data){
    // បញ្ចូលទំនិញក្នុង order_items
    $conn->query("INSERT INTO order_items (order_id, product_id, price, quantity) VALUES ('$order_id','$product_id','{$data['price']}','{$data['qty']}')");
    // កាត់ស្តុក
    $conn->query("UPDATE products SET stock = stock - {$data['qty']} WHERE id = $product_id");
  }  
  header("location: pos.php");
  exit;
}

$category = $conn->query("SELECT * FROM `categories`");
$product = $conn->query("   
    SELECT products.*, categories.name AS category_name
    FROM products
    LEFT JOIN categories
    ON products.category_id = categories.id");
include "./includes/header.php";
?>
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <h2 class="mb-1">Point of Sale</h2>
      <p class="text-muted mb-0">Select products and enter quantities.</p>
    </div>
    <a href="orders.php" class="btn btn-warning">Order History</a>
  </div>
  <!-- Search -->
  <form method="GET" class="row g-2 mb-3 align-items-center" action="pos.php">
    <!-- Search Input -->
    <div class="col-md-4">
      <input id="procate" type="text" class="form-control border-black" placeholder="Search product or category">
    </div>

    <!-- Category Select Dropdown -->
    <div class="col-md-3">
      <select id="categoryFilter" name="category" class="form-select border-black">
        <option value="">All Categories</option>
        <?php while ($row = $category->fetch_assoc()): ?>
          <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>

    <!-- Action Buttons -->
    <div class="col-1">
      <a class="btn btn-light border-black" href="pos.php"><i class="fa-solid fa-clock-rotate-left"></i></a>
    </div>
  </form>

  <!-- Sale form -->
  <form method="POST">
    <div class="card border-0 shadow-sm">
      <div class="table-responsive">
        <table class="table table-bordered align-middle text-nowrap border-secondary-subtle">
          <thead>
            <tr>
              <th>Product</th>
              <th>Category</th>
              <th>Price</th>
              <th style="width:200px">Quantity to sell</th>
              <th>In stock</th>

            </tr>
          </thead>
          <tbody id="salebody">
            <?php while ($row = $product->fetch_assoc()): ?>
              <tr>
                <td class="fw-semibold"><?= $row['name'] ?></td>
                <td class="text-success"><?= $row['category_name'] ?? 'No Category' ?></td>

                <td><?= $row['price'] ?></td>
                <td>
                  <input
                    class="form-control qty-input border-black"
                    type="number"
                    name="qty[<?= $row['id'] ?>]"
                    min="0"
                    max="<?= $row['stock'] ?>"
                    data-price="<?= $row['price'] ?>">
                </td>
                <td><?= $row['stock'] ?></td>
              </tr>
            <?php endwhile; ?>
            <tr id="nosale" style="display: none;">
            <td colspan="6" class="text-center bg-warning-subtle">Not found</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="card-body d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
          <span class="text-muted">Total:</span>
          <strong class="fs-4" id="estimated-total">$0.00</strong>
        </div>
        <button class="btn btn-success btn-lg"><i class="fa-solid fa-cash-register me-2"></i>Complete Sale</button>
      </div>
    </div>
  </form>
<script>
  //search
    document.getElementById("procate").addEventListener("input", function(){
    let search = this.value.toLowerCase();
    let found = 0;
    document.querySelectorAll("#salebody tr:not(#nosale)").forEach(function (row){
        let text = (row.cells[0].textContent + row.cells[1].textContent).toLowerCase();
        if (text.includes(search)) {
            row.style.display = "";
            found++;
        } else {
            row.style.display = "none";
        }
    });
    if(found === 0){
      document.getElementById('nosale').style.display = "";
    }else{
      document.getElementById('nosale').style.display = "none";
    }
  });

  //filter
  const Filter = document.getElementById('categoryFilter');
  Filter.addEventListener("change", function() {
  const searchbody = document.querySelectorAll("#salebody tr:not(#nosale)");
    const category = Filter.value.toLowerCase();
    let found = 0;
    searchbody.forEach(function(row) {
      const cate = row.cells[1].textContent.toLowerCase();
      if (category === "" || cate === category) {
        row.style.display = "";
        found++;
      } else {
        row.style.display = "none";
      }
    });
    if(found === 0){
      document.getElementById('nosale').style.display = "";
    }else{
      document.getElementById('nosale').style.display = "none";
    }
  });

  // Calculate Total

  let total = 0;
  document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('input', function() {
      total = 0;
      document.querySelectorAll('.qty-input').forEach(item => {
        total += item.value * item.dataset.price;
      });
      document.getElementById('estimated-total').innerHTML = '$' + total.toFixed(2);
    })
  })
</script>
<?php include "./includes/footer.php"; ?>