<?php  

class PrestamoJuego {
    private int    $id;
    private int    $socioId;
    private int    $juegoId;
    private string $fechaPrestamo;
    private string $fechaDevolucion;
    private string $socioNombre;
    private string $socioFoto;
    private string $nombreJuego;
    private string $estado;

    public function __construct(int $id, int $socioId, int $juegoId,
                                string $fechaPrestamo, string $fechaDevolucion, string $socioNombre, string $socioFoto, string $nombreJuego, string $estado) {
        $this->id              = $id;
        $this->socioId         = $socioId;
        $this->juegoId         = $juegoId;
        $this->fechaPrestamo   = $fechaPrestamo;
        $this->fechaDevolucion = $fechaDevolucion;
        $this->socioNombre     = $socioNombre;
        $this->socioFoto       = $socioFoto;
        $this->nombreJuego     = $nombreJuego;
        $this->estado          = $estado;
    }

    public function getId():            int    { return $this->id; }
    public function getSocioId():       int    { return $this->socioId; }
    public function getJuegoId():       int    { return $this->juegoId; }
    public function getFechaPrestamo(): string { return $this->fechaPrestamo; }
    public function getFechaDevolucion(): string { return $this->fechaDevolucion; }
    public function getSocioNombre():   string { return $this->socioNombre; }
    public function getSocioFoto():     string { return $this->socioFoto; }
    public function getJuegoNombre():   string { return $this->nombreJuego; }
    public function getEstado():        string { return $this->estado; }
    public function getFotoJuego(): ?string {


        global $pdo;
        $stmt = $pdo->prepare("SELECT foto 
                               FROM juegos 
                               WHERE id = ?");
        $stmt->execute([$this->juegoId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['foto'] : null;
    }

    private static function desdeFila(array $f): PrestamoJuego
    {
        return new PrestamoJuego(
            (int) ($f['id'] ?? 0),
            (int) ($f['socioId'] ?? $f['socio_id'] ?? 0),
            (int) ($f['juegoId'] ?? $f['juego_id'] ?? 0),
            (string) ($f['fechaPrestamo'] ?? $f['fecha_prestamo'] ?? ''),
            (string) ($f['fechaDevolucion'] ?? ''),
            (string) ($f['socioNombre'] ?? ''),
            (string) ($f['socioFoto'] ?? ''),
            (string) ($f['nombreJuego'] ?? ''),
            (string) ($f['estado'] ?? '')
        );
    }

    public static function buscarPorId(PDO $pdo, int $id): ?PrestamoJuego {
        $stmt = $pdo->prepare("SELECT p.*, j.nombre AS nombreJuego, j.foto, s.nombre AS socioNombre, s.foto AS socioFoto
                               FROM prestamosjuegos p
                               INNER JOIN juegos j ON p.juegoId = j.id
                               INNER JOIN socios s ON p.socioId = s.id
                               WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $f = $stmt->fetch(PDO::FETCH_ASSOC);
        return $f ? self::desdeFila($f) : null;
    }

    public static function crear(PDO $pdo, int $socioId, int $juegoId, string $estado, string $fechaPrestamo, string $fechaDevolucion): bool {
        $stmt = $pdo->prepare("INSERT INTO prestamosjuegos (socioId, juegoId, estado, fechaPrestamo, fechaDevolucion)
                               VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$socioId, $juegoId, $estado, $fechaPrestamo, $fechaDevolucion]);
    }

    public static function actualizar(PDO $pdo, int $id, int $socioId, int $juegoId, string $estado, string $fechaPrestamo, string $fechaDevolucion): bool {
        $stmt = $pdo->prepare("UPDATE prestamosjuegos 
                               SET socioId = ?, juegoId = ?, estado = ? ,fechaPrestamo = ? ,fechaDevolucion = ?
                               WHERE id = ?");
        return $stmt->execute([$socioId, $juegoId, $estado, $fechaPrestamo, $fechaDevolucion, $id]);
    }

    public static function eliminar(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM prestamosjuegos 
                               WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function contarTotal(PDO $pdo): int {
        $stmt = $pdo->query("SELECT COUNT(*) 
                             FROM prestamosjuegos");
        return (int) $stmt->fetchColumn();
    }

    public static function listarPaginado(PDO $pdo, int $paginaActual, int $porPagina): array {
        $inicio = ($paginaActual - 1) * $porPagina;

        $stmt = $pdo->prepare("SELECT p.id, p.socioId, p.juegoId, p.estado, p.fechaPrestamo, p.fechaDevolucion,
                               s.nombre AS socioNombre, s.foto AS socioFoto, j.nombre AS nombreJuego
            FROM prestamosjuegos p
            INNER JOIN socios s ON p.socioId = s.id
            INNER JOIN juegos j ON p.juegoId = j.id
            ORDER BY p.fechaPrestamo DESC
            LIMIT :inicio, :cantidad
        ");

        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();

        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $prestamosjuegos = [];

        foreach ($filas as $f) {
            $prestamosjuegos[] = self::desdeFila($f);
        }

        return $prestamosjuegos;
    }
}
