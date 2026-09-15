<?php require __DIR__ . '/../includes/header.php'; ?>

<h1 class="titulo"><br><br><b>Bienvenidos</b></h1>

<div class="row mt-5">

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarLibros">
                <img class="card-img-top" src="https://statics.forbesuruguay.com/2024/11/crop/67407a774a603__600x390.webp" alt="Libros">
            </a>
            <div class="card-body">
                <h5 class="card-title">Libritos</h5>
                <p class="card-text">Libritos disponibles</p>
                <a href="index.php?accion=listarLibros" class="btn btn-primary">Ver Libritos</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarSocios">
                <img class="card-img-top" src="https://imagenes.excelsior.com.mx/files/og_thumbnail/uploads/2025/03/11/691ffb95e51ea.jpeg" alt="Socios">
            </a>
            <div class="card-body">
                <h5 class="card-title">Usuarios</h5>
                <p class="card-text">Sociedad de lectores</p>
                <a href="index.php?accion=listarSocios" class="btn btn-primary">Ver Usuarios</a>
            </div>
        </div>
    </div>

    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <a href="index.php?accion=listarPrestamos">
                <img class="card-img-top" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQLfB2R3avYAP-mu4DI27Gz26QW7mcJDZTMn3TafilEoWUBRZ4kJoS_Bywt&s=10" alt="Prestamos">
            </a>
            <div class="card-body">
                <h5 class="card-title">Prestamos</h5>
                <p class="card-text">Prestamos hechos</p>
                <a href="index.php?accion=listarPrestamos" class="btn btn-primary">Ver Prestamos</a>
            </div>
        </div>
    </div>

</div>

<?php require __DIR__ . '/../includes/footer.php'; ?>
