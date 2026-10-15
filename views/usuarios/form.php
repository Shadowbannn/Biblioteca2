<?php
$esEdicion = isset($usuario);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esEdicion ? 'Editar Usuario' : 'Registro' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/log.css">
</head>

<body>

    <div class="login-card">

        <div class="reg-left">
            <img src="img/reg.png" alt="Ilustración Registro">
        </div>

        <div class="login-right">
            <h1><?= $esEdicion ? 'Editar Cuenta' : 'Crear Cuenta' ?></h1>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 15px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?accion=<?= $esEdicion ? 'editarUsuario' : 'crearUsuario' ?>">

                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                <?php endif; ?>

                <div class="input-container">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="nombre" placeholder="Nombre"
                           value="<?= htmlspecialchars($_POST['nombre'] ?? ($esEdicion ? $usuario->getNombre() : '')) ?>" required>
                </div>

                <div class="input-container">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" placeholder="Correo Electrónico"
                           value="<?= htmlspecialchars($_POST['email'] ?? ($esEdicion ? $usuario->getEmail() : '')) ?>" required>
                </div>

                <div class="input-container">
                    <i class="fa-solid fa-user-shield"></i>
                    <input type="text" name="roles" placeholder="Roles (separados por comas)"
                           value="<?= htmlspecialchars($_POST['roles'] ?? ($esEdicion ? $usuario->getRoles() : '')) ?>">
                </div>

                <div class="input-container">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password"
                           placeholder="<?= $esEdicion ? 'Nueva contraseña (opcional)' : 'Contraseña' ?>"
                           <?= $esEdicion ? '' : 'required' ?>>
                </div>

                <div class="input-container">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="confirmar_password"
                           placeholder="Confirmar Contraseña"
                           <?= $esEdicion ? '' : 'required' ?>>
                </div>

                <button type="submit" class="btn-ingresar"><?= $esEdicion ? 'GUARDAR CAMBIOS' : 'REGISTRARSE' ?></button>

            </form>

            <div class="create-account">
                <?php if ($esEdicion || !empty($_SESSION['usuario_id'])): ?>
                    <a href="index.php?accion=listarUsuarios">Volver al listado <i class="fa-solid fa-arrow-right"></i></a>
                <?php else: ?>
                    <a href="index.php?accion=formLogin">Ya tengo una cuenta <i class="fa-solid fa-arrow-right"></i></a>
                <?php endif; ?>
            </div>

        </div>
    </div>

</body>
</html>
