<?php
require __DIR__ . '/../../includes/header.php';
$esc = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$estados = ['pendiente' => 'warning', 'aceptada' => 'success', 'rechazada' => 'danger'];
?>
<h1 class="titulo mt-5"><b>Solicitudes</b></h1>
<div class="card">
    <div class="card-header bg-dark text-white d-flex justify-content-between flex-wrap gap-2">
        <span>Pedidos y reservas de libros</span>
        <span>Total: <?= (int) $totalRegistros ?></span>
    </div>
    <div class="card-body">
        <?php if (empty($solicitudes)): ?>
            <p class="mb-0">Todavía no hay solicitudes registradas.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-dark align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Código</th>
                            <th scope="col">Libro</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Solicitante</th>
                            <th scope="col">Cédula</th>
                            <th scope="col">Clase</th>
                            <th scope="col">Teléfono</th>
                            <th scope="col">Socio</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $solicitud): ?>
                            <tr>
                                <td>#<?= (int) $solicitud['id'] ?></td>
                                <td><?= $esc($solicitud['libro_titulo'] ?? 'Libro eliminado') ?></td>
                                <td><?= $solicitud['tipo'] === 'reserva' ? 'Reserva' : 'Pedido' ?></td>
                                <td><?= $esc($solicitud['solicitante_nombre'] ?: 'Sin datos') ?></td>
                                <td><?= $esc($solicitud['cedula']) ?></td>
                                <td><?= $esc($solicitud['solicitante_clase'] ?: '—') ?></td>
                                <td><?= $esc($solicitud['contacto'] ?: '—') ?></td>
                                <td><?= $solicitud['nuevo_socio'] ? 'Solicita registro' : 'Existente' ?></td>
                                <td><span class="badge bg-<?= $estados[$solicitud['estado']] ?? 'secondary' ?>"><?= $esc(ucfirst($solicitud['estado'])) ?></span></td>
                                <td><?= $esc($solicitud['creada_en']) ?></td>
                                <td><?= $esc($solicitud['observacion'] ?: '—') ?></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php if ($totalPaginas > 1): ?>
                <nav aria-label="Páginas de solicitudes" class="d-flex justify-content-center align-items-center gap-3">
                    <?php if ($paginaActual > 1): ?>
                        <a class="btn btn-outline-secondary" href="index.php?accion=listarSolicitudes&amp;pagina=<?= $paginaActual - 1 ?>">Anterior</a>
                    <?php endif; ?>
                    <span>Página <?= $paginaActual ?> de <?= $totalPaginas ?></span>
                    <?php if ($paginaActual < $totalPaginas): ?>
                        <a class="btn btn-outline-secondary" href="index.php?accion=listarSolicitudes&amp;pagina=<?= $paginaActual + 1 ?>">Siguiente</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<a class="btn btn-secondary mt-3" href="index.php">Volver al inicio</a>
<?php require __DIR__ . '/../../includes/footer.php'; ?>
