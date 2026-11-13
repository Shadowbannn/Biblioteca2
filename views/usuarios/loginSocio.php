<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso de socios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/log.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
    <div class="login-card">
        <div class="login-left">
            <a href="inicioo.php">
                <img src="https://gestioneducativa.utu.edu.uy/wp-content/uploads/2023/03/LOGO-BIBLIOTECA-BLANCO-1280x683.png" alt="Logo de la biblioteca">
            </a>
        </div>
        <div class="login-right">
            <h1>Acceso de socio</h1>
            <?php if (!empty($error)): ?><div class="alert alert-danger"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
            <form method="POST" action="index.php?accion=loginSocio">
                <div class="input-container">
                    <i class="fa-solid fa-id-card"></i>
                    <input type="text" name="cedula" placeholder="Cédula" autocomplete="username" value="<?= htmlspecialchars($_POST['cedula'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                </div>
                <div class="input-container">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Contraseña" autocomplete="current-password" required>
                </div>
                <button type="submit" class="btn-ingresar">INGRESAR</button>
            </form>
            <br>
            <div class="create-account"><a href="index.php?accion=formLogin"><i class="fa-solid fa-school"></i><i class="fa-solid fa-arrow-right"></i></a></div>
        </div>
    </div>
</body>
</html>
