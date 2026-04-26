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

    <div id="alertBannerContainer" class="container mt-4"></div>

    <div class="container py-5">
        <div class="mb-5">
            <h1 class="fw-bold m-0">Dashboard</h1>
            <p class="text-secondary">System snapshot and key metrics.</p>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-12 col-md-4">
                <div class="card card-hover h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-4 me-3"><i class="bi bi-box-seam fs-4"></i></div>
                        <h6 class="text-muted fw-bold m-0">Total Products</h6>
                    </div>
                    <h2 class="fw-extrabold m-0" id="totalItems">...</h2>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card card-hover h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-4 me-3"><i class="bi bi-currency-dollar fs-4"></i></div>
                        <h6 class="text-muted fw-bold m-0">Inventory Value</h6>
                    </div>
                    <h2 class="fw-extrabold m-0" id="totalValue">...</h2>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card card-hover h-100 p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-4 me-3"><i class="bi bi-exclamation-triangle fs-4"></i></div>
                        <h6 class="text-muted fw-bold m-0">Low Stock</h6>
                    </div>
                    <h2 class="fw-extrabold m-0" id="lowStockCount">...</h2>
                    <p class="small text-danger m-0 fw-bold" id="lowStockMsg"></p>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-5 text-center">
                <h4 class="fw-bold mb-3">Ready to update your inventory?</h4>
                <a href="products.php" class="btn btn-primary px-4">Manage All Products</a>
            </div>
        </div>
    </div>

    <script src="../assets/js/app.js"></script>
    <script>
        async function fetchStats() {
            App.loading(true);
            try {
                const response = await fetch('../api/get_stats.php');
                const result = await response.json();

                if (result.success) {
                    document.getElementById('totalItems').textContent = result.stats.total_items.toLocaleString();
                    document.getElementById('totalValue').textContent = '$' + result.stats.total_value.toLocaleString(undefined, {minimumFractionDigits: 2});
                    document.getElementById('lowStockCount').textContent = result.stats.low_stock;
                    
                    const alertContainer = document.getElementById('alertBannerContainer');
                    if (result.stats.low_stock > 0) {
                        document.getElementById('lowStockMsg').textContent = `${result.stats.low_stock} items need attention`;
                        alertContainer.innerHTML = `
                            <div class="alert alert-warning border-0 shadow-sm rounded-4 p-3 d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i>
                                <span><b>Inventory Alert:</b> ${result.stats.low_stock} items are running low on stock.</span>
                            </div>`;
                    } else {
                        document.getElementById('lowStockMsg').className = 'small text-success m-0 fw-bold';
                        document.getElementById('lowStockMsg').textContent = 'All items healthy';
                    }
                }
            } catch (error) {
                App.toast('Failed to load stats', 'danger');
            } finally {
                App.loading(false);
            }
        }
        fetchStats();
    </script>
</body>
</html>
