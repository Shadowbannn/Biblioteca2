<?php require __DIR__ . '/../../includes/header.php'; ?>
<h1 class="titulo mt-5"><b>Juegos</b></h1>



<div class="card">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="mb-0">Listado de Juegos</span>
            <span class="mb-0">
                Total de Juegos: <?= number_format((int) ($totalRegistros ?? 0), 0, ',', '.') ?>
            </span>
        </div>


    <div class="card-body">
        <div class="mb-3">
            <a href="index.php?accion=formCrearJuego" class="btn btn-success">
                ➕ Agregar Juego
            </a>

            <a href="index.php?accion=formCrearPrestamoJuegos" class="btn btn-success">
                ➕ Agregar Préstamo
            </a>

            <a href="index.php?accion=listarPrestamosJuegos" class="btn btn-success">
                ➕ Ver Préstamos de Juego
            </a>            
        </div>

        <input class="form-control" id="myInput" type="search" placeholder="Buscar juegos..." aria-label="Buscar en los juegos de esta página">
        <br>
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">

                 <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                            <th scope="col">Foto</th>                            
                            <th scope="col">Nombre</th>
                            <th scope="col">Editor</th>                            
                            <th scope="col">Disponible</th>
                            <th scope="col">Código</th>
                            <th scope="col">Editar</th>
                            <th scope="col">Eliminar</th>                            
                        </tr>
                    </thead>    
                    <tbody id="myTable">                    
            <?php foreach ($juegos as $juego): ?>

                <tr>

                        <td>
                             <?php if ($juego->getFoto()): ?>
                                <img src="uploads/juegos/<?= htmlspecialchars($juego->getFoto()) ?>"
                                     alt="Foto de <?= htmlspecialchars($juego->getNombre()) ?>"
                                     class="card-img-top mb-3"
                                     style="height: 76px; withd: 54px;">
                            <?php else: ?>
                                <img src="assets/sin-portada.png"
                                     alt="Foto por defecto"
                                     class="card-img-top mb-3"
                                     style="height: 76px; withd: 54px;">
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($juego->getNombre()) ?></td>
                        <td><?= $juego->getEditor() ?></td>
                        <td><?= $juego->getDisponible() ?></td>
                        <td><span class="juego-id">#<?= $juego->getId() ?></td>
                        <td><div class="mt-auto d-flex gap-2">
                            
                                <a href="index.php?accion=formEditarJuego&id=<?= $juego->getId() ?>"
                                   class="btn btn-warning btn-sm">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                            </div>
                        </td>
                        <td><div class="mt-auto d-flex gap-2">

                                <a href="index.php?accion=eliminarJuego&id=<?= $juego->getId() ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('¿Deseas eliminar este juego?')">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div></td>


                        </tr>
                         <?php endforeach; ?>

                    </tbody>

                 </table>


            <?php if (empty($juegos)): ?>
                <p class="text-muted">Todavía no hay libros cargados.</p>
            <?php endif; ?>

        </div>
    </div>
<?php require __DIR__ . '/../../includes/paginadorJuego.php'; ?>
<br>
</div>



<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">← Volver al inicio</a>
</div>


<?php require __DIR__ . '/../../includes/footer.php'; ?>

<script>
$(document).ready(function(){
  $("#myInput").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#myTable tr").filter(function() {
      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
  });
});
</script>    