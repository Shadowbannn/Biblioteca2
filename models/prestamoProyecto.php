<?php  

class PrestamoProyecto {
    private int    $id;
    private int    $socioId;
    private int    $proyectoId;
    private string $fechaPrestamo;
    private string $fechaDevolucion;
    private string $socioNombre;
    private string $socioFoto;
    private string $nombreProyecto;
    private string $estado;

    public function __construct(int $id, int $socioId, int $proyectoId,
                                string $fechaPrestamo, string $fechaDevolucion, string $socioNombre, string $socioFoto, string $nombreProyecto, string $estado) {
        $this->id              = $id;
        $this->socioId         = $socioId;
        $this->proyectoId      = $proyectoId;
        $this->fechaPrestamo   = $fechaPrestamo;
        $this->fechaDevolucion = $fechaDevolucion;
        $this->socioNombre     = $socioNombre;
        $this->socioFoto       = $socioFoto;
        $this->nombreProyecto  = $nombreProyecto;
        $this->estado          = $estado;
    }

    public function getId():            int    { return $this->id; }
    public function getSocioId():       int    { return $this->socioId; }
    public function getProyectoId():    int    { return $this->proyectoId; }
    public function getFechaPrestamo(): string { return $this->fechaPrestamo; }
    public function getFechaDevolucion(): string { return $this->fechaDevolucion; }
    public function getSocioNombre():   string { return $this->socioNombre; }
    public function getSocioFoto():     string { return $this->socioFoto; }
    public function getProyectoNombre():   string { return $this->nombreProyecto; }
    public function getEstado():        string { return $this->estado; }
    public function getPortadaProyecto(): ?string {


        global $pdo;
        $stmt = $pdo->prepare("SELECT portada 
                               FROM proyectos 
                               WHERE id = ?");
        $stmt->execute([$this->proyectoId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['portada'] : null;
    }

    private static function desdeFila(array $f): PrestamoProyecto
    {
        return new PrestamoProyecto(
            (int) ($f['id'] ?? 0),
            (int) ($f['socioId'] ?? $f['socio_id'] ?? 0),
            (int) ($f['proyectoId'] ?? $f['proyecto_id'] ?? 0),
            (string) ($f['fechaPrestamo'] ?? $f['fecha_prestamo'] ?? ''),
            (string) ($f['fechaDevolucion'] ?? ''),
            (string) ($f['socioNombre'] ?? ''),
            (string) ($f['socioFoto'] ?? ''),
            (string) ($f['nombreProyecto'] ?? ''),
            (string) ($f['estado'] ?? '')
        );
    }

    public static function buscarPorId(PDO $pdo, int $id): ?PrestamoProyecto {
        $stmt = $pdo->prepare("SELECT p.*, pr.nombre AS nombreProyecto, pr.portada AS portadaProyecto, s.nombre AS socioNombre, s.portada AS socioFoto
                               FROM prestamosproyecto p
                               INNER JOIN proyectos pr ON p.proyectoId = pr.id
                               INNER JOIN socios s ON p.socioId = s.id
                               WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $f = $stmt->fetch(PDO::FETCH_ASSOC);
        return $f ? self::desdeFila($f) : null;
    }

    public static function crear(PDO $pdo, int $socioId, int $proyectoId, string $estado, string $fechaPrestamo, string $fechaDevolucion): bool {
        $stmt = $pdo->prepare("INSERT INTO prestamosproyecto (socioId, proyectoId, estado, fechaPrestamo, fechaDevolucion)
                               VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$socioId, $proyectoId, $estado, $fechaPrestamo, $fechaDevolucion]);
    }

    public static function actualizar(PDO $pdo, int $id, int $socioId, int $proyectoId, string $estado, string $fechaPrestamo, string $fechaDevolucion): bool {
        $stmt = $pdo->prepare("UPDATE prestamosproyecto 
                               SET socioId = ?, proyectoId = ?, estado = ? ,fechaPrestamo = ? ,fechaDevolucion = ?
                               WHERE id = ?");
        return $stmt->execute([$socioId, $proyectoId, $estado, $fechaPrestamo, $fechaDevolucion, $id]);
    }

    public static function eliminar(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM prestamosproyecto 
                               WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function contarTotal(PDO $pdo): int {
        $stmt = $pdo->query("SELECT COUNT(*) 
                             FROM prestamosproyecto");
        return (int) $stmt->fetchColumn();
    }

    public static function listarPaginado(PDO $pdo, int $paginaActual, int $porPagina): array {
        $inicio = ($paginaActual - 1) * $porPagina;

        $stmt = $pdo->prepare("SELECT p.id, p.socioId, p.proyectoId, p.estado, p.fechaPrestamo, p.fechaDevolucion,
                               s.nombre AS socioNombre, s.foto AS socioFoto, pr.nombre AS nombreProyecto
            FROM prestamosproyecto p
            INNER JOIN socios s ON p.socioId = s.id
            INNER JOIN proyectos pr ON p.proyectoId = pr.id
            ORDER BY p.fechaPrestamo DESC
            LIMIT :inicio, :cantidad
        ");

        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();

        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $prestamosproyecto = [];

        foreach ($filas as $f) {
            $prestamosproyecto[] = self::desdeFila($f);
        }

        return $prestamosproyecto;
    }
}
