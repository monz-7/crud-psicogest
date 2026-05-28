<?php

// ELIMINA UN PACIENTE

require_once("db.php");

// Valida el UUID
if (!isset($_GET['uuid']) || empty($_GET['uuid'])) {
    header("Location: index.php");
    exit();
}

// Obtiene el UUID
$uuid = $_GET['uuid'];

// Prepara la consulta (mejor que inyección directa)
$sql = "DELETE FROM patients WHERE uuid = ?";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta.");
}

// Vincula el parámetro
$stmt->bind_param("s", $uuid);

// Ejecuta
if ($stmt->execute()) {
    header("Location: index.php?view=patients");
    exit();
} else {
    echo "Error al eliminar.";
}

// Cierra recursos
$stmt->close();
$conexion->close();