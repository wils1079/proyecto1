<?php
// conexion.php - ajuste credenciales si es necesario
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';         // <- cambia si tu servidor tiene password
$DB_NAME = 'mesa_ayuda';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    die("Error de conexión a la base de datos: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");
