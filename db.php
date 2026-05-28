<?php

// Archivo de conexión a la base de datos

// Parámetros de conexión
$host = "localhost"; // servidor
$user = "root"; // usuario por defecto en xampp
$password = ""; // contraseña vacía por defecto en xampp
$dbname = "crud_psicogest"; // nombre de la base de datos

// Crea la conexión
$conexion = new mysqli($host, $user, $password, $dbname);

// Verificar si hay error en la conexión
if ($conexion->connect_error) {
    die("Error de conexión:" . $conexion->connect_error);
}

// Establece codificación UTF-8 para evitar problemas con acentos
$conexion->set_charset("utf8");