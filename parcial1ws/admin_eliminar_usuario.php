<?php
// admin_eliminar_usuario.php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id']) || $_SESSION['perfil'] !== 'Administrador') {
    header('Location: login.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = intval($_POST['user_id'] ?? 0);
    if ($uid <= 0) die('ID inválido.');
    // prevent deleting self
    if ($uid == $_SESSION['user_id']) die('No puedes eliminarte a ti mismo.');
    $del = $mysqli->prepare("DELETE FROM usuarios WHERE id = ?");
    $del->bind_param('i',$uid);
    $del->execute();
    $del->close();
    header('Location: dashboard_admin.php');
    exit;
}
header('Location: dashboard_admin.php');
