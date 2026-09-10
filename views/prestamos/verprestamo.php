<?php require __DIR__ . '/../../includes/header.php'; ?>

<h1 class="titulo mt-5"><br><b>Préstamos</b></h1>

<div class="mb-3">
    <a href="index.php?accion=formCrearPrestamo" class="btn btn-success">
        ➕ Agregar préstamo
    </a>
</div>

<div class="card">
    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Listado de Préstamos</h4>
    </div>

    <div class="card-body">
        <?php require __DIR__ . '/../../includes/paginador.php'; ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            <?php foreach ($prestamos as $prestamo): ?>

                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                             <?php if (!empty($prestamo->getLibroPortada())): ?>
                                        <img src="/carpeta2/uploads/portadas/<?= htmlspecialchars($prestamo->getLibroPortada()) ?>"
                                            alt="Portada de <?= htmlspecialchars($prestamo->getLibroTitulo()) ?>"
                                            class="portada-prestamo">
                                    <?php else: ?>
                                        <img src="/carpeta2/assets/sin-portada.png"
                                            alt="Sin portada"
                                            class="portada-prestamo">
                                    <?php endif; ?>

                            <h5 class="card-title">
                                <b>ID:</b> <?= $prestamo->getId() ?><br>
                                <b>Prestado:</b> <?= date('d/m/Y', strtotime($prestamo->getFechaPrestamo())) ?><br> 
                                <b>Libro:</b> <?= htmlspecialchars($prestamo->getLibroTitulo()) ?><br> 
                                <b>Usuario:</b> <?= htmlspecialchars($prestamo->getSocioNombre()) ?><br> 
                            </h5>

                            <div class="mt-auto d-flex gap-2">
                                <a href="index.php?accion=formEditarPrestamo&id=<?= $prestamo->getId() ?>"
                                   class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <a href="index.php?accion=eliminarPrestamo&id=<?= $prestamo->getId() ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Deseas eliminar este préstamo?')">
                                    Eliminar
                                </a>
                            </div>

                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

            <?php if (empty($prestamos)): ?>
                <p class="text-muted">Todavía no hay préstamos cargados.</p>
            <?php endif; ?>

        </div>
    </div>

    <?php require __DIR__ . '/../../includes/paginador.php'; ?>
</div>

<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>