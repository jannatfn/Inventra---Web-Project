<!-- navbar.php -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">
            <i class="fas fa-boxes me-2 text-primary"></i>Inventra
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                        <i class="fas fa-chart-line me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'active' : ''; ?>" href="inventory.php">
                        <i class="fas fa-list me-1"></i> Inventory
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">
                        <i class="fas fa-user me-1"></i> Profile
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center mt-3 mt-lg-0">
                <a href="add_product_page.php" class="btn btn-primary btn-sm me-3 w-100 w-lg-auto">
                    <i class="fas fa-plus me-1"></i> Add Product
                </a>
                <a href="api/logout.php" class="btn btn-outline-danger btn-sm w-100 w-lg-auto">
                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>
