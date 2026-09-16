<?php
session_start();

require __DIR__ . '/../../../config/conexion.php';
require __DIR__ . '/../models/user.php';

$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    $encontrado = Usuario::validarCredenciales($pdo, $email, $password);

    if ($encontrado) {
        $_SESSION["usuario"] = $encontrado->getNombre();
        $_SESSION["usuario_id"] = $encontrado->getId();
        header("Location: ../../../index.php");
        exit();
    } else {
        $mensaje = "Usuario o contraseña incorrectos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome para los íconos de usuario y candado -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="../../log.css">
</head>

<body>

    <div class="login-card">

        <!-- Mitad izquierda: Imagen/Ilustración -->
        <div class="login-left">
            <img src="https://gestioneducativa.utu.edu.uy/wp-content/uploads/2023/03/LOGO-BIBLIOTECA-BLANCO-1280x683.png" alt="Ilustración Login">
        </div>

        <!-- Mitad derecha: Formulario -->
        <div class="login-right">
            <h1>Iniciar Sesión</h1>

            <?php
            if ($mensaje != "") {
                echo "<div class='alert alert-danger' style='border-radius: 15px;'>$mensaje</div>";
            }
            ?>

            <form method="POST">

                <div class="input-container">
                    <i class="fa-solid fa-user"></i>
                    <input
                        type="email"
                        name="email"
                        placeholder="Correo electrónico"
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

                <button type="submit" class="btn-ingresar">INGRESAR</button>

                <div class="links-container">
                    <a href="../../../img/car.mp4">¿Olvidaste tu <strong>usuario</strong> o <strong>contraseña</strong>?</a>
                </div>
            </form>

            <div class="create-account">
                <a href="registrarse.php">Crear una cuenta <i class="fa-solid fa-arrow-right"></i></a>
            </div>

        </div>
    </div>

</body>
</html>
