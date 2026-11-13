<?php
class Libro {


    private ?int   $id;
    private string $titulo;
    private string $autor;
    private int    $anioPublicacion;
    private int    $cantidadPaginas;
    private array $generos;
    private array $generoIds;
    private string    $disponible;
    private int    $stock;
    private ?string $portada;
    private string $codigoDewey;


    public function __construct(string $titulo, string $autor,
                                int $anioPublicacion, int $cantidadPaginas,
                                ?int $id = null, string $genero = '', string $disponible = 'Sí', int $stock = 1, ?string $portada = null, string $generoIds = '', string $codigoDewey = '000') {
        $this->titulo            = $titulo;
        $this->autor             = $autor;
        $this->anioPublicacion   = $anioPublicacion;
        $this->cantidadPaginas   = $cantidadPaginas;
        $this->id                = $id;
        $this->disponible        = $disponible;
        $this->generos           = $genero === '' ? [] : array_values(array_filter(array_map('trim', explode(',', $genero))));
        $this->generoIds         = $generoIds === '' ? [] : array_map('intval', explode(',', $generoIds));
        $this->stock             = $stock;
        $this->portada           = $portada;
        $this->codigoDewey       = $codigoDewey;
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
        return $this->generos ? implode(', ', $this->generos) : 'Sin género';
    }

    public function getGeneros(): array {
        return $this->generos;
    }

    public function getGeneroIds(): array {
        return $this->generoIds;
    }

    public function getDisponible() {
        return $this->disponible;
    }

    public function getStock() {
        return $this->stock;
    }

    public function getPortada() {
        return $this->portada;
    }

    public function getCodigoDewey(): string {
        return $this->codigoDewey;
    }


    private static function desdeFila(array $f): Libro {
        return new Libro(
            $f['titulo'],
            $f['autor'],
            (int) $f['anio_publicacion'],
            (int) $f['cantidad_paginas'],
            (int) $f['id'],
            (string) ($f['generos_nombre'] ?? ''),
            (string) ($f['disponible'] ?? 'Sí'),
            (int) ($f['stock'] ?? 1),
            $f['portada'] ?? null,
            (string) ($f['genero_ids'] ?? ''),
            (string) ($f['codigo_dewey'] ?? '000')
        );
    }

    private static function selectConGeneros(): string {
        return "SELECT l.*,
                       COALESCE((SELECT GROUP_CONCAT(g.nombre ORDER BY g.nombre SEPARATOR ', ')
                                 FROM libro_generos lg
                                 INNER JOIN generos g ON g.id = lg.genero_id
                                 WHERE lg.libro_id = l.id), '') AS generos_nombre,
                       COALESCE((SELECT GROUP_CONCAT(lg.genero_id ORDER BY g.nombre SEPARATOR ',')
                                 FROM libro_generos lg
                                 INNER JOIN generos g ON g.id = lg.genero_id
                                 WHERE lg.libro_id = l.id), '') AS genero_ids
                FROM libros l";
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
            self::selectConGeneros() . " ORDER BY l.titulo DESC LIMIT :inicio, :cantidad"
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
    public static function listarGeneros(PDO $pdo): array {
        return $pdo->query("SELECT id, nombre FROM generos ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    private static function filtrosBusqueda(string $buscar, string $campo, string $disponibilidad, string $genero = ''): array {
        $where = [];
        $params = [];
        $columnas = ['titulo', 'autor'];
        if ($buscar !== '') {
            if (in_array($campo, $columnas, true)) {
                $where[] = "l.$campo LIKE :buscar";
            } else {
                $where[] = '(l.titulo LIKE :buscarTitulo OR l.autor LIKE :buscarAutor OR EXISTS (
                    SELECT 1 FROM libro_generos lg
                    INNER JOIN generos g ON g.id = lg.genero_id
                    WHERE lg.libro_id = l.id AND g.nombre LIKE :buscarGenero
                ))';
                $params[':buscarTitulo'] = '%' . $buscar . '%';
                $params[':buscarAutor'] = '%' . $buscar . '%';
                $params[':buscarGenero'] = '%' . $buscar . '%';
            }
            if ($campo !== 'todos' && in_array($campo, $columnas, true)) {
                $params[':buscar'] = '%' . $buscar . '%';
            }
        }
        if ($genero === 'sin_genero') {
            $where[] = 'NOT EXISTS (SELECT 1 FROM libro_generos lg WHERE lg.libro_id = l.id)';
        } elseif ($genero !== '' && ctype_digit($genero) && (int) $genero > 0) {
            $where[] = 'EXISTS (SELECT 1 FROM libro_generos lg WHERE lg.libro_id = l.id AND lg.genero_id = :genero)';
            $params[':genero'] = (int) $genero;
        }
        if ($disponibilidad === '1' || $disponibilidad === '0') {
            $where[] = 'l.disponible = :disponible';
            $params[':disponible'] = $disponibilidad === '1' ? 'Sí' : 'No';
        }
        return [$where ? ' WHERE ' . implode(' AND ', $where) : '', $params];
    }

    public static function contarFiltrados(PDO $pdo, string $buscar = '', string $campo = 'todos', string $disponibilidad = '', string $genero = ''): int {
        [$where, $params] = self::filtrosBusqueda($buscar, $campo, $disponibilidad, $genero);
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM libros l' . $where);
        foreach ($params as $clave => $valor) {
            $stmt->bindValue($clave, $valor, is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public static function buscarPaginado(PDO $pdo, int $pagina, int $porPagina, string $buscar = '', string $campo = 'todos', string $disponibilidad = '', string $genero = ''): array {
        [$where, $params] = self::filtrosBusqueda($buscar, $campo, $disponibilidad, $genero);
        $inicio = (max(1, $pagina) - 1) * $porPagina;
        $stmt = $pdo->prepare(self::selectConGeneros() . $where . '
                               ORDER BY l.titulo DESC, l.id ASC LIMIT :inicio, :cantidad');
        foreach ($params as $clave => $valor) {
            $stmt->bindValue($clave, $valor, is_int($valor) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
        $stmt->bindValue(':cantidad', $porPagina, PDO::PARAM_INT);
        $stmt->execute();
        return array_map([self::class, 'desdeFila'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public static function buscarPorId(PDO $pdo, int $id): ?Libro {
        $stmt = $pdo->prepare(self::selectConGeneros() . " WHERE l.id = ?");
        $stmt->execute([$id]);
        $f = $stmt->fetch(PDO::FETCH_ASSOC);
        return $f ? self::desdeFila($f) : null;
    }


    // =========================
    // CRUD
    // =========================

    public static function normalizarGeneroIds(PDO $pdo, $generoIds): array {
        if (!is_array($generoIds)) {
            throw new InvalidArgumentException('Selecciona géneros válidos.');
        }

        $ids = [];
        foreach ($generoIds as $valor) {
            if (!is_string($valor) && !is_int($valor)) {
                throw new InvalidArgumentException('Selecciona géneros válidos.');
            }
            $id = filter_var($valor, FILTER_VALIDATE_INT);
            if ($id === false || $id < 1) {
                throw new InvalidArgumentException('Selecciona géneros válidos.');
            }
            $ids[] = $id;
        }
        $ids = array_values(array_unique($ids));
        if (!$ids) {
            return [];
        }

        $marcadores = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT id FROM generos WHERE id IN ($marcadores)");
        $stmt->execute($ids);
        $validos = array_map('intval', $stmt->fetchAll(PDO::FETCH_COLUMN));
        if (count($validos) !== count($ids)) {
            throw new InvalidArgumentException('Uno o más géneros ya no están disponibles. Actualiza la página e inténtalo nuevamente.');
        }
        return $ids;
    }

    private static function normalizarNombreGenero(string $nombre): string {
        $nombre = preg_replace('/\s+/u', ' ', trim($nombre));
        if ($nombre === false || $nombre === '') {
            throw new InvalidArgumentException('Escribe un nombre de género válido.');
        }
        if (strpos($nombre, ',') !== false) {
            throw new InvalidArgumentException('Agrega un solo género por vez; no uses comas.');
        }
        if (preg_match('/^sin\s+g[eé]nero$/iu', $nombre)) {
            throw new InvalidArgumentException('“Sin género” representa libros sin géneros asociados y no se puede agregar como género.');
        }
        $cantidadCaracteres = preg_match_all('/./us', $nombre);
        if ($cantidadCaracteres === false || $cantidadCaracteres > 120) {
            throw new InvalidArgumentException('El nombre del género no puede superar los 120 caracteres.');
        }

        return $nombre;
    }

    public static function normalizarGenerosNuevos(array $nombres): array {
        $nombresNormalizados = [];
        foreach ($nombres as $nombre) {
            if (!is_string($nombre)) {
                throw new InvalidArgumentException('El nombre del género no es válido.');
            }
            $nombresNormalizados[] = self::normalizarNombreGenero($nombre);
        }
        return $nombresNormalizados;
    }

    private static function obtenerOCrearGenero(PDO $pdo, string $nombre): int {
        $nombre = self::normalizarNombreGenero($nombre);
        $stmt = $pdo->prepare(
            'INSERT INTO generos (nombre) VALUES (?)
             ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)'
        );
        $stmt->execute([$nombre]);
        $generoId = (int) $pdo->lastInsertId();

        if ($generoId < 1) {
            $stmt = $pdo->prepare('SELECT id FROM generos WHERE nombre = ? LIMIT 1');
            $stmt->execute([$nombre]);
            $generoId = (int) $stmt->fetchColumn();
        }
        if ($generoId < 1) {
            throw new RuntimeException('No se pudo guardar el género.');
        }

        return $generoId;
    }

    private static function codigoDeweyPorGenero(string $genero): ?string {
        $genero = mb_strtolower(trim($genero), 'UTF-8');
        $genero = strtr($genero, [
            'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
            'ü' => 'u', 'ñ' => 'n',
        ]);
        $genero = preg_replace('/[^a-z0-9]+/', '', $genero);
        $codigos = [
            'administracion' => '658',
            'administraciondepersonal' => '658.3',
            'antropologia' => '301.2',
            'arquitectura' => '720',
            'arte' => '700',
            'atlas' => '912',
            'biologia' => '570',
            'calidad' => '658.56',
            'carpinteria' => '684',
            'cerrajeria' => '683',
            'comics' => '741.5',
            'construccion' => '690',
            'contabilidad' => '657',
            'derecho' => '340',
            'dibujo' => '741.2',
            'diccionarios' => '030',
            'economia' => '330',
            'educacion' => '370',
            'educacionfisica' => '796',
            'electronica' => '621.38',
            'electrotecnia' => '621.3',
            'fantasia' => '800',
            'ficcion' => '800',
            'filosofia' => '100',
            'fisica' => '530',
            'folletos' => '000',
            'frances' => '440',
            'gastronomia' => '641.5',
            'geografia' => '910',
            'historia' => '900',
            'historico' => '900',
            'idiomaespanol' => '460',
            'infantil' => '800',
            'informatica' => '004',
            'ingles' => '420',
            'juvenil' => '800',
            'laboratorio' => '507',
            'literatura' => '800',
            'logistica' => '658.5',
            'marketing' => '658.8',
            'matematica' => '510',
            'mecanica' => '621.8',
            'misterio' => '800',
            'novelasgraficas' => '741.5',
            'peluqueriaprofesional' => '646.72',
            'portugues' => '469',
            'publicidad' => '659.1',
            'quimica' => '540',
            'revistas' => '050',
            'romance' => '800',
            'seguridadindustrial' => '363.11',
            'sociologia' => '301',
            'suspenso' => '800',
            'tecnologia' => '600',
            'terror' => '800',
            'trabajos' => '000',
            'turismo' => '910.2',
        ];

        return $codigos[$genero] ?? null;
    }

    private static function codigoDeweySugerido(PDO $pdo, array $generoIds, array $generosNuevos, string $titulo): string {
        if ($generoIds) {
            $marcadores = implode(',', array_fill(0, count($generoIds), '?'));
            $stmt = $pdo->prepare("SELECT id, nombre FROM generos WHERE id IN ($marcadores)");
            $stmt->execute($generoIds);
            $nombresPorId = [];
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $fila) {
                $nombresPorId[(int) $fila['id']] = (string) $fila['nombre'];
            }

            foreach ($generoIds as $generoId) {
                if (!isset($nombresPorId[$generoId])) {
                    continue;
                }
                $codigo = self::codigoDeweyPorGenero($nombresPorId[$generoId]);
                if ($codigo !== null) {
                    return $codigo;
                }
            }
        }

        foreach ($generosNuevos as $genero) {
            $codigo = self::codigoDeweyPorGenero($genero);
            if ($codigo !== null) {
                return $codigo;
            }
        }

        $tituloNormalizado = mb_strtolower($titulo, 'UTF-8');
        if (str_contains($tituloNormalizado, 'atlas')) {
            return '912';
        }
        if (str_contains($tituloNormalizado, 'dicc') || str_contains($tituloNormalizado, 'enciclop')) {
            return '030';
        }

        return '000';
    }

    public static function normalizarCodigoDewey(string $codigo): string {
        $codigo = trim($codigo);
        if ($codigo === '') {
            return '';
        }
        if (!preg_match('/^[0-9]{3}(?:\.[0-9]{1,6})?$/', $codigo)) {
            throw new InvalidArgumentException('El código Dewey debe tener tres dígitos y, opcionalmente, decimales (por ejemplo, 641.5).');
        }
        return $codigo;
    }

    private static function guardarGeneros(PDO $pdo, int $libroId, array $generoIds, array $nuevosGeneros = []): void {
        foreach ($nuevosGeneros as $nombre) {
            if (!is_string($nombre)) {
                throw new InvalidArgumentException('El nombre del género no es válido.');
            }
            $generoIds[] = self::obtenerOCrearGenero($pdo, $nombre);
        }
        $generoIds = array_values(array_unique($generoIds));

        $stmt = $pdo->prepare('INSERT INTO libro_generos (libro_id, genero_id) VALUES (?, ?)');
        foreach ($generoIds as $generoId) {
            $stmt->execute([$libroId, $generoId]);
        }
    }

    public static function crear(PDO $pdo, string $titulo, string $autor, int $anio,
                                  int $paginas, array $generoIds, string $disponible, int $stock, ?string $portada,
                                  array $nuevosGeneros = [], string $codigoDewey = ''): bool {
        $codigoDewey = self::normalizarCodigoDewey($codigoDewey);
        if ($codigoDewey === '') {
            $codigoDewey = self::codigoDeweySugerido($pdo, $generoIds, $nuevosGeneros, $titulo);
        }
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                "INSERT INTO libros (titulo, autor, anio_publicacion, cantidad_paginas, genero, disponible, stock, portada, codigo_dewey)
                 VALUES (?, ?, ?, ?, NULL, ?, ?, ?, ?)"
            );
            $success = $stmt->execute([$titulo, $autor, $anio, $paginas, $disponible, $stock, $portada, $codigoDewey]);
            if (!$success) {
                $pdo->rollBack();
                return false;
            }
            self::guardarGeneros($pdo, (int) $pdo->lastInsertId(), $generoIds, $nuevosGeneros);
            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function actualizar(PDO $pdo, int $id, string $titulo, string $autor, int $anio,
                                       int $paginas, array $generoIds, string $disponible, int $stock, ?string $portada,
                                       array $nuevosGeneros = [], string $codigoDewey = ''): bool {
        $codigoDewey = self::normalizarCodigoDewey($codigoDewey);
        if ($codigoDewey === '') {
            $codigoDewey = self::codigoDeweySugerido($pdo, $generoIds, $nuevosGeneros, $titulo);
        }
        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare(
                "UPDATE libros
                 SET titulo = ?, autor = ?, anio_publicacion = ?, cantidad_paginas = ?, genero = NULL, disponible = ?, stock = ?, portada = ?, codigo_dewey = ?
                 WHERE id = ?"
            );
            $success = $stmt->execute([$titulo, $autor, $anio, $paginas, $disponible, $stock, $portada, $codigoDewey, $id]);
            if (!$success) {
                $pdo->rollBack();
                return false;
            }
            $delete = $pdo->prepare('DELETE FROM libro_generos WHERE libro_id = ?');
            $delete->execute([$id]);
            self::guardarGeneros($pdo, $id, $generoIds, $nuevosGeneros);
            $pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }

    public static function eliminar(PDO $pdo, int $id): bool {
        $stmt = $pdo->prepare("DELETE FROM libros WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
