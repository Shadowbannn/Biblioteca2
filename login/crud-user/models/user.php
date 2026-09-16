<?php

// Asume la tabla:
// usuarios(id INT PK, nombre VARCHAR, email VARCHAR UNIQUE, password VARCHAR, fecha_registro TIMESTAMP)
// Si tu tabla tiene otros nombres de columna, avisá y se ajusta acá nomás.

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
        ?int $id = null
    ) {
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFechaRegistro(): string
    {
        return $this->fecha_registro;
    }

    // Ojo: esto devuelve el HASH, nunca la contraseña real.
    // No lo muestres en ninguna vista, es solo para password_verify().
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

    // =========================
    // CONSULTAS
    // =========================

    public static function listar(PDO $pdo): array
    {
        $stmt = $pdo->query("SELECT * FROM usuarios ORDER BY id DESC");

        $usuarios = [];
        while ($f = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = self::desdeFila($f);
        }

        return $usuarios;
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

    // =========================
    // ESCRITURA
    // =========================

    public static function crear(PDO $pdo, string $nombre, string $email, string $password): bool
    {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            "INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)"
        );

        return $stmt->execute([$nombre, $email, $passwordHash]);
    }

    public static function actualizar(
        PDO $pdo,
        int $id,
        string $nombre,
        string $email,
        ?string $password = null
    ): bool {
        if ($password !== null && $password !== '') {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare(
                "UPDATE usuarios SET nombre = ?, email = ?, password = ? WHERE id = ?"
            );
            return $stmt->execute([$nombre, $email, $passwordHash, $id]);
        }

        $stmt = $pdo->prepare("UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?");
        return $stmt->execute([$nombre, $email, $id]);
    }

    public static function eliminar(PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // =========================
    // LOGIN
    // =========================

    // Devuelve el Usuario si las credenciales son correctas, o null si no.
    // Acá SÍ se usa password_verify contra el hash guardado — nunca comparar
    // la contraseña en texto plano contra la columna.
    public static function validarCredenciales(PDO $pdo, string $email, string $password): ?Usuario
    {
        $encontrado = self::buscarPorEmail($pdo, $email);

        if ($encontrado && password_verify($password, $encontrado->getPasswordHash())) {
            return $encontrado;
        }

        return null;
    }
}
