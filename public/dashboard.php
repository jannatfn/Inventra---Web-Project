<?php require_once '../config/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="container py-5 mt-3">
        <header class="mb-5">
            <h1 class="fw-extrabold mb-1">Hello, <?php echo explode(' ', $_SESSION['user_name'])[0]; ?></h1>
            <p class="text-dim fs-5">Here's what's happening with your inventory today.</p>
        </header>

        <div class="row g-4 mb-5">
            <!-- Stats -->
            <div class="col-12 col-md-4">
                <div class="card h-100 p-4 border-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-4 me-3"><i class="bi bi-box-seam fs-4"></i></div>
                        <h6 class="text-dim fw-bold m-0 uppercase small tracking-wider">Total Items</h6>
                    </div>
                    <h1 class="fw-extrabold mb-0" id="totalItems">0</h1>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card h-100 p-4 border-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-4 me-3"><i class="bi bi-currency-dollar fs-4"></i></div>
                        <h6 class="text-dim fw-bold m-0 uppercase small tracking-wider">Total Value</h6>
                    </div>
                    <h1 class="fw-extrabold mb-0" id="totalValue">$0.00</h1>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card h-100 p-4 border-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-4 me-3"><i class="bi bi-exclamation-triangle fs-4"></i></div>
                        <h6 class="text-dim fw-bold m-0 uppercase small tracking-wider">Low Stock</h6>
                    </div>
                    <h1 class="fw-extrabold mb-0" id="lowStockCount">0</h1>
                    <p class="text-danger small fw-bold m-0" id="lowStockMsg">Loading alerts...</p>
                </div>
            </div>
        </div>

        <div class="card border-0 bg-elevated p-5 text-center">
            <h3 class="fw-bold mb-3">Inventory Management</h3>
            <p class="text-dim mb-4">View your complete list of products or add new items to your digital catalog.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="products.php" class="btn btn-primary px-5 py-3">View Inventory</a>
            </div>
        </div>
    </div>

    <script src="../assets/js/app.js"></script>
    <script>
        async function fetchStats() {
            try {
                const response = await fetch('../api/get_stats.php');
                const result = await response.json();

                if (result.success) {
                    document.getElementById('totalItems').textContent = result.stats.total_items;
                    document.getElementById('totalValue').textContent = '$' + result.stats.total_value.toLocaleString(undefined, {minimumFractionDigits: 2});
                    document.getElementById('lowStockCount').textContent = result.stats.low_stock;
                    document.getElementById('lowStockMsg').textContent = result.stats.low_stock > 0 ? `${result.stats.low_stock} items need restocking` : 'All items healthy';
                    document.getElementById('lowStockMsg').className = result.stats.low_stock > 0 ? 'text-danger small fw-bold' : 'text-success small fw-bold';
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
        fetchStats();
    </script>
</body>
</html>
