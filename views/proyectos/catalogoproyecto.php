<?php require __DIR__ . '/../../includes/header2.php'; ?>


<h1 class="titulo mt-5"><b>Proyectos</b></h1>

<div class="card">

    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Listado</h4>
    </div>

    <div class="card-body">

        <form method="GET" action="index.php" class="row g-2 align-items-end mb-4">
            <input type="hidden" name="accion" value="listarProyectosP">
            <div class="col-12 col-md-8">
                <label class="form-label" for="buscar">Buscar proyectos</label>
                <input id="buscar" type="search" name="buscar" class="form-control" placeholder="Nombre, integrantes, clase o curso..." value="<?= htmlspecialchars($buscar, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button class="btn btn-primary flex-fill" type="submit">Buscar</button>
                <a class="btn btn-outline-secondary" href="index.php?accion=listarProyectosP" title="Limpiar búsqueda">Limpiar</a>
            </div>
        </form>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            <?php foreach ($proyectos as $proyecto): ?>

                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">

                            <?php if ($proyecto->getPortada()): ?>
                                <img src="<?= htmlspecialchars($proyecto->getRutaPortada(), ENT_QUOTES, 'UTF-8') ?>"
                                     alt="Portada de <?= htmlspecialchars($proyecto->getNombre()) ?>"
                                     class="card-img-top mb-3"
                                     style="max-height: 400px; object-fit: cover;">
                            <?php else: ?>
                                <img src="assets/sin-portada.webp"
                                     alt="Portada por defecto"
                                     class="card-img-top mb-3"
                                     style="max-height: 400px; object-fit: cover;">
                            <?php endif; ?>

                            <h5 class="card-title">
                                <?= htmlspecialchars($proyecto->getNombre()) ?>
                            </h5>

                            <p class="card-text">
                                <b>Integrantes:</b> <?= htmlspecialchars($proyecto->getIntegrantes()) ?><br>
                                <b>Clase:</b> <?= htmlspecialchars($proyecto->getClase(), ENT_QUOTES, 'UTF-8') ?><br>
                                <b>Curso:</b> <?= $proyecto->getCurso() ?><br>
                                <b>Año:</b> <?= htmlspecialchars($proyecto->getAnio()) ?><br>
                                <b>Código:</b> <?= htmlspecialchars($proyecto->getId()) ?><br>

                            </p>

                            <div class="mt-auto d-flex gap-2">
                                <a href="index.php?accion=formSolicitudProyecto&amp;proyecto_id=<?= (int) $proyecto->getId() ?>&amp;tipo=prestamo"
                                   class="btn btn-warning btn-sm">
                                    Pedir
                                </a>

                                <a href="index.php?accion=formSolicitudProyecto&amp;proyecto_id=<?= (int) $proyecto->getId() ?>&amp;tipo=reserva"
                                   class="btn btn-danger btn-sm">
                                    Reservar
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

            <?php if (empty($proyectos)): ?>
                <p class="text-muted">No se encontraron proyectos.</p>
            <?php endif; ?>

        </div>
    </div>

    <?php require __DIR__ . '/../../includes/paginadorProyectoP.php'; ?>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
