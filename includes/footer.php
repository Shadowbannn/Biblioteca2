</div>

<footer class="bg-dark text-white text-center p-3 mt-5">
    <?php if (empty($_SESSION['usuario_id'])): ?>
        <div class="footer-publico text-start mx-auto py-4">
            <div class="row g-4">
                <section class="col-md-6 col-lg-3" aria-labelledby="footer-biblioteca">
                    <h2 id="footer-biblioteca" class="h5">Nuestra biblioteca</h2>
                    <p class="small text-white-50">Un espacio para descubrir lecturas, compartir juegos y conocer nuevos proyectos.</p>
                    <a href="inicioo.php" class="d-inline-block">Volver al inicio</a>
                </section>
                <section class="col-md-6 col-lg-3" aria-labelledby="footer-contacto">
                    <h2 id="footer-contacto" class="h5">Contacto</h2>
                    <!-- Sustituir los datos pendientes por los de la biblioteca. -->
                    <address class="small mb-0">
                        <p class="mb-2">Correo: <span class="text-white-50">etsr826@hotmail.com</span></p>
                        <p class="mb-2">Teléfono: <span class="text-white-50">4622 3001</span></p>
                        <p class="mb-0">Dirección: <span class="text-white-50">Pantaleón Quesada Nº 770 esquina Av. Brasil</span></p>
                    </address>
                </section>

                <section class="col-md-6 col-lg-3" aria-labelledby="footer-horario">
                    <h2 id="footer-horario" class="h5">Horario de atención</h2>
                    <p class="mb-2">Lunes: <span class="text-white-50">09:00 - 13:00 | 14:00 - 18:00</span></p>
                    <p class="mb-2">Martes: <span class="text-white-50">09:35 - 12:20</span></p>
                    <p class="mb-2">Miércoles: <span class="text-white-50">09:00 - 13:00</span></p>
                    <p class="mb-2">Jueves: <span class="text-white-50">07:45 - 12:00</span></p>
                    <p class="mb-2">Viernes: <span class="text-white-50">09:00 - 13:00 | 15:00 - 20:30</span></p>

                    
                    

                    <p class="small mb-0">Consulta con la biblioteca antes de planificar tu visita.</p>
                </section>
                <section class="col-md-6 col-lg-3" aria-labelledby="footer-enlaces">
                    <h2 id="footer-enlaces" class="h5">Enlaces útiles</h2>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="index.php?accion=listarCatalogo">Catálogo de libros</a></li>
                        <li class="mb-2"><a href="index.php?accion=listarJuegosP">Juegos</a></li>
                        <li class="mb-2"><a href="index.php?accion=listarProyectosP">Proyectos</a></li>
                        <li><a href="index.php?accion=formLogin">Ingresar a mi cuenta</a></li>
                    </ul>
                </section>
            </div>
        </div>


        <hr class="border-secondary">
    <?php else: ?>
        <div class="footer-publico text-start mx-auto py-4">
            <div class="row g-4">
                <section class="col-md-6 col-lg-3" aria-labelledby="footer-quantum">
                    <h2 id="footer-quantum" class="h5">Quantum Dev</h2>
                    <p class="small text-white-50 mb-0">Equipo de desarrollo del sistema de gestión de la biblioteca.</p>
                </section>
                <section class="col-md-6 col-lg-3" aria-labelledby="footer-empresa-contacto">
                    <h2 id="footer-empresa-contacto" class="h5">Contacto de la empresa</h2>
                    <!-- Completar con los datos oficiales de Quantum Dev. -->
                    <address class="small mb-0">
                        <p class="mb-2">Correo: <span class="text-white-50">antioniotoniosigma@gmail.com</span></p>
                        <p class="mb-2">Teléfono: <span class="text-white-50">094 759 451</span></p>
                        <p class="mb-0">Sitio web: <span class="text-white-50">Quantum.dev</span></p>
                    </address>
                </section>
                <section class="col-md-6 col-lg-3" aria-labelledby="footer-soporte">
                    <h2 id="footer-soporte" class="h5">Soporte técnico</h2>
                    <p class="small text-white-50">Para reportar un problema, indica la sección del sistema y describe lo ocurrido, sin incluir contraseñas.</p>
                    <p class="small mb-0">Horario de soporte: <span class="text-white-50">06:00 - 07:00</span></p>
                </section>
                <section class="col-md-6 col-lg-3" aria-labelledby="footer-sistema">
                    <h2 id="footer-sistema" class="h5">Accesos al sistema</h2>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><a href="index.php?accion=inicio">Panel de inicio</a></li>
                        <li class="mb-2"><a href="index.php?accion=listarLibros">Libros</a></li>
                        <li class="mb-2"><a href="index.php?accion=listarJuegos">Juegos</a></li>
                        <li><a href="index.php?accion=listarProyectos">Proyectos</a></li>
                    </ul>
                </section>
            </div>
        </div>
        <hr class="border-secondary">
    <?php endif; ?>
    © 2026 Quantum Dev - All rights reserved
</footer>

</body>
</html>
