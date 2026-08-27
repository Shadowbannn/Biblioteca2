<?php
require_once __DIR__ . '/includes/seguridad.php';
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/models/Libro.php';
require_once __DIR__ . '/models/Socio.php';
require_once __DIR__ . '/controllers/LibroController.php';
require_once __DIR__ . '/controllers/SocioController.php';


$accion = $_GET['accion'] ?? 'inicio';

switch ($accion) {

    case 'inicio':
        require __DIR__ . '/views/inicio.php';
        break;

        //LIBROS//
        
    case 'listarLibros':
        listarLibros();
        break;
    case 'formCrearLibro':
        formCrearLibro();
        break;
    case 'crearLibro';
        crearLibro();
        break;
    case 'formEditarLibro';
        editarLibro();
        break;
    case 'eliminarLibro';
        eliminarLibro();
        break;

    // SOCIOS //
        
    case 'listarSocios':
        listarSocios();
        break;
    case 'formCrearSocio':
        formCrearSocio();
        break;
    case 'crearSocio';
        crearSocio();
        break;
    case 'formEditarSocio';
        editarSocio();
        break;
    case 'eliminarSocio';
        eliminarSocio();
        break;

    // PRESTAMOS //

    default:
        http_response_code(404);
        echo "Página no encontrada chaval";

}
?>
