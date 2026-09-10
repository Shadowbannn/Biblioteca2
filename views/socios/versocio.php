<?php require __DIR__ . '/../../includes/header.php'; ?>

<h1 class="titulo mt-5"><br><b>Socios</b></h1>

<div class="mb-3">
    <a href="index.php?accion=formCrearSocio" class="btn btn-success">
        ➕ Agregar socio
    </a>
</div>

<div class="card">

    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Listado de Socios</h4>
    </div>

    <div class="card-body">
        <?php require __DIR__ . '/../../includes/paginador.php'; ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            <?php foreach ($socios as $socio): ?>

                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">

                        
                            <?php if (method_exists($socio, 'getImagen') && $socio->getImagen()): ?>
                                    <img src="/carpeta2/uploads/imganes/<?= htmlspecialchars($socio->getImagen()) ?>"
                                         alt="Imagen de <?= htmlspecialchars($socio->getNombre()) ?>"
                                         class="card-img-top mb-3"
                                         style="max-height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="https://static.vecteezy.com/system/resources/previews/019/879/186/large_2x/user-icon-on-transparent-background-free-png.png"
                                            alt="Imagen por defecto"
                                            class="card-img-top mb-3"
                                            style="max-height: 200px; object-fit: cover;">
                                <?php endif; ?>    

                            <p class="card-text">
                                <b>Nombre:</b> <?= htmlspecialchars($socio->getNombre()) ?><br>
                                <b>Apellido:</b> <?= htmlspecialchars($socio->getApellido()) ?><br>
                                <b>Cedula:</b> <?= $socio->getCedula() ?><br>
                                <b>ID:</b> <?= $socio->getId() ?><br>
                                <b>Telefono:</b> <?= ($socio->getTelefono()) ?><br>
                                <b>Registrado/a:</b> <?= ($socio->getFecharegistro()) ?><br>
                            </p>

                            <div class="mt-auto d-flex gap-2">
                                <a href="index.php?accion=formEditarSocio&id=<?= $socio->getId() ?>"
                                   class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <a href="index.php?accion=eliminarSocio&id=<?= $socio->getId() ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Deseas eliminar este socio$socio?')">
                                    Eliminar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>

            <?php if (empty($socio)): ?>
                <p class="text-muted">Todavía no hay socios cargados.</p>
            <?php endif; ?>

        </div>
    </div>

    <?php require __DIR__ . '/../../includes/paginador.php'; ?>
</div>

<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
