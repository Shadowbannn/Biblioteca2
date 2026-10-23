<?php require __DIR__ . '/../../includes/header.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<h1 class="titulo mt-5"><b>Usuarios</b></h1>



<div class="card">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Cuentas del sistema</h4>  
        </div>
        <br>  
    <div class="mb-3">
        <a href="index.php?accion=formCrearUsuario" class="btn btn-success">
            ➕ Agregar usuario
        </a>
    </div>    

    <div class="card-body">
        <input class="form-control" id="myInput" type="text" placeholder="Search..">
        <br>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">



                <table class="table table-striped table-dark">
                    <thead>
                        <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Registrado</th>
                        <th scope="col">Rol</th>
                        <th scope="col">Código</th>
                        <th scope="col">Editar</th>
                        <th scope="col">Eliminar</th>
                        </tr>
                    </thead>


                    <tbody id="myTable">
                        <?php foreach ($usuarios as $u): ?>

                            <tr>
                                <td><?= htmlspecialchars($u->getNombre()) ?></td>
                                <td><?= $u->getEmail() ?></td>
                                <td><?= $u->getFechaRegistro() !== '' ? date('d/m/Y', strtotime($u->getFechaRegistro())) : '-' ?></td>
                                <td><?= htmlspecialchars($u->getRoles()) ?></td>
                                <td><?= $u->getId() ?></td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="index.php?accion=formEditarUsuario&id=<?= $u->getId() ?>"
                                           class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-user-pen"></i>
                                        </a>
                                    </div>
                                </td>

                                <td>
                                    <div class="d-flex gap-2 mt-1">
                                        <a href="index.php?accion=eliminarUsuario&id=<?= $u->getId() ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('¿Deseas eliminar este usuario?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
            <?php endforeach; ?>                            
                    </tbody>

                </table>
            </div>

            <?php if (empty($usuarios)): ?>
                <p class="text-muted">Todavía no hay usuarios cargados.</p>
            <?php endif; ?>

        </div>
    </div>
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

