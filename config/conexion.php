<?php
// Este archivo lo incluyen todos los demás con require.
// Acá se abre la conexión UNA vez y queda disponible en $pdo.

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=biblioteca2;charset=utf8mb4",
        "root",
        ""
    );
    // Si algo falla en una consulta, que PHP lance una excepción
    // (¿te acordás de try/catch? Acá se usa en el mundo real)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}