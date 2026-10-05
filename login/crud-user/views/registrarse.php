<?php
session_start();

// Usamos require_once para evitar choques si el controlador ya los llamó
require_once __DIR__ . '/../../../config/conexion.php';
require_once __DIR__ . '/../models/user.php';
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

        <div class="reg-left">
            <img src="../../../img/reg.png" alt="Ilustración Registro">
        </div>

        <div class="login-right">
            <h1>Crear Cuenta</h1>

            <!-- Muestra el mensaje de error si el controlador lo definió -->
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 15px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <!-- El formulario ahora envía la petición al controlador con la acción 'crear' -->
            <form method="POST" action="../controller/usercontroller.php?accion=crear">

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