<?php
$esEdicion = isset($socio);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $esEdicion ? 'Editar Socio' : 'Agregar Socio' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form.css">
</head>

<body>

<div class="container mt-5">
    <div class="form-card">

        <h2><span class="centrado"><?= $esEdicion ? 'Editar Socio' : 'Agregar Socio' ?></span></h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <form action="index.php?accion=<?= $esEdicion ? 'editarSocio' : 'crearSocio' ?>"
                  method="POST" enctype="multipart/form-data">

                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= $socio->getId() ?>">
                <?php endif; ?>

                <input type="text" name="nombre" id="nombre" class="form-control mb-3"
                       placeholder="Nombre"
                       value="<?= $esEdicion ? htmlspecialchars($socio->getNombre()) : '' ?>" required>
                       
                <input type="text" name="apellido" id="apellido" class="form-control mb-3"
                       placeholder="Apellido"
                       value="<?= $esEdicion ? htmlspecialchars($socio->getApellido()) : '' ?>" required> 

                <input type="text" name="cedula" id="cedula" class="form-control mb-3"
                       placeholder="Cedula"
                       value="<?= $esEdicion ? htmlspecialchars($socio->getCedula()) : '' ?>" required>

                <input type="number" name="telefono" id="telefono" class="form-control mb-3"
                       placeholder="Telefono"
                       value="<?= $esEdicion ? $socio->getTelefono() : '' ?>" required>

                <input type="date" name="fecha_registro" id="fecha_registro" class="form-control mb-3"
                       placeholder="Fecha de registro"
                       value="<?= $esEdicion ? $socio->getFecharegistro() : '' ?>" required>


                <button type="submit" class="btn btn-success w-100 mb-2">
                    <?= $esEdicion ? 'Guardar Cambios' : 'Crear socio' ?>
                </button>

                <a href="index.php?accion=listarSocios" class="btn btn-danger w-100">
                    Cancelar
                </a>

            </form>
        </div>
    </div>
</div>

</body>
</html>
