<?php
$current = basename($_SERVER['PHP_SELF']); //កូដនេះប្រើសម្រាប់ ស្គាល់ថា User កំពុងបើក Page ណា នៅក្នុង Website របស់យើង
include "./config/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini pos system</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Khmer:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body>
<style>
  body { font-family: 'Noto Sans Khmer', sans-serif;
}
  
</style>
    <div class="min-vh-100 d-flex">
        <aside class="bg-dark text-white p-3 d-flex flex-column" style="width: 300px;">
            <a class="text-white text-decoration-none fs-5 fw-bold mb-4" href="index.php">Mini Mart POS</a>
            <ul class="nav nav-pills flex-column gap-2">
                <li><a href="index.php" class="nav-link text-light <?= $current == 'index.php' ? 'active' : '' ?>">ផ្ទាំងគ្រប់គ្រង</a></li>
                <li><a href="pos.php" class="nav-link text-light <?= $current == 'pos.php' ? 'active' : '' ?>">លក់ទំនិញ</a></li>
                <li><a href="products.php" class="nav-link text-light <?= $current == 'products.php' ? 'active' : '' ?>">ទំនិញ</a></li>
                <li><a href="categories.php" class="nav-link text-light <?= $current == 'categories.php' ? 'active' : '' ?>">ប្រភេទ</a></li>
                <li><a href="orders.php" class="nav-link text-light <?= $current == 'orders.php' ? 'active' : '' ?>">ការលក់</a></li>
            </ul>
            <div class="mt-auto pt-3 border-top border-secondary">
                <div class="mb-2"></div>
                    <a href="logout.php" class="btn btn-outline-light w-100"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i>ចាកចេញ</a>
            </div>
        </aside>

        <!-- main contain -->
         <div class="flex-grow-1 " style="min-width: 0;">
            <nav class="navbar bg-white border-bottom px-4">
                <span class="navbar-brand mb-0 h1 fs-6"></span>
                <span class="btn btn-outline-primary px-2 py-1.5">Name : <?= htmlspecialchars($_SESSION['username']) ?>
                </span>
            </nav>
            <main class="container-fluid p-3 p-md-4">