<?php
$esc = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
$mensajePassword = (string) ($_SESSION['mensaje_password'] ?? '');
$mensajeFoto = (string) ($_SESSION['mensaje_foto'] ?? '');
unset($_SESSION['mensaje_password']);
unset($_SESSION['mensaje_foto']);
$fotoActual = $socio->getFoto() ? basename($socio->getFoto()) : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar cuenta | Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/log.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/usuario-form.css?v=2">
</head>
<body class="usuario-form-page">
    <main class="login-card usuario-form-card">
        <aside class="reg-left usuario-form-visual">
            <img src="img/reg.png" alt="Personas leyendo y compartiendo libros">
        </aside>

        <section class="login-right usuario-form-content">
            <div class="usuario-form-heading">
                <h1>Editar cuenta</h1>
                <p>Actualiza la contraseña de tu cuenta.</p>
            </div>

            <?php if ($mensajePassword !== ''): ?>
                <div class="alert <?= $mensajePassword === 'Contraseña actualizada.' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                    <?= $esc($mensajePassword) ?>
                </div>
            <?php endif; ?>

            <?php if ($mensajeFoto !== ''): ?>
                <div class="alert <?= $mensajeFoto === 'Foto de perfil actualizada.' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                    <?= $esc($mensajeFoto) ?>
                </div>
            <?php endif; ?>

            <form class="usuario-form mb-4" id="foto-form" method="post" enctype="multipart/form-data" action="index.php?accion=actualizarFotoSocio">
                <input type="hidden" name="csrf" value="<?= $esc($_SESSION['cambiar_password_socio_csrf']) ?>">
                <div class="usuario-foto-field">
                    <div class="usuario-foto-preview">
                        <?php if ($fotoActual !== ''): ?>
                            <img src="uploads/fotos/<?= rawurlencode($fotoActual) ?>" alt="Foto de perfil actual">
                        <?php else: ?>
                            <span aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                        <?php endif; ?>
                    </div>
                    <div class="usuario-foto-control">
                        <label for="foto">Nueva foto de perfil</label>
                        <input id="foto" type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp" aria-describedby="foto-ayuda" data-max-size="5242880" data-max-size-label="5 MB" required>
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
                <button type="submit" class="btn-ingresar"><i class="fa-solid fa-camera" aria-hidden="true"></i>Actualizar foto</button>
            </form>

            <form class="usuario-form" method="post" action="index.php?accion=cambiarPasswordSocio">
                <input type="hidden" name="csrf" value="<?= $esc($_SESSION['cambiar_password_socio_csrf']) ?>">

                <div class="usuario-form-grid">
                    <div class="usuario-field">
                        <label for="password-actual">Contraseña actual</label>
                        <div class="input-container"><i class="fa-solid fa-lock" aria-hidden="true"></i><input id="password-actual" type="password" name="password_actual" autocomplete="current-password" placeholder="Contraseña actual" required></div>
                    </div>
                    <div class="usuario-field">
                        <label for="password-nueva">Nueva contraseña</label>
                        <div class="input-container"><i class="fa-solid fa-lock" aria-hidden="true"></i><input id="password-nueva" type="password" name="password_nueva" autocomplete="new-password" minlength="8" placeholder="Mínimo 8 caracteres" required></div>
                    </div>
                    <div class="usuario-field usuario-field-wide">
                        <label for="password-confirmacion">Confirmar nueva contraseña</label>
                        <div class="input-container"><i class="fa-solid fa-lock" aria-hidden="true"></i><input id="password-confirmacion" type="password" name="password_confirmacion" autocomplete="new-password" minlength="8" placeholder="Repite la nueva contraseña" required></div>
                    </div>
                </div>

                <button type="submit" class="btn-ingresar"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i>Guardar cambios</button>
            </form>

            <div class="usuario-form-back">
                <a href="index.php?accion=misPrestamos"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Volver a mis préstamos</a>
            </div>
        </section>
    </main>
    <script src="js/editar-cuenta-foto.js" defer></script>
</body>
</html>
