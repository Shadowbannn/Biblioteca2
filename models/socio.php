<?php
class Socio {

    // --- ATRIBUTOS (propiedades privadas) ---
    private ?int   $id;              // ← NUEVO: null si todavía no está en la BD
    private string $nombre;
    private string $apellido;
    private string $cedula;
    private string    $telefono;
    private int    $fecha_registro;

    // --- CONSTRUCTOR ---
    public function __construct(string $nombre, string $apellido, string $cedula,
                                string $telefono, int $fecha_registro,
                                ?int $id = null) {
        $this->nombre          = $nombre;
        $this->apellido        = $apellido;
        $this->telefono        = $telefono;
        $this->cedula          = $cedula;
        $this->fecha_registro  = $fecha_registro;
        $this->id              = $id; 
    }

    // --- GETTERS ---
    public function getId() {                // ← NUEVO
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

     public function getApellido() {
        return $this->apellido;
    }

    public function getCedula() {
        return $this->cedula;
    }

    public function getTelefono() {
        return $this->telefono;
    }

    public function getFecharegistro() {
        return $this->fecha_registro;
    }

    // --- MÉTODO mostrarInfo() ---
    // (tu método de siempre, lo dejamos: sigue sirviendo para depurar)
    public function mostrarInfo(): void {
        echo "  Nombre               : {$this->nombre}\n";
        echo "  Apellido             : {$this->apellido}\n";
        echo "  Cedula               : {$this->cedula}\n";
        echo "  Telefono             : {$this->telefono}\n";
        echo "  Fecha registro       : {$this->fecha_registro}\n";
        echo "  ID                   : {$this->id}\n";
        
    }
    private static function desdeFila(array $f): Socio {
        return new Socio(
            $f['nombre'],
            $f['apellido'],
            $f['cedula'],
            (string) $f['telefono'],
            (int) $f['fecha_registro'],
            (int) $f['id'],
        );
    }

    public static function contarTotal(PDO $pdo): int {
        return (int) $pdo->query("SELECT COUNT(*) FROM socios")->fetchColumn();
    }

    public static function listarPaginado(PDO $pdo, int $paginaActual, int $porPagina): array {
        $inicio = ($paginaActual - 1) * $porPagina;

        $stmt = $pdo->prepare(
            "SELECT * FROM socios ORDER BY nombre LIMIT :inicio, :cantidad"
        );
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();

        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $socios = [];
        foreach ($filas as $f) {
            $socios[] = self::desdeFila($f);
        }
        return $socios;
    }


     public static function buscarPorId(PDO $pdo, int $cedula): ?Socio {
        $stmt = $pdo->prepare("SELECT * FROM Socios WHERE id = ?");
        $stmt->execute([$id]);
        $f = $stmt->fetch(PDO::FETCH_ASSOC);
        return $f ? self::desdeFila($f) : null;
    }

    public static function crear(PDO $pdo, string $nombre, string $apellido, int $cedula, string $telefono,
                                  int $fecha_registro, int $id): bool {
        $stmt = $pdo->prepare(
            "INSERT INTO socios (nombre, apellido, cedula, fecha_registro, telefono, id)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$nombre, $apellido, $cedula, $fecha_registro, $id]);
    }

    public static function actualizar(PDO $pdo, string $nombre, string $apellido, int $cedula, string $telefono,
                                  int $fecha_registro, int $id): bool {
        $stmt = $pdo->prepare(
            "UPDATE socios
             SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, fecha_registro = ?,
             WHERE id = ?"
        );
        return $stmt->execute([$nombre, $apellido, $cedula, $fecha_registro, $id, $telefono]);
    }

    public static function eliminar(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM socios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function listar(PDO $pdo): array
{
    $stmt = $pdo->query(
        "SELECT * FROM socios ORDER BY id DESC"
    );

    $socios = [];

    while ($f = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $socios[] = self::desdeFila($f);
    }

    return $socios;
}
}
