<?php  

class Prestamo {
    private int    $id;
    private int    $socioId;
    private int    $libroId;
    private string $fechaPrestamo;
    private string $fechaDevolucion;
    private string $socioNombre;
    private string $socioFoto;
    private string $libroTitulo;
    private string $estado;

    public function __construct(int $id, int $socioId, int $libroId,
                                string $fechaPrestamo, string $fechaDevolucion, string $socioNombre, string $socioFoto, string $libroTitulo, string $estado) {
        $this->id            = $id;
        $this->socioId       = $socioId;
        $this->libroId       = $libroId;
        $this->fechaPrestamo = $fechaPrestamo;
        $this->fechaDevolucion = $fechaDevolucion;
        $this->socioNombre   = $socioNombre;
        $this->socioFoto     = $socioFoto;
        $this->libroTitulo   = $libroTitulo;
        $this->estado        = $estado;
    }

    public function getId():            int    { return $this->id; }
    public function getSocioId():       int    { return $this->socioId; }
    public function getLibroId():       int    { return $this->libroId; }
    public function getFechaPrestamo(): string { return $this->fechaPrestamo; }
    public function getFechaDevolucion(): string { return $this->fechaDevolucion; }
    public function getSocioNombre():   string { return $this->socioNombre; }
    public function getSocioFoto():     string { return $this->socioFoto; }
    public function getLibroTitulo():   string { return $this->libroTitulo; }
    public function getEstado():        string { return $this->estado; }
    public function getLibroPortada(): ?string {


        global $pdo;
        $stmt = $pdo->prepare("SELECT portada 
                               FROM libros 
                               WHERE id = ?");
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
            (string) ($f['fechaDevolucion'] ?? ''),
            (string) ($f['socioNombre'] ?? ''),
            (string) ($f['socioFoto'] ?? ''),
            (string) ($f['libroTitulo'] ?? ''),
            (string) ($f['estado'] ?? '')
        );
    }

    public static function buscarPorId(PDO $pdo, int $id): ?Prestamo {
        $stmt = $pdo->prepare("SELECT p.*, l.titulo AS libroTitulo, l.autor, l.portada, s.nombre AS socioNombre, s.foto AS socioFoto
                               FROM prestamos p
                               INNER JOIN libros l ON p.libroId = l.id
                               INNER JOIN socios s ON p.socioId = s.id
                               WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        $f = $stmt->fetch(PDO::FETCH_ASSOC);
        return $f ? self::desdeFila($f) : null;
    }

    public static function crear(PDO $pdo, int $socioId, int $libroId, string $estado, string $fechaPrestamo, string $fechaDevolucion): bool {
        return self::guardarConDisponibilidad($pdo, [$libroId], function () use ($pdo, $socioId, $libroId, $estado, $fechaPrestamo, $fechaDevolucion) {
        $stmt = $pdo->prepare("INSERT INTO prestamos (socioId, libroId, estado, fechaPrestamo, fechaDevolucion)
                               VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$socioId, $libroId, $estado, $fechaPrestamo, $fechaDevolucion]);
        });
    }

    public static function actualizar(PDO $pdo, int $id, int $socioId, int $libroId, string $estado, string $fechaPrestamo, string $fechaDevolucion): bool {
        return self::guardarConDisponibilidad($pdo, [$libroId], function () use ($pdo, $id, $socioId, $libroId, $estado, $fechaPrestamo, $fechaDevolucion) {
        $stmt = $pdo->prepare("UPDATE prestamos 
                               SET socioId = ?, libroId = ?, estado = ? ,fechaPrestamo = ? ,fechaDevolucion = ?
                               WHERE id = ?");
        return $stmt->execute([$socioId, $libroId, $estado, $fechaPrestamo, $fechaDevolucion, $id]);
        }, $id);
    }

    public static function eliminar(PDO $pdo, int $id): bool {
        return self::guardarConDisponibilidad($pdo, [], function () use ($pdo, $id) {
        $stmt = $pdo->prepare("DELETE FROM prestamos 
                               WHERE id = ?");
        return $stmt->execute([$id]);
        }, $id);
    }

    private static function guardarConDisponibilidad(PDO $pdo, array $libroIds, callable $guardar, ?int $prestamoId = null): bool {
        $transaccionPropia = !$pdo->inTransaction();
        if ($transaccionPropia) {
            $pdo->beginTransaction();
        }
        try {
            if ($prestamoId !== null) {
                $stmt = $pdo->prepare('SELECT libroId FROM prestamos WHERE id = ? FOR UPDATE');
                $stmt->execute([$prestamoId]);
                $libroAnterior = $stmt->fetchColumn();
                if ($libroAnterior !== false) {
                    $libroIds[] = (int) $libroAnterior;
                }
            }
            $libroIds = array_unique($libroIds);
            sort($libroIds);
            foreach ($libroIds as $libroId) {
                $stmt = $pdo->prepare('SELECT id FROM libros WHERE id = ? FOR UPDATE');
                $stmt->execute([$libroId]);
                $stmt->fetchColumn();
            }
            if (!$guardar()) {
                throw new RuntimeException('No se pudo guardar el prestamo.');
            }
            foreach ($libroIds as $libroId) {
                self::actualizarDisponibilidad($pdo, $libroId);
            }
            if ($transaccionPropia) {
                $pdo->commit();
            }
            return true;
        } catch (Throwable $e) {
            if ($transaccionPropia && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    private static function actualizarDisponibilidad(PDO $pdo, int $libroId): void {
        $stmt = $pdo->prepare("UPDATE libros SET disponible = CASE
            WHEN EXISTS (SELECT 1 FROM prestamos WHERE libroId = ? AND estado = 'Prestado')
            THEN 'No' ELSE 'Sí' END WHERE id = ?");
        $stmt->execute([$libroId, $libroId]);
    }

    public static function contarTotal(PDO $pdo): int {
        $stmt = $pdo->query("SELECT COUNT(*) 
                             FROM prestamos");
        return (int) $stmt->fetchColumn();
    }

    public static function listarPaginado(PDO $pdo, int $paginaActual, int $porPagina): array {
        $inicio = ($paginaActual - 1) * $porPagina;

        $stmt = $pdo->prepare("SELECT p.id, p.socioId, p.libroId, p.estado, p.fechaPrestamo, p.fechaDevolucion,
                               s.nombre AS socioNombre, s.foto AS socioFoto, l.titulo AS libroTitulo
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
