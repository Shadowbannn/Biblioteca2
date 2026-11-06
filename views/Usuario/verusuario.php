<?php require __DIR__ . '/../../includes/header.php'; ?>

<div class="cardsmall">
<h1 class="titulo mt-5"><b>Usuarios</b></h1>

<div class="mb-3">
    <a href="index.php?accion=formCrearUsuario" class="btn btn-success">
        ➕ Agregar usuario
    </a>
</div>
</div>

<div class="card">

    <div class="card-header bg-dark text-white">
        <h4 class="mb-0">Listado de Usuarios</h4>
    </div>

    <div class="card-body">
        <?php require __DIR__ . '/../../includes/paginador.php'; ?>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

            <?php foreach ($usuarios as $usuario): ?>

                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">

                        
                            <?php if (method_exists($usuario, 'getFoto') && $usuario->getFoto()): ?>
                                    <img src="/carpeta2/uploads/fotos/<?= htmlspecialchars($usuario->getFoto()) ?>"
                                         alt="Foto de <?= htmlspecialchars($usuario->getNombre()) ?>"
                                         class="card-img-top mb-3"
                                         style="max-height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="https://static.vecteezy.com/system/resources/previews/019/879/186/large_2x/user-icon-on-transparent-background-free-png.png"
                                            alt="Imagen por defecto"
                                            class="card-img-top mb-3"
                                            style="max-height: 200px; object-fit: cover;">
                                <?php endif; ?>    

                            <p class="card-text">
                                <b>Nombre:</b> <?= htmlspecialchars($usuario->getNombre()) ?><br>
                                <b>Cedula:</b> <?= $usuario->getCedula() ?><br>                            
                                <b>Registrado:</b> <?= date('d/m/Y', strtotime($usuario->getFecharegistro())) ?><br>
                                <b>Clase:</b> <?= htmlspecialchars($usuario->getClase()) ?><br>
                                <b>ID:</b> <?= $usuario->getId() ?><br>


                            </p>

                            <div class="mt-auto d-flex gap-2">
                                <a href="index.php?accion=formEditarUsuario&id=<?= $usuario->getId() ?>"
                                   class="btn btn-warning btn-sm">
                                    Editar
                                </a>

                                <a href="index.php?accion=eliminarSocio&id=<?= $socio->getId() ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Deseas eliminar este socio?')">
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



<?php require __DIR__ . '/../../includes/footer.php'; ?>
