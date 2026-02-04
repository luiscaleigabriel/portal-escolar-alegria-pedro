// Toggle sidebar on mobile
document.getElementById('sidebarCollapse').addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('active');
    document.getElementById('content').classList.toggle('active');
});

// Close sidebar when clicking outside on mobile
document.addEventListener('click', function (event) {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const sidebarCollapse = document.getElementById('sidebarCollapse');

    if (window.innerWidth <= 992 &&
        sidebar.classList.contains('active') &&
        !sidebar.contains(event.target) &&
        !sidebarCollapse.contains(event.target)) {
        sidebar.classList.remove('active');
        content.classList.remove('active');
    }
});

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(function () {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Update active menu item
document.addEventListener('livewire:navigated', function () {
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
});

// resources/js/sidebar.js ou adicione no final do arquivo

document.addEventListener('DOMContentLoaded', function () {
    // Menu ativo baseado na URL atual
    function setActiveMenu() {
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.nav-link');

        menuLinks.forEach(link => {
            link.classList.remove('active');
            const href = link.getAttribute('href');

            if (href && currentPath.startsWith(href) && href !== '/') {
                link.classList.add('active');
            }
        });

        // Verificar também pela rota nomeada
        const currentRoute = document.body.getAttribute('data-current-route');
        if (currentRoute) {
            menuLinks.forEach(link => {
                if (link.getAttribute('data-route') === currentRoute) {
                    link.classList.add('active');
                }
            });
        }
    }

    // Submenu toggle
    document.querySelectorAll('.menu-toggle').forEach(toggle => {
        toggle.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            const submenu = this.closest('.nav-link').nextElementSibling;
            if (submenu && submenu.classList.contains('nav-submenu')) {
                submenu.classList.toggle('show');
                this.classList.toggle('rotated');
            }
        });
    });

    // Mobile menu collapse
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    if (sidebarCollapse) {
        sidebarCollapse.addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('content').classList.toggle('active');

            // Salvar estado no localStorage
            const isCollapsed = document.getElementById('sidebar').classList.contains('active');
            localStorage.setItem('sidebarCollapsed', isCollapsed);
        });
    }

    // Restaurar estado do sidebar
    const sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
    if (sidebarCollapsed === 'true' && window.innerWidth > 992) {
        document.getElementById('sidebar').classList.add('active');
        document.getElementById('content').classList.add('active');
    }

    // Fechar sidebar ao clicar fora (mobile)
    document.addEventListener('click', function (event) {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        if (window.innerWidth <= 992 &&
            sidebar.classList.contains('active') &&
            !sidebar.contains(event.target) &&
            !event.target.closest('#sidebarCollapse')) {
            sidebar.classList.remove('active');
            content.classList.remove('active');
            localStorage.setItem('sidebarCollapsed', 'false');
        }
    });

    // Atualizar contadores em tempo real (simulação)
    function updateNotificationCounters() {
        // Aqui você implementaria uma chamada AJAX para atualizar contadores
        // Por enquanto, é apenas uma simulação
        const messageBadges = document.querySelectorAll('.badge-notification');
        messageBadges.forEach(badge => {
            const currentCount = parseInt(badge.textContent);
            if (!isNaN(currentCount) && currentCount > 0) {
                badge.style.animation = 'pulse 2s infinite';
            }
        });
    }

    // Atualizar contadores a cada 30 segundos
    setInterval(updateNotificationCounters, 30000);

    // Tooltips para ícones
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Inicializar
    setActiveMenu();
    updateNotificationCounters();

    // Livewire navigation handler
    document.addEventListener('livewire:navigated', function () {
        setTimeout(setActiveMenu, 100);
    });
});

// Funções globais para controle do menu
window.toggleSidebar = function () {
    document.getElementById('sidebar').classList.toggle('active');
    document.getElementById('content').classList.toggle('active');

    const isCollapsed = document.getElementById('sidebar').classList.contains('active');
    localStorage.setItem('sidebarCollapsed', isCollapsed);
};

window.expandAllMenus = function () {
    document.querySelectorAll('.nav-submenu').forEach(menu => {
        menu.classList.add('show');
    });
    document.querySelectorAll('.menu-toggle').forEach(toggle => {
        toggle.classList.add('rotated');
    });
};

window.collapseAllMenus = function () {
    document.querySelectorAll('.nav-submenu').forEach(menu => {
        menu.classList.remove('show');
    });
    document.querySelectorAll('.menu-toggle').forEach(toggle => {
        toggle.classList.remove('rotated');
    });
};

function showSystemDetails() {
    const modal = new bootstrap.Modal(document.getElementById('systemDetailsModal'));
    modal.show();
}

function refreshSystemInfo() {
    // Aqui você implementaria a atualização das informações do sistema
    alert('Atualizando informações do sistema...');
    location.reload();
}

// Adicionar botão para detalhes do sistema na barra de ações
document.addEventListener('DOMContentLoaded', function () {
    const actionBar = document.querySelector('.card-header .d-flex');
    if (actionBar) {
        const infoButton = document.createElement('button');
        infoButton.className = 'btn btn-sm btn-outline-info ms-2';
        infoButton.innerHTML = '<i class="fas fa-info-circle"></i>';
        infoButton.title = 'Informações do Sistema';
        infoButton.onclick = showSystemDetails;
        actionBar.appendChild(infoButton);
    }
});

// Tooltips
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
