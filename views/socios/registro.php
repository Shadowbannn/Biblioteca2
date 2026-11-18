<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de socio | Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/log.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/usuario-form.css?v=3">
</head>
<body class="usuario-form-page">
    <main class="login-card usuario-form-card">
        <aside class="reg-left usuario-form-visual">
            <img src="img/reg.png" alt="Personas leyendo y compartiendo libros">
            <div class="usuario-form-visual-caption">
                <span><i class="fa-solid fa-book-open" aria-hidden="true"></i>Biblioteca</span>
                <p>Únete a la comunidad y disfruta de sus servicios.</p>
            </div>
        </aside>
        <section class="login-right usuario-form-content">
            <div class="usuario-form-heading">
                <span class="usuario-form-eyebrow">CREA TU CUENTA</span>
                <h1>Registro de socio</h1>
                <p>Completa tus datos para acceder a los servicios de la biblioteca.</p>
            </div>
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>
            <form class="usuario-form" id="foto-form" method="POST" enctype="multipart/form-data" action="index.php?accion=registrarSocio">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['registro_socio_csrf'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                <div class="usuario-foto-field">
                    <div class="usuario-foto-preview">
                        <span aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                    </div>
                    <div class="usuario-foto-control">
                        <label for="foto">Foto de perfil <span>(opcional)</span></label>
                        <input id="foto" type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp" aria-describedby="foto-ayuda" data-max-size="5242880" data-max-size-label="5 MB">
                        <small id="foto-ayuda">JPG, PNG o WEBP. Máximo 5 MB.</small>
                    </div>
                </div>

                <div class="usuario-cropper" id="foto-editor" hidden>
                    <div class="usuario-cropper-preview">
                        <canvas id="foto-canvas" width="320" height="320" aria-label="Previsualización recortada de la foto"></canvas>
                    </div>
                    <div class="usuario-cropper-controls">
                        <label for="foto-zoom">Acercar imagen</label>
                        <input id="foto-zoom" type="range" min="1" max="3" step="0.05" value="1">
                        <small>Arrastra la imagen para ajustar el encuadre.</small>
                    </div>
                </div>
                <p class="usuario-foto-status" id="foto-status" role="status" aria-live="polite"></p>

                <div class="usuario-form-grid">
                    <div class="usuario-field">
                        <label for="nombre">Nombre completo</label>
                        <div class="input-container"><i class="fa-solid fa-user" aria-hidden="true"></i><input id="nombre" type="text" name="nombre" maxlength="100" autocomplete="name" placeholder="Nombre completo" value="<?= htmlspecialchars(is_string($_POST['nombre'] ?? null) ? $_POST['nombre'] : '', ENT_QUOTES, 'UTF-8') ?>" required></div>
                    </div>
                    <div class="usuario-field">
                        <label for="clase">Clase</label>
                        <div class="input-container"><i class="fa-solid fa-users" aria-hidden="true"></i><input id="clase" type="text" name="clase" maxlength="50" placeholder="Clase" value="<?= htmlspecialchars(is_string($_POST['clase'] ?? null) ? $_POST['clase'] : '', ENT_QUOTES, 'UTF-8') ?>" required></div>
                    </div>
                    <div class="usuario-field">
                        <label for="cedula">Cédula</label>
                        <div class="input-container"><i class="fa-solid fa-id-card" aria-hidden="true"></i><input id="cedula" type="text" name="cedula" maxlength="20" autocomplete="username" placeholder="Cédula" value="<?= htmlspecialchars(is_string($_POST['cedula'] ?? null) ? $_POST['cedula'] : '', ENT_QUOTES, 'UTF-8') ?>" required></div>
                    </div>
                    <div class="usuario-field">
                        <label for="telefono">Teléfono</label>
                        <div class="input-container"><i class="fa-solid fa-phone" aria-hidden="true"></i><input id="telefono" type="tel" name="telefono" maxlength="20" autocomplete="tel" placeholder="Teléfono" value="<?= htmlspecialchars(is_string($_POST['telefono'] ?? null) ? $_POST['telefono'] : '', ENT_QUOTES, 'UTF-8') ?>" required></div>
                    </div>
                    <div class="usuario-field">
                        <label for="password">Contraseña</label>
                        <div class="input-container"><i class="fa-solid fa-lock" aria-hidden="true"></i><input id="password" type="password" name="password" minlength="8" maxlength="72" autocomplete="new-password" placeholder="Crea una contraseña" required></div>
                    </div>
                    <div class="usuario-field">
                        <label for="confirmacion">Confirmar contraseña</label>
                        <div class="input-container"><i class="fa-solid fa-lock" aria-hidden="true"></i><input id="confirmacion" type="password" name="confirmacion" minlength="8" maxlength="72" autocomplete="new-password" placeholder="Repite la contraseña" required></div>
                    </div>
                </div>

                <button type="submit" class="btn-ingresar"><i class="fa-solid fa-user-plus" aria-hidden="true"></i> Crear cuenta</button>
            </form>

            <div class="usuario-form-back">
                <a href="index.php?accion=formLoginSocio"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Ya tengo una cuenta</a>
            </div>
        </section>
    </main>
    <script src="js/editar-cuenta-foto.js" defer></script>
</body>
</html>
