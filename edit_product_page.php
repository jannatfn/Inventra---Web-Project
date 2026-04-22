<?php require_once 'includes/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra - Edit Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title mb-4">Edit Product</h3>
                        
                        <form id="editProductForm">
                            <input type="hidden" id="productId">
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="price" class="form-label">Price ($)</label>
                                <input type="number" class="form-control" id="price" step="0.01" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" required>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Update Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Get the product ID from the URL
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id');

        // Fetch product data and pre-fill the form
        async function fetchProduct() {
            if (!productId) {
                alert('No product ID found.');
                window.location.href = 'inventory.php';
                return;
            }

            try {
                const response = await fetch(`api/get_product.php?id=${productId}`);
                const product = await response.json();

                if (product.error) {
                    alert(product.error);
                    window.location.href = 'inventory.php';
                } else {
                    document.getElementById('productId').value = product.id;
                    document.getElementById('name').value = product.name;
                    document.getElementById('price').value = product.price;
                    document.getElementById('quantity').value = product.quantity;
                }
            } catch (error) {
                console.error('Error fetching product:', error);
            }
        }

        // Handle form submission
        document.getElementById('editProductForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('id', document.getElementById('productId').value);
            formData.append('name', document.getElementById('name').value);
            formData.append('price', document.getElementById('price').value);
            formData.append('quantity', document.getElementById('quantity').value);

            try {
                const response = await fetch('api/update_product.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success' || result.status === 'info') {
                    alert(result.message);
                    window.location.href = 'inventory.php';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error updating product:', error);
            }
        });

        // Initialize
        fetchProduct();
    </script>
</body>
</html>
ml>
