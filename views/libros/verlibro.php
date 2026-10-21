<?php require __DIR__ . '/../../includes/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<h1 class="titulo mt-5"><b>Libros</b></h1>



<div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="mb-0">Listado de Libros</span>
            <span class="mb-0">
                Total de libros: <?= number_format((int) ($totalRegistros ?? 0), 0, ',', '.') ?>
            </span>
        </div>
       
        
<form method="GET" action="index.php" class="row g-2 align-items-end mb-4">
    <input type="hidden" name="accion" value="listarLibros">
    <div class="col-12 col-lg-5">
        <label class="form-label" style="color: white; for="buscar">Buscar libros</label>
        <input id="buscar" type="search" name="buscar" class="form-control"
               placeholder="Título, autor o género..."
               value="<?= htmlspecialchars($buscar ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>
    <div class="col-6 col-lg-3">
        <select id="campo" name="campo" class="form-select">
            <?php foreach (['todos' => 'Todos los campos', 'titulo' => 'Título', 'autor' => 'Autor', 'genero' => 'Género'] as $clave => $texto): ?>
                <option value="<?= $clave ?>" <?= ($campo ?? 'todos') === $clave ? 'selected' : '' ?>><?= $texto ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-6 col-lg-2">
        <select id="disponibilidad" name="disponibilidad" class="form-select">
            <option value="" <?= ($disponibilidad ?? '') === '' ? 'selected' : '' ?>>Todas</option>
            <option value="1" <?= ($disponibilidad ?? '') === '1' ? 'selected' : '' ?>>Disponibles</option>
            <option value="0" <?= ($disponibilidad ?? '') === '0' ? 'selected' : '' ?>>No disponibles</option>
        </select>
    </div>
    <div class="col-12 col-lg-2 d-flex gap-2">
        <button class="btn btn-primary flex-fill" type="submit">Buscar</button>
        <a class="btn btn-outline-secondary" href="index.php?accion=listarLibros" title="Limpiar filtros">Limpiar</a>
    </div>
</form>
   <?php require __DIR__ . '/../../includes/paginador.php'; ?>

    <div class="card-body">
        <div class="mb-3">
            <a href="index.php?accion=formCrearLibro" class="btn btn-success">
                ➕ Agregar Libro
            </a>
        </div>


        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

                 <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Título</th>
                            <th scope="col">Autor</th>                            
                            <th scope="col">Género</th>
                            <th scope="col">Año</th>
                            <th scope="col">Páginas</th>
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
                                     style="height: 76px; withd: 54px;">
                            <?php else: ?>
                                <img src="assets/sin-portada.webp"
                                     alt="Portada por defecto"
                                     class="card-img-top mb-3"
                                     style="height: 76px; withd: 54px;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($libro->getTitulo()) ?></td>
                        <td><?= $libro->getAutor() ?></td>
                        <td><?= $libro->getGenero() ?></td>
                        <td><?= $libro->getAnioPublicacion() ?></td>
                        <td><span class="libro-id">#<?= $libro->getId() ?></td>
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
