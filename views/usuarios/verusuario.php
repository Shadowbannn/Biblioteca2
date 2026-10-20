<?php require __DIR__ . '/../../includes/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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


                <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Registrado</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Código</th>
                        <th scope="col">Editar</th>
                        <th scope="col">Eliminar</th>
                        </tr>
                    </thead>


                    <tbody>
                            <tr>
                                <td><?= htmlspecialchars($u->getNombre()) ?></td>
                                <td><?= $u->getEmail() ?></td>
                                <td><?= $u->getFechaRegistro() !== '' ? date('d/m/Y', strtotime($u->getFechaRegistro())) : '-' ?>
                                <td><?= htmlspecialchars($u->getRoles()) ?></td>
                                <td><?= $u->getId() ?></td>
                                <td><div class="mt-auto d-flex gap-2">
                                    
                                        <a href="index.php?accion=formEditarUsuario&id=<?= $u->getId() ?>"
                                        class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-user-pen"></i>
                                        </a>
                                    </div>
                                </td>
                                <td><div class="mt-auto d-flex gap-2">

                                        <a href="index.php?accion=eliminarUsuario&id=<?= $u->getId() ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Deseas eliminar este Usuario?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>


                            </tr>
                    </tbody>

                </table>
                            
            <?php endforeach; ?>

            <?php if (empty($usuarios)): ?>
                <p class="text-muted">Todavía no hay usuarios cargados.</p>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
