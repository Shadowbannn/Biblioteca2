<footer class="bg-dark text-white text-center p-3 mt-5">
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
                        <li><a href="<?= !empty($_SESSION['socio_id']) ? 'index.php?accion=misPrestamos' : 'index.php?accion=formLoginSocio' ?>"><?= !empty($_SESSION['socio_id']) ? 'Mis préstamos' : 'Acceso de socios' ?></a></li>
                    </ul>
                </section>
            </div>
        </div>


        <hr class="border-secondary">
   

    <nav class="footer-legal" aria-label="Información legal">
        <a href="legal.php#licencia">Licencia</a>
        <a href="legal.php#terminos">Términos del servicio</a>
        <a href="legal.php#privacidad">Privacidad</a>
        <a href="legal.php#cookies">Preferencias de cookies</a>
         © 2026 Quantum Dev
    </nav>
    
</footer>
