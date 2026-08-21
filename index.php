<?php
require_once __DIR__ . '/includes/seguridad.php';
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/models/Libro.php';
require_once __DIR__ . '/controllers/LibroController.php';

$accion = $_GET['accion'] ?? 'inicio';

switch ($accion) {

    case 'inicio':
        require __DIR__ . '/views/inicio.php';
        break;

    // --- Libros ---
    case 'listarLibros':
        listarLibros();
        break;
    case 'formCrearLibro':
        formCrearLibro();
        break;
    case 'crearLibro':
        crearLibro();
        break;
    case 'formEditarLibro':
        formEditarLibro();
        break;
    case 'editarLibro':
        editarLibro();
        break;
    case 'eliminarLibro':
        eliminarLibro();
        break;

    // --- Socios y Prestamos: todavía no existen en este proyecto.
    // Cuando los armes, siguiendo el mismo patrón, agregás sus casos acá
    // (ej: listarSocios, crearSocio, etc.), cada uno llamando a su propio
    // controlador (SocioController.php, PrestamoController.php).
    case 'listarSocios':
    case 'listarPrestamos':
        http_response_code(501);
        echo "Este módulo todavía no está armado en MVC.";
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada";
}
