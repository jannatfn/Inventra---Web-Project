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

    <?php include 'navbar.php'; ?>

    <div class="container py-4 py-lg-5">
        <div class="row align-items-center mb-4">
            <div class="col-12 col-md-6 text-center text-md-start mb-3 mb-md-0">
                <h2 class="fw-bold text-dark m-0">Product Inventory</h2>
            </div>
            <div class="col-12 col-md-6 text-center text-md-end">
                <span class="badge bg-white text-dark shadow-sm p-2 px-3 border">
                    <i class="fas fa-info-circle me-1 text-primary"></i>
                    <span id="productCount">0</span> Products Total
                </span>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="productTable">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th class="ps-4 py-3">Product Name</th>
                                <th class="py-3">Price</th>
                                <th class="py-3">Quantity</th>
                                <th class="py-3">Status</th>
                                <th class="text-end pe-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data loaded via JS -->
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
