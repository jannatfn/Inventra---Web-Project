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

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Inventra Dashboard</a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <!-- Total Products Card -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 bg-primary text-white">
                    <div class="card-body py-4">
                        <h5 class="card-title">Total Products</h5>
                        <h1 class="display-4 fw-bold"><?php echo $total_products; ?></h1>
                        <p class="card-text">Total items in your inventory.</p>
                        <a href="index.html" class="btn btn-light btn-sm mt-2">Manage Inventory</a>
                    </div>
                </div>
            </div>

            <!-- Low Stock Card -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-0 bg-warning text-dark">
                    <div class="card-body py-4">
                        <h5 class="card-title">Low Stock Alerts</h5>
                        <h1 class="display-4 fw-bold"><?php echo $low_stock_count; ?></h1>
                        <p class="card-text">Items with quantity less than 5.</p>
                        <a href="index.html" class="btn btn-dark btn-sm mt-2">Check Stock</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3>Quick Actions</h3>
                        <p class="text-muted">Welcome back! Manage your system using the cards above or jump straight into your inventory.</p>
                        <hr>
                        <div class="d-flex gap-2">
                            <a href="index.html" class="btn btn-secondary">View Product List</a>
                            <a href="index.html" class="btn btn-outline-primary">Add New Item</a>
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
