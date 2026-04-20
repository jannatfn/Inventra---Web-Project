<?php require_once 'auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra - Inventory Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .low-stock { background-color: #fff3cd; }
        .action-btn { transition: transform 0.2s; }
        .action-btn:hover { transform: scale(1.1); }
    </style>
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="dashboard.php text-primary">
                <i class="fas fa-boxes me-2"></i>Inventra
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-content="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="inventory.php">Inventory</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="add_product_page.php" class="btn btn-primary btn-sm me-3">
                        <i class="fas fa-plus me-1"></i> Add Product
                    </a>
                    <a href="logout.php" class="btn btn-outline-danger btn-sm">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Product Inventory</h2>
            <div class="text-muted">
                <span id="productCount">0</span> Products Total
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="productTable">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Product Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Function to fetch and display products
        async function loadProducts() {
            try {
                const response = await fetch('get_products.php');
                const products = await response.json();

                const tableBody = document.querySelector('#productTable tbody');
                const countDisplay = document.getElementById('productCount');
                tableBody.innerHTML = '';
                countDisplay.textContent = products.length;

                products.forEach(product => {
                    const isLowStock = product.quantity < 5;
                    const statusBadge = isLowStock 
                        ? '<span class="badge bg-danger">Low Stock</span>' 
                        : '<span class="badge bg-success">In Stock</span>';
                    
                    const rowClass = isLowStock ? 'low-stock' : '';

                    const row = `<tr class="${rowClass}">
                        <td class="ps-4 fw-medium">${product.name}</td>
                        <td>$${parseFloat(product.price).toFixed(2)}</td>
                        <td>${product.quantity}</td>
                        <td>${statusBadge}</td>
                        <td class="text-end pe-4">
                            <a href="edit_product_page.php?id=${product.id}" class="btn btn-outline-primary btn-sm action-btn me-2" title="Edit Product">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="deleteProduct(${product.id})" class="btn btn-outline-danger btn-sm action-btn" title="Delete Product">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>`;
                    tableBody.innerHTML += row;
                });
            } catch (error) {
                console.error('Error fetching products:', error);
            }
        }

        // Function to delete a product
        async function deleteProduct(id) {
            if (!confirm('Are you sure you want to delete this product?')) return;

            const formData = new FormData();
            formData.append('id', id);

            try {
                const response = await fetch('delete_product.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success') {
                    loadProducts(); // Refresh the list
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error deleting product:', error);
            }
        }

        // Load products on page load
        loadProducts();
    </script>
</body>
</html>
