<nav class="navbar navbar-expand-lg py-3 sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold text-primary d-flex align-items-center" href="dashboard.php">
            <i class="bi bi-box-seam me-2 fs-3"></i> Inventra
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>" href="products.php">Inventory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">My Profile</a>
                </li>
            </ul>
            <div class="d-flex align-items-center mt-3 mt-lg-0">
                <span class="me-3 small text-secondary d-none d-md-inline fw-semibold">
                    <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <button id="globalLogoutBtn" class="btn btn-outline-danger btn-sm rounded-pill px-3">Logout</button>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('globalLogoutBtn').addEventListener('click', async () => {
        if(confirm('Are you sure you want to logout?')) {
            await fetch('../api/logout.php');
            window.location.href = 'login.php';
        }
    });
</script>
