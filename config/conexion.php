<?php
// Este archivo lo incluyen todos los demás con require.
// Acá se abre la conexión UNA vez y queda disponible en $pdo.

try {
    $host = getenv('DB_HOST');
    $port = getenv('DB_PORT');
    $db   = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');

    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$db",
        $user,
        $pass
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
