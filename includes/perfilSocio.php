<?php
$perfilSocio = Socio::buscarPorId($pdo, (int) $_SESSION['socio_id']);
$escPerfilSocio = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
?>
<?php if ($perfilSocio): ?>
    <li class="dropdown perfil-usuario perfil-socio">
        <button type="button" class="perfil-usuario-toggle" id="perfilSocioDropdown"
            data-bs-toggle="dropdown" aria-expanded="false"
            aria-label="Abrir menú de cuenta de <?= $escPerfilSocio($perfilSocio->getNombre()) ?>">
            <?php if ($perfilSocio->getFoto()): ?>
                <img class="perfil-usuario-foto" src="uploads/fotos/<?= $escPerfilSocio($perfilSocio->getFoto()) ?>" alt="">
            <?php else: ?>
                <span class="perfil-usuario-foto perfil-usuario-avatar" aria-hidden="true"><i class="fa-solid fa-user"></i></span>
            <?php endif; ?>
            <span class="perfil-usuario-chevron"><i class="fa-solid fa-chevron-down" aria-hidden="true"></i></span>
        </button>
        <div class="dropdown-menu dropdown-menu-end dropdown-menu-dark perfil-usuario-menu" aria-labelledby="perfilSocioDropdown">
            <div class="perfil-usuario-resumen">
                <strong><?= $escPerfilSocio($perfilSocio->getNombre()) ?></strong>
                <span><i class="fa-solid fa-id-card" aria-hidden="true"></i> Socio · <?= $escPerfilSocio($perfilSocio->getCedula()) ?></span>
            </div>

            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="index.php?accion=editarCuentaSocio">
                <i class="fa-solid fa-user-pen" aria-hidden="true"></i> Editar cuenta
            </a>
            <a class="dropdown-item perfil-usuario-salir" href="index.php?accion=logout">
                <i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i> Cerrar sesión
            </a>
        </div>
    </li>
<?php endif; ?>
