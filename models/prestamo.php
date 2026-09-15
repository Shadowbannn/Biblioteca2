<?php  

class Prestamo {
    private INT $id;
    private INT $socioId;
    private INT $libroId;
    private STRING $fechaPrestamo;
    private STRING $socioNombre;
    private STRING $libroTitulo;

    public function __construct($id, $socioId, $libroId, $fechaPrestamo, $socioNombre, $libroTitulo) {
        $this->id = $id;
        $this->socioId = $socioId;
        $this->libroId = $libroId;
        $this->fechaPrestamo = $fechaPrestamo;
        $this->socioNombre = $socioNombre;
        $this->libroTitulo = $libroTitulo;
    }



    public function getId(): INT {
        return $this->id;
    }

    public function getSocioId(): INT {
        return $this->socioId;
    }

    public function getLibroId(): INT {
        return $this->libroId;
    }

    public function getFechaPrestamo(): STRING {
        return $this->fechaPrestamo;
    }

    public function getSocioNombre(): STRING {
        return $this->socioNombre;
    }

    public function getLibroTitulo(): STRING {
        return $this->libroTitulo;
    }

    public function getLibroPortada(): ?string {
        global $pdo;
        $stmt = $pdo->prepare("SELECT portada FROM libros WHERE id = ?");
        $stmt->execute([$this->libroId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['portada'] : null;
    }

    public static function buscarPorId($pdo, $id) {
        $stmt = $pdo->prepare("SELECT p.id, p.socioId, p.libroId, p.fechaPrestamo, s.nombre, s.apellido, l.titulo, l.portada
                               FROM prestamos p
                               INNER JOIN socios s ON p.socioId = s.id
                               INNER JOIN libros l ON p.libroId = l.id
                               WHERE p.id = ?");
                       

        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function crear($pdo, $socioId, $libroId, $fechaPrestamo, $socioNombre, $libroTitulo) {
        $stmt = $pdo->prepare("
            INSERT INTO prestamos (socioId, libroId, fechaPrestamo, socioNombre, libroTitulo)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([$socioId, $libroId, $fechaPrestamo, $socioNombre, $libroTitulo]);
    }

    public static function eliminar($pdo, $id) {
        $stmt = $pdo->prepare("
            DELETE FROM prestamos
            WHERE id = ?
        ");

        $stmt->execute([$id]);
    }
    
    public function mostrarInfo(): void
    {
        echo "socioId          : {$this->socioId}\n";
        echo "libroId          : {$this->libroId}\n";
        echo "Fecha prestamo  : {$this->fechaPrestamo}\n";
        echo "Socio nombre    : {$this->socioNombre}\n";
        echo "Libro titulo    : {$this->libroTitulo}\n";
        echo "ID              : {$this->id}\n";
    }

  

    private static function desdeFila(array $f): Prestamo
    {
        return new Prestamo(
            (INT) $f['id'],
            (INT) $f['socioId'],
            (INT) $f['libroId'],
            (string) $f['fechaPrestamo'],
            (string) $f['socioNombre'],
            (string) $f['libroTitulo']
        );
    }

 

    public static function contarTotal(PDO $pdo): int
    {
        $stmt = $pdo->query(
            "SELECT COUNT(*) FROM prestamos"
        );

        return (int) $stmt->fetchColumn();
    }


    public static function listarPaginado(
        PDO $pdo,
        int $paginaActual,
        int $porPagina
    ): array {
        $inicio = ($paginaActual - 1) * $porPagina;

        $stmt = $pdo->prepare(
            "SELECT *
             FROM prestamos
             ORDER BY fechaPrestamo DESC
             LIMIT :inicio, :cantidad"
        );

        $stmt->bindValue(
            ':inicio',
            $inicio,
            PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':cantidad',
            $porPagina,
            PDO::PARAM_INT
        );

        $stmt->execute();

        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $prestamos = [];

        foreach ($filas as $f) {
            $prestamos[] = self::desdeFila($f);
        }

        return $prestamos;
    }
}
