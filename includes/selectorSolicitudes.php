<?php $_SESSION['revision_solicitud_csrf'] ??= bin2hex(random_bytes(32)); ?>
<?php if (!empty($_SESSION['revision_solicitud_mensaje'])): ?>
    <div class="alert alert-info" role="status"><?= htmlspecialchars($_SESSION['revision_solicitud_mensaje'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php unset($_SESSION['revision_solicitud_mensaje']); ?>
<?php endif; ?>
<form method="get" action="index.php" class="d-flex align-items-center flex-wrap gap-2 mb-3">
    <label for="tipo-solicitudes" class="form-label mb-0 text-white">Ver solicitudes de:</label>
    <select id="tipo-solicitudes" name="accion" class="form-select w-auto" onchange="this.form.submit()">
        <option value="listarSolicitudes" <?= $accionSolicitudes === 'listarSolicitudes' ? 'selected' : '' ?>>Libros</option>
        <option value="listarSolicitudesProyectos" <?= $accionSolicitudes === 'listarSolicitudesProyectos' ? 'selected' : '' ?>>Proyectos</option>
        <option value="listarSolicitudesJuegos" <?= $accionSolicitudes === 'listarSolicitudesJuegos' ? 'selected' : '' ?>>Juegos</option>
    </select>
    <noscript><button type="submit" class="btn btn-primary">Ver solicitudes</button></noscript>
</form>
