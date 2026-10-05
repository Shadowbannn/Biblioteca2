<?php

class Usuario
{
    private ?int $id;
    private string $nombre;
    private string $email;
    private string $password;
    private string $fecha_registro;

    public function __construct(
        string $nombre,
        string $email,
        string $password,
        string $fecha_registro = '',
        ?int   $id = null
    ) {
        $this->nombre           = $nombre;
        $this->email            = $email;
        $this->password         = $password;
        $this->fecha_registro   = $fecha_registro;
        $this->id               = $id;
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFechaRegistro(): string
    {
        return $this->fecha_registro;
    }

    public function getPasswordHash(): string
    {
        return $this->password;
    }

    private static function desdeFila(array $f): Usuario
    {
        return new Usuario(
            (string) $f['nombre'],
            (string) $f['email'],
            (string) $f['password'],
            (string) ($f['fecha_registro'] ?? ''),
            isset($f['id']) ? (int) $f['id'] : null
        );
    }



    public static function buscarPorId(PDO $pdo, int $id): ?Usuario
    {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);

        $f = $stmt->fetch(PDO::FETCH_ASSOC);

        return $f ? self::desdeFila($f) : null;
    }

    public static function buscarPorEmail(PDO $pdo, string $email): ?Usuario
    {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);

        $f = $stmt->fetch(PDO::FETCH_ASSOC);

        return $f ? self::desdeFila($f) : null;
    }

    public static function emailExiste(PDO $pdo, string $email, ?int $excluirId = null): bool
    {
        if ($excluirId) {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
            $stmt->execute([$email, $excluirId]);
        } else {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
        }

        return (bool) $stmt->fetch();
    }


    public static function crear(PDO $pdo, string $nombre, string $email, string $password): bool
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password) 
                               VALUES (?, ?, ?)");

        return $stmt->execute([$nombre, $email, $passwordHash]);
    }


    public static function eliminar(PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

  
    

    public static function validarCredenciales(PDO $pdo, string $email, string $password): ?Usuario
    {
        $encontrado = self::buscarPorEmail($pdo, $email);

        if ($encontrado && password_verify($password, $encontrado->getPasswordHash())) {
            return $encontrado;
        }

        return null;
    }
}