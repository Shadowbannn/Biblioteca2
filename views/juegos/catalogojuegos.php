<?php require __DIR__ . '/../../includes/header2.php'; ?>
<h1 class="titulo mt-5"><b>Juegos</b></h1>


<div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="mb-0">Listado de Juegos</span>
            <span class="mb-0">
                Total de Juegos: <?= number_format((int) ($totalRegistros ?? 0), 0, ',', '.') ?>
            </span>
        </div>


    <div class="card-body">
        <form method="GET" action="index.php" class="row g-2 align-items-end mb-4">
            <input type="hidden" name="accion" value="listarJuegosP">
            <div class="col-12 col-md-8">
                <label class="form-label" for="buscar">Buscar juegos</label>
                <input id="buscar" type="search" name="buscar" class="form-control" placeholder="Nombre o editor..." value="<?= htmlspecialchars($buscar, ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button class="btn btn-primary flex-fill" type="submit">Buscar</button>
                <a class="btn btn-outline-secondary" href="index.php?accion=listarJuegosP" title="Limpiar búsqueda">Limpiar</a>
            </div>
        </form>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
            <?php foreach ($juegos as $juego): ?>

            <div class="col">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">

                             <?php if ($juego->getFoto()): ?>
                                <img src="uploads/juegos/<?= htmlspecialchars($juego->getFoto()) ?>"
                                     alt="Foto de <?= htmlspecialchars($juego->getNombre()) ?>"
                                     class="card-img-top mb-3"
                                     style="height: 200px; withd: 54px;">
                            <?php else: ?>
                                <img src="assets/sin-portada.png"
                                     alt="Foto por defecto"
                                     class="card-img-top mb-3"
                                     style="height: 200px; withd: 54px;">
                            <?php endif; ?>

                    <h5 class="card-title">
                        <?= htmlspecialchars($juego->getNombre()) ?>
                    </h5>                

                        <p class="card-text">
                            <b>Editor:</b><?= $juego->getEditor() ?></td><br>
                            <b>Disponible:</b><?= $juego->getDisponible() ?></td><br>
                            <b>Código:</b><span class="juego-id">#<?= $juego->getId() ?></td><br>
                        </p>

                            <div class="mt-auto d-flex gap-2">
                                <a href="index.php?accion=formSolicitudJuego&amp;juego_id=<?= (int) $juego->getId() ?>&amp;tipo=prestamo"
                                   class="btn btn-warning btn-sm">
                                    Pedir
                                </a>

                                <a href="index.php?accion=formSolicitudJuego&amp;juego_id=<?= (int) $juego->getId() ?>&amp;tipo=reserva"
                                   class="btn btn-danger btn-sm">
                                    Reservar
                                </a>
                </div>

            </div>
        </div>
    </div>
                         <?php endforeach; ?>


            <?php if (empty($juegos)): ?>
                <p class="text-muted">No se encontraron juegos.</p>
            <?php endif; ?>

        </div>
    </div>
<?php require __DIR__ . '/../../includes/paginadorJuegoP.php'; ?>
<br>
</div>



<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>


<?php require __DIR__ . '/../../includes/footer.php'; ?>