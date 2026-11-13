<?php
$esEdicion = isset($libro);
$generosSeleccionados = $_POST['generos'] ?? ($esEdicion ? $libro->getGeneroIds() : []);
if (!is_array($generosSeleccionados)) {
    $generosSeleccionados = [];
}
$generosSeleccionados = array_filter($generosSeleccionados, static function ($valor) {
    return is_string($valor) || is_int($valor);
});
$generosSeleccionados = array_map('strval', $generosSeleccionados);
$generosNuevos = $_POST['generos_nuevos'] ?? [];
if (!is_array($generosNuevos)) {
    $generosNuevos = [];
}
$generosNuevos = array_values(array_unique(array_filter($generosNuevos, static function ($valor) {
    return is_string($valor) && trim($valor) !== '';
})));
$nuevoGeneroInput = $_POST['nuevo_genero'] ?? '';
if (!is_string($nuevoGeneroInput)) {
    $nuevoGeneroInput = '';
}
$codigoDeweyInput = $_POST['codigo_dewey'] ?? ($esEdicion ? $libro->getCodigoDewey() : '');
if (!is_string($codigoDeweyInput)) {
    $codigoDeweyInput = '';
}
$generosSeleccionadosNombres = [];
foreach ($generos as $opcionGenero) {
    if (in_array((string) $opcionGenero['id'], $generosSeleccionados, true)) {
        $generosSeleccionadosNombres[] = $opcionGenero['nombre'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8">
    <title><?= $esEdicion ? 'Editar Libro' : 'Agregar Libro' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/form.css?v=2">
    <link rel="stylesheet" href="css/responsive.css">
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


                <label for="titulo" class="form-label">Libro</label>
                <input type="text" name="titulo" id="titulo" class="form-control mb-3"
                       placeholder="Título"
                       value="<?= $esEdicion ? htmlspecialchars($libro->getTitulo()) : '' ?>" required>

                <label for="autor" class="form-label">Autor</label>
                <input type="text" name="autor" id="autor" class="form-control mb-3"
                       placeholder="Autor"
                       value="<?= $esEdicion ? htmlspecialchars($libro->getAutor()) : '' ?>" required>

                <label for="anio" class="form-label">Año de publicación</label>
                <input type="number" name="anio" id="anio" class="form-control mb-3"
                       placeholder="Año"
                       value="<?= $esEdicion ? $libro->getAnioPublicacion() : '' ?>" required>

                <label for="paginas" class="form-label">Cantidad de páginas</label>
                <input type="number" name="paginas" id="paginas" class="form-control mb-3"
                       placeholder="Páginas"
                       value="<?= $esEdicion ? $libro->getCantidadPaginas() : '' ?>" required>
                
                <label for="buscar-genero" class="form-label" id="buscar-genero-label" hidden>Buscar género</label>
                <div class="generos-selector mb-1" id="generos-selector">
                    <input type="search" id="buscar-genero" class="form-control mb-2"
                           placeholder="Escribe para filtrar..." autocomplete="off"
                           aria-controls="lista-generos" aria-describedby="generos-ayuda" hidden>
                    <div class="generos-seleccionados mb-2">
                        <strong id="generos-contador" aria-live="polite"><?= count($generosSeleccionadosNombres) + count($generosNuevos) ?> <?= count($generosSeleccionadosNombres) + count($generosNuevos) === 1 ? 'género seleccionado' : 'géneros seleccionados' ?></strong>
                        <div id="generos-etiquetas" class="generos-etiquetas" aria-live="polite">
                            <?php foreach ($generosSeleccionadosNombres as $nombreGenero): ?>
                                <span class="genero-etiqueta"><?= htmlspecialchars($nombreGenero, ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endforeach; ?>
                            <?php foreach ($generosNuevos as $nombreGenero): ?>
                                <span class="genero-etiqueta"><?= htmlspecialchars($nombreGenero, ENT_QUOTES, 'UTF-8') ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div id="generos-nuevos-pendientes" hidden>
                        <?php foreach ($generosNuevos as $nombreGenero): ?>
                            <input type="hidden" name="generos_nuevos[]" value="<?= htmlspecialchars($nombreGenero, ENT_QUOTES, 'UTF-8') ?>" data-genero-nuevo>
                        <?php endforeach; ?>
                    </div>
                    <div id="lista-generos" class="generos-opciones" role="group" aria-label="Géneros disponibles">
                    <?php foreach ($generos as $opcionGenero): ?>
                        <?php $generoId = (string) $opcionGenero['id']; ?>
                        <label class="genero-opcion" for="genero-<?= (int) $opcionGenero['id'] ?>">
                            <input type="checkbox" name="generos[]" id="genero-<?= (int) $opcionGenero['id'] ?>"
                                   value="<?= (int) $opcionGenero['id'] ?>"
                                   <?= in_array($generoId, $generosSeleccionados, true) ? 'checked' : '' ?>>
                            <span><?= htmlspecialchars($opcionGenero['nombre'], ENT_QUOTES, 'UTF-8') ?></span>
                        </label>
                    <?php endforeach; ?>
                    </div>
                    <p id="generos-sin-resultados" class="mb-0 mt-2" role="status" hidden>No hay géneros que coincidan con la búsqueda.</p>
                    <label for="nuevo-genero" class="form-label mt-3">¿No encuentras el género?</label>
                    <div class="genero-agregar">
                        <input type="text" name="nuevo_genero" id="nuevo-genero" class="form-control"
                               maxlength="120" placeholder="Escribe un género nuevo"
                               value="<?= htmlspecialchars($nuevoGeneroInput, ENT_QUOTES, 'UTF-8') ?>"
                               aria-describedby="nuevo-genero-ayuda genero-nuevo-mensaje">
                        <button type="button" class="btn btn-primary" id="agregar-genero">Agregar</button>
                    </div>
                    <small id="nuevo-genero-ayuda" class="form-text text-muted d-block">Se agregará a la base y quedará seleccionado al guardar el libro. Agrega un solo género por vez.</small>
                    <p id="genero-nuevo-mensaje" class="mb-0 mt-1" role="status" aria-live="polite"></p>
                </div>
                <small id="generos-ayuda" class="form-text text-muted d-block mb-3">Escribe para buscar y marca cada casilla. Los géneros seleccionados quedan visibles arriba.</small>

                <label for="codigo_dewey" class="form-label">Código Dewey aproximado</label>
                <input type="text" name="codigo_dewey" id="codigo_dewey" class="form-control mb-1"
                       maxlength="10" pattern="[0-9]{3}(\.[0-9]{1,6})?"
                       placeholder="Automático según género"
                       value="<?= htmlspecialchars($codigoDeweyInput, ENT_QUOTES, 'UTF-8') ?>">
                <small class="form-text text-muted d-block mb-3">Si lo dejas vacío se asignará una clasificación estimada según los géneros. Puedes corregirla manualmente.</small>

                <?php $disponibilidadActual = $_POST['disponible'] ?? ($esEdicion ? $libro->getDisponible() : 'Sí'); ?>
                <label for="disponible" class="form-label">Disponibilidad</label>
                <select name="disponible" id="disponible" class="form-select mb-3" required>
                    <option value="Sí" <?= $disponibilidadActual === 'Sí' ? 'selected' : '' ?>>Sí</option>
                    <option value="No" <?= $disponibilidadActual === 'No' ? 'selected' : '' ?>>No</option>
                </select>

                <label for="stock" class="form-label">Stock</label>
                <input type="number" name="stock" id="stock" class="form-control mb-3"
                       min="0" step="1"
                       value="<?= htmlspecialchars((string) ($_POST['stock'] ?? ($esEdicion ? $libro->getStock() : 1))) ?>" required>

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

<script src="js/libros-generos.js?v=2" defer></script>
</body>
</html>
