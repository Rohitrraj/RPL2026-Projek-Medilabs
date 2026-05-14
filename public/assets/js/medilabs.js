document.querySelectorAll('[data-print]').forEach((button) => {
    button.addEventListener('click', () => window.print());
});
document.querySelectorAll('.button, .service-card, .feature-card, .services-index-card').forEach((item) => {
    item.addEventListener('mouseenter', () => {
        item.classList.add('is-hovering');
    });

    item.addEventListener('mouseleave', () => {
        item.classList.remove('is-hovering');
    });
});