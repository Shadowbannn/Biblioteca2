<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/includes/permisos.php';
require_once __DIR__ . '/config/conexion.php';
require_once __DIR__ . '/models/Libro.php';
require_once __DIR__ . '/models/socio.php';
require_once __DIR__ . '/models/Prestamo.php';
require_once __DIR__ . '/models/PrestamoJuego.php';
require_once __DIR__ . '/models/PrestamoProyecto.php';
require_once __DIR__ . '/models/Usuario.php';
require_once __DIR__ . '/models/Juego.php';
require_once __DIR__ . '/models/proyecto.php';
require_once __DIR__ . '/models/solicitud.php';
require_once __DIR__ . '/models/solicitudjuego.php';
require_once __DIR__ . '/models/solicitudproyecto.php';

require_once __DIR__ . '/controllers/LibroController.php';
require_once __DIR__ . '/controllers/SocioController.php';
require_once __DIR__ . '/controllers/PrestamoController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/JuegoController.php';
require_once __DIR__ . '/controllers/ProyectoController.php';
require_once __DIR__ . '/controllers/PrestamoControllerJuego.php';
require_once __DIR__ . '/controllers/PrestamoControllerProyecto.php';


require_once __DIR__ . '/controllers/SolicitudController.php';
require_once __DIR__ . '/controllers/RevisionSolicitudController.php';
require_once __DIR__ . '/controllers/NotificacionController.php';
require_once __DIR__ . '/controllers/SolicitudJuegoController.php';
require_once __DIR__ . '/controllers/SolicitudProyectoController.php';

$accion = $_REQUEST['accion'] ?? 'inicio';

if (!empty($_SESSION['usuario_id'])) {
    $stmtRol = $pdo->prepare('SELECT roles FROM usuarios WHERE id = ?');
    $stmtRol->execute([(int) $_SESSION['usuario_id']]);
    $rolesUsuario = $stmtRol->fetchColumn();
    if ($rolesUsuario === false) {
        unset($_SESSION['usuario_id'], $_SESSION['usuario'], $_SESSION['usuario_roles']);
        session_regenerate_id(true);
        header('Location: index.php?accion=formLogin');
        exit;
    }
    $_SESSION['usuario_roles'] = (string) $rolesUsuario;
} else {
    unset($_SESSION['usuario_roles']);
}
$esAdmin = usuarioTieneRol('Admin');

// Acciones accesibles sin iniciar sesión
$accionesPublicas = ['formLogin', 'login', 'formLoginSocio', 'loginSocio',
                    'listarCatalogo', 'listarJuegosP', 'listarProyectosP', 
                    'formSolicitudLibro', 'guardarSolicitudLibro', 'formSolicitudJuego', 'guardarSolicitudJuego', 'formSolicitudProyecto', 'guardarSolicitudProyecto'];

if (empty($_SESSION['usuario_id']) && !in_array($accion, $accionesPublicas, true)) {
    if (empty($_SESSION['socio_id']) || !in_array($accion, ['misPrestamos', 'editarCuentaSocio', 'actualizarFotoSocio', 'cambiarPasswordSocio', 'logout'], true)) {
        header('Location: inicioo.php');
        exit;
    }
}

if (!empty($_SESSION['socio_id']) && empty($_SESSION['usuario_id']) && !in_array($accion, ['misPrestamos', 'editarCuentaSocio', 'actualizarFotoSocio', 'cambiarPasswordSocio', 'logout'], true) && !in_array($accion, $accionesPublicas, true)) {
    header('Location: index.php?accion=misPrestamos');
    exit;
}

$accionesSoloAdmin = [
    'listarUsuarios',
    'formCrearUsuario',
    'crearUsuario',
    'eliminarUsuario',
];
if (in_array($accion, $accionesSoloAdmin, true) && !$esAdmin) {
    http_response_code(403);
    exit('Solo el administrador puede gestionar usuarios.');
}
if (!$esAdmin && $accion === 'formEditarUsuario' && (int) ($_GET['id'] ?? 0) !== (int) ($_SESSION['usuario_id'] ?? 0)) {
    http_response_code(403);
    exit('Solo puedes editar tu propia cuenta.');
}
if (!$esAdmin && $accion === 'editarUsuario' && (int) ($_POST['id'] ?? 0) !== (int) ($_SESSION['usuario_id'] ?? 0)) {
    http_response_code(403);
    exit('Solo puedes editar tu propia cuenta.');
}

// Acciones accesibles con sudo su

switch ($accion) {
    case 'gestionarNotificacion':
        gestionarNotificacion();
        break;
    case 'revisarSolicitud':
        revisarSolicitud();
        break;
    case 'eliminarSolicitudAceptada':
        eliminarSolicitudAceptada();
        break;
    case 'listarSolicitudes':
        listarSolicitudes();
        break;
    case 'formSolicitudLibro':
        formSolicitudLibro();
        break;
    case 'guardarSolicitudLibro':
        guardarSolicitudLibro();
        break; 

        
    case 'listarSolicitudesJuegos':
        listarSolicitudesJuegos();
        break;
    case 'formSolicitudJuego':
        formSolicitudJuego();
        break;
    case 'guardarSolicitudJuego':
        guardarSolicitudJuego();
        break; 
    case 'listarSolicitudesProyectos':
        listarSolicitudesProyectos();
        break;
    case 'formSolicitudProyecto':
        formSolicitudProyecto();
        break;
    case 'guardarSolicitudProyecto':
        guardarSolicitudProyecto();
        break;
    // LOGIN //

    case 'formLogin':
        formLogin();
        break;
    case 'login':
        login();
        break;
    case 'formLoginSocio':
        formLoginSocio();
        break;
    case 'loginSocio':
        loginSocio();
        break;
    case 'misPrestamos':
        misPrestamos();
        break;
    case 'editarCuentaSocio':
        editarCuentaSocio();
        break;
    case 'actualizarFotoSocio':
        actualizarFotoSocio();
        break;
    case 'cambiarPasswordSocio':
        cambiarPasswordSocio();
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
