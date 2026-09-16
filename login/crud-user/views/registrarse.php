<?php
session_start();

require __DIR__ . '/../../../config/conexion.php';
require __DIR__ . '/../models/user.php';

$mensaje = "";
$tipo_alerta = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmar_password = $_POST["confirmar_password"] ?? "";

    if ($nombre === "" || $email === "" || $password === "") {
        $mensaje = "Todos los campos son obligatorios.";
        $tipo_alerta = "alert-danger";
    } elseif ($password !== $confirmar_password) {
        $mensaje = "Las contraseñas no coinciden.";
        $tipo_alerta = "alert-danger";
    } elseif (Usuario::emailExiste($pdo, $email)) {
        $mensaje = "Ese correo ya está registrado.";
        $tipo_alerta = "alert-danger";
    } else {
        Usuario::crear($pdo, $nombre, $email, $password);
        $mensaje = "¡Cuenta creada exitosamente! Ya puedes iniciar sesión.";
        $tipo_alerta = "alert-success";
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

    <link rel="stylesheet" href="../../log.css">
</head>

<body>

    <div class="login-card">

        <!-- Mitad izquierda: Imagen/Ilustración -->
        <div class="reg-left">
            <img src="../../../img/reg.png" alt="Ilustración Registro">
        </div>

        <!-- Mitad derecha: Formulario -->
        <div class="login-right">
            <h1>Crear Cuenta</h1>

            <?php
            if ($mensaje != "") {
                echo "<div class='alert $tipo_alerta' style='border-radius: 15px; font-size: 14px;'>$mensaje</div>";
            }
            ?>

            <form method="POST">

                <div class="input-container">
                    <i class="fa-solid fa-user"></i>
                    <input
                        type="text"
                        name="nombre"
                        placeholder="Nombre"
                        value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>"
                        required>
                </div>

                <div class="input-container">
                    <i class="fa-solid fa-envelope"></i>
                    <input
                        type="email"
                        name="email"
                        placeholder="Correo Electrónico"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
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
                <a href="login.php">Ya tengo una cuenta <i class="fa-solid fa-arrow-right"></i></a>
            </div>

        </div>
    </div>

</body>
</html>
