<form method="GET" action="index.php" class="row g-2 align-items-end mb-4">
    <input type="hidden" name="accion" value="listarLibros">
    <div class="col-12 col-lg-5">
        <label class="form-label" style="color: white; for="buscar">Buscar libros</label>
        <input id="buscar" type="search" name="buscar" class="form-control"
               placeholder="Título, autor o género..."
               value="<?= htmlspecialchars($buscar ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="col-6 col-lg-3">
        <select id="campo" name="campo" class="form-select">
            <?php foreach (['todos' => 'Todos los campos', 'titulo' => 'Título', 'autor' => 'Autor', 'genero' => 'Género'] as $clave => $texto): ?>
                <option value="<?= $clave ?>" <?= ($campo ?? 'todos') === $clave ? 'selected' : '' ?>><?= $texto ?></option>
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
