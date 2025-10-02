<?php
// admin_estado.php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id']) || $_SESSION['perfil'] !== 'Administrador') {
    header('Location: login.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = intval($_POST['ticket_id'] ?? 0);
    $estado = $_POST['estado'] ?? '';
    $allowed = ['abierto','en_progreso','finalizado','cerrado'];
    if ($ticket_id <= 0 || !in_array($estado, $allowed)) die('Datos inválidos.');
    $upd = $mysqli->prepare("UPDATE tickets SET estado = ? WHERE id = ?");
    $upd->bind_param('si',$estado,$ticket_id);
    $upd->execute();
    $upd->close();
    // notify client
    $s = $mysqli->prepare("SELECT usuario_id FROM tickets WHERE id = ?");
    $s->bind_param('i',$ticket_id);
    $s->execute();
    $r = $s->get_result()->fetch_assoc();
    $s->close();
    $cliente_id = $r['usuario_id'] ?? null;
    if ($cliente_id) {
        $msg = "El estado de tu ticket #$ticket_id cambió a: $estado.";
        $ins = $mysqli->prepare("INSERT INTO notificaciones (usuario_id, ticket_id, mensaje) VALUES (?,?,?)");
        $ins->bind_param('iis',$cliente_id,$ticket_id,$msg);
        $ins->execute();
        $ins->close();
    }
    header('Location: dashboard_admin.php');
    exit;
}
header('Location: dashboard_admin.php');
