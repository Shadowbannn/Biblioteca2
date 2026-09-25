<?php

class Socio
{
    private ?int $id;
    private string $nombre;
    private string $clase;
    private string $cedula;
    private string $telefono;
    private string $fecha_registro;
    private ?string $foto;

    public function __construct(
        string $nombre,
        string $clase,
        string $cedula,
        string $telefono,
        string $fecha_registro,
        string $foto = null,
        ?int $id = null
    ) {
        $this->nombre = $nombre;
        $this->clase = $clase;
        $this->cedula = $cedula;
        $this->telefono = $telefono;
        $this->fecha_registro = $fecha_registro;
        $this->foto = $foto;
        $this->id = $id;
    }

    // =========================
    // GETTERS
    // =========================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getClase(): string
    {
        return $this->clase;
    }

    public function getCedula(): string
    {
        return $this->cedula;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function getFecharegistro(): string
    {
        return $this->fecha_registro;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

  
    

    public function mostrarInfo(): void
    {
        echo "Nombre          : {$this->nombre}\n";
        echo "Clase           : {$this->clase}\n";
        echo "Cedula          : {$this->cedula}\n";
        echo "Telefono        : {$this->telefono}\n";
        echo "Fecha registro  : {$this->fecha_registro}\n";
        echo "ID              : {$this->id}\n";
    }

  

    private static function desdeFila(array $f): Socio
    {
        return new Socio(
            (string) $f['nombre'],
            (string) $f['clase'],
            (string) $f['cedula'],
            (string) $f['telefono'],
            (string) $f['fecha_registro'],
            isset($f['foto']) ? (string) $f['foto'] : null,
            isset($f['id']) ? (int) $f['id'] : null
        );
    }

 

    public static function contarTotal(PDO $pdo): int
    {
        $stmt = $pdo->query(
            "SELECT COUNT(*) FROM socios"
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
             FROM socios
             ORDER BY nombre
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

        $socios = [];

        foreach ($filas as $f) {
            $socios[] = self::desdeFila($f);
        }

        return $socios;
    }


    public static function buscarPorId(
        PDO $pdo,
        int $id
    ): ?Socio {
        $stmt = $pdo->prepare(
            "SELECT *
             FROM socios
             WHERE id = ?"
        );

        $stmt->execute([$id]);

        $f = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$f) {
            return null;
        }

        return self::desdeFila($f);
    }

   
    public static function crear(
        PDO $pdo,
        string $nombre,
        string $clase,
        string $cedula,
        string $telefono,
        string $fecha_registro,
        string $foto = null
    ): bool {
        $stmt = $pdo->prepare(
            "INSERT INTO socios
            (nombre, clase, cedula, telefono, fecha_registro, foto)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $nombre,
            $clase,
            $cedula,
            $telefono,
            $fecha_registro,
            $foto
        ]);
    }

    

    public static function actualizar(
        PDO $pdo,
        int $id,
        string $nombre,
        string $clase,
        string $cedula,
        string $telefono,
        string $fecha_registro,
        string $foto = null
    ): bool {
        $stmt = $pdo->prepare(
            "UPDATE socios
             SET nombre = ?, clase = ?, cedula = ?, telefono = ?, fecha_registro = ?, foto = ?
             WHERE id = ?"
        );

        return $stmt->execute([
            $nombre,
            $clase,
            $cedula,
            $telefono,
            $fecha_registro,
            $foto,
            $id
        ]);
    }


 public static function eliminar(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM socios WHERE id = ?");
        return $stmt->execute([$id]);
        
    }


    public static function listar(PDO $pdo): array
    {
        $stmt = $pdo->query(
            "SELECT *
             FROM socios
             ORDER BY id DESC"
        );

        $socios = [];

        while ($f = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $socios[] = self::desdeFila($f);
        }

        return $socios;
    }
}