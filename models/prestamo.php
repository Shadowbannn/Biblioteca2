<?php  

class Prestamo {
    private int $id;
    private int $socioId;
    private int $libroId;
    private string $fechaPrestamo;
    private string $socioNombre;
    private string $libroTitulo;

    public function __construct(int $id, int $socioId, int $libroId, string $fechaPrestamo, string $socioNombre, string $libroTitulo) {
        $this->id            = $id;
        $this->socioId       = $socioId;
        $this->libroId       = $libroId;
        $this->fechaPrestamo = $fechaPrestamo;
        $this->socioNombre   = $socioNombre;
        $this->libroTitulo   = $libroTitulo;
    }

    public function getId():            int    { return $this->id; }
    public function getSocioId():       int    { return $this->socioId; }
    public function getLibroId():       int    { return $this->libroId; }
    public function getFechaPrestamo(): string { return $this->fechaPrestamo; }
    public function getSocioNombre():   string { return $this->socioNombre; }
    public function getLibroTitulo():   string { return $this->libroTitulo; }
    public function getLibroPortada(): ?string {
        global $pdo;
        $stmt = $pdo->prepare("SELECT portada FROM libros WHERE id = ?");
        $stmt->execute([$this->libroId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['portada'] : null;
    }

    private static function desdeFila(array $f): Prestamo
    {
        return new Prestamo(
            (int) ($f['id'] ?? 0),
            (int) ($f['socioId'] ?? $f['socio_id'] ?? 0),
            (int) ($f['libroId'] ?? $f['libro_id'] ?? 0),
            (string) ($f['fechaPrestamo'] ?? $f['fecha_prestamo'] ?? ''),
            (string) ($f['socioNombre'] ?? ''),
            (string) ($f['libroTitulo'] ?? '')
        );
    }

    public static function buscarPorId(PDO $pdo, int $id): ?Prestamo {
        $stmt = $pdo->prepare("SELECT p.*, l.titulo, l.autor, l.portada, s.nombre, s.apellido
        FROM prestamos p
        INNER JOIN libros l ON p.libroId = l.id
        INNER JOIN socios s ON p.socioId = s.id
        WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $f = $stmt->fetch(PDO::FETCH_ASSOC);
        return $f ? self::desdeFila($f) : null;
    }

    public static function crear(PDO $pdo, int $socioId, int $libroId, string $fechaPrestamo): bool {
        $stmt = $pdo->prepare("
            INSERT INTO prestamos (socioId, libroId, fechaPrestamo)
            VALUES (?, ?, ?)
        ");
        return $stmt->execute([$socioId, $libroId, $fechaPrestamo]);
    }

    public static function actualizar(PDO $pdo, int $id, int $socioId, int $libroId, string $fechaPrestamo): bool {
        $stmt = $pdo->prepare("
            UPDATE prestamos 
            SET socioId = ?, libroId = ?, fechaPrestamo = ?
            WHERE id = ?
        ");
        return $stmt->execute([$socioId, $libroId, $fechaPrestamo, $id]);
    }

    public static function eliminar(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM prestamos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function contarTotal(PDO $pdo): int {
        $stmt = $pdo->query("SELECT COUNT(*) FROM prestamos");
        return (int) $stmt->fetchColumn();
    }

    public static function listarPaginado(PDO $pdo, int $paginaActual, int $porPagina): array {
        $inicio = ($paginaActual - 1) * $porPagina;

        $stmt = $pdo->prepare("
            SELECT p.id, p.socioId, p.libroId, p.fechaPrestamo,
                   CONCAT(s.nombre, ' ', s.apellido) AS socioNombre,
                   l.titulo AS libroTitulo
            FROM prestamos p
            INNER JOIN socios s ON p.socioId = s.id
            INNER JOIN libros l ON p.libroId = l.id
            ORDER BY p.fechaPrestamo DESC
            LIMIT :inicio, :cantidad
        ");

        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();

        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $prestamos = [];

        foreach ($filas as $f) {
            $prestamos[] = self::desdeFila($f);
        }

        return $prestamos;
    }
}