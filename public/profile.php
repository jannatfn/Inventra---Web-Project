<?php require_once '../config/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <?php include '../includes/navbar.php'; ?>

    <div class="container py-5">
        <div class="mb-5">
            <h1 class="fw-bold m-0">Account Settings</h1>
            <p class="text-secondary">Manage your personal information and security.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card p-4 h-100">
                    <h5 class="fw-bold mb-4">Personal Details</h5>
                    <form id="profileForm">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Full Name</label>
                            <input type="text" name="name" id="nameInput" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Email Address</label>
                            <input type="email" name="email" id="emailInput" class="form-control shadow-none" required>
                        </div>
                        <button type="submit" class="btn btn-primary px-4">Update Profile</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card p-4 h-100 border-warning border-opacity-25">
                    <h5 class="fw-bold mb-4 text-warning"><i class="bi bi-shield-lock me-2"></i>Change Password</h5>
                    <form id="passwordForm">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Current Password</label>
                            <input type="password" name="current_password" class="form-control shadow-none" required>
                        </div>
                        <hr class="my-4 opacity-50">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">New Password</label>
                            <input type="password" name="new_password" id="newPassword" class="form-control shadow-none" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Confirm Password</label>
                            <input type="password" id="confirmPassword" class="form-control shadow-none" required>
                        </div>
                        <button type="submit" class="btn btn-outline-warning w-100">Securely Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/app.js"></script>
    <script>
        async function loadProfile() {
            App.loading(true);
            try {
                const res = await fetch('../api/profile/get.php');
                const data = await res.json();
                if (data.success) {
                    document.getElementById('nameInput').value = data.user.name;
                    document.getElementById('emailInput').value = data.user.email;
                }
            } catch (e) { App.toast('Error loading profile', 'danger'); }
            finally { App.loading(false); }
        }

        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            App.loading(true);
            try {
                const res = await fetch('../api/profile/update.php', {
                    method: 'POST',
                    body: JSON.stringify(Object.fromEntries(new FormData(e.target)))
                });
                const result = await res.json();
                App.toast(result.message, result.success ? 'success' : 'danger');
                if(result.success) location.reload(); 
            } catch (e) { App.toast('Update failed', 'danger'); }
            finally { App.loading(false); }
        });

        document.getElementById('passwordForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            if (document.getElementById('newPassword').value !== document.getElementById('confirmPassword').value) {
                App.toast('Passwords do not match', 'warning');
                return;
            }
            App.loading(true);
            try {
                const res = await fetch('../api/profile/change_password.php', {
                    method: 'POST',
                    body: JSON.stringify(Object.fromEntries(new FormData(e.target)))
                });
                const result = await res.json();
                App.toast(result.message, result.success ? 'success' : 'danger');
                if(result.success) e.target.reset();
            } catch (e) { App.toast('Operation failed', 'danger'); }
            finally { App.loading(false); }
        });

        loadProfile();
    </script>
</body>
</html>
