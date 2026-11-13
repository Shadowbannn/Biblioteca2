<?php
class Juego {


    private ?int    $id;
    private string  $nombre;
    private string  $editor;
    private int     $stock;
    private int     $disponible;
    private ?string $foto;


    public function __construct(
        string $nombre,
        string $editor,
        int $stock = 1,
        int $disponible = 1,
        ?int $id = null,
        ?string $foto = null
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->editor = $editor;
        $this->stock = $stock;
        $this->disponible = $disponible;
        $this->foto = $foto;
    }

    // =========================
    // GETTERS
    // =========================
   
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEditor() {
        return $this->editor;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getDisponible() {
        return $this->disponible;
    }


    public function getFoto() {
        return $this->foto;
    }


    private static function desdeFila(array $f): Juego {
        return new Juego(
            $f['nombre'],
            $f['editor'] ?? '',
            (int) ($f['stock'] ?? 1),
            (int) $f['disponible'],
            (int) $f['id'],
            $f['foto'] ?? null
        );
    }

       public static function contarTotal(PDO $pdo): int
    {
        $stmt = $pdo->query(
            "SELECT COUNT(*) FROM juegos"
        );

        return (int) $stmt->fetchColumn();
    }


    public static function listarPaginado(PDO $pdo, 
        int $paginaActual, int $porPagina): array {
        $inicio = ($paginaActual - 1) * $porPagina;

        $stmt = $pdo->prepare(
            "SELECT * FROM juegos 
             ORDER BY nombre DESC 
             LIMIT :inicio, :cantidad"
        );
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();

        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $juegos = [];
        foreach ($filas as $f) {
            $juegos[] = self::desdeFila($f);
        }
        return $juegos;
    }

    private static function filtroBusqueda(string $buscar): array {
        if ($buscar === '') {
            return ['', []];
        }

        $like = '%' . $buscar . '%';
        return [
            ' WHERE nombre LIKE :nombre OR editor LIKE :editor',
            [':nombre' => $like, ':editor' => $like],
        ];
    }

    public static function contarFiltrados(PDO $pdo, string $buscar = ''): int {
        [$where, $params] = self::filtroBusqueda($buscar);
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM juegos' . $where);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function buscarPaginado(PDO $pdo, int $pagina, int $porPagina, string $buscar = ''): array {
        [$where, $params] = self::filtroBusqueda($buscar);
        $inicio = (max(1, $pagina) - 1) * $porPagina;
        $stmt = $pdo->prepare(
            'SELECT * FROM juegos' . $where . ' ORDER BY nombre DESC, id ASC LIMIT :inicio, :cantidad'
        );
        foreach ($params as $clave => $valor) {
            $stmt->bindValue($clave, $valor, PDO::PARAM_STR);
        }
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();

        $juegos = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $juegos[] = self::desdeFila($fila);
        }
        return $juegos;
    }

    public static function buscarPorId(PDO $pdo, int $id): ?Juego {
        $stmt = $pdo->prepare("SELECT * FROM juegos WHERE id = ?");
        $stmt->execute([$id]);
        $f = $stmt->fetch(PDO::FETCH_ASSOC);
        return $f ? self::desdeFila($f) : null;
    }


    // =========================
    // CRUD
    // =========================

    public static function crear(PDO $pdo, string $nombre, string $editor, int $stock, int $disponible,
                                 ?string $foto): bool {
        $stmt = $pdo->prepare(
            "INSERT INTO juegos (nombre, editor, stock, disponible, foto)
             VALUES (?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$nombre, $editor, $stock, $disponible, $foto]);
    }

    public static function actualizar(PDO $pdo, int $id, string $nombre, string $editor, int $stock, int $disponible,
                                     ?string $foto): bool {
        $stmt = $pdo->prepare(
            "UPDATE juegos
             SET nombre = ?, editor = ?, stock = ?, disponible = ?, foto = ?
             WHERE id = ?"
        );
        return $stmt->execute([$nombre, $editor, $stock, $disponible, $foto, $id]);
    }

    public static function eliminar(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM juegos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
