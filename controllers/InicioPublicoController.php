<?php

// Preparar la sesión y la clase visual de la página de inicio pública.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['socio_id'])) {
    require_once __DIR__ . '/../config/conexion.php';
    require_once __DIR__ . '/../models/socio.php';
}

$bodyClass = 'pagina-inicio';

