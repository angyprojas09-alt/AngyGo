<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$bd = "angygo";

$conexion = new mysqli($servidor, $usuario, $password, $bd);

if ($conexion->connect_error) {
    die("Error de conexión con la base de datos.");
}

$conexion->set_charset("utf8mb4");
