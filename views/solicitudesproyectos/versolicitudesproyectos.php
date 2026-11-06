<?php
require __DIR__ . '/../../includes/header.php';
$esc = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$estados = ['pendiente' => 'warning', 'aceptada' => 'success', 'rechazada' => 'danger'];
$accionSolicitudes = 'listarSolicitudesProyectos';
?>
<h1 class="titulo mt-5"><b>Solicitudes</b></h1>

<div class="card">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h4 class="mb-0">Solicitudes de proyectos</h4>
        <span>Total: <?= (int) $totalRegistros ?></span>
    </div>

    <div class="card-body">
        <?php require __DIR__ . '/../../includes/selectorSolicitudes.php'; ?>
        <input class="form-control" id="myInput" type="search" placeholder="Buscar solicitudes..." aria-label="Buscar en las solicitudes de esta página">
        <br>
        <?php if (!empty($solicitudes)): ?>
            <div class="table-responsive">
                <table class="table table-striped align-middle table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Proyecto</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Cédula</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Clase</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Socio</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Código</th>
                            <th scope="col">Aceptar</th>
                            <th scope="col">Rechazar</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr>
                                <td><?= $esc($solicitud['proyecto_nombre'] ?? 'Proyecto eliminado') ?></td>
                                <td><?= $esc($solicitud['solicitante_nombre'] ?: 'Sin datos') ?></td>
                                <td><?= $esc($solicitud['cedula']) ?></td>
                                <td><?= $esc($solicitud['contacto'] ?: '—') ?></td>
                                <td class="text-nowrap"><?= $esc(date('d/m/Y', strtotime($solicitud['creada_en']))) ?></td>
                                <td><?= $esc($solicitud['solicitante_clase'] ?: '—') ?></td>
                                <td><?= $solicitud['tipo'] === 'reserva' ? 'Reserva' : 'Pedido' ?></td>
                                <td><?= $solicitud['nuevo_socio'] ? 'Solicita registro' : 'Existente' ?></td>
                                <td><span class="badge bg-<?= $estados[$solicitud['estado']] ?? 'secondary' ?>"><?= $esc(ucfirst($solicitud['estado'])) ?></span></td>
                                <td>#<?= (int) $solicitud['id'] ?></td>
                                <td>
                                    <?php $accionRevision = 'aceptada'; require __DIR__ . '/../../includes/accionesSolicitud.php'; ?>
                                </td>
                                <td>
                                    <?php $accionRevision = 'rechazada'; require __DIR__ . '/../../includes/accionesSolicitud.php'; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p id="sinResultados" class="text-muted" role="status" hidden>No hay solicitudes que coincidan con la búsqueda en esta página.</p>
            <?php if ($totalPaginas > 1): ?>
                <div class="d-flex justify-content-center align-items-center gap-3 mt-3" role="navigation" aria-label="Páginas de solicitudes">
                    <?php if ($paginaActual > 1): ?>
                        <a class="btn btn-outline-secondary" href="index.php?accion=listarSolicitudesProyectos&amp;pagina=<?= $paginaActual - 1 ?>">Anterior</a>
                    <?php endif; ?>
                    <span>Página <?= $paginaActual ?> de <?= $totalPaginas ?></span>
                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a class="btn btn-outline-secondary" href="index.php?accion=listarSolicitudesProyectos&amp;pagina=<?= $paginaActual + 1 ?>">Siguiente</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-muted">Todavía no hay solicitudes registradas.</p>
        <?php endif; ?>
    </div>
</div>
<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>

<script>
document.getElementById('myInput').addEventListener('input', function () {
    const busqueda = this.value.toLocaleLowerCase('es');
    let visibles = 0;
    document.querySelectorAll('#myTable tr').forEach(fila => {
        fila.hidden = !fila.textContent.toLocaleLowerCase('es').includes(busqueda);
        if (!fila.hidden) visibles++;
    });
    const mensaje = document.getElementById('sinResultados');
    if (mensaje) mensaje.hidden = visibles > 0;
});
</script>

<?php require __DIR__ . '/../../includes/footer.php'; ?>