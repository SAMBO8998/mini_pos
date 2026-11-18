<?php
include "./config/db.php";
$id = (int)$_GET['id'];
$sql = $conn->query("
SELECT orders.*, users.username
FROM orders
LEFT JOIN users ON users.id = orders.user_id
where orders.id = $id 
");
$order = mysqli_fetch_assoc($sql);
if(!$order){
    echo "Fail";
    exit;
}

$sql_one = $conn->query("
SELECT order_items.*, products.name
FROM order_items
LEFT JOIN products ON products.id = order_items.product_id
WHERE order_items.order_id = $id
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Mart Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container">
        <article class="card rounded-4 col-lg-8 mx-auto my-5 p-md-5">
                <header class="d-flex justify-content-between flex-wrap gap-3 border-bottom pb-4">
                    <div> 
                    <h1 class="fs-3 fw-bold mb-0"><i class="fa-solid fa-store me-2"></i>Mini Mart</h1>
                    <p class="text-secondary fw-bold">Sales Receipt</p>
                </div>
                <p class="text-md-end mb-0">
                    <strong>Receipt : <?= $order['receipt_no'] ?></strong><br>
                    <strong>Date : <?= $order['created_at'] ?></strong><br>
                    <strong>Cashier : <?= $order['username'] ?? 'Unknow'?></strong><br> 
                </p>
            </header>
            <table class="table align-middle mt-4">
                <thead class="table-light">
                    <tr>
                        <th class="fw-semibold">Product</th>
                        <th class="fw-semibold">Price</th>
                        <th class="fw-semibold">Qty</th>
                        <th class="fw-semibold text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $sql_one->fetch_assoc()): ?>
                    <tr>
                        <td><?= $item['name'] ?></td>
                        <td>$<?= $item['price'] ?></td>
                        <td><?= $item['quantity'] ?></td>
                        <td class="text-end">$<?= $item['price'] * $item['quantity'] ?></td>
                      
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <section class="col-md-5 ms-auto border-top border-2 border-dark pt-3">
                <p class="d-flex justify-content-between mb-2 fw-bold fs-5"><span>Subtotal</span>$<?= $order['total'] ?></p>
            </section>

            <footer class="text-center text-muted mt-5">
                <p class="mb-2"><i class="fa-solid fa-heart"></i> Thank you for shopping</p>
                <small>Please keep this receipt for your records.</small>
            </footer>

            <nav class="d-flex justify-content-between mt-4 d-print-none">
                <a href="./orders.php" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Back to Orders
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fa-solid fa-print me-2"></i>Print Receipt
                </button>
            </nav>

        </article>
    </main>
</body>
</html>