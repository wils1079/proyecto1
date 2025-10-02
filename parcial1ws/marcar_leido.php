<?php
// marcar_leido.php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: dashboard_cliente.php'); exit; }
$stmt = $mysqli->prepare("UPDATE notificaciones SET leido = 1 WHERE id = ? AND usuario_id = ?");
$stmt->bind_param('ii',$id, $_SESSION['user_id']);
$stmt->execute();
$stmt->close();
header('Location: dashboard_cliente.php');
