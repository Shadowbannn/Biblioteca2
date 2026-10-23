<?php require __DIR__ . '/../../includes/header.php'; ?>
<h1 class="titulo mt-5"><b>Libros</b></h1>



<div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="mb-0">Listado de Libros</span>
            <span class="mb-0">
                Total de libros: <?= number_format((int) ($totalRegistros ?? 0), 0, ',', '.') ?>
            </span>
        </div>


<?php require __DIR__ . '/../../includes/filtroLibros.php'; ?>
   <?php require __DIR__ . '/../../includes/paginador.php'; ?>

    <div class="card-body">
        <div class="mb-3">
            <a href="index.php?accion=formCrearLibro" class="btn btn-success">
                ➕ Agregar Libro
            </a>
                <a href="index.php?accion=formCrearPrestamo" class="btn btn-success">
                    ➕ Agregar Préstamo
                </a>
                    <a href="index.php?accion=listarPrestamos" class="btn btn-success">
                        ➕ Ver Préstamo
                    </a>
        </div>


        <div class="table-responsive">

                 <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Portada</th>
                            <th scope="col">Título</th>
                            <th scope="col">Autor</th>
                            <th scope="col">Género</th>
                            <th scope="col">Año</th>
                            <th scope="col">Código</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
            <?php foreach ($libros as $libro): ?>

                <tr>

                        <td>
                            <?php if ($libro->getPortada()): ?>
                                <img src="uploads/portadas/<?= htmlspecialchars($libro->getPortada()) ?>"
                                     alt="Portada de <?= htmlspecialchars($libro->getTitulo()) ?>"
                                     class="card-img-top mb-3"
                                     style="height: 76px; width: 54px;">
                            <?php else: ?>
                                <img src="assets/sin-portada.webp"
                                     alt="Portada por defecto"
                                     class="card-img-top mb-3"
                                     style="height: 76px; width: 54px;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($libro->getTitulo()) ?></td>
                        <td><?= $libro->getAutor() ?></td>
                        <td><?= $libro->getGenero() ?></td>
                        <td><?= $libro->getAnioPublicacion() ?></td>
                        <td><span class="libro-id">#<?= $libro->getId() ?></span></td>
                        <td><div class="mt-auto d-flex gap-2">

                                <a href="index.php?accion=formEditarLibro&id=<?= $libro->getId() ?>"
                                   class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                            </div>
                        </td>
                        <td><div class="mt-auto d-flex gap-2">

                                <a href="index.php?accion=eliminarLibro&id=<?= $libro->getId() ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Deseas eliminar este libro?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div></td>


                        </tr>
                         <?php endforeach; ?>

                    </tbody>

                 </table>

            <?php if (empty($libros)): ?>
                <p class="text-muted">No se encontraron libros con esos filtros.</p>
            <?php endif; ?>

        </div>
    </div>

 <?php require __DIR__ . '/../../includes/paginador.php'; ?>
</div>

<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>


<?php require __DIR__ . '/../../includes/footer.php'; ?>
