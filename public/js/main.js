document.addEventListener('DOMContentLoaded', function() {
    // Mobile Menu Toggle
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            this.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
    }

    // Sticky Header Scroll Effect
    const header = document.querySelector('.main-header');
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Simple Tab Switching for Dashboard (if needed)
    const dashLinks = document.querySelectorAll('.dash-nav a');
    dashLinks.forEach(link => {
        link.addEventListener('click', function() {
            dashLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });
});
