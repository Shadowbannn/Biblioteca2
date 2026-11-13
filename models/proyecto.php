<?php
class Proyecto {


    private ?int   $id;
    private string $nombre;
    private string $integrantes;
    private int    $anio;
    private string $clase;
    private string $curso;
    private ?string $portada;


    public function __construct(string $nombre, string $integrantes,
                                int $anio, string $clase,
                                ?int $id = null, string $curso = '', ?string $portada = null) {
        $this->nombre            = $nombre;
        $this->integrantes             = $integrantes;
        $this->anio     = $anio;
        $this->clase    = $clase;
        $this->id       = $id;
        $this->curso    = $curso;
        $this->portada  = $portada;
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

    public function getIntegrantes() {
        return $this->integrantes;
    }

    public function getAnio() {
        return $this->anio;
    }

    public function getClase() {
        return $this->clase;
    }

    public function getCurso() {
        return $this->curso;
    }

    public function getPortada() {
        return $this->portada;
    }

    public function getRutaPortada(): string {
        if (!$this->portada) {
            return 'assets/sin-portada.webp';
        }

        if (strpos($this->portada, 'uploads/proyectos/') === 0 ||
            strpos($this->portada, 'uploads/portadas/') === 0) {
            return $this->portada;
        }

        return 'uploads/portadas/' . $this->portada;
    }

    public function mostrarInfo(): void {
        echo "  PROYECTO\n";
        echo "  Nombre              : {$this->nombre}\n";
        echo "  Integrantes               : {$this->integrantes}\n";
        echo "  Año  : {$this->anio}\n";
        echo "  Clase : {$this->clase}\n";
        echo "  Curso              : {$this->curso}\n";
    }

    private static function desdeFila(array $f): Proyecto {
        return new Proyecto(
            $f['nombre'],
            (string) ($f['integrantes'] ?? ''),
            (int) $f['anio'],
            (string) ($f['clase'] ?? ''),
            (int) $f['id'],
            (string) ($f['curso'] ?? ''),
            $f['portada']
        );
    }

    public static function contarTotal(PDO $pdo): int {
        return (int) $pdo->query("SELECT COUNT(*) FROM proyectos")->fetchColumn();
    }

    public static function listarPaginado(PDO $pdo, int $paginaActual, int $porPagina): array {
        $inicio = ($paginaActual - 1) * $porPagina;

        $stmt = $pdo->prepare(
            "SELECT * FROM proyectos ORDER BY nombre LIMIT :inicio, :cantidad"
        );
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();

        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $proyectos = [];
        foreach ($filas as $f) {
            $proyectos[] = self::desdeFila($f);
        }
        return $proyectos;
    }

    private static function filtroBusqueda(string $buscar): array {
        if ($buscar === '') {
            return ['', []];
        }

        $like = '%' . $buscar . '%';
        return [
            ' WHERE nombre LIKE :nombre OR integrantes LIKE :integrantes OR clase LIKE :clase OR curso LIKE :curso OR CAST(anio AS CHAR) LIKE :anio',
            [
                ':nombre' => $like,
                ':integrantes' => $like,
                ':clase' => $like,
                ':curso' => $like,
                ':anio' => $like,
            ],
        ];
    }

    public static function contarFiltrados(PDO $pdo, string $buscar = ''): int {
        [$where, $params] = self::filtroBusqueda($buscar);
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM proyectos' . $where);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    public static function buscarPaginado(PDO $pdo, int $pagina, int $porPagina, string $buscar = ''): array {
        [$where, $params] = self::filtroBusqueda($buscar);
        $inicio = (max(1, $pagina) - 1) * $porPagina;
        $stmt = $pdo->prepare(
            'SELECT * FROM proyectos' . $where . ' ORDER BY nombre, id LIMIT :inicio, :cantidad'
        );
        foreach ($params as $clave => $valor) {
            $stmt->bindValue($clave, $valor, PDO::PARAM_STR);
        }
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();

        $proyectos = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $proyectos[] = self::desdeFila($fila);
        }
        return $proyectos;
    }

    public static function buscarPorId(PDO $pdo, int $id): ?Proyecto {
        $stmt = $pdo->prepare("SELECT * FROM proyectos WHERE id = ?");
        $stmt->execute([$id]);
        $f = $stmt->fetch(PDO::FETCH_ASSOC);
        return $f ? self::desdeFila($f) : null;
    }

    public static function crear(PDO $pdo, string $nombre, string $integrantes, int $anio,
                                   string $clase, string $curso, ?string $portada): bool {
        $stmt = $pdo->prepare(
            "INSERT INTO proyectos (nombre, integrantes, anio, clase, curso, portada)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$nombre, $integrantes, $anio, $clase, $curso, $portada]);
    }

    public static function actualizar(PDO $pdo, int $id, string $nombre, string $integrantes, int $anio,
                                     string $clase, string $curso,  ?string $portada): bool {
        $stmt = $pdo->prepare(
            "UPDATE proyectos
             SET nombre = ?, integrantes = ?, anio = ?, clase = ?, curso = ?, portada = ?
             WHERE id = ?"
        );
        return $stmt->execute([$nombre, $integrantes, $anio, $clase, $curso, $portada, $id]);
    }

    public static function eliminar(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM proyectos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
