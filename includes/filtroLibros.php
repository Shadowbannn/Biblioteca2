<form method="GET" action="index.php" class="row g-2 align-items-end mb-4">
    <input type="hidden" name="accion" value="listarLibros">
    <div class="col-12 col-lg-5">
        <label class="form-label" style="color: white;" for="buscar">Buscar libros</label>
        <input id="buscar" type="search" name="buscar" class="form-control"
               placeholder="Título, autor o género..."
               value="<?= htmlspecialchars($buscar ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="col-6 col-lg-3">
        <select id="genero" name="genero" class="form-select" aria-label="Género">
            <option value="">Todos los géneros</option>
            <option value="sin_genero" <?= ($genero ?? '') === 'sin_genero' ? 'selected' : '' ?>>Sin género</option>
            <?php foreach ($generos as $opcionGenero): ?>
                <option value="<?= (int) $opcionGenero['id'] ?>" <?= (string) ($genero ?? '') === (string) $opcionGenero['id'] ? 'selected' : '' ?>><?= htmlspecialchars($opcionGenero['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-6 col-lg-2">
        <select id="disponibilidad" name="disponibilidad" class="form-select">
            <option value="" <?= ($disponibilidad ?? '') === '' ? 'selected' : '' ?>>Todas</option>
            <option value="1" <?= ($disponibilidad ?? '') === '1' ? 'selected' : '' ?>>Disponibles</option>
            <option value="0" <?= ($disponibilidad ?? '') === '0' ? 'selected' : '' ?>>No disponibles</option>
        </select>
    </div>
    <div class="col-12 col-lg-2 d-flex gap-2">
        <button class="btn btn-primary flex-fill" type="submit">Buscar</button>
        <a class="btn btn-outline-secondary" href="index.php?accion=listarLibros" title="Limpiar filtros">Limpiar</a>
    </div>
</form>
