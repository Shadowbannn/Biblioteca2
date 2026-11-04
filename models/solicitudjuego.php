<?php
require_once __DIR__ . '/notificacion.php';

class SolicitudJuego
{
    public static function contar(PDO $pdo): int
    {
        return (int) $pdo->query('SELECT COUNT(*) FROM solicitudes_juegos')->fetchColumn();
    }

    public static function listarPaginado(PDO $pdo, int $pagina, int $cantidad): array
    {
        $stmt = $pdo->prepare("SELECT sj.*, j.nombre 
                               AS juego_nombre,
                               COALESCE(NULLIF(sj.nombre, ''), s.nombre, '') 
                               AS solicitante_nombre,

                               COALESCE(NULLIF(sj.clase, ''), s.clase, '') 
                               AS solicitante_clase,

                               COALESCE(NULLIF(sj.telefono, ''), s.telefono, '') 
                               AS contacto

                                FROM solicitudes_juegos sj
                                LEFT JOIN juegos j
                                ON j.id = sj.juego_id
                                LEFT JOIN socios s 
                                ON s.id = sj.socio_id
                                ORDER BY sj.creada_en 
                                DESC, sj.id DESC
                                LIMIT :cantidad OFFSET :inicio");
        $stmt->bindValue(':cantidad', $cantidad, PDO::PARAM_INT);
        $stmt->bindValue(':inicio', (max(1, $pagina) - 1) * $cantidad, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function crear(PDO $pdo, int $juegoId, ?int $socioId, bool $nuevoSocio,
        string $cedula, string $nombre, string $clase, string $telefono, string $tipo): void
    {
        $stmt = $pdo->prepare("INSERT INTO solicitudes_juegos
            (juego_id, socio_id, nuevo_socio, cedula, nombre, clase, telefono, tipo, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendiente')");
        $stmt->execute([$juegoId, $socioId, (int) $nuevoSocio, $cedula, $nombre, $clase, $telefono, $tipo]);
        Notificacion::nuevaSolicitud($pdo, 'juegos', (int) $pdo->lastInsertId());
    }

    public static function tienePendiente(PDO $pdo, int $juegoId, string $cedula, string $tipo): bool
    {
        $stmt = $pdo->prepare("SELECT id 
                               FROM solicitudes_juegos
                               WHERE juego_id = ? 
                               AND cedula = ? 
                               AND tipo = ? 
                               AND estado = 'pendiente' 
                               LIMIT 1");

        $stmt->execute([$juegoId, $cedula, $tipo]);
        return $stmt->fetchColumn() !== false;
    }

    public static function revisar(PDO $pdo, int $id, string $estado, int $usuarioId): bool
    {
        if (!in_array($estado, ['aceptada', 'rechazada'], true)) {
            throw new InvalidArgumentException('Estado de revision no valido.');
        }
        if ($estado === 'aceptada') {
            $stmt = $pdo->prepare("DELETE FROM solicitudes_juegos WHERE id = ? AND estado = 'pendiente'");
            $stmt->execute([$id]);
            return $stmt->rowCount() === 1;
        }
        $stmt = $pdo->prepare("UPDATE solicitudes_juegos
            SET estado = ?, revisada_en = NOW(), revisada_por = ?
            WHERE id = ? AND estado = 'pendiente'");
        $stmt->execute([$estado, $usuarioId, $id]);
        return $stmt->rowCount() === 1;
    }
}
