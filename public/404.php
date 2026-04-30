<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventra | 404 Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css?v=<?php echo @filemtime('../assets/css/style.css') ?: time(); ?>">
    <style>
        .error-code { 
            font-size: clamp(6rem, 20vw, 12rem); 
            font-weight: 800; 
            background: linear-gradient(135deg, var(--accent) 0%, var(--white) 100%); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
            line-height: 1; 
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: var(--bg-deep);">

    <div class="text-center p-4 animate-fade">
        <h1 class="error-code mb-4">404</h1>
        <h2 class="fw-extrabold mb-3 display-4">Page Lost in Space</h2>
        <p class="text-dim mb-5 fs-5 mx-auto" style="max-width: 500px;">The inventory record for this URL seems to be missing or relocated. Let's get you back to safety.</p>
        <a href="dashboard.php" class="btn btn-primary btn-lg px-5 py-3 shadow-lg">
            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

</body>
</html>
