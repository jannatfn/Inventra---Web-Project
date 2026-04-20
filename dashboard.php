<?php
require_once 'auth.php';
require_once 'db_connect.php';

// Fetch total product count
$sql_total = "SELECT COUNT(*) as total FROM products";
$result_total = mysqli_query($conn, $sql_total);
$data_total = mysqli_fetch_assoc($result_total);
$total_products = $data_total['total'];

// Fetch low stock count (less than 5)
$sql_low = "SELECT COUNT(*) as low_stock FROM products WHERE quantity < 5";
$result_low = mysqli_query($conn, $sql_low);
$data_low = mysqli_fetch_assoc($result_low);
$low_stock_count = $data_low['low_stock'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra - Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <?php include 'navbar.php'; ?>

    <div class="container py-4 py-lg-5">
        <div class="row g-4 mb-4">
            <!-- Total Products Card -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 bg-primary text-white h-100 transition-hover">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0 opacity-75">Total Products</h5>
                            <i class="fas fa-box-open fa-2x opacity-25"></i>
                        </div>
                        <h1 class="display-3 fw-bold mb-3"><?php echo $total_products; ?></h1>
                        <a href="inventory.php" class="btn btn-light btn-sm px-3 rounded-pill">View All Items</a>
                    </div>
                </div>
            </div>

            <!-- Low Stock Card -->
            <div class="col-12 col-md-6">
                <div class="card shadow-sm border-0 <?php echo $low_stock_count > 0 ? 'bg-danger text-white' : 'bg-success text-white'; ?> h-100 transition-hover">
                    <div class="card-body p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0 opacity-75">Stock Alerts</h5>
                            <i class="fas fa-exclamation-triangle fa-2x opacity-25"></i>
                        </div>
                        <h1 class="display-3 fw-bold mb-3"><?php echo $low_stock_count; ?></h1>
                        <p class="mb-3">Low stock items found.</p>
                        <a href="inventory.php" class="btn btn-dark btn-sm px-3 rounded-pill text-white">Check Inventory</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4 p-lg-5">
                        <h3 class="fw-bold">Welcome Back!</h3>
                        <p class="text-muted fs-5 mb-4">Easily track and manage your inventory from one central hub.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="inventory.php" class="btn btn-outline-dark px-4">
                                <i class="fas fa-list-ul me-2"></i>Inventory List
                            </a>
                            <a href="add_product_page.php" class="btn btn-primary px-4">
                                <i class="fas fa-plus-circle me-2"></i>Quick Add
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
