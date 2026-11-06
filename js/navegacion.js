document.querySelectorAll('.navegacion-principal').forEach(nav => {
    const boton = nav.querySelector('.menu-movil');
    if (!boton) return;
    const cerrar = () => {
        nav.classList.remove('menu-abierto');
        boton.setAttribute('aria-expanded', 'false');
    };
    boton.addEventListener('click', () => {
        const abierto = nav.classList.toggle('menu-abierto');
        boton.setAttribute('aria-expanded', String(abierto));
    });
    nav.addEventListener('keydown', event => {
        if (event.key === 'Escape') { cerrar(); boton.focus(); }
    });
    nav.querySelectorAll('a').forEach(enlace => enlace.addEventListener('click', cerrar));
    document.documentElement.classList.add('menu-listo');
});
