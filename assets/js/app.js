// Global App Utilities
const App = {
    // Show a toast message
    toast: (message, type = 'primary') => {
        const container = document.getElementById('toast-wrapper') || (() => {
            const div = document.createElement('div');
            div.id = 'toast-wrapper';
            div.className = 'toast-container';
            document.body.appendChild(div);
            return div;
        })();

        const toast = document.createElement('div');
        toast.className = 'custom-toast';
        
        const colors = {
            primary: '#4f46e5',
            success: '#10b981',
            danger: '#ef4444',
            warning: '#f59e0b'
        };

        toast.style.borderLeftColor = colors[type] || colors.primary;
        toast.innerHTML = `<span>${message}</span>`;
        
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    },

    // Show/Hide global loading overlay
    loading: (show) => {
        const loader = document.getElementById('loader-overlay') || (() => {
            const div = document.createElement('div');
            div.id = 'loader-overlay';
            div.innerHTML = '<div class="spinner-border text-primary" role="status"></div>';
            document.body.appendChild(div);
            return div;
        })();
        loader.style.display = show ? 'flex' : 'none';
    }
};
