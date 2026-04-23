<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; }
        .btn-primary { border-radius: 10px; padding: 10px; font-weight: 600; }
    </style>
</head>
<body>

    <div class="card p-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold">Create Account</h2>
            <p class="text-muted">Join Inventra today</p>
        </div>

        <div id="alertBox" class="alert d-none"></div>

        <form id="registerForm">
            <div class="mb-3">
                <label class="form-label small fw-semibold">Full Name</label>
                <input type="text" name="name" class="form-control shadow-none" placeholder="John Doe" required>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold">Email Address</label>
                <input type="email" name="email" class="form-control shadow-none" placeholder="name@example.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold">Password</label>
                <input type="password" name="password" class="form-control shadow-none" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>
            <p class="text-center small m-0">Already have an account? <a href="login.php" class="text-decoration-none fw-bold">Login</a></p>
        </form>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const alertBox = document.getElementById('alertBox');
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('../api/register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();

                alertBox.className = `alert ${result.success ? 'alert-success' : 'alert-danger'}`;
                alertBox.textContent = result.message;
                alertBox.classList.remove('d-none');

                if (result.success) {
                    e.target.reset();
                    setTimeout(() => window.location.href = 'login.php', 1500);
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });
    </script>
</body>
</html>
