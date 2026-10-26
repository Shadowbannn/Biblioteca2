<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/models/Libro.php';
require_once __DIR__ . '/models/socio.php';
require_once __DIR__ . '/models/Prestamo.php';
require_once __DIR__ . '/models/PrestamoJuego.php';
require_once __DIR__ . '/models/PrestamoProyecto.php';
require_once __DIR__ . '/models/Usuario.php';
require_once __DIR__ . '/models/Juego.php';
require_once __DIR__ . '/models/proyecto.php';


require_once __DIR__ . '/controllers/LibroController.php';
require_once __DIR__ . '/controllers/SocioController.php';
require_once __DIR__ . '/controllers/PrestamoController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/JuegoController.php';
require_once __DIR__ . '/controllers/ProyectoController.php';
require_once __DIR__ . '/controllers/PrestamoControllerJuego.php';
require_once __DIR__ . '/controllers/PrestamoControllerProyecto.php';


require_once __DIR__ . '/controllers/SolicitudController.php';

$accion = $_REQUEST['accion'] ?? 'inicio';

// Acciones accesibles sin iniciar sesión
$accionesPublicas = ['formLogin', 'login', 'formCrearUsuario', 'crearUsuario', 'listarCatalogo', 'listarJuegosP', 'listarProyectosP', 'formSolicitudLibro'];

if (empty($_SESSION['usuario_id']) && !in_array($accion, $accionesPublicas, true)) {
    header('Location: inicioo.php');
    exit;
}

// Acciones accesibles con sudo su

switch ($accion) {
    case 'formSolicitudLibro':
        formSolicitudLibro();
        break;

    // LOGIN //

    case 'formLogin':
        formLogin();
        break;
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;

    case 'inicio':
        require __DIR__ . '/views/inicio.php';
        break;

    // LIBROS //
        
    case 'listarCatalogo':
        listarCatalogo();
        break;
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
        formEditarSocio(); 
        break;
    case 'editarSocio':
        editarSocio();    
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


    // PRESTAMOS JUEGOS //

    case 'listarPrestamosJuegos':
        listarPrestamosJuegos();
        break;
    case 'formCrearPrestamoJuegos':
        formCrearPrestamoJuegos();
        break;
    case 'crearPrestamoJuegos':
        crearPrestamoJuegos();
        break;
    case 'formEditarPrestamoJuegos':
        formEditarPrestamoJuegos(); // CORREGIDO
        break;
    case 'editarPrestamoJuegos':
        editarPrestamoJuegos();     // AGREGADO
        break;
    case 'eliminarPrestamoJuegos':
        eliminarPrestamoJuegos();
        break;

        // PRESTAMOS PROYECTOS //

    case 'listarPrestamosProyectos':
        listarPrestamosProyectos();
        break;
    case 'formCrearPrestamoProyectos':
        formCrearPrestamoProyectos();
        break;
    case 'crearPrestamoProyectos':
        crearPrestamoProyectos();
        break;
    case 'formEditarPrestamoProyectos':
        formEditarPrestamoProyectos(); 
        break;
    case 'editarPrestamoProyectos':
        editarPrestamoProyectos();     // AGREGADO
        break;
    case 'eliminarPrestamoProyectos':
        eliminarPrestamoProyectos();
        break;

    // USUARIOS (cuentas del sistema) //

    case 'listarUsuarios':
        listarUsuarios();
        break;
    case 'formCrearUsuario':
        formCrearUsuario();
        break;
    case 'crearUsuario':
        crearUsuario();
        break;
    case 'formEditarUsuario':
        formEditarUsuario();
        break;
    case 'editarUsuario':
        editarUsuario();
        break;
    case 'eliminarUsuario':
        eliminarUsuario();
        break;

    // JUEGOS //
    case 'listarJuegosP':
        listarJuegosP();
        break;
    case 'listarJuegos':
        listarJuegos();
        break;
    case 'formCrearJuego':
        formCrearJuego();
        break;
    case 'crearJuego':
        crearJuego();
        break;
    case 'formEditarJuego':
        formEditarJuego();
        break;
    case 'editarJuego':
        editarJuego();
        break;
    case 'eliminarJuego':
        eliminarJuego();
        break;

        // PROYECTOS //
        
    case 'listarProyectosP':
        listarProyectosP();
        break;
    case 'listarProyectos':
        listarProyectos();
        break;
    case 'formCrearProyecto':
        formCrearProyecto();
        break;
    case 'crearProyecto':
        crearProyecto();
        break;
    case 'formEditarProyecto':
        formEditarProyecto(); 
        break;
    case 'editarProyecto':
        editarProyecto();     
        break;
    case 'eliminarProyecto':
        eliminarProyecto();
        break;


default:
        http_response_code(404);
        
        // Imprimimos la etiqueta <pre> para que el HTML respete el formato del arte ASCII
        echo "<pre>";
        echo <<<EOT

    ░██████████ ░█████████  ░█████████    ░██████   ░█████████         ░██████  ░█████████                     
    ░██         ░██     ░██ ░██     ░██  ░██   ░██  ░██     ░██       ░██   ░██ ░██    ░██                     
    ░██         ░██     ░██ ░██     ░██ ░██     ░██ ░██     ░██       ░██             ░██                      
    ░█████████  ░█████████  ░█████████  ░██     ░██ ░█████████        ░███████       ░██                       
    ░██         ░██   ░██   ░██   ░██   ░██     ░██ ░██   ░██         ░██   ░██     ░██                        
    ░██         ░██    ░██  ░██    ░██   ░██   ░██  ░██    ░██        ░██   ░██     ░██                        
    ░██████████ ░██     ░██ ░██     ░██   ░██████   ░██     ░██        ░██████      ░██                        
                                                                                                            
                                                                                                            
    ██████   █████   ██████  ██ ███    ██  █████      ███    ██  ██████      ███████ ███    ██  ██████  ██████  ███    ██ ████████ ██████   █████  ██████   █████  
    ██   ██ ██   ██ ██       ██ ████   ██ ██   ██     ████   ██ ██    ██     ██      ████   ██ ██      ██    ██ ████   ██    ██    ██   ██ ██   ██ ██   ██ ██   ██ 
    ██████  ███████ ██   ███ ██ ██ ██  ██ ███████     ██ ██  ██ ██    ██     █████   ██ ██  ██ ██      ██    ██ ██ ██  ██    ██    ██████  ███████ ██   ██ ███████ 
    ██      ██   ██ ██    ██ ██ ██  ██ ██ ██   ██     ██  ██ ██ ██    ██     ██      ██  ██ ██ ██      ██    ██ ██  ██ ██    ██    ██   ██ ██   ██ ██   ██ ██   ██ 
    ██      ██   ██  ██████  ██ ██   ████ ██   ██     ██   ████  ██████      ███████ ██   ████  ██████  ██████  ██   ████    ██    ██   ██ ██   ██ ██████  ██   ██ 
                                                                                                                                                                
                                                                                                                                                               
                                                                                                            
                                                                                                            
                                                                                                                                                                                                                                                                                                            
EOT;
        break; // Cerramos el caso default
}
?>
