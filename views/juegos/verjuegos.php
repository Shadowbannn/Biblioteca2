<?php require __DIR__ . '/../../includes/header.php'; ?>
<h1 class="titulo mt-5"><b>Juegos</b></h1>



<div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="mb-0">Listado de Juegos</span>
            <span class="mb-0">
                Total de Juegos: <?= number_format((int) ($totalRegistros ?? 0), 0, ',', '.') ?>
            </span>
        </div>

    <div class="card-body">
        <?php require __DIR__ . '/../../includes/paginadorJuego.php'; ?>
        <div class="mb-3">
            <a href="index.php?accion=formCrearJuego" class="btn btn-success">
                ➕ Agregar Juego
            </a>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            <?php foreach ($juegos as $juego): ?>

                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">

                            <?php if ($juego->getFoto()): ?>
                                <img src="uploads/juegos/<?= htmlspecialchars($juego->getFoto()) ?>"
                                     alt="Foto de <?= htmlspecialchars($juego->getNombre()) ?>"
                                     class="card-img-top mb-3"
                                     style="max-height: 400px; object-fit: cover;">
                            <?php else: ?>
                                <img src="assets/sin-portada.png"
                                     alt="Foto por defecto"
                                     class="card-img-top mb-3"
                                     style="max-height: 400px; object-fit: cover;">
                            <?php endif; ?>

                            <h5 class="card-title">
                                <?= htmlspecialchars($juego->getNombre()) ?>
                            </h5>

                            <p class="card-text">
                                <b>Editor:</b> <?= htmlspecialchars($juego->getEditor()) ?><br>
                                <b>Disponible:</b> <?= $juego->getDisponible() ?><br>
                            </p>

                            <div class="mt-auto d-flex gap-2">
                                <a href="index.php?accion=formEditarJuego&id=<?= $juego->getId() ?>"
                                   class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <a href="index.php?accion=eliminarJuego&id=<?= $juego->getId() ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Deseas eliminar este juego?')">
                                    Eliminar
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

            <?php if (empty($juegos)): ?>
                <p class="text-muted">Todavía no hay libros cargados.</p>
            <?php endif; ?>

        </div>
    </div>

    <?php require __DIR__ . '/../../includes/paginadorJuego.php'; ?>
</div>

<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>


<?php require __DIR__ . '/../../includes/footer.php'; ?>
