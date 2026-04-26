<?php require_once '../config/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
            <div>
                <h1 class="fw-bold m-0">Inventory</h1>
                <p class="text-secondary m-0">Tracking <span id="productCount" class="fw-bold text-primary">0</span> products.</p>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" onclick="prepareAdd()">
                <i class="bi bi-plus-lg me-2"></i>Add Product
            </button>
        </div>

        <div class="table-container shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        <!-- Loaded via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0 p-4 pb-0">
                    <h4 class="fw-bold m-0" id="modalTitle">New Item</h4>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="productForm">
                        <input type="hidden" name="id" id="productId">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Item Name</label>
                            <input type="text" name="name" id="name" class="form-control shadow-none" required>
                        </div>
                        <div class="row g-3">
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Price ($)</label>
                                <input type="number" name="price" id="price" class="form-control shadow-none" step="0.01" required>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-muted">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control shadow-none" required>
                            </div>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary py-2" id="submitBtn">Save Item</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/app.js"></script>
    <script>
        let products = [];
        const modal = new bootstrap.Modal(document.getElementById('productModal'));

        async function loadProducts() {
            App.loading(true);
            try {
                const res = await fetch('../api/products/read.php');
                products = await res.json();
                renderTable();
            } catch (e) {
                App.toast('Failed to load inventory', 'danger');
            } finally {
                App.loading(false);
            }
        }

        function renderTable() {
            const tbody = document.getElementById('productTableBody');
            tbody.innerHTML = '';
            
            if (products.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-muted fw-medium">No items found.</td></tr>`;
                return;
            }

            products.forEach(p => {
                const isLow = p.quantity < 5;
                const row = `
                    <tr class="${isLow ? 'bg-danger bg-opacity-10' : ''}">
                        <td class="ps-4 fw-semibold">${p.name}</td>
                        <td class="fw-bold text-dark">$${parseFloat(p.price).toFixed(2)}</td>
                        <td class="text-secondary">${p.quantity} units</td>
                        <td>
                            <span class="badge ${isLow ? 'bg-danger' : 'bg-success'} rounded-pill">
                                ${isLow ? 'Low Stock' : 'Active'}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-light btn-sm rounded-3 me-2" onclick="prepareEdit(${p.id})"><i class="bi bi-pencil-fill"></i></button>
                            <button class="btn btn-light btn-sm rounded-3 text-danger" onclick="deleteProduct(${p.id})"><i class="bi bi-trash-fill"></i></button>
                        </td>
                    </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });
            document.getElementById('productCount').textContent = products.length;
        }

        function prepareAdd() {
            document.getElementById('modalTitle').textContent = 'New Item';
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
        }

        function prepareEdit(id) {
            const p = products.find(x => x.id == id);
            document.getElementById('modalTitle').textContent = 'Edit Item';
            document.getElementById('productId').value = p.id;
            document.getElementById('name').value = p.name;
            document.getElementById('price').value = p.price;
            document.getElementById('quantity').value = p.quantity;
            modal.show();
        }

        document.getElementById('productForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(e.target));
            const isEdit = data.id !== '';
            
            App.loading(true);
            try {
                const url = isEdit ? '../api/products/update.php' : '../api/products/create.php';
                const res = await fetch(url, { method: 'POST', body: JSON.stringify(data) });
                const result = await res.json();
                
                if (result.success) {
                    modal.hide();
                    App.toast(result.message, 'success');
                    loadProducts();
                } else {
                    App.toast(result.message, 'danger');
                }
            } catch (e) { App.toast('Operation failed', 'danger'); }
            finally { App.loading(false); }
        });

        async function deleteProduct(id) {
            if (!confirm('Delete this item?')) return;
            App.loading(true);
            try {
                const res = await fetch('../api/products/delete.php', { method: 'POST', body: JSON.stringify({ id }) });
                const result = await res.json();
                if (result.success) {
                    App.toast('Item removed', 'success');
                    loadProducts();
                }
            } catch (e) { App.toast('Delete failed', 'danger'); }
            finally { App.loading(false); }
        }

        loadProducts();
    </script>
</body>
</html>
