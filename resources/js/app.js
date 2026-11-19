import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const observador = new IntersectionObserver((entradas) => {
        entradas.forEach((entrada) => {
            if (entrada.isIntersecting) {
                entrada.target.classList.add('visible');
                observador.unobserve(entrada.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.revelar').forEach((el) => observador.observe(el));
});