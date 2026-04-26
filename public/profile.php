<?php require_once '../config/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | My Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #1e293b; }
        .navbar { background: white; border-bottom: 1px solid #e2e8f0; }
        .profile-card { border: none; border-radius: 20px; }
        .btn-primary { border-radius: 10px; font-weight: 600; }
        .form-control { border-radius: 10px; padding: 12px; border: 1px solid #e2e8f0; background: #f8fafc; }
        .form-control:focus { background: white; box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1); border-color: #667eea; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="dashboard.php">Inventra</a>
            <div class="ms-auto d-flex align-items-center">
                <a href="dashboard.php" class="btn btn-link text-decoration-none text-secondary me-3">Dashboard</a>
                <a href="products.php" class="btn btn-link text-decoration-none text-secondary me-3">Inventory</a>
                <button id="logoutBtn" class="btn btn-outline-danger btn-sm rounded-pill px-3">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row g-4 justify-content-center">
            
            <!-- Left Side: Profile Info -->
            <div class="col-lg-6">
                <div class="card profile-card shadow-sm p-4 h-100">
                    <div class="d-flex align-items-center mb-5">
                        <div class="bg-primary text-white d-flex align-items-center justify-content-center rounded-circle fw-bold fs-2 me-4" style="width: 80px; height: 80px;" id="avatarCircle">U</div>
                        <div>
                            <h2 class="fw-bold m-0" id="displayUserName"><?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
                            <p class="text-secondary m-0">System Admin</p>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-4">Personal Details</h5>
                    <div id="profileAlert" class="alert d-none py-2 small fw-bold mb-4 rounded-3"></div>
                    
                    <form id="profileForm">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Full Name</label>
                            <input type="text" name="name" id="nameInput" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Email Address</label>
                            <input type="email" name="email" id="emailInput" class="form-control shadow-none" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">Save Changes</button>
                    </form>
                </div>
            </div>

            <!-- Right Side: Security -->
            <div class="col-lg-5">
                <div class="card profile-card shadow-sm p-4 h-100">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-circle me-3">
                            <i class="bi bi-shield-lock fs-3"></i>
                        </div>
                        <h4 class="fw-bold m-0">Security</h4>
                    </div>
                    <p class="text-secondary small mb-5">Update your password to keep your account secure.</p>

                    <div id="passwordAlert" class="alert d-none py-2 small fw-bold mb-4 rounded-3"></div>

                    <form id="passwordForm">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Current Password</label>
                            <input type="password" name="current_password" class="form-control shadow-none" required>
                        </div>
                        <hr class="my-4">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">New Password</label>
                            <input type="password" name="new_password" id="newPassword" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Confirm New Password</label>
                            <input type="password" id="confirmPassword" class="form-control shadow-none" required>
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100 py-2 fw-bold">Update Password</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load Profile Data
        async function loadProfile() {
            const response = await fetch('../api/profile/get.php');
            const data = await response.json();
            if (data.success) {
                document.getElementById('nameInput').value = data.user.name;
                document.getElementById('emailInput').value = data.user.email;
                document.getElementById('displayUserName').textContent = data.user.name;
                document.getElementById('avatarCircle').textContent = data.user.name.charAt(0).toUpperCase();
            }
        }

        // Handle Profile Update
        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const alertBox = document.getElementById('profileAlert');
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            const response = await fetch('../api/profile/update.php', {
                method: 'POST',
                body: JSON.stringify(data)
            });
            const result = await response.json();

            alertBox.className = `alert ${result.success ? 'alert-success' : 'alert-danger'} d-block py-2 small fw-bold mb-4 rounded-3`;
            alertBox.textContent = result.message;
            if (result.success) loadProfile();
        });

        // Handle Password Change
        document.getElementById('passwordForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const alertBox = document.getElementById('passwordAlert');
            const newPass = document.getElementById('newPassword').value;
            const confPass = document.getElementById('confirmPassword').value;

            if (newPass !== confPass) {
                alertBox.className = 'alert alert-danger d-block py-2 small fw-bold mb-4 rounded-3';
                alertBox.textContent = 'New passwords do not match.';
                return;
            }

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            const response = await fetch('../api/profile/change_password.php', {
                method: 'POST',
                body: JSON.stringify(data)
            });
            const result = await response.json();

            alertBox.className = `alert ${result.success ? 'alert-success' : 'alert-danger'} d-block py-2 small fw-bold mb-4 rounded-3`;
            alertBox.textContent = result.message;
            if (result.success) e.target.reset();
        });

        // Logout
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            await fetch('../api/logout.php');
            window.location.href = 'login.php';
        });

        loadProfile();
    </script>
</body>
</html>
