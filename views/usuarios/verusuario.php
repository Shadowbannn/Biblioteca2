<?php require __DIR__ . '/../../includes/header.php'; ?>

<div class="cardsmall">
<h1 class="titulo mt-5"><b>Usuarios</b></h1>

<div class="mb-3">
    <a href="index.php?accion=formCrearUsuario" class="btn btn-success">
        ➕ Agregar usuario
    </a>
</div>
</div>

<div class="card">

    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Cuentas del sistema</h4>
    </div>

    <div class="card-body">
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            <?php foreach ($usuarios as $u): ?>
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">

                            <p class="card-text">
                                <b>Nombre:</b> <?= htmlspecialchars($u->getNombre()) ?><br>
                                <b>Correo:</b> <?= htmlspecialchars($u->getEmail()) ?><br>
                                <b>Registrado:</b> <?= $u->getFechaRegistro() !== '' ? date('d/m/Y', strtotime($u->getFechaRegistro())) : '-' ?><br>
                                <b>ID:</b> <?= $u->getId() ?><br>
                            </p>

                            <div class="mt-auto d-flex gap-2">
                                <a href="index.php?accion=formEditarUsuario&id=<?= $u->getId() ?>"
                                   class="btn btn-warning btn-sm">Editar</a>

                                <?php if ($u->getId() !== (int) ($_SESSION['usuario_id'] ?? 0)): ?>
                                    <a href="index.php?accion=eliminarUsuario&id=<?= $u->getId() ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('¿Deseas eliminar este usuario?')">Eliminar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($usuarios)): ?>
                <p class="text-muted">Todavía no hay usuarios cargados.</p>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
