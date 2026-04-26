<?php require_once '../config/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #1e293b; }
        .navbar { background: white; border-bottom: 1px solid #e2e8f0; }
        .table-card { border: none; border-radius: 16px; overflow: hidden; }
        .btn-primary { border-radius: 10px; }
        .badge-low-stock { background-color: #fee2e2; color: #ef4444; font-weight: 600; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="dashboard.php">Inventra</a>
            <div class="ms-auto d-flex align-items-center">
                <a href="dashboard.php" class="btn btn-link text-decoration-none text-secondary me-3">Dashboard</a>
                <button id="logoutBtn" class="btn btn-outline-danger btn-sm rounded-pill px-3">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold m-0">Inventory</h2>
                <p class="text-secondary m-0">Manage your product list</p>
            </div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#productModal" onclick="prepareAdd()">
                <i class="bi bi-plus-lg me-2"></i>Add Product
            </button>
        </div>

        <div class="card table-card shadow-sm">
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
                    <tbody id="productTableBody">
                        <!-- Data loaded via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Product Modal (Add/Edit) -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 pb-0">
                    <h5 class="fw-bold" id="modalTitle">Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="productForm">
                        <input type="hidden" name="id" id="productId">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control shadow-none" required>
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-semibold">Price ($)</label>
                                <input type="number" name="price" id="price" class="form-control shadow-none" step="0.01" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-semibold">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control shadow-none" required>
                            </div>
                        </div>
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn btn-primary py-2" id="submitBtn">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let products = [];
        const productModal = new bootstrap.Modal(document.getElementById('productModal'));

        // Load Products
        async function loadProducts() {
            const response = await fetch('../api/products/read.php');
            products = await response.json();
            renderTable();
        }

        function renderTable() {
            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '';
            
            if (products.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-secondary">No products found. Add your first item!</td></tr>`;
                return;
            }

            products.forEach(p => {
                const isLow = p.quantity < 5;
                const row = `
                    <tr style="${isLow ? 'background-color: #fff1f2;' : ''}">
                        <td class="ps-4 fw-medium">${p.name}</td>
                        <td>$${parseFloat(p.price).toFixed(2)}</td>
                        <td>${p.quantity}</td>
                        <td>
                            ${isLow ? '<span class="badge badge-low-stock">Low Stock</span>' : '<span class="badge bg-success bg-opacity-10 text-success">In Stock</span>'}
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-light btn-sm rounded-circle me-2" onclick="prepareEdit(${p.id})"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-light btn-sm rounded-circle text-danger" onclick="deleteProduct(${p.id})"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        // Add Logic
        function prepareAdd() {
            document.getElementById('modalTitle').textContent = 'Add Product';
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
        }

        // Edit Logic
        function prepareEdit(id) {
            const p = products.find(x => x.id == id);
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('productId').value = p.id;
            document.getElementById('name').value = p.name;
            document.getElementById('price').value = p.price;
            document.getElementById('quantity').value = p.quantity;
            productModal.show();
        }

        // Form Submit (Create or Update)
        document.getElementById('productForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());
            const isEdit = data.id !== '';
            
            const url = isEdit ? '../api/products/update.php' : '../api/products/create.php';
            
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            if (result.success) {
                productModal.hide();
                loadProducts();
            } else {
                alert(result.message);
            }
        });

        // Delete Logic
        async function deleteProduct(id) {
            if (!confirm('Are you sure you want to delete this product?')) return;
            
            const response = await fetch('../api/products/delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            
            const result = await response.json();
            if (result.success) loadProducts();
        }

        // Logout
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            await fetch('../api/logout.php');
            window.location.href = 'login.php';
        });

        // Init
        loadProducts();
    </script>
</body>
</html>
