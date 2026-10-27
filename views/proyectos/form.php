<?php
$esEdicion = isset($proyecto);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $esEdicion ? 'Editar Proyecto' : 'Agregar Proyecto' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form.css">
</head>

<body>

<div class="container mt-5">
    <div class="form-card">

        <h2><span class="centrado"><?= $esEdicion ? 'Editar Proyecto' : 'Agregar Proyecto' ?></span></h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <form action="index.php?accion=<?= $esEdicion ? 'editarProyecto' : 'crearProyecto' ?>"
                  method="POST" enctype="multipart/form-data">


                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= $proyecto->getId() ?>">
                <?php endif; ?>


                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control mb-3"
                       placeholder="Título"
                       value="<?= $esEdicion ? htmlspecialchars($proyecto->getNombre()) : '' ?>" required>

                <!-- CORREGIDO: name="integrantes" en minúscula -->
                <label for="Integrantes" class="form-label">Integrantes</label>
                <input type="text" name="integrantes" id="Integrantes" class="form-control mb-3"
                       placeholder="Integrantes"
                       value="<?= $esEdicion ? htmlspecialchars($proyecto->getIntegrantes()) : '' ?>" required>

                <label for="anio" class="form-label">Año de creación</label>
                <input type="number" name="anio" id="anio" class="form-control mb-3"
                       placeholder="Año"
                       value="<?= $esEdicion ? $proyecto->getAnio() : '' ?>" required>

                <!-- CORREGIDO: name="clase" en minúscula -->
                <label for="Clase" class="form-label">Clase</label>
                <input type="text" name="clase" id="Clase" class="form-control mb-3"
                       placeholder="Clase"
                       value="<?= $esEdicion ? htmlspecialchars($proyecto->getClase(), ENT_QUOTES, 'UTF-8') : '' ?>" required>

                <!-- CORREGIDO: name="curso" en minúscula -->
                <label for="Curso" class="form-label">Curso</label>       
                <input type="text" name="curso" id="Curso" class="form-control mb-3"
                       placeholder="Curso"
                       value="<?= $esEdicion ? htmlspecialchars($proyecto->getCurso()) : '' ?>">

                <?php if ($esEdicion): ?>
                    <label>Portada actual</label><br>
                    <div class="mb-2">
                        <?php if (!empty($proyecto->getPortada())): ?>
                            <img src="uploads/portadas/<?= htmlspecialchars($proyecto->getPortada()) ?>"
                                 alt="Portada actual" class="portada-preview" style="max-width:150px;">
                        <?php else: ?>
                            <p class="text-muted">Este proyecto no tiene portada.</p>
                        <?php endif; ?>
                    </div>
                    <label>Cambiar portada (opcional)</label>
                <?php else: ?>
                    <label>Portada</label>
                <?php endif; ?>

                <!-- CORREGIDO: name="caratula" para que coincida con $_FILES['caratula'] en tu controlador -->
                <input type="file" name="caratula" class="form-control mb-3" accept="image/*">

                <button type="submit" class="btn btn-success w-100 mb-2">
                    <?= $esEdicion ? 'Guardar Cambios' : 'Crear proyecto' ?>
                </button>

                <a href="index.php?accion=listarProyectos" class="btn btn-danger w-100">
                    Cancelar
                </a>

            </form>
        </div>
    </div>
</div>

</body>
</html>