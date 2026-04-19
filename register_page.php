<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra - Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">
            <h2 class="text-center mb-4">Create Account</h2>
            
            <form id="registerForm">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="John Doe" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Create a password" required>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-success">Register</button>
                </div>
            </form>
            
            <hr>
            
            <div class="text-center">
                <p class="mb-0">Already have an account? <a href="login_page.php">Login here</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);

            try {
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.text(); // register.php currently returns text

                if (result.includes("successful")) {
                    alert(result);
                    window.location.href = 'login_page.php';
                } else {
                    alert('Error: ' + result);
                }
            } catch (error) {
                console.error('Error during registration:', error);
                alert('An error occurred. Please try again.');
            }
        });
    </script>
</body>
</html>
