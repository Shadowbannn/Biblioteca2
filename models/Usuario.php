<?php

class Usuario
{
    private ?int $id;
    private string $nombre;
    private string $email;
    private string $password;
    private string $fecha_registro;
    private string $roles;

    public function __construct(
        string $nombre,
        string $email,
        string $password,
        string $fecha_registro = '',
        string $roles = '',
        ?int   $id = null
    ) {
        $this->nombre           = $nombre;
        $this->email            = $email;
        $this->password         = $password;
        $this->fecha_registro   = $fecha_registro;
        $this->roles            = $roles;
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


    public function getRoles(): string
    {
        return $this->roles;
    }

    private static function desdeFila(array $f): Usuario
    {
        return new Usuario(
            (string) $f['nombre'],
            (string) $f['email'],
            (string) $f['password'],
            (string) ($f['fecha_registro'] ?? ''),
            (string) ($f['roles'] ?? ''),
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


    public static function crear(PDO $pdo, string $nombre, string $email, string $password, string $roles = ''): bool
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, email, password, roles) 
                               VALUES (?, ?, ?, ?)");

        return $stmt->execute([$nombre, $email, $passwordHash, $roles]);
    }


    public static function eliminar(PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

  
    

    public static function listar(PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM usuarios ORDER BY id");
        $usuarios = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $f) {
            $usuarios[] = self::desdeFila($f);
        }

        return $usuarios;
    }

    // Si $password viene vacío se conserva la contraseña actual
    public static function actualizar(PDO $pdo, int $id, string $nombre, string $email, string $password = '', string $roles = ''): bool
    {
        if ($password !== '') {
            $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, password = ?, roles = ? WHERE id = ?");
            return $stmt->execute([$nombre, $email, password_hash($password, PASSWORD_DEFAULT), $roles, $id]);
        }

        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ?, roles = ? WHERE id = ?" );
        return $stmt->execute([$nombre, $email, $roles, $id]);
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