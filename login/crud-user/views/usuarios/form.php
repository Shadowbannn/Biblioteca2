<?php
$esEdicion = isset($usuario);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $esEdicion ? 'Editar Usuario' : 'Agregar Usuario' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form.css">
</head>

<body>

<div class="container mt-5">
    <div class="form-card">

        <h2><span class="centrado"><?= $esEdicion ? 'Editar Usuario' : 'Agregar Usuario' ?></span></h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <form action="index.php?accion=<?= $esEdicion ? 'editarUsuario' : 'crearUsuario' ?>" method="POST">

                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= $usuario->getId() ?>">
                <?php endif; ?>

                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control mb-3"
                       placeholder="Nombre"
                       value="<?= $esEdicion ? htmlspecialchars($usuario->getNombre()) : '' ?>" required>

                <label for="email" class="form-label">Correo electrónico</label>
                <input type="email" name="email" id="email" class="form-control mb-3"
                       placeholder="Correo electrónico"
                       value="<?= $esEdicion ? htmlspecialchars($usuario->getEmail()) : '' ?>" required>

                <label for="password" class="form-label">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control mb-3"
                       placeholder="<?= $esEdicion ? 'Dejar en blanco para no cambiarla' : 'Contraseña' ?>"
                       <?= $esEdicion ? '' : 'required' ?>>

                <label for="confirmar_password" class="form-label">Confirmar contraseña</label>
                <input type="password" name="confirmar_password" id="confirmar_password" class="form-control mb-3"
                       placeholder="<?= $esEdicion ? 'Repetir solo si cambiaste la contraseña' : 'Confirmar contraseña' ?>"
                       <?= $esEdicion ? '' : 'required' ?>>

                <button type="submit" class="btn btn-success w-100 mb-2">
                    <?= $esEdicion ? 'Guardar Cambios' : 'Crear usuario' ?>
                </button>

                <a href="index.php?accion=listarUsuarios" class="btn btn-danger w-100">
                    Cancelar
                </a>

            </form>
        </div>
    </div>
</div>

</body>
</html>
