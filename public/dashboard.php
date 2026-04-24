<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #1e293b; }
        .navbar { background: white; border-bottom: 1px solid #e2e8f0; }
        .stat-card { border: none; border-radius: 16px; transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
        .icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">Inventra</a>
            <div class="ms-auto d-flex align-items-center">
                <span class="me-3 small text-secondary d-none d-md-inline">Logged in as <b><?php echo htmlspecialchars($_SESSION['user_name']); ?></b></span>
                <button id="logoutBtn" class="btn btn-outline-danger btn-sm rounded-pill px-3">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="mb-4">
            <h2 class="fw-bold">Overview</h2>
            <p class="text-secondary">Control and monitor your system from here.</p>
        </div>

        <div class="row g-4">
            <!-- Total Products Card -->
            <div class="col-12 col-md-4">
                <div class="card stat-card shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-primary bg-opacity-10 text-primary me-3">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <h6 class="card-subtitle text-secondary mb-0 fw-semibold">Total Items</h6>
                        </div>
                        <h2 class="card-title fw-bold mb-0" id="totalItems">...</h2>
                    </div>
                </div>
            </div>

            <!-- Total Value Card -->
            <div class="col-12 col-md-4">
                <div class="card stat-card shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-success bg-opacity-10 text-success me-3">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <h6 class="card-subtitle text-secondary mb-0 fw-semibold">Inventory Value</h6>
                        </div>
                        <h2 class="card-title fw-bold mb-0" id="totalValue">...</h2>
                    </div>
                </div>
            </div>

            <!-- Low Stock Card -->
            <div class="col-12 col-md-4">
                <div class="card stat-card shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-box bg-danger bg-opacity-10 text-danger me-3">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <h6 class="card-subtitle text-secondary mb-0 fw-semibold">Stock Alerts</h6>
                        </div>
                        <h2 class="card-title fw-bold mb-0" id="lowStockCount">...</h2>
                        <small class="text-danger fw-medium" id="lowStockMsg">Items under 5 units</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4 text-center">
                    <p class="text-muted mb-0">Ready to manage your products? <a href="#" class="btn btn-primary btn-sm ms-2">View Inventory</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fetch Stats from API
        async function fetchStats() {
            try {
                const response = await fetch('../api/get_stats.php');
                const result = await response.json();

                if (result.success) {
                    document.getElementById('totalItems').textContent = result.stats.total_items.toLocaleString();
                    document.getElementById('totalValue').textContent = '$' + result.stats.total_value.toLocaleString(undefined, {minimumFractionDigits: 2});
                    document.getElementById('lowStockCount').textContent = result.stats.low_stock;
                    
                    if (result.stats.low_stock > 0) {
                        document.getElementById('lowStockMsg').textContent = `${result.stats.low_stock} items need restocking`;
                    } else {
                        document.getElementById('lowStockMsg').className = 'text-success fw-medium';
                        document.getElementById('lowStockMsg').textContent = 'All stock healthy';
                    }
                }
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        }

        // Logout
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            const response = await fetch('../api/logout.php');
            const result = await response.json();
            if (result.success) window.location.href = 'login.php';
        });

        // Initialize
        fetchStats();
    </script>
</body>
</html>
