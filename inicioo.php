<?php
require __DIR__ . '/controllers/InicioPublicoController.php';
require __DIR__ . '/includes/headerp.php';
?>


<div id="carouselInicio" class="carousel slide" data-bs-ride="carousel" aria-label="Presentación de la biblioteca">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselInicio" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Bienvenida"></button>
            <button type="button" data-bs-target="#carouselInicio" data-bs-slide-to="1" aria-label="Catálogo"></button>
            <button type="button" data-bs-target="#carouselInicio" data-bs-slide-to="2" aria-label="Juegos"></button>
        </div>
        <div class="carousel-inner"style="
            padding-right: 20px;
            border-left-width: 20px;">

            <div class="carousel-item active">
                <img class="d-block w-100" src="https://www.utu.edu.uy/wp-content/uploads/2025/09/WhatsApp-Image-2025-08-28-at-8.00.05-AM-1024x768.jpeg" style="height: 500px;" alt="Libros de la biblioteca">
                <div class="carousel-caption d-none d-md-block">
                    <h1 class="h5">Bienvenidos</h1>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="https://www.utu.edu.uy/wp-content/uploads/2025/09/WhatsApp-Image-2025-08-29-at-11.28.24-AM2-1-edited-scaled.jpeg" style="height: 500px;" alt="Un espacio para descubrir nuevas lecturas">
                <div class="carousel-caption d-none d-md-block">
                    <h2 class="h5">Tu próxima lectura te espera</h2>
                    <p>Explora el catálogo y encuentra una historia para ti.</p>
                    <a href="index.php?accion=listarCatalogo" class="btn btn-primary">Ver catálogo</a>
                </div>
            </div>
            <div class="carousel-item">
                <img class="d-block w-100" src="https://miro.medium.com/v2/resize:fit:8064/1*OwSz8qBtLFs2d7aqbR-Ufg.jpeg" style="height: 500px;" alt="Juegos para compartir y aprender">
                <div class="carousel-caption d-none d-md-block">
                    <h2 class="h5">Más formas de aprender</h2>
                    <p>Descubre nuestros juegos y proyectos.</p>
                    <a href="#destacados" class="btn btn-primary">Explorar</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselInicio" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselInicio" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Siguiente</span>
        </button>
    </div>



<div class="container-fluid inicio-contenedor">
<main id="inicio">
    
    <section id="destacados" class="mt-5" aria-labelledby="titulo-destacados">
        <h2 id="titulo-destacados" class="h3 mb-4">Descubre la biblioteca</h2>
        <div class="inicio-tarjetas">
            
            <!-- Destacado: Catálogo -->
                <article id="destacado-catalogo" class="card">
                    <a href="index.php?accion=listarCatalogo">
                    <img class="card-img-top" src="img/1.png" alt="Catálogo" loading="lazy">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title h5">Catálogo</h3>
                        <p class="card-text">Encuentra tu próxima lectura y explora los libros de nuestra biblioteca.</p>
                        <a href="index.php?accion=listarCatalogo" class="btn btn-primary mt-auto">Explorar catálogo</a>
                    </div>
                </article>

            <!-- Destacado: Juegos -->
                <article id="destacado-juegos" class="card">
                    <a href="index.php?accion=listarJuegosP">
                    <img class="card-img-top" src="img/6.png" alt="Juegos" loading="lazy">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title h5">Juegos</h3>
                        <p class="card-text">Descubre juegos para compartir, aprender y disfrutar en compañía.</p>
                        <a href="index.php?accion=listarJuegosP" class="btn btn-primary mt-auto">Ver juegos</a>
                    </div>
                </article>

            <!-- Destacado: Proyectos -->
                <article id="destacado-proyectos" class="card">
                    <a href="index.php?accion=listarProyectosP">
                    <img class="card-img-top" src="img/5.png" alt="Proyectos" loading="lazy">
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h3 class="card-title h5">Proyectos</h3>
                        <p class="card-text">Conoce los proyectos de la biblioteca y descubre nuevas ideas.</p>
                        <a href="index.php?accion=listarProyectosP" class="btn btn-primary mt-auto">Conocer proyectos</a>
                    </div>
                </article>
        </div>
    </section>

    <section id="noticias" class="mt-5" aria-labelledby="titulo-noticias">
        <h2 id="titulo-noticias" class="h3 mb-4">Noticias</h2>
        <div class="card">
            <div class="card-body">
                <h3 class="card-title h5">Novedades de la biblioteca</h3>
                <p class="card-text mb-0">se vienen cositas...</p>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/includes/footer.php'; ?>
