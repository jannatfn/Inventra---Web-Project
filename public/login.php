<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Access Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="d-flex align-items-center justify-content-center p-3" style="min-height: 100vh; background-color: var(--bg-deep);">

    <div class="card p-4 p-lg-5 animate-fade" style="width: 100%; max-width: 480px;">
        <div class="text-center mb-5">
            <div class="mb-3 d-flex align-items-center justify-content-center text-primary fs-1 fw-bold">
                <i class="bi bi-box-seam-fill me-3"></i> Inventra
            </div>
            <h2 class="fw-extrabold m-0">Welcome Back</h2>
            <p class="text-dim">Please sign in to manage inventory.</p>
        </div>

        <div id="alertBox" class="alert d-none rounded-4 mb-4 fw-bold"></div>

        <form id="loginForm">
            <div class="mb-3">
                <label class="form-label small fw-bold text-dim uppercase tracking-wider">Email Address</label>
                <input type="email" name="email" class="form-control shadow-none" placeholder="name@company.com" required autocomplete="email">
            </div>
            <div class="mb-5">
                <div class="d-flex justify-content-between">
                    <label class="form-label small fw-bold text-dim uppercase tracking-wider">Password</label>
                    <a href="#" class="text-primary text-decoration-none small fw-bold">Forgot?</a>
                </div>
                <input type="password" name="password" class="form-control shadow-none" placeholder="••••••••" required autocomplete="current-password">
            </div>
            <button type="submit" id="submitBtn" class="btn btn-primary w-100 py-3 fs-5">
                <span id="btnText">Sign in</span>
            </button>
        </form>

        <div class="text-center mt-5">
            <span class="text-dim fw-medium">Don't have an account?</span>
            <a href="register.php" class="text-primary text-decoration-none fw-bold ms-1">Sign up</a>
        </div>
    </div>

    <script src="../assets/js/app.js"></script>
    <script>
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const alertBox = document.getElementById('alertBox');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            submitBtn.disabled = true;
            btnText.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Checking...`;
            alertBox.classList.add('d-none');

            try {
                const formData = new FormData(loginForm);
                const data = Object.fromEntries(formData.entries());

                const response = await fetch('../api/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const text = await response.text(); // Get raw text first to handle non-JSON errors
                let result;

                try {
                    result = JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid server response. Raw output: ' + text.substring(0, 100));
                }

                if (result.success) {
                    alertBox.className = 'alert alert-success d-block rounded-4 mb-4';
                    alertBox.textContent = 'Identity verified. Redirecting...';
                    setTimeout(() => window.location.href = 'dashboard.php', 600);
                } else {
                    alertBox.className = 'alert alert-danger d-block rounded-4 mb-4';
                    alertBox.textContent = result.message || 'Invalid credentials.';
                    submitBtn.disabled = false;
                    btnText.textContent = 'Sign in';
                }
            } catch (error) {
                console.error('Login error:', error);
                alertBox.className = 'alert alert-danger d-block rounded-4 mb-4';
                alertBox.textContent = error.message.includes('fetch') ? 'Network error: Check if XAMPP is running.' : error.message;
                submitBtn.disabled = false;
                btnText.textContent = 'Sign in';
            }
        });
    </script>
</body>
</html>
