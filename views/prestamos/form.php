<?php
$esEdicion = isset($prestamo) && is_object($prestamo);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?= $esEdicion ? 'Editar prestamo' : 'Agregar prestamo' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form.css">
</head>

<body>

<div class="container mt-5">
    <div class="form-card">

        <h2><span class="centrado"><?= $esEdicion ? 'Editar prestamo' : 'Agregar prestamo' ?></span></h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        

            <form action="index.php?accion=<?= $esEdicion ? 'editarPrestamo' : 'crearPrestamo' ?>" method="POST">            <div class="mb-3">
                <label class="form-label">Socio</label>
                <select name="socioId" id="socioId" class="form-select" required>
                    <?php foreach ($socios as $socio): ?>
                        <option value="<?= $socio->getId() ?>" <?= ($esEdicion && $prestamo->getSocioId() == $socio->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($socio->getNombre() . ' ' . $socio->getApellido()) ?> (Cédula: <?= htmlspecialchars($socio->getCedula()) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Libro</label>
                <select name="libroId" id="libroId" class="form-select" required>
                    <?php foreach ($libros as $libro): ?>
                        <option value="<?= $libro->getId() ?>" <?= ($esEdicion && $prestamo->getLibroId() == $libro->getId()) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($libro->getTitulo()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Fecha de préstamo</label>
                <input type="date" name="fechaPrestamo" id="fechaPrestamo" class="form-control" 
                value="<?= $esEdicion ? htmlspecialchars($prestamo->getFechaPrestamo()) : date('Y-m-d') ?>" required>
            </div>

            <button type="submit" class="btn btn-success w-100 mb-2">
                <?= $esEdicion ? 'Guardar Cambios' : 'Crear prestamo' ?>
            </button>

            <a href="index.php?accion=listarPrestamos" class="btn btn-danger w-100">
                Cancelar
            </a>
        </form>
    </div>
</div>

</body>
</html>
