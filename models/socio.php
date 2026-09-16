<?php

class Socio
{
    private ?int $id;
    private string $nombre;
    private string $apellido;
    private string $cedula;
    private string $telefono;
    private string $fecha_registro;

    public function __construct(
        string $nombre,
        string $apellido,
        string $cedula,
        string $telefono,
        string $fecha_registro,
        ?int $id = null
    ) {
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->cedula = $cedula;
        $this->telefono = $telefono;
        $this->fecha_registro = $fecha_registro;
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

    public function getApellido(): string
    {
        return $this->apellido;
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

  
    

    public function mostrarInfo(): void
    {
        echo "Nombre          : {$this->nombre}\n";
        echo "Apellido        : {$this->apellido}\n";
        echo "Cedula          : {$this->cedula}\n";
        echo "Telefono        : {$this->telefono}\n";
        echo "Fecha registro  : {$this->fecha_registro}\n";
        echo "ID              : {$this->id}\n";
    }

  

    private static function desdeFila(array $f): Socio
    {
        return new Socio(
            (string) $f['nombre'],
            (string) $f['apellido'],
            (string) $f['cedula'],
            (string) $f['telefono'],
            (string) $f['fecha_registro'],
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
        string $apellido,
        string $cedula,
        string $telefono,
        string $fecha_registro
    ): bool {
        $stmt = $pdo->prepare(
            "INSERT INTO socios
            (nombre, apellido, cedula, telefono, fecha_registro)
            VALUES (?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $nombre,
            $apellido,
            $cedula,
            $telefono,
            $fecha_registro
        ]);
    }

    

    public static function actualizar(
        PDO $pdo,
        int $id,
        string $nombre,
        string $apellido,
        string $cedula,
        string $telefono,
        string $fecha_registro
    ): bool {
        $stmt = $pdo->prepare(
            "UPDATE socios
             SET nombre = ?, apellido = ?, cedula = ?, telefono = ?, fecha_registro = ?
             WHERE id = ?"
        );

        return $stmt->execute([
            $nombre,
            $apellido,
            $cedula,
            $telefono,
            $fecha_registro,
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