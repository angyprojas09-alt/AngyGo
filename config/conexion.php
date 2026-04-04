<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$bd = "angygo";
$charset  = "utf8mb4";

try {
    $dsn = "mysql:host=$servidor;dbname=$bd;charset=$charset";
    $opciones = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $conexion = new PDO($dsn, $usuario, $password, $opciones);
} catch (PDOException $e) {
    // Si falla, esto detendrá la ejecución y te dirá por qué (útil para QA)
    die("Error crítico de conexión: " . $e->getMessage());
}
