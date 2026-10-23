<?php require __DIR__ . '/../../includes/header2.php'; ?>


<h1 class="titulo mt-5"><b>Proyectos</b></h1>

<div class="card">

    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Listado</h4>
    </div>

    <div class="card-body">

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            <?php foreach ($proyectos as $proyecto): ?>

                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">

                            <?php if ($proyecto->getPortada()): ?>
                                <img src="uploads/portadas/<?= htmlspecialchars($proyecto->getPortada()) ?>"
                                     alt="Portada de <?= htmlspecialchars($proyecto->getNombre()) ?>"
                                     class="card-img-top mb-3"
                                     style="max-height: 400px; object-fit: cover;">
                            <?php else: ?>
                                <img src="assets/sin-portada.png"
                                     alt="Portada por defecto"
                                     class="card-img-top mb-3"
                                     style="max-height: 400px; object-fit: cover;">
                            <?php endif; ?>

                            <h5 class="card-title">
                                <?= htmlspecialchars($proyecto->getNombre()) ?>
                            </h5>

                            <p class="card-text">
                                <b>Integrantes:</b> <?= htmlspecialchars($proyecto->getIntegrantes()) ?><br>
                                <b>Clase:</b> <?= $proyecto->getClase() ?><br>
                                <b>Curso:</b> <?= $proyecto->getCurso() ?><br>
                                <b>Año:</b> <?= htmlspecialchars($proyecto->getAnio()) ?><br>
                                <b>Código:</b> <?= htmlspecialchars($proyecto->getId()) ?><br>

                            </p>

                            <div class="mt-auto d-flex gap-2">
                                <a href="#"
                                   class="btn btn-warning btn-sm">
                                    Pedir
                                </a>

                                <a href="#"
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

    <?php require __DIR__ . '/../../includes/paginadorProyectoP.php'; ?>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
