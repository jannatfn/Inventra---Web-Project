<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <div class="auth-card">
        <div class="auth-header text-center mb-4">
            <h2>Welcome Back</h2>
            <p class="text-secondary">Please enter your details</p>
        </div>

        <div id="alertBox" class="alert d-none py-2 small"></div>

        <form id="loginForm">
            <div class="mb-3">
                <label class="form-label small fw-semibold text-secondary">Email Address</label>
                <input type="email" name="email" class="form-control shadow-none" placeholder="name@company.com" required>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-semibold text-secondary">Password</label>
                <input type="password" name="password" class="form-control shadow-none" placeholder="••••••••" required>
            </div>
            
            <button type="submit" id="submitBtn" class="btn btn-auth w-100 mb-3 d-flex align-items-center justify-content-center">
                <span class="spinner-border spinner-border-sm me-2 loading-spinner" id="spinner"></span>
                <span id="btnText">Sign In</span>
            </button>
            
            <p class="text-center small text-secondary">
                New to Inventra? <a href="register.php" class="text-decoration-none fw-bold text-primary">Create an account</a>
            </p>
        </form>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btnText');
        const alertBox = document.getElementById('alertBox');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // UI State: Loading
            submitBtn.disabled = true;
            spinner.style.display = 'inline-block';
            btnText.textContent = 'Authenticating...';
            alertBox.classList.add('d-none');

            const formData = new FormData(loginForm);
            const data = Object.fromEntries(formData.entries());

            try {
                const response = await fetch('../api/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();

                if (result.success) {
                    alertBox.className = 'alert alert-success d-block py-2 small';
                    alertBox.textContent = 'Success! Redirecting...';
                    setTimeout(() => window.location.href = 'dashboard.php', 800);
                } else {
                    alertBox.className = 'alert alert-danger d-block py-2 small';
                    alertBox.textContent = result.message;
                    // Reset UI State
                    submitBtn.disabled = false;
                    spinner.style.display = 'none';
                    btnText.textContent = 'Sign In';
                }
            } catch (error) {
                console.error('Error:', error);
                alertBox.className = 'alert alert-danger d-block py-2 small';
                alertBox.textContent = 'Server connection error.';
                submitBtn.disabled = false;
                spinner.style.display = 'none';
                btnText.textContent = 'Sign In';
            }
        });
    </script>
</body>
</html>
