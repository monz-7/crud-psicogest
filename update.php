<?php

// ACTUALIZA DATOS EN LA DB

require_once("db.php");

// Verifica el método POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit();
}

// OBTIENE DATOS DEL FORM
$uuid = trim($_POST['uuid']);
$doc_type = trim($_POST['doctype']);
$doc_number = trim($_POST['docnumber']);
$names = trim($_POST['names']);
$surnames = trim($_POST['surnames']);
$birthdate = trim($_POST['birthdate']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);

// Valida email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Correo inválido.");
}

// Valida la ubicación
$location = trim($_POST['location'] ?? "");
$country  = trim($_POST['country'] ?? "");

if ($location === "colombia") {
    $location = "Colombia";
} elseif (!empty($country)) {
    $location = $country;
} else {
    die("Ubicación inválida.");
}

// Valida que ningún campo obligatorio esté vacío
if (
      empty($doc_type) ||
      empty($doc_number) ||
      empty($names) ||
      empty($surnames) ||
      empty($birthdate) ||
      empty($phone) ||
      empty($email)
  ) {
      die("Todos los campos son obligatorios.");
  }

// Prepara la consulta (mejor que inyección directa)
$sql = "UPDATE patients SET
    doc_type = ?,
    doc_number = ?,
    names = ?,
    surnames = ?,
    birthdate = ?,
    phone = ?,
    email = ?,
    location = ?
WHERE uuid = ?";

$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error al preparar la consulta.");
}

// Vincula el parámetro
$stmt->bind_param(
    "sssssssss",
    $doc_type,
    $doc_number,
    $names,
    $surnames,
    $birthdate,
    $phone,
    $email,
    $location,
    $uuid
);

// Ejecuta la consulta
if ($stmt->execute()) {
    header("Location: index.php?view=patients");
    exit();
} else {
    echo "Error al actualizar.";
}

// Cierra los recursos
$stmt->close();
$conexion->close();