<?php
$esEdicion = isset($libro);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $esEdicion ? 'Editar Libro' : 'Agregar Libro' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form.css">
</head>

<body>

<div class="container mt-5">
    <div class="form-card">

        <h2><span class="centrado"><?= $esEdicion ? 'Editar Libro' : 'Agregar Libro' ?></span></h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <form action="index.php?accion=<?= $esEdicion ? 'editarLibro' : 'crearLibro' ?>"
                  method="POST" enctype="multipart/form-data">

                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= $libro->getId() ?>">
                <?php endif; ?>

                <input type="text" name="titulo" id="titulo" class="form-control mb-3"
                       placeholder="Título"
                       value="<?= $esEdicion ? htmlspecialchars($libro->getTitulo()) : '' ?>" required>

                <input type="text" name="autor" id="autor" class="form-control mb-3"
                       placeholder="Autor"
                       value="<?= $esEdicion ? htmlspecialchars($libro->getAutor()) : '' ?>" required>

                <input type="number" name="anio" id="anio" class="form-control mb-3"
                       placeholder="Año"
                       value="<?= $esEdicion ? $libro->getAnioPublicacion() : '' ?>" required>

                <input type="number" name="paginas" id="paginas" class="form-control mb-3"
                       placeholder="Páginas"
                       value="<?= $esEdicion ? $libro->getCantidadPaginas() : '' ?>" required>

                <input type="text" name="genero" id="genero" class="form-control mb-3"
                       placeholder="Género (ej: Fantasia, Juvenil)"
                       value="<?= $esEdicion ? htmlspecialchars($libro->getGenero()) : '' ?>">

                <?php if ($esEdicion): ?>
                    <label>Portada actual</label><br>
                    <div class="mb-2">
                        <?php if (!empty($libro->getPortada())): ?>
                            <img src="uploads/portadas/<?= htmlspecialchars($libro->getPortada()) ?>"
                                 alt="Portada actual" class="portada-preview" style="max-width:150px;">
                        <?php else: ?>
                            <p class="text-muted">Este libro no tiene portada.</p>
                        <?php endif; ?>
                    </div>
                    <label>Cambiar portada (opcional)</label>
                <?php else: ?>
                    <label>Portada</label>
                <?php endif; ?>

                <input type="file" name="portada" class="form-control mb-3" accept="image/*">

                <button type="submit" class="btn btn-success w-100 mb-2">
                    <?= $esEdicion ? 'Guardar Cambios' : 'Crear libro' ?>
                </button>

                <a href="index.php?accion=listarLibros" class="btn btn-danger w-100">
                    Cancelar
                </a>

            </form>
        </div>
    </div>
</div>

</body>
</html>
