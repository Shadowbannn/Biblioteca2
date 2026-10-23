<?php require __DIR__ . '/../../includes/header.php'; ?>
<h1 class="titulo mt-5"><b>Proyectos</b></h1>



<div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="mb-0">Listado de Proyectos</span>
            <span class="mb-0">
                Total de proyectos: <?= number_format((int) ($totalRegistros ?? 0), 0, ',', '.') ?>
            </span>
        </div>
       
        
 <?php require __DIR__ . '/../../includes/paginadorProyecto.php'; ?>

    <div class="card-body">
        <div class="mb-3">
            <a href="index.php?accion=formCrearProyecto" class="btn btn-success">
                ➕ Agregar Proyecto
            </a>
        </div>


        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

                 <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Portada</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Integrantes</th>                            
                            <th scope="col">Clase</th>
                            <th scope="col">Año</th>
                            <th scope="col">Curso</th>
                            <th scope="col">Codigo</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Eliminar</th>                            
                        </tr>
                    </thead>    
                    <tbody>                    
            <?php foreach ($proyectos as $proyecto): ?>

                <tr>

                        <td>
                            <?php if ($proyecto->getPortada()): ?>
                                <img src="uploads/portadas/<?= htmlspecialchars($proyecto->getPortada()) ?>"
                                     alt="Portada de <?= htmlspecialchars($proyecto->getNombre()) ?>"
                                     class="card-img-top mb-3"
                                     style="height: 76px; withd: 54px;">
                            <?php else: ?>
                                <img src="assets/sin-portada.webp"
                                     alt="Portada por defecto"
                                     class="card-img-top mb-3"
                                     style="height: 76px; withd: 54px;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($proyecto->getNombre()) ?></td>
                        <td><?= $proyecto->getIntegrantes() ?></td>
                        <td><?= $proyecto->getClase() ?></td>
                        <td><?= $proyecto->getCurso() ?></td>
                        <td><?= $proyecto->getAnio() ?></td>
                        <td><span class="proyecto-id">#<?= $proyecto->getId() ?></td>
                        <td><div class="mt-auto d-flex gap-2">
                            
                                <a href="index.php?accion=formEditarProyecto&id=<?= $proyecto->getId() ?>"
                                   class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                            </div>
                        </td>
                        <td><div class="mt-auto d-flex gap-2">

                                <a href="index.php?accion=eliminarProyecto&id=<?= $proyecto->getId() ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Deseas eliminar este proyecto?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div></td>


                        </tr>
                         <?php endforeach; ?>

                    </tbody>

                 </table>

            <?php if (empty($proyectos)): ?>
                <p class="text-muted">No se encontraron proyectos con esos filtros.</p>
            <?php endif; ?>

        </div>
    </div>

 <?php require __DIR__ . '/../../includes/paginadorProyecto.php'; ?>
</div>

<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>


<?php require __DIR__ . '/../../includes/footer.php'; ?>
