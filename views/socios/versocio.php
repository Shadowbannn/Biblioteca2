<?php require __DIR__ . '/../../includes/header.php'; ?>
<h1 class="titulo mt-5"><b>Socios</b></h1>

<div class="card">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Listado de Socios</h4>

    </div>
    <br>
    <div class="mb-3">
        <a href="index.php?accion=formCrearSocio" class="btn btn-success"
        style="margin-left: 14px;">
             ➕ Agregar socio
         </a>
    </div>

    <div class="card-body">
        <input class="form-control" id="myInput" type="search" placeholder="Buscar socios..." aria-label="Buscar en los socios de esta página">
        <br>
        <?php if (!empty($socios)): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Foto</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Cedula</th>
                            <th scope="col">Telefono</th>
                            <th scope="col">Registrado</th>
                            <th scope="col">Clase</th>
                            <th scope="col">Código</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php foreach ($socios as $socio): ?>
                        <tr>
                            <!-- Foto del socio -->
                        <td><?php if (method_exists($socio, 'getFoto') && $socio->getFoto()): ?>
                                    <img src="/carpeta2/uploads/fotos/<?= htmlspecialchars($socio->getFoto()) ?>"
                                         alt="Foto de <?= htmlspecialchars($socio->getNombre()) ?>"
                                             class="rounded-circle"
                                         style="max-height: 50px; max-width: 50px;">
                            <?php else: ?>
                                    <img src="https://static.vecteezy.com/system/resources/previews/019/879/186/large_2x/user-icon-on-transparent-background-free-png.png"
                                            alt="Imagen por defecto"
                                            class="rounded-circle"
                                            style="max-height: 50px; max-width: 50px;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($socio->getNombre()) ?></td>
                        <td><?= $socio->getCedula() ?></td>
                        <td><?= $socio->getTelefono() ?></td>
                        <td><?= date('d/m/Y', strtotime($socio->getFecharegistro())) ?></td>
                        <td><?= $socio->getClase() ?></td>
                        <td><span class="socio-id">#<?= $socio->getId() ?></td>

                        <!-- Acciones -->
                        <td>
                            <div class="d-flex gap-2">
                                <a href="index.php?accion=formEditarSocio&id=<?= $socio->getId() ?>"
                                    class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                            </div>
                        </td>

                        <td>
                            <div class="d-flex gap-2 mt-1">
                                <a href="index.php?accion=eliminarSocio&id=<?= $socio->getId() ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Deseas eliminar este socio?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
        <?php else: ?>
            <p class="text-muted">Todavía no hay socios cargados.</p>
        <?php endif; ?>

    </div>

</div>
<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>



<script>
document.getElementById('myInput').addEventListener('input', function () {
    const busqueda = this.value.toLocaleLowerCase('es');
    document.querySelectorAll('#myTable tr').forEach(fila => {
        fila.hidden = !fila.textContent.toLocaleLowerCase('es').includes(busqueda);
    });
});
</script>

<?php require __DIR__ . '/../../includes/footer.php'; ?>