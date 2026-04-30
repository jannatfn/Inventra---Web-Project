<?php require_once '../config/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo filemtime('../assets/css/style.css'); ?>">
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="container py-5 animate-fade">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
            <div>
                <h1 class="fw-extrabold m-0 display-4">Inventory</h1>
                <p class="text-muted m-0 fs-5">Tracking <span id="productCount" class="text-accent fw-bold">0</span> products.</p>
            </div>
            <button class="btn btn-primary px-4 py-2 shadow-lg" data-bs-toggle="modal" data-bs-target="#productModal" onclick="prepareAdd()">
                <i class="bi bi-plus-lg me-2"></i>Add Product
            </button>
        </div>

        <div class="row g-3 mb-5">
            <div class="col-md-8">
                <div class="search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" id="searchInput" class="form-control shadow-none" placeholder="Search products by name...">
                </div>
            </div>
            <div class="col-md-4">
                <select id="stockFilter" class="form-select shadow-none">
                    <option value="all">All Products</option>
                    <option value="low">Low Stock (< 5)</option>
                </select>
            </div>
        </div>

        <div class="table-container shadow-lg">
            <div class="table-responsive">
                <table class="table align-middle">
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
                        <tr><td colspan="5" class="text-center py-5">Fetching inventory...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 p-4 pb-0">
                    <h4 class="fw-bold m-0" id="modalTitle">Product Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="productForm">
                        <input type="hidden" name="id" id="productId">
                        <div class="mb-4">
                            <label class="form-label">Item Name</label>
                            <input type="text" name="name" id="name" class="form-control" required placeholder="Enter product name">
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Price ($)</label>
                                <input type="number" name="price" id="price" class="form-control" step="0.01" required placeholder="0.00">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Quantity</label>
                                <input type="number" name="quantity" id="quantity" class="form-control" required placeholder="0">
                            </div>
                        </div>
                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-primary py-3">Save Item</button>
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
                const result = await res.json();
                if (result.success) {
                    products = result.products;
                    renderTable();
                }
            } catch (e) { App.toast('Error loading inventory', 'danger'); }
            finally { App.loading(false); }
        }

        function renderTable() {
            const tbody = document.getElementById('productTableBody');
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const filterValue = document.getElementById('stockFilter').value;
            tbody.innerHTML = '';

            const filtered = products.filter(p => {
                const matchesSearch = p.name.toLowerCase().includes(searchTerm);
                const matchesFilter = filterValue === 'all' || (filterValue === 'low' && p.quantity < 5);
                return matchesSearch && matchesFilter;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-muted">No matching items found.</td></tr>`;
                document.getElementById('productCount').textContent = "0";
                return;
            }

            filtered.forEach(p => {
                const isLow = p.quantity < 5;
                const row = `
                    <tr class="${isLow ? 'low-stock-row' : ''}">
                        <td class="ps-4 fw-bold text-white">${p.name}</td>
                        <td class="fw-bold" style="color: var(--accent) !important;">$${parseFloat(p.price).toFixed(2)}</td>
                        <td class="text-white fw-medium">${p.quantity} Units</td>
                        <td>
                            <span class="badge ${isLow ? 'bg-danger text-white' : 'bg-success text-white'}">
                                ${isLow ? 'Low Stock' : 'In Stock'}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-primary btn-sm me-2 rounded-circle shadow-none" style="width:34px; height:34px; padding:0;" onclick="prepareEdit(${p.id})">
                                <i class="bi bi-pencil-square" style="font-size: 0.95rem;"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-sm rounded-circle shadow-none" style="width:34px; height:34px; padding:0;" onclick="deleteProduct(${p.id})">
                                <i class="bi bi-trash-fill" style="font-size: 0.95rem;"></i>
                            </button>
                        </td>
                    </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });
            document.getElementById('productCount').textContent = filtered.length;
        }

        function prepareAdd() {
            document.getElementById('modalTitle').textContent = 'Add New Item';
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
        }

        function prepareEdit(id) {
            const p = products.find(x => x.id == id);
            document.getElementById('modalTitle').textContent = 'Edit Item Details';
            document.getElementById('productId').value = p.id;
            document.getElementById('name').value = p.name;
            document.getElementById('price').value = p.price;
            document.getElementById('quantity').value = p.quantity;
            modal.show();
        }

        document.getElementById('productForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(e.target));
            App.loading(true);
            try {
                const url = data.id ? '../api/products/update.php' : '../api/products/create.php';
                const res = await fetch(url, { method: 'POST', body: JSON.stringify(data) });
                const result = await res.json();
                if (result.success) {
                    modal.hide();
                    App.toast(result.message, 'success');
                    loadProducts();
                }
            } catch (e) { App.toast('Operation failed', 'danger'); }
            finally { App.loading(false); }
        });

        async function deleteProduct(id) {
            if (!confirm('Delete this item permanently?')) return;
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

        document.getElementById('searchInput').addEventListener('input', renderTable);
        document.getElementById('stockFilter').addEventListener('change', renderTable);

        loadProducts();
    </script>
</body>
</html>
