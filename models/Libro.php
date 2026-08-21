<?php
class Libro {


    private ?int   $id;
    private string $titulo;
    private string $autor;
    private int    $anioPublicacion;
    private int    $cantidadPaginas;
    private string $genero;
    private ?string $portada;


    public function __construct(string $titulo, string $autor,
                                int $anioPublicacion, int $cantidadPaginas,
                                ?int $id = null, string $genero = '', ?string $portada = null) {
        $this->titulo            = $titulo;
        $this->autor             = $autor;
        $this->anioPublicacion   = $anioPublicacion;
        $this->cantidadPaginas   = $cantidadPaginas;
        $this->id                = $id;
        $this->genero            = $genero;
        $this->portada           = $portada;
    }

   
    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getAutor() {
        return $this->autor;
    }

    public function getAnioPublicacion() {
        return $this->anioPublicacion;
    }

    public function getCantidadPaginas() {
        return $this->cantidadPaginas;
    }

    public function getGenero() {
        return $this->genero;
    }

    public function getPortada() {
        return $this->portada;
    }

    public function mostrarInfo(): void {
        echo "  LIBRO\n";
        echo "  Título              : {$this->titulo}\n";
        echo "  Autor               : {$this->autor}\n";
        echo "  Año de publicación  : {$this->anioPublicacion}\n";
        echo "  Cantidad de páginas : {$this->cantidadPaginas}\n";
        echo "  Género              : {$this->genero}\n";
    }

    private static function desdeFila(array $f): Libro {
        return new Libro(
            $f['titulo'],
            $f['autor'],
            (int) $f['anio_publicacion'],
            (int) $f['cantidad_paginas'],
            (int) $f['id'],
            $f['genero'],
            $f['portada']
        );
    }

    public static function contarTotal(PDO $pdo): int {
        return (int) $pdo->query("SELECT COUNT(*) FROM libros")->fetchColumn();
    }

    public static function listarPaginado(PDO $pdo, int $paginaActual, int $porPagina): array {
        $inicio = ($paginaActual - 1) * $porPagina;

        $stmt = $pdo->prepare(
            "SELECT * FROM libros ORDER BY titulo LIMIT :inicio, :cantidad"
        );
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();

        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $libros = [];
        foreach ($filas as $f) {
            $libros[] = self::desdeFila($f);
        }
        return $libros;
    }

    public static function buscarPorId(PDO $pdo, int $id): ?Libro {
        $stmt = $pdo->prepare("SELECT * FROM libros WHERE id = ?");
        $stmt->execute([$id]);
        $f = $stmt->fetch(PDO::FETCH_ASSOC);
        return $f ? self::desdeFila($f) : null;
    }

    public static function crear(PDO $pdo, string $titulo, string $autor, int $anio,
                                  int $paginas, string $genero, ?string $portada): bool {
        $stmt = $pdo->prepare(
            "INSERT INTO libros (titulo, autor, anio_publicacion, cantidad_paginas, genero, portada)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$titulo, $autor, $anio, $paginas, $genero, $portada]);
    }

    public static function actualizar(PDO $pdo, int $id, string $titulo, string $autor, int $anio,
                                       int $paginas, string $genero, ?string $portada): bool {
        $stmt = $pdo->prepare(
            "UPDATE libros
             SET titulo = ?, autor = ?, anio_publicacion = ?, cantidad_paginas = ?, genero = ?, portada = ?
             WHERE id = ?"
        );
        return $stmt->execute([$titulo, $autor, $anio, $paginas, $genero, $portada, $id]);
    }

    public static function eliminar(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM libros WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
