<?php require __DIR__ . '/../../includes/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="css/libros.css">

<section class="libros-catalogo" aria-labelledby="libros-titulo">
    <header class="libros-encabezado">
        <div>
            <p class="libros-etiqueta">BIBLIOTECA · CATÁLOGO</p>
            <h1 id="libros-titulo">Libros</h1>
            <p class="libros-descripcion">Consulta y administra los títulos de tu biblioteca.</p>
        </div>
        <a href="index.php?accion=formCrearLibro" class="btn libros-agregar">
            <i class="fa-solid fa-plus" aria-hidden="true"></i> Agregar libro
        </a>
    </header>

    <div class="libros-panel">
        <div class="libros-resumen">
            <h2><i class="fa-solid fa-book-open" aria-hidden="true"></i> Listado de libros</h2>
            <span class="libros-total"><?= number_format((int) ($totalRegistros ?? 0), 0, ',', '.') ?> libros en total</span>
        </div>

        <?php if (empty($libros)): ?>
            <div class="libros-vacio">
                <i class="fa-solid fa-book-open" aria-hidden="true"></i>
                <h3>Tu biblioteca empieza aquí</h3>
                <p>Todavía no hay libros cargados. Agrega el primer título para comenzar.</p>
                <a href="index.php?accion=formCrearLibro" class="btn libros-agregar">Agregar mi primer libro</a>
            </div>
        <?php else: ?>
            <div class="table-responsive libros-tabla-contenedor" role="region" aria-label="Listado de libros" tabindex="0">
                <table class="table libros-tabla align-middle mb-0">
                    <thead>
                        <tr>
                            <th scope="col">Libro / Autor</th>
                            <th scope="col">Género</th>
                            <th scope="col">Año</th>
                            <th scope="col">Páginas</th>
                            <th scope="col">Código</th>
                            <th scope="col" class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($libros as $libro): ?>
                            <tr>
                                <td>
                                    <div class="libros-identidad">
                                        <img src="<?= $libro->getPortada() ? 'uploads/portadas/' . htmlspecialchars($libro->getPortada(), ENT_QUOTES, 'UTF-8') : 'assets/sin-portada.webp' ?>"
                                             alt="Portada de <?= htmlspecialchars($libro->getTitulo(), ENT_QUOTES, 'UTF-8') ?>"
                                             class="libros-portada" width="54" height="76" loading="lazy">
                                        <div>
                                            <span class="libros-nombre"><?= htmlspecialchars($libro->getTitulo(), ENT_QUOTES, 'UTF-8') ?></span>
                                            <span class="libros-autor"><?= htmlspecialchars((string) $libro->getAutor(), ENT_QUOTES, 'UTF-8') ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="libros-genero"><?= htmlspecialchars((string) $libro->getGenero(), ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td><?= htmlspecialchars((string) $libro->getAnioPublicacion(), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars((string) $libro->getCantidadPaginas(), ENT_QUOTES, 'UTF-8') ?></td>
                                <td><span class="libros-codigo">#<?= (int) $libro->getId() ?></span></td>
                                <td>
                                    <div class="libros-acciones">
                                        <a href="index.php?accion=formEditarLibro&id=<?= (int) $libro->getId() ?>" class="btn libros-editar" aria-label="Editar <?= htmlspecialchars($libro->getTitulo(), ENT_QUOTES, 'UTF-8') ?>" title="Editar libro">
                                            <i class="fa-solid fa-pen-to-square" aria-hidden="true"></i>
                                        </a>
                                        <a href="index.php?accion=eliminarLibro&id=<?= (int) $libro->getId() ?>" class="btn libros-eliminar" aria-label="Eliminar <?= htmlspecialchars($libro->getTitulo(), ENT_QUOTES, 'UTF-8') ?>" title="Eliminar libro" onclick="return confirm('¿Deseas eliminar este libro?')">
                                            <i class="fa-solid fa-trash" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <?php require __DIR__ . '/../../includes/paginador.php'; ?>

    <a href="index.php" class="libros-volver"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Volver al inicio</a>
</section>

<?php require __DIR__ . '/../../includes/footer.php'; ?>
