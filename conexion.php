<?php
// conexion.php

$servername = "localhost";
$username = "oscar franco";
$password = "";
$dbname = "tecnored";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// ¡Conexión lista!
?>