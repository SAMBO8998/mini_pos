<?php
include "./includes/auth.php";
include "./config/db.php";
//total products
$total_pro = $conn->query("SELECT COUNT(*) AS total_pro FROM products");
$result_pro = mysqli_fetch_assoc($total_pro);

$total_pro1 = $conn->query("SELECT COUNT(*) AS total_pro FROM products")->fetch_row()[0];

//total category
$total_cate = $conn->query("SELECT COUNT(*) AS total_cate FROM categories")->fetch_row()[0];

//total order or sale
$total_order = $conn->query("SELECT COUNT(*) AS total_order FROM orders")->fetch_row()[0];

//total money from order
$total_money = $conn->query("SELECT SUM(total) AS total_money FROM orders")->fetch_row()[0];

//show data table
$result_sale = $conn->query("
  	SELECT orders.*, users.username
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC LIMIT 5");



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
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">ផ្ទាំងគ្រប់គ្រង</h2>
        <p class="text-muted mb-0">សូមស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រង Mini Mart របស់អ្នក.</p>
    </div>
    <a href="pos.php" class="btn btn-primary"><i class="fa-solid fa-cart-shopping me-2"></i>លក់ថ្មី</a>
</div>

<!-- Stat cards -->
<div class="row g-3 mb-4">

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">ទំនិញ</div>
                    <h3 class="mb-0"><?= $result_pro['total_pro'] ?></h3>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3"><i class="fa-solid fa-box fa-lg"></i></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">ប្រភេទ</div>
                    <h3 class="mb-0"><?= $total_cate ?></h3>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 text-success p-3"><i class="fa-solid fa-tags fa-lg"></i></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">ការលក់</div>
                    <h3 class="mb-0"><?= $total_order ?></h3>
                </div>
                <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3"><i class="fa-solid fa-receipt fa-lg"></i></div>
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">ចំណូលសរុប</div>
                    <h3 class="mb-0">$<?= $total_money ?></h3>
                </div>
                <div class="rounded-circle bg-info bg-opacity-10 text-info p-3"><i class="fa-solid fa-sack-dollar fa-lg"></i></div>
            </div>
        </div>
    </div>

</div>

<!-- Recent orders -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between">
        <strong>ការលក់ថ្មីៗ</strong>
        <a href="orders.php">មើលទាំងអស់</a>
    </div>  
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-nowrap border-secondary-subtle">
            <thead>
                <tr>
                    <th>វិក្កយបត្រ</th>
                    <th>អ្នកគិតលុយ</th>
                    <th>សរុប</th>
                    <th>កាលបរិច្ឆេទ</th>
                    <th>វិក័យប័ត្រ</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_sale->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['receipt_no'] ?></td>
                    <td><?= $row['username'] ?></td>
                    <td>$<?= $row['total'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td><a class="btn btn-sm btn-outline-primary" href="./order_view.php?id=<?= $row['id'] ?>">
                    <i class="fa-solid fa-receipt me-1"></i>មើលវិក័យបត្រ</a></td>
                </tr>
                <?php endwhile; ?>
                <!-- Empty state -->
                <!-- <tr>
                    <td colspan="5" class="text-center text-muted py-4">មិនទាន់មានការលក់នៅឡើយទេ។</td>
                </tr> -->
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
<?php include "./includes/footer.php"; ?>