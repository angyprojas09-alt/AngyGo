<?php
session_start();
require_once("../config/conexion.php");

if (!isset($_SESSION["usuario_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../public/login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    var_dump($conexion);
    exit;
    $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: ../views/admin/ver_usuarios.php");
exit();
