<?php require __DIR__ . '/../../includes/header.php'; ?>

<h1 class="titulo mt-5"><br><b>Libros</b></h1>

<div class="mb-3">
    <a href="index.php?accion=formCrearLibro" class="btn btn-success">
        ➕ Agregar Libro
    </a>
</div>

<div class="card">

    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Listado de Libros</h4>
    </div>

    <div class="card-body">
        <?php require __DIR__ . '/../../includes/paginador.php'; ?>

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
                                <a href="index.php?accion=formEditarLibro&id=<?= $libro->getId() ?>"
                                   class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <a href="index.php?accion=eliminarLibro&id=<?= $libro->getId() ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Deseas eliminar este libro?')">
                                    Eliminar
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

    <?php require __DIR__ . '/../../includes/paginador.php'; ?>
</div>

<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
