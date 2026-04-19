<?php
require_once 'auth.php';
require_once 'db_connect.php';

// Fetch total product count
$sql = "SELECT COUNT(*) as total FROM products";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);
$total_products = $data['total'];
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
            <a class="navbar-brand" href="#">Inventra</a>
            <div class="ms-auto">
                <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <!-- Total Products Card -->
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Total Products</div>
                    <div class="card-body">
                        <h1 class="card-title"><?php echo $total_products; ?></h1>
                        <p class="card-text">Items currently in inventory.</p>
                        <a href="index.html" class="btn btn-light btn-sm">View Inventory</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h3>Welcome to your Dashboard</h3>
                        <p>Use the navigation to manage your products and users.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
