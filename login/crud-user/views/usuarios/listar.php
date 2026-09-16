<?php require __DIR__ . '/../../../../includes/header.php'; ?>

<h1 class="titulo mt-5"><br><b>Cuentas del sistema</b></h1>

<div class="mb-3">
    <a href="index.php?accion=formCrearUsuario" class="btn btn-success">
        ➕ Agregar usuario
    </a>
</div>

<div class="card">

    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Listado de Usuarios</h4>
    </div>

    <div class="card-body">

        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Fecha de registro</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= $u->getId() ?></td>
                        <td><?= htmlspecialchars($u->getNombre()) ?></td>
                        <td><?= htmlspecialchars($u->getEmail()) ?></td>
                        <td><?= htmlspecialchars($u->getFechaRegistro()) ?></td>
                        <td class="text-end">
                            <a href="index.php?accion=formEditarUsuario&id=<?= $u->getId() ?>"
                               class="btn btn-sm btn-warning">
                                Editar
                            </a>
                            <a href="index.php?accion=eliminarUsuario&id=<?= $u->getId() ?>"
                               class="btn btn-sm btn-danger">
                                Eliminar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">Todavía no hay usuarios cargados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>
</div>

<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>

<?php require __DIR__ . '/../../../../includes/footer.php'; ?>
