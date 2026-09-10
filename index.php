<?php
require_once __DIR__ . '/includes/seguridad.php';
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/models/Libro.php';
require_once __DIR__ . '/models/Socio.php';
require_once __DIR__ . '/models/Prestamo.php';
require_once __DIR__ . '/controllers/LibroController.php';
require_once __DIR__ . '/controllers/SocioController.php';
require_once __DIR__ . '/controllers/PrestamoController.php';

// Leemos la acción tanto de $_GET como de $_POST (por si se envía el formulario vía POST)
$accion = $_REQUEST['accion'] ?? 'inicio';

switch ($accion) {

    case 'inicio':
        require __DIR__ . '/views/inicio.php';
        break;

    // LIBROS //
        
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
        formEditarLibro(); // CORREGIDO: Muestra el formulario de edición
        break;
    case 'editarLibro':
        editarLibro();     // AGREGADO: Procesa los datos enviados por el formulario
        break;
    case 'eliminarLibro':
        eliminarLibro();
        break;

    // SOCIOS //
        
    case 'listarSocios':
        listarSocios();
        break;
    case 'formCrearSocio':
        formCrearSocio();
        break;
    case 'crearSocio':
        crearSocio();
        break;
    case 'formEditarSocio':
        formEditarSocio(); // CORREGIDO
        break;
    case 'editarSocio':
        editarSocio();     // AGREGADO
        break;
    case 'eliminarSocio':
        eliminarSocio();
        break;

    // PRESTAMOS //

    case 'listarPrestamos':
        listarPrestamos();
        break;
    case 'formCrearPrestamo':
        formCrearPrestamo();
        break;
    case 'crearPrestamo':
        crearPrestamo();
        break;
    case 'formEditarPrestamo':
        formEditarPrestamo(); // CORREGIDO
        break;
    case 'editarPrestamo':
        editarPrestamo();     // AGREGADO
        break;
    case 'eliminarPrestamo':
        eliminarPrestamo();
        break;

    default:
        http_response_code(404);
        echo "Página no encontrada chaval";

}
?>