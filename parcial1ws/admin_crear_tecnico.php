<?php
// admin_crear_tecnico.php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id']) || $_SESSION['perfil'] !== 'Administrador') {
    header('Location: login.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($nombre=='' || $correo=='' || $password=='') die('Datos incompletos.');
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) die('Correo inválido.');
    $stmt = $mysqli->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->bind_param('s',$correo);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows>0) { $stmt->close(); die('Correo ya registrado.'); }
    $stmt->close();
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $mysqli->prepare("INSERT INTO usuarios (nombre, correo, password, perfil) VALUES (?,?,?, 'Técnico')");
    $ins->bind_param('sss',$nombre,$correo,$hash);
    $ins->execute();
    $ins->close();
    header('Location: dashboard_admin.php');
    exit;
}
header('Location: dashboard_admin.php');
