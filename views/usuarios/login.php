<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/log.css">
</head>

<body>

    <div class="login-card">

        <div class="login-left">
            <img src="https://gestioneducativa.utu.edu.uy/wp-content/uploads/2023/03/LOGO-BIBLIOTECA-BLANCO-1280x683.png" alt="Ilustración Login">
        </div>

        <div class="login-right">
            <h1>Iniciar Sesión</h1>

            <?php if (isset($_GET['registro']) && $_GET['registro'] === 'exito'): ?>
                <div class="alert alert-success" style="color: #155724; background-color: #d4edda; border-color: #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    Registro exitoso. Puedes iniciar sesión con tu nueva cuenta.
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger" style="margin-bottom: 15px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?accion=login">

                <div class="input-container">
                    <i class="fa-solid fa-user"></i>
                    <input type="email" name="email" placeholder="Correo electrónico"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="input-container">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Contraseña" required>
                </div>

                <button type="submit" class="btn-ingresar">INGRESAR</button>

                <div class="links-container">
                    <a href="img/car.mp4">¿Olvidaste tu <strong>usuario</strong> o <strong>contraseña</strong>?</a>
                </div>
            </form>

              <div class="create-account">
                <a href="index.php?accion=listarCatalogo">Catalogo <i class="fa-solid fa-arrow-right"></i></a>
            </div>


        </div>
    </div>

</body>
</html>
