<?php
$esEdicion = isset($juego);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $esEdicion ? 'Editar Juego' : 'Agregar Juego' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form.css">
</head>

<body>

<div class="container mt-5">
    <div class="form-card">

        <h2><span class="centrado"><?= $esEdicion ? 'Editar Juego' : 'Agregar Juego' ?></span></h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="mb-3">
            <form action="index.php?accion=<?= $esEdicion ? 'editarJuego' : 'crearJuego' ?>"
                  method="POST" enctype="multipart/form-data">


                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= $juego->getId() ?>">
                <?php endif; ?>


                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control mb-3"
                       placeholder="Nombre"
                       value="<?= $esEdicion ? htmlspecialchars($juego->getNombre()) : '' ?>" required>

                <label for="editor" class="form-label">Editor</label>
                <input type="text" name="editor" id="editor" class="form-control mb-3"
                       placeholder="Editor"
                       value="<?= $esEdicion ? htmlspecialchars($juego->getEditor()) : '' ?>" required>

                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" id="stock" min="0" class="form-control mb-3"
                       placeholder="Stock"
                       value="<?= htmlspecialchars((string) ($_POST['stock'] ?? ($esEdicion ? $juego->getStock() : 1))) ?>" required>

                <?php $disponibilidadActual = (string) ($_POST['disponible'] ?? ($esEdicion ? $juego->getDisponible() : 1)); ?>
                <label for="disponible" class="form-label">Disponibilidad</label>
                <select name="disponible" id="disponible" class="form-select mb-3" required>
                    <option value="1" <?= $disponibilidadActual === '1' ? 'selected' : '' ?>>Sí</option>
                    <option value="0" <?= $disponibilidadActual === '0' ? 'selected' : '' ?>>No</option>
                </select>


                <?php if ($esEdicion): ?>
                    <label>Foto actual</label><br>
                    <div class="mb-2">
                        <?php if (!empty($juego->getFoto())): ?>
                            <img src="uploads/juegos/<?= htmlspecialchars($juego->getFoto()) ?>"
                                 alt="Foto actual" class="portada-preview" style="max-width:150px;">
                        <?php else: ?>
                            <p class="text-muted">Este juego no tiene foto.</p>
                        <?php endif; ?>
                    </div>
                    <label>Cambiar foto (opcional)</label>
                <?php else: ?>
                    <label>Foto</label>
                <?php endif; ?>

                <input type="file" name="foto" class="form-control mb-3" accept="image/*">

                <button type="submit" class="btn btn-success w-100 mb-2">
                    <?= $esEdicion ? 'Guardar Cambios' : 'Crear juego' ?>
                </button>

                <a href="index.php?accion=listarJuegos" class="btn btn-danger w-100">
                    Cancelar
                </a>

            </form>
        </div>
    </div>
</div>

</body>
</html>
