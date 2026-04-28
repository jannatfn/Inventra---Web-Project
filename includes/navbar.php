<!-- includes/navbar.php -->
<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <i class="bi bi-box-seam-fill me-2"></i>
            <span>Inventra</span>
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="bi bi-list fs-2 text-white"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'products.php' ? 'active' : ''; ?>" href="products.php">
                        Inventory
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>" href="profile.php">
                        Profile
                    </a>
                </li>
            </ul>
            <div class="d-flex align-items-center mt-3 mt-lg-0 border-top border-secondary border-opacity-25 pt-3 pt-lg-0">
                <button id="globalLogoutBtn" class="btn btn-outline-danger btn-sm w-100 w-lg-auto px-4">
                    Logout
                </button>
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('globalLogoutBtn')?.addEventListener('click', async () => {
        if(confirm('Log out from Inventra?')) {
            await fetch('../api/logout.php', { method: 'POST' });
            window.location.href = 'login.php';
        }
    });
</script>
