// logout.js
async function logout() {
    try {
        const response = await fetch('/api/logout.php', {
            method: 'POST'
        });

        if (response.ok) {
            // Success: Redirect to login page
            window.location.href = 'auth_frontend.html';
        } else {
            console.error('Logout failed');
            // Fallback redirect
            window.location.href = 'auth_frontend.html';
        }
    } catch (error) {
        console.error('Logout error:', error);
        window.location.href = 'auth_frontend.html';
    }
}

// Attach to any element with id="logoutBtn" or class="btn-logout"
document.addEventListener('DOMContentLoaded', () => {
    const logoutBtns = document.querySelectorAll('#logoutBtn, .btn-logout, .btn-outline-danger');
    logoutBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            logout();
        });
    });
});
