// Import Bootstrap JS
import * as bootstrap from 'bootstrap';
import './template/main.js'

// Seus scripts personalizados

// Toggle Sidebar
document.getElementById('toggleSidebar').addEventListener('click', function () {
    document.getElementById('adminSidebar').classList.toggle('active');
});

// Fullscreen
document.getElementById('fullscreenBtn').addEventListener('click', function () {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
            console.log(`Error attempting to enable fullscreen: ${err.message}`);
        });
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
});

// Animações de entrada
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.animate-card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
    });
});

// Auto-refresh pendentes
setInterval(() => {
    const pendingBadge = document.querySelector('.nav-badge');
    if (pendingBadge) {
        fetch('/api/admin/pending-count')
            .then(response => response.json())
            .then(data => {
                if (data.count > 0) {
                    pendingBadge.textContent = data.count;
                    pendingBadge.style.display = 'flex';
                } else {
                    pendingBadge.style.display = 'none';
                }
            });
    }
}, 30000); // A cada 30 segundos
