<?php
require_once __DIR__ . '/../models/Usuario.php';
$perfilUsuario = Usuario::buscarPorId($pdo, (int) $_SESSION['usuario_id']);
$escPerfil = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<?php if ($perfilUsuario): ?>
    <li class="perfil-usuario">
        <?php if ($perfilUsuario->getFoto()): ?>
            <img class="perfil-usuario-foto" src="uploads/usuarios/<?= $escPerfil($perfilUsuario->getFoto()) ?>" alt="Foto de <?= $escPerfil($perfilUsuario->getNombre()) ?>">
        <?php else: ?>
            <span class="perfil-usuario-foto perfil-usuario-avatar" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
        <?php endif; ?>
        <span class="perfil-usuario-datos">
            <strong><?= $escPerfil($perfilUsuario->getNombre()) ?></strong>
            <small><?= $escPerfil($perfilUsuario->getRoles() ?: 'Sin rol asignado') ?></small>
        </span>
    </li>
<?php endif; ?>
