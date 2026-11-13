<?php

function usuarioTieneRol(string $rol): bool
{
    $rolesSesion = $_SESSION['usuario_roles'] ?? '';
    if (!is_string($rolesSesion)) {
        return false;
    }

    $roles = array_map(
        static function (string $rolUsuario): string {
            return strtolower(trim($rolUsuario));
        },
        explode(',', $rolesSesion)
    );

    return in_array(strtolower(trim($rol)), $roles, true);
}
