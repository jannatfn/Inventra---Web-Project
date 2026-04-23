<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Inventra</a>
            <div class="ms-auto text-white">
                <span class="me-3">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                <button id="logoutBtn" class="btn btn-outline-danger btn-sm">Logout</button>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card p-5 text-center shadow-sm">
            <h1 class="display-4">Inventory Dashboard</h1>
            <p class="lead">You are securely logged in. Start managing your products.</p>
        </div>
    </div>

    <script>
        document.getElementById('logoutBtn').addEventListener('click', async () => {
            const response = await fetch('../api/logout.php');
            const result = await response.json();
            if (result.success) window.location.href = 'login.php';
        });
    </script>
</body>
</html>
