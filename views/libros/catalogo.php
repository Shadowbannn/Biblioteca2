<?php require __DIR__ . '/../../includes/header2.php'; ?>


<h1 class="titulo mt-5"><b>Catalogo</b></h1>

<div class="card">

    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Listado</h4>
    </div>

    <div class="card-body">
        <?php require __DIR__ . '/../../includes/paginadorCatalogo.php'; ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            <?php foreach ($libros as $libro): ?>

                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">

                            <?php if ($libro->getPortada()): ?>
                                <img src="uploads/portadas/<?= htmlspecialchars($libro->getPortada()) ?>"
                                     alt="Portada de <?= htmlspecialchars($libro->getTitulo()) ?>"
                                     class="card-img-top mb-3"
                                     style="max-height: 400px; object-fit: cover;">
                            <?php else: ?>
                                <img src="assets/sin-portada.png"
                                     alt="Portada por defecto"
                                     class="card-img-top mb-3"
                                     style="max-height: 400px; object-fit: cover;">
                            <?php endif; ?>

                            <h5 class="card-title">
                                <?= htmlspecialchars($libro->getTitulo()) ?>
                            </h5>

                            <p class="card-text">
                                <b>Autor:</b> <?= htmlspecialchars($libro->getAutor()) ?><br>
                                <b>Año:</b> <?= $libro->getAnioPublicacion() ?><br>
                                <b>Páginas:</b> <?= $libro->getCantidadPaginas() ?><br>
                                <b>Género:</b> <?= htmlspecialchars($libro->getGenero()) ?><br>
                            </p>

                            <div class="mt-auto d-flex gap-2">
                                <a href="index.php?accion=formSolicitudLibro&amp;libro_id=<?= (int) $libro->getId() ?>&amp;tipo=prestamo"
                                   class="btn btn-warning btn-sm">
                                    Pedir
                                </a>

                                <a href="index.php?accion=formSolicitudLibro&amp;libro_id=<?= (int) $libro->getId() ?>&amp;tipo=reserva"
                                   class="btn btn-danger btn-sm">
                                    Reservar
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

            <?php if (empty($libros)): ?>
                <p class="text-muted">Todavía no hay libros cargados.</p>
            <?php endif; ?>

        </div>
    </div>

    <?php require __DIR__ . '/../../includes/paginadorCatalogo.php'; ?>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
