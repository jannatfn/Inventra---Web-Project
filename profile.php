<?php require_once 'includes/auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra - My Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 text-center">
                        <div class="mb-4">
                            <i class="fas fa-user-circle fa-5x text-secondary"></i>
                        </div>
                        <h3 class="mb-4">User Profile</h3>
                        
                        <form id="profileForm" class="text-start">
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" required>
                            </div>
                            
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Fetch current user data
        async function loadProfile() {
            try {
                const response = await fetch('api/get_profile.php');
                const user = await response.json();

                if (user.error) {
                    alert(user.error);
                } else {
                    document.getElementById('name').value = user.name;
                    document.getElementById('email').value = user.email;
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Handle profile update
        document.getElementById('profileForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData();
            formData.append('name', document.getElementById('name').value);
            formData.append('email', document.getElementById('email').value);

            try {
                const response = await fetch('api/update_profile.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();

                if (result.status === 'success') {
                    alert(result.message);
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Error updating profile:', error);
            }
        });

        // Initialize
        loadProfile();
    </script>
</body>
</html>
ml>
