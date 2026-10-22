<?php
require_once __DIR__ . '/functions.php';
require_login();
$pageTitle = $pageTitle ?? 'Mini Mart POS';
$current = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> | Mini Mart POS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
 <div class="container-fluid">
  <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-store me-2"></i>Mini Mart POS</a>
  <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
  <div class="collapse navbar-collapse" id="nav">
   <ul class="navbar-nav me-auto">
    <li class="nav-item"><a class="nav-link <?= $current==='index.php'?'active':'' ?>" href="index.php">Dashboard</a></li>
    <li class="nav-item"><a class="nav-link <?= $current==='pos.php'?'active':'' ?>" href="pos.php">POS / Sell</a></li>
    <li class="nav-item"><a class="nav-link <?= in_array($current,['products.php','product_form.php'])?'active':'' ?>" href="products.php">Products</a></li>
    <li class="nav-item"><a class="nav-link <?= in_array($current,['categories.php','category_form.php'])?'active':'' ?>" href="categories.php">Categories</a></li>
    <li class="nav-item"><a class="nav-link <?= in_array($current,['orders.php','order_view.php'])?'active':'' ?>" href="orders.php">Orders</a></li>
   </ul>
   <span class="navbar-text text-white me-3"><?= e($_SESSION['user']['full_name']) ?></span>
   <a class="btn btn-outline-light btn-sm" href="logout.php">Logout</a>
  </div>
 </div>
</nav>
<main class="container-fluid p-3 p-md-4">
