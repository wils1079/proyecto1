<?php
// tec_cambiar_estado.php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id']) || $_SESSION['perfil'] !== 'Técnico') {
    header('Location: login.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = intval($_POST['ticket_id'] ?? 0);
    $estado = $_POST['estado'] ?? '';
    $allowed = ['en_progreso','finalizado'];
    if ($ticket_id<=0 || !in_array($estado,$allowed)) die('Datos inválidos.');
    // verify assigned to this tech
    $s = $mysqli->prepare("SELECT usuario_id, asignado_a FROM tickets WHERE id = ?");
    $s->bind_param('i',$ticket_id);
    $s->execute();
    $r = $s->get_result()->fetch_assoc();
    $s->close();
    if ($r['asignado_a'] != $_SESSION['user_id']) die('No autorizado.');
    $upd = $mysqli->prepare("UPDATE tickets SET estado = ? WHERE id = ?");
    $upd->bind_param('si',$estado,$ticket_id);
    $upd->execute();
    $upd->close();
    // notify client
    $cliente_id = $r['usuario_id'];
    $msg = "El técnico actualizó el estado de tu ticket #$ticket_id a: $estado.";
    $ins = $mysqli->prepare("INSERT INTO notificaciones (usuario_id, ticket_id, mensaje) VALUES (?,?,?)");
    $ins->bind_param('iis',$cliente_id,$ticket_id,$msg);
    $ins->execute();
    $ins->close();
    header('Location: dashboard_tec.php');
    exit;
}
header('Location: dashboard_tec.php');
