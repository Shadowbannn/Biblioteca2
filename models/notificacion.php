<?php
class Notificacion
{
    public static function nuevaSolicitud(PDO $pdo, string $categoria, int $id): void
    {
        $rutas = ['libros' => 'listarSolicitudes', 'juegos' => 'listarSolicitudesJuegos', 'proyectos' => 'listarSolicitudesProyectos'];
        if (!isset($rutas[$categoria])) {
            throw new InvalidArgumentException('Categoria no valida.');
        }
        $stmt = $pdo->prepare('INSERT INTO notificaciones (usuario_id, mensaje, enlace)
            SELECT id, ?, ? FROM usuarios');
        $stmt->execute(['Nueva solicitud de ' . $categoria . ' #' . $id . '.', 'index.php?accion=' . $rutas[$categoria]]);
    }

    public static function recientes(PDO $pdo, int $usuarioId): array
    {
        $stmt = $pdo->prepare('SELECT id, mensaje, leida, creada_en FROM notificaciones
            WHERE usuario_id = ? ORDER BY leida ASC, creada_en DESC, id DESC LIMIT 20');
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function contarPendientes(PDO $pdo, int $usuarioId): int
    {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM notificaciones WHERE usuario_id = ? AND leida = 0');
        $stmt->execute([$usuarioId]);
        return (int) $stmt->fetchColumn();
    }

    public static function abrir(PDO $pdo, int $id, int $usuarioId): ?string
    {
        $stmt = $pdo->prepare('SELECT enlace FROM notificaciones WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $usuarioId]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$fila) {
            return null;
        }
        $stmt = $pdo->prepare('UPDATE notificaciones SET leida = 1 WHERE id = ? AND usuario_id = ?');
        $stmt->execute([$id, $usuarioId]);
        $permitidos = ['index.php?accion=listarSolicitudes', 'index.php?accion=listarSolicitudesJuegos', 'index.php?accion=listarSolicitudesProyectos'];
        return in_array($fila['enlace'], $permitidos, true) ? $fila['enlace'] : 'index.php';
    }

    public static function marcarTodasLeidas(PDO $pdo, int $usuarioId): void
    {
        $stmt = $pdo->prepare('UPDATE notificaciones SET leida = 1 WHERE usuario_id = ? AND leida = 0');
        $stmt->execute([$usuarioId]);
    }
}
