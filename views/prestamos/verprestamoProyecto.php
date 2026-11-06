<?php require __DIR__ . '/../../includes/header.php'; ?>
<h1 class="titulo mt-5"><b>Préstamos de Proyectos</b></h1>

<div class="card">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Listado de Préstamos</h4>
        
    </div>
    <br>
    <div class="mb-3">
        <a href="index.php?accion=formCrearPrestamoProyectos" class="btn btn-success"
        style="margin-left: 14px;">
             ➕ Agregar préstamo
         </a>
    </div>

    <div class="card-body">
        <input class="form-control" id="myInput" type="text" placeholder="Search..">
        <br>

        <?php if (!empty($prestamos)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-dark align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Portada</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Socio</th>
                            <th scope="col">Proyecto</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Devolver</th>
                            <th scope="col">Código</th>
                            <th scope="col">Acciones</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php foreach ($prestamos as $prestamo): ?>
                            <tr>
                                <!-- Portada del Proyecto -->
                                <td>
                                    <?php $portadaProyecto = $prestamo->getPortadaProyecto(); ?>
                                    <?php if (!empty($portadaProyecto)): ?>
                                        <img src="/carpeta2/uploads/Proyectos/<?= htmlspecialchars($portadaProyecto) ?>"
                                             alt="Portada de <?= htmlspecialchars($prestamo->getProyectoNombre()) ?>"
                                             class="portada-prestamo"
                                             style="max-height: 100px; max-width: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="/carpeta2/assets/sin-portada.webp"
                                             alt="Sin portada"
                                             class="portada-prestamo"
                                             style="max-height: 100px; max-width: 50px; object-fit: cover;">
                                    <?php endif; ?>
                                </td>

                                <!-- Foto del socio -->
                                <td>
                               <?php if ($prestamo->getSocioFoto() !== ''): ?>
                                        <img src="/carpeta2/uploads/fotos/<?= htmlspecialchars($prestamo->getSocioFoto()) ?>"
                                             alt="Foto de <?= htmlspecialchars($prestamo->getSocioNombre()) ?>"
                                             class="rounded-circle"
                                             style="max-height: 50px; max-width: 50px; width: 50px; height: 50px; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="https://static.vecteezy.com/system/resources/previews/019/879/186/large_2x/user-icon-on-transparent-background-free-png.png"
                                             alt="Imagen por defecto"
                                             class="rounded-circle"
                                             style="max-height: 50px; max-width: 50px; width: 50px; height: 50px; object-fit: cover;">
                                    <?php endif; ?>
                                </td>

                                <td><?= htmlspecialchars($prestamo->getSocioNombre()) ?></td>
                                <td><?= htmlspecialchars($prestamo->getProyectoNombre()) ?></td>
                                <td>
                                    <?php
                                    $estado = $prestamo->getEstado();
                                    $claseEstado = match ($estado) {
                                        'Reservado' => 'text-bg-warning',
                                        'Prestado' => 'text-bg-primary',
                                        'Devuelto' => 'text-bg-success',
                                        'Cancelado' => 'text-bg-danger',
                                        default => 'text-bg-secondary',
                                    };
                                    ?>
                                    <span class="badge rounded-pill <?= $claseEstado ?>">
                                        <?= htmlspecialchars($estado, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </td>
                                <td><?= date('d/m/Y', strtotime($prestamo->getFechaDevolucion())) ?></td>
                                <td><span class="prestamo-id">#<?= $prestamo->getId() ?></td>

                                <!-- Acciones -->
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="index.php?accion=formEditarPrestamoProyectos&id=<?= $prestamo->getId() ?>"
                                           class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-user-pen"></i>
                                        </a>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex gap-2 mt-1">
                                        <a href="index.php?accion=eliminarPrestamoProyectos&id=<?= $prestamo->getId() ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('¿Deseas eliminar este préstamo?')">
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
            <p class="text-muted">Todavía no hay préstamos cargados.</p>
        <?php endif; ?>

    </div>

</div>

<div class="mt-3">
    <a href="index.php?accion=listarProyectos" class="btn btn-secondary">← Volver al inicio</a>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>

<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>
