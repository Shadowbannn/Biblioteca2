<?php
$esEdicion = isset($prestamo) && is_object($prestamo);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esEdicion ? 'Editar Préstamo' : 'Agregar Préstamo' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form.css">
</head>

<body>

<div class="container mt-5">
    <div class="form-card">

        <h2 class="text-center mb-4"><?= $esEdicion ? 'Editar Préstamo' : 'Agregar Préstamo' ?></h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="index.php?accion=<?= $esEdicion ? 'editarPrestamo' : 'crearPrestamo' ?>" method="POST">

            <?php if ($esEdicion): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($prestamo->getId()) ?>">
            <?php endif; ?>

            <!-- Selección de socio insta -->
            <div class="mb-3">
                <label for="socioId" class="form-label">Socio</label>
                <select name="socioId" id="socioId" class="form-select" required>
                    <option value="">Selecciona un Socio</option>
                    <?php if (isset($socios)): ?>
                        <?php foreach ($socios as $s): ?>
                            <option value="<?= $s->getId() ?>" 
                                <?= ($esEdicion && $prestamo->getSocioId() == $s->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s->getNombre() . ' ' . $s->getApellido()) ?> (Cédula: <?= htmlspecialchars($s->getCedula()) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Selección de Librovich -->
            <div class="mb-3">
                <label for="libroId" class="form-label">Libro</label>
                <select name="libroId" id="libroId" class="form-select" required>
                    <option value="">Selecciona un Libro</option>
                    <?php if (isset($libros)): ?>
                        <?php foreach ($libros as $l): ?>
                            <option value="<?= $l->getId() ?>" 
                                <?= ($esEdicion && $prestamo->getLibroId() == $l->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($l->getTitulo()) ?> - <?= htmlspecialchars($l->getAutor()) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Fecha de Prextamo -->
            <div class="mb-3">
                <label for="fechaPrestamo" class="form-label">Fecha de Préstamo</label>
                <input type="date" name="fechaPrestamo" id="fechaPrestamo" class="form-control"
                       value="<?= $esEdicion ? htmlspecialchars($prestamo->getFechaPrestamo()) : date('Y-m-d') ?>" required>
            </div>

            <button type="submit" class="btn btn-success w-100 mb-2">
                <?= $esEdicion ? 'Guardar Cambios' : 'Crear Préstamo' ?>
            </button>

            <a href="index.php?accion=listarPrestamos" class="btn btn-danger w-100">
                Cancelar
            </a>

        </form>
    </div>
</div>

</body>
</html>