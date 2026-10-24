<?php

// Preparar la sesión y la clase visual de la página de inicio pública.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$bodyClass = 'pagina-inicio';

