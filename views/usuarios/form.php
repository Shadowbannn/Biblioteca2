<?php
$esEdicion = isset($usuario);
$escUsuario = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $esEdicion ? 'Editar cuenta' : 'Crear cuenta' ?> | Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/log.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/usuario-form.css">
</head>
<body class="usuario-form-page">
    <main class="login-card usuario-form-card">
        <aside class="reg-left usuario-form-visual">
            <img src="img/reg.png" alt="Personas leyendo y compartiendo libros">
        </aside>

        <section class="login-right usuario-form-content">
            <div class="usuario-form-heading">
                <h1><?= $esEdicion ? 'Editar cuenta' : 'Crear cuenta' ?></h1>
                <p><?= $esEdicion ? 'Actualiza tu perfil y tus datos de acceso.' : 'Completa tus datos para crear una cuenta.' ?></p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" role="alert"><?= $escUsuario($error) ?></div>
            <?php endif; ?>

            <form class="usuario-form" method="POST" enctype="multipart/form-data" action="index.php?accion=<?= $esEdicion ? 'editarUsuario' : 'crearUsuario' ?>">
                <?php if ($esEdicion): ?>
                    <input type="hidden" name="id" value="<?= (int) $usuario->getId() ?>">
                <?php endif; ?>

                <div class="usuario-foto-field">
                    <div class="usuario-foto-preview">
                        <?php if ($esEdicion && $usuario->getFoto()): ?>
                            <img src="uploads/usuarios/<?= $escUsuario($usuario->getFoto()) ?>" alt="Foto actual de <?= $escUsuario($usuario->getNombre()) ?>">
                        <?php else: ?>
                            <span aria-hidden="true"><i class="fa-solid fa-user"></i></span>
                        <?php endif; ?>
                    </div>
                    <div class="usuario-foto-control">
                        <label for="foto">Foto de perfil <span>(opcional)</span></label>
                        <input id="foto" type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/webp" aria-describedby="foto-ayuda">
                        <small id="foto-ayuda">JPG, PNG o WEBP. Máximo 2 MB.</small>
                    </div>
                </div>

                <div class="usuario-form-grid">
                    <div class="usuario-field">
                        <label for="nombre">Nombre</label>
                        <div class="input-container"><i class="fa-solid fa-user" aria-hidden="true"></i><input id="nombre" type="text" name="nombre" autocomplete="name" placeholder="Nombre" value="<?= $escUsuario($_POST['nombre'] ?? ($esEdicion ? $usuario->getNombre() : '')) ?>" required></div>
                    </div>
                    <div class="usuario-field">
                        <label for="email">Correo electrónico</label>
                        <div class="input-container"><i class="fa-solid fa-envelope" aria-hidden="true"></i><input id="email" type="email" name="email" autocomplete="email" placeholder="nombre@correo.com" value="<?= $escUsuario($_POST['email'] ?? ($esEdicion ? $usuario->getEmail() : '')) ?>" required></div>
                    </div>
                    <div class="usuario-field usuario-field-wide">
                        <label for="roles">Rol o permisos</label>
                        <div class="input-container"><i class="fa-solid fa-user-shield" aria-hidden="true"></i><input id="roles" type="text" name="roles" placeholder="Ej. Bibliotecaria" value="<?= $escUsuario($_POST['roles'] ?? ($esEdicion ? $usuario->getRoles() : '')) ?>"></div>
                        <small>Si corresponde, escribe los roles separados por comas.</small>
                    </div>
                    <div class="usuario-field">
                        <label for="password"><?= $esEdicion ? 'Nueva contraseña' : 'Contraseña' ?> <?= $esEdicion ? '<span>(opcional)</span>' : '' ?></label>
                        <div class="input-container"><i class="fa-solid fa-lock" aria-hidden="true"></i><input id="password" type="password" name="password" autocomplete="new-password" placeholder="<?= $esEdicion ? 'Dejar en blanco para conservarla' : 'Crea una contraseña' ?>" <?= $esEdicion ? '' : 'required' ?>></div>
                    </div>
                    <div class="usuario-field">
                        <label for="confirmar_password">Confirmar contraseña</label>
                        <div class="input-container"><i class="fa-solid fa-lock" aria-hidden="true"></i><input id="confirmar_password" type="password" name="confirmar_password" autocomplete="new-password" placeholder="Repite la contraseña" <?= $esEdicion ? '' : 'required' ?>></div>
                    </div>
                </div>

                <button type="submit" class="btn-ingresar"><i class="fa-solid fa-floppy-disk" aria-hidden="true"></i> <?= $esEdicion ? 'Guardar cambios' : 'Crear cuenta' ?></button>
            </form>

            <div class="usuario-form-back">
                <?php if ($esEdicion || !empty($_SESSION['usuario_id'])): ?>
                    <a href="index.php?accion=listarUsuarios"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Volver al listado</a>
                <?php else: ?>
                    <a href="index.php?accion=formLogin"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i> Ya tengo una cuenta</a>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
