<?php
$esEdicion = isset($prestamo) && is_object($prestamo);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esEdicion ? 'Editar Préstamo' : 'Agregar Préstamo' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>   
    <link rel="stylesheet" href="css/form.css">
</head>

<body>

<div class="container mt-5">
    <div class="form-card">

        <h2 class="text-center mb-4"><?= $esEdicion ? 'Editar Préstamo' : 'Agregar Préstamo' ?></h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="index.php?accion=<?= $esEdicion ? 'editarPrestamoJuegos' : 'crearPrestamoJuegos' ?>" method="POST">

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
                                <?= htmlspecialchars($s->getNombre()) ?> (Cédula: <?= htmlspecialchars($s->getCedula()) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>


            <!-- Selección de Juego -->
            <div class="mb-3">
                <label for="juegoId" class="form-label">Juego</label>
                <select name="juegoId" id="juegoId" class="form-select" required>
                    <option value="">Selecciona un Juego</option>
                    <?php if (isset($juegos)): ?>
                        <?php foreach ($juegos as $juego): ?>

                            <option value="<?= $juego->getId() ?>"
                                <?= ($esEdicion && $prestamo->getJuegoId() == $juego->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($juego->getNombre()) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>


             <!-- Selección de Estado -->
            <div class="mb-3">
                <label for="estado" class="form-label">Estado</label>
                <select name="estado" id="estado" class="form-select" required>
                    <option value="">Selecciona un Estado</option>
                    <option value="Reservado">Reservado</option>
                    <option value="Prestado">Prestado</option>
                    <option value="Devuelto">Devuelto</option>       
                    <option value="Cancelado">Cancelado</option>             
                </select>
            </div>

            <!-- Fecha de Prextamo -->
            <div class="mb-3">
                <label for="fechaPrestamoJuego" class="form-label">Fecha de Préstamo Realizado</label>
                <input type="date" name="fechaPrestamoJuego" id="fechaPrestamoJuego" class="form-control"
                       value="<?= $esEdicion ? htmlspecialchars($prestamo->getFechaPrestamo()) : date('Y-m-d') ?>" required>
            </div>

            <!-- Fecha de Devolución -->
            <div class="mb-3">
                <label for="fechaDevolucion" class="form-label">Fecha de Devolución Estimada</label>
                <input type="date" name="fechaDevolucion" id="fechaDevolucion" class="form-control"
                value="<?= $esEdicion ? htmlspecialchars($prestamo->getFechaDevolucion()) : date('Y-m-d', strtotime('+7 days')) ?>" required>
            </div>

            <button type="submit" class="btn btn-success w-100 mb-2">
                <?= $esEdicion ? 'Guardar Cambios' : 'Crear Préstamo' ?>
            </button>

            <a href="index.php?accion=listarJuegos" class="btn btn-danger w-100">
                Cancelar
            </a>

        </form>
    </div>
</div>

</body>
</html>

<script>
    new TomSelect('#juegoId', {
        placeholder: '',
        maxOptions: null,        // muestra todas las opciones, sin límite
        allowEmptyOption: true
    });
</script>
