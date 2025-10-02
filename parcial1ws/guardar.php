<?php
// guardar.php - guarda nuevo usuario con password_hash
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registro.php');
    exit;
}

$nombre = trim($_POST['nombre'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$password = $_POST['password'] ?? '';
$perfil = $_POST['perfil'] ?? 'Cliente';

if ($nombre === '' || $correo === '' || $password === '' || $perfil === '') {
    die('Todos los campos son obligatorios.');
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die('Correo inválido.');
}

$stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE correo = ?");
$stmt->bind_param('s', $correo);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $stmt->close();
    die('El correo ya está registrado.');
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);
$insert = $mysqli->prepare("INSERT INTO usuarios (nombre, correo, password, perfil) VALUES (?,?,?,?)");
$insert->bind_param('ssss', $nombre, $correo, $hash, $perfil);
if ($insert->execute()) {
    $insert->close();
    header('Location: login.php?msg=usuario_creado');
    exit;
} else {
    $insert->close();
    die('Error al guardar usuario: ' . $mysqli->error);
}
