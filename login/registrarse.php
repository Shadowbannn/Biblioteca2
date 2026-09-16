<?php
session_start();

$mensaje = "";
$tipo_alerta = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $correo = $_POST["correo"];
    $password = $_POST["password"];
    $confirmar_password = $_POST["confirmar_password"];

    // Validación básica: comprobar que las contraseñas coincidan
    if ($password !== $confirmar_password) {
        $mensaje = "Las contraseñas no coinciden.";
        $tipo_alerta = "alert-danger"; // Color rojo de Bootstrap
    } else {
        // Aquí iría tu código para guardar en la base de datos
        // Por ahora, solo mostraremos un mensaje de éxito
        $mensaje = "¡Cuenta creada exitosamente! Ya puedes iniciar sesión.";
        $tipo_alerta = "alert-success"; // Color verde de Bootstrap
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Enlazamos al MISMO archivo CSS que usa el login -->
    <link rel="stylesheet" href="log.css">
</head>

<body>

    <div class="login-card">
        
        <!-- Mitad izquierda: Imagen/Ilustración -->
        <div class="reg-left">
            <img src="../img/reg.png" alt="Ilustración Registro">
        </div>

        <!-- Mitad derecha: Formulario -->
        <div class="login-right">
            <h1>Crear Cuenta</h1>

            <?php
            // Muestra el mensaje de error o éxito
            if($mensaje != ""){
                echo "<div class='alert $tipo_alerta' style='border-radius: 15px; font-size: 14px;'>$mensaje</div>";
            }
            ?>

            <form method="POST">
                
                <div class="input-container">
                    <i class="fa-solid fa-user"></i>
                    <input 
                        type="text" 
                        name="usuario" 
                        placeholder="Usuario" 
                        required>
                </div>

                <div class="input-container">
                    <i class="fa-solid fa-envelope"></i>
                    <input 
                        type="email" 
                        name="correo" 
                        placeholder="Correo Electrónico" 
                        required>
                </div>

                <div class="input-container">
                    <i class="fa-solid fa-lock"></i>
                    <input 
                        type="password" 
                        name="password" 
                        placeholder="Contraseña" 
                        required>
                </div>

                <div class="input-container">
                    <i class="fa-solid fa-lock"></i>
                    <input 
                        type="password" 
                        name="confirmar_password" 
                        placeholder="Confirmar Contraseña" 
                        required>
                </div>

                <button type="submit" class="btn-ingresar">REGISTRARSE</button>

            </form>

            <div class="create-account">
                <!-- Cambia 'index.php' por el nombre real de tu archivo de login si es distinto -->
                <a href="login.php">Ya tengo una cuenta <i class="fa-solid fa-arrow-right"></i></a>
            </div>
            
        </div>
    </div>

</body>
</html>