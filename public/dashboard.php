<?php require_once '../config/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Dashboard</title>
    <!-- CSS Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo time(); ?>">
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="container py-5 animate-fade">
        <!-- Header -->
        <div class="mb-5 text-center text-md-start">
            <h1 class="fw-extrabold display-5 mb-1">Hello, <?php echo explode(' ', $_SESSION['user_name'] ?? 'Admin')[0]; ?></h1>
            <p class="text-muted fs-5">Overview of your current stock and analytics.</p>
        </div>

        <div class="row g-4 mb-5">
            <!-- Stats -->
            <div class="col-12 col-md-4">
                <div class="card h-100 card-hover border-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-4 me-3"><i class="bi bi-box-seam fs-4"></i></div>
                        <h6 class="text-muted fw-bold m-0 text-uppercase tracking-wider">Total Items</h6>
                    </div>
                    <h1 class="fw-extrabold display-6 m-0" id="totalItems">0</h1>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card h-100 card-hover border-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-4 me-3"><i class="bi bi-currency-dollar fs-4"></i></div>
                        <h6 class="text-muted fw-bold m-0 text-uppercase tracking-wider">Total Value</h6>
                    </div>
                    <h1 class="fw-extrabold display-6 m-0" id="totalValue">$0.00</h1>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="card h-100 card-hover border-0">
                    <div class="d-flex align-items-center mb-3">
                        <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-4 me-3"><i class="bi bi-exclamation-triangle fs-4"></i></div>
                        <h6 class="text-muted fw-bold m-0 text-uppercase tracking-wider">Low Stock</h6>
                    </div>
                    <h1 class="fw-extrabold display-6 m-0 text-white" id="lowStockCount">0</h1>
                    <p class="small fw-bold m-0 mt-2" id="lowStockMsg"></p>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="card h-100 border-0 shadow-lg">
                    <h5 class="fw-bold mb-4">Stock Levels by Product</h5>
                    <div style="height: 300px;">
                        <canvas id="inventoryBarChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-4">
                <div class="card h-100 border-0 shadow-lg">
                    <h5 class="fw-bold mb-4">Stock Health</h5>
                    <div style="height: 300px;">
                        <canvas id="stockPieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js and App Logic -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/js/app.js"></script>
    <script>
        async function fetchStats() {
            try {
                const response = await fetch('../api/get_stats.php');
                const result = await response.json();
                if (result.success) {
                    document.getElementById('totalItems').textContent = result.stats.total_items.toLocaleString();
                    document.getElementById('totalValue').textContent = '$' + result.stats.total_value.toLocaleString(undefined, {minimumFractionDigits: 2});
                    document.getElementById('lowStockCount').textContent = result.stats.low_stock;
                    
                    const msgEl = document.getElementById('lowStockMsg');
                    msgEl.textContent = result.stats.low_stock > 0 ? `${result.stats.low_stock} items need restocking` : 'All items healthy';
                    msgEl.className = result.stats.low_stock > 0 ? 'text-danger small fw-bold mt-2' : 'text-success small fw-bold mt-2';
                }
            } catch (error) { console.error(error); }
        }

        async function initDashboardCharts() {
            try {
                const response = await fetch('../api/products/read.php');
                const result = await response.json();
                if (!result.success) return;

                const products = result.products;
                Chart.defaults.color = '#8499B1';
                Chart.defaults.borderColor = 'rgba(123, 109, 141, 0.2)';

                // Bar Chart
                new Chart(document.getElementById('inventoryBarChart'), {
                    type: 'bar',
                    data: {
                        labels: products.map(p => p.name),
                        datasets: [{
                            label: 'Quantity',
                            data: products.map(p => p.quantity),
                            backgroundColor: '#A5C4D4',
                            borderRadius: 6
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
                    }
                });

                // Doughnut Chart
                const low = products.filter(p => p.quantity < 5).length;
                new Chart(document.getElementById('stockPieChart'), {
                    type: 'doughnut',
                    data: {
                        labels: ['Low Stock', 'Healthy'],
                        datasets: [{
                            data: [low, products.length - low],
                            backgroundColor: ['#ff4d6d', '#2ec4b6'],
                            borderWidth: 0
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '75%' }
                });
            } catch (e) { console.error(e); }
        }

        fetchStats();
        initDashboardCharts();
    </script>
</body>
</html>
