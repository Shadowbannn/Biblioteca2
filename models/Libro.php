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

    // =========================
    // GETTERS
    // =========================
   
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


    private static function desdeFila(array $f): Libro {
        return new Libro(
            $f['titulo'],
            $f['autor'],
            (int) $f['anio_publicacion'],
            (int) $f['cantidad_paginas'],
            (int) $f['id'],
            (string) ($f['genero'] ?? ''),
            $f['portada'] ?? null
        );
    }

       public static function contarTotal(PDO $pdo): int
    {
        $stmt = $pdo->query(
            "SELECT COUNT(*) FROM libros"
        );

        return (int) $stmt->fetchColumn();
    }


    public static function listarPaginado(PDO $pdo, 
        int $paginaActual, int $porPagina): array {
        $inicio = ($paginaActual - 1) * $porPagina;

        $stmt = $pdo->prepare(
            "SELECT * FROM libros 
             ORDER BY titulo ASC 
             LIMIT :inicio, :cantidad"
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


    // Búsqueda filtrada con PDO; compatible con la paginación actual.
    private static function filtrosBusqueda(string $buscar, string $campo, string $disponibilidad): array {
        $where = [];
        $params = [];
        $columnas = ['titulo', 'autor', 'genero'];
        if ($buscar !== '') {
            if (in_array($campo, $columnas, true)) {
                $where[] = "$campo LIKE :buscar";
            } else {
                $where[] = '(titulo LIKE :buscarTitulo OR autor LIKE :buscarAutor OR genero LIKE :buscarGenero)';
                $params[':buscarTitulo'] = '%' . $buscar . '%';
                $params[':buscarAutor'] = '%' . $buscar . '%';
                $params[':buscarGenero'] = '%' . $buscar . '%';
            }
            if ($campo !== 'todos' && in_array($campo, $columnas, true)) {
                $params[':buscar'] = '%' . $buscar . '%';
            }
        }
        if ($disponibilidad === '1' || $disponibilidad === '0') {
            $where[] = 'disponible = :disponible';
            $params[':disponible'] = (int) $disponibilidad;
        }
        return [$where ? ' WHERE ' . implode(' AND ', $where) : '', $params];
    }

    public static function contarFiltrados(PDO $pdo, string $buscar = '', string $campo = 'todos', string $disponibilidad = ''): int {
        [$where, $params] = self::filtrosBusqueda($buscar, $campo, $disponibilidad);
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM libros' . $where);
        foreach ($params as $clave => $valor) {
            $stmt->bindValue($clave, $valor, is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public static function buscarPaginado(PDO $pdo, int $pagina, int $porPagina, string $buscar = '', string $campo = 'todos', string $disponibilidad = ''): array {
        [$where, $params] = self::filtrosBusqueda($buscar, $campo, $disponibilidad);
        $inicio = (max(1, $pagina) - 1) * $porPagina;
        $stmt = $pdo->prepare('SELECT * FROM libros' . $where . ' 
                               ORDER BY titulo DESC, id ASC LIMIT :inicio, :cantidad');
        foreach ($params as $clave => $valor) {
            $stmt->bindValue($clave, $valor, is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();
        return array_map([self::class, 'desdeFila'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function buscarPorId(PDO $pdo, int $id): ?Libro {
        $stmt = $pdo->prepare("SELECT * FROM libros WHERE id = ?");
        $stmt->execute([$id]);
        $f = $stmt->fetch(PDO::FETCH_ASSOC);
        return $f ? self::desdeFila($f) : null;
    }


    // =========================
    // CRUD
    // =========================

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
