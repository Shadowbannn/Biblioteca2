<?php
require_once __DIR__ . '/../models/Usuario.php';
$perfilUsuario = Usuario::buscarPorId($pdo, (int) $_SESSION['usuario_id']);
$escPerfil = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>

<?php if ($perfilUsuario): ?>
    <li class="dropdown perfil-usuario">
        <button type="button" class="perfil-usuario-toggle" id="perfilUsuarioDropdown"
            data-bs-toggle="dropdown" aria-expanded="false"
            aria-label="Abrir menú de cuenta de <?= $escPerfil($perfilUsuario->getNombre()) ?>">
            <?php if ($perfilUsuario->getFoto()): ?>
                <img class="perfil-usuario-foto" src="uploads/usuarios/<?= $escPerfil($perfilUsuario->getFoto()) ?>" alt="">
            <?php else: ?>
                <span class="perfil-usuario-foto perfil-usuario-avatar" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
            <?php endif; ?>
            <span class="perfil-usuario-chevron"><i class="fa-solid fa-chevron-down" aria-hidden="true"></i></span>
        </button>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark perfil-usuario-menu" aria-labelledby="perfilUsuarioDropdown">
            <div class="perfil-usuario-resumen">
                <strong><?= $escPerfil($perfilUsuario->getNombre()) ?></strong>
                <span><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> <?= $escPerfil($perfilUsuario->getRoles() ?: 'Usuario') ?></span>
            </div>
            <a class="dropdown-item" href="index.php?accion=formEditarUsuario&amp;id=<?= (int) $perfilUsuario->getId() ?>">
                <i class="fa-solid fa-gear" aria-hidden="true"></i> Configurar cuenta
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item perfil-usuario-salir" href="index.php?accion=logout">
                <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Cerrar sesión
            </a>
        </div>
    </li>
<?php endif; ?>
