
<?php

$hostActual = $_SERVER['HTTP_HOST'] ?? 'localhost';

$esLocal = in_array($hostActual, [
    'localhost',
    '127.0.0.1'
]);

if ($esLocal) {

    // Configuración XAMPP
    $config = [
        'host' => 'localhost',
        'dbname' => 'biblioteca2',
        'user' => 'root',
        'password' => ''
    ];

} else {

    // Configuración InfinityFree
    $config = require __DIR__ . '/conexion.produccion.php';

}

try {

    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
        $config['user'],
        $config['password']
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    error_log($e->getMessage());

    die("No se pudo conectar con la base de datos.");

}
