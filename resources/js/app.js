// Import Bootstrap JS
import * as bootstrap from 'bootstrap';

// Inicialize tooltips, popovers, etc.
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Seus scripts personalizados
console.log('Bootstrap carregado! aq');
