<?php

// INSERTA DATOS EN LA DB

require_once("db.php");

// Verifica que la petición sea POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Verifica la coincidencia de contraseñas
    if ($_POST['password'] !== $_POST['password-confirm']) {
        die("Las contraseñas no coinciden.");
    }

    // Verifica la coincidencia de correos
    if ($_POST['email'] !== $_POST['email-confirm']) {
        die("Los correos no coinciden.");
    }

    // GENERA UUID
    $uuid = bin2hex(random_bytes(16));

    // OBTIENE DATOS DEL FORM
    $doc_type = trim($_POST['doctype']);
    $doc_number = trim($_POST['docnumber']);
    $names = trim($_POST['names']);
    $surnames = trim($_POST['surnames']);
    $birthdate = trim($_POST['birthdate']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $location = trim($_POST['location'] ?? "");
    $country = trim($_POST['country'] ?? "");

    // Valida email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Correo inválido.");
    }

    // Valida que ningún campo obligatorio esté vacío
    if (
        empty($doc_type) ||
        empty($doc_number) ||
        empty($names) ||
        empty($surnames) ||
        empty($birthdate) ||
        empty($phone) ||
        empty($email) ||
        empty($password)
    ) {
        die("Todos los campos son obligatorios.");
    }

    // Hashea la contraseña (NUNCA guardar en texto plano)
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Valida la ubicación
    if ($location === "colombia") {
        $location = "Colombia";
    } elseif (!empty($country)) {
        $location = $country;
    } else {
        die("Ubicación inválida.");
    }

    // Prepara la consulta (mejor que inyección directa)
    $sql = "INSERT INTO patients 
    (uuid, doc_type, doc_number, names, surnames, birthdate, phone, email, password, location)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);

    if (!$stmt) {
        die("Error al preparar la consulta.");
    }

    // Vincula el parámetro
    $stmt->bind_param(
        "ssssssssss",
        $uuid,
        $doc_type,
        $doc_number,
        $names,
        $surnames,
        $birthdate,
        $phone,
        $email,
        $password,
        $location
    );

    // Ejecuta la consulta
    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error al registrar: " . $stmt->error;
    }

    // Cierra los recursos
    $stmt->close();
    $conexion->close();
}