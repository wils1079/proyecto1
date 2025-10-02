<?php
// admin_asignar.php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id']) || $_SESSION['perfil'] !== 'Administrador') {
    header('Location: login.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = intval($_POST['ticket_id'] ?? 0);
    $asig = ($_POST['asignado_a'] === '') ? NULL : intval($_POST['asignado_a']);
    if ($ticket_id <= 0) die('Ticket inválido.');
    $upd = $mysqli->prepare("UPDATE tickets SET asignado_a = ? WHERE id = ?");
    $upd->bind_param('ii', $asig, $ticket_id);
    $upd->execute();
    $upd->close();
    // fetch ticket to notify client
    $s = $mysqli->prepare("SELECT usuario_id FROM tickets WHERE id = ?");
    $s->bind_param('i',$ticket_id);
    $s->execute();
    $r = $s->get_result()->fetch_assoc();
    $s->close();
    $cliente_id = $r['usuario_id'] ?? null;
    if ($cliente_id) {
        if ($asig) {
            // notify client assigned to tech
            $techname = '';
            $q = $mysqli->prepare("SELECT nombre FROM usuarios WHERE id = ?");
            $q->bind_param('i',$asig);
            $q->execute();
            $qa = $q->get_result()->fetch_assoc();
            $techname = $qa['nombre'] ?? '';
            $q->close();
            $msg = "Tu ticket #$ticket_id fue asignado al técnico: $techname.";
        } else {
            $msg = "Tu ticket #$ticket_id quedó sin asignar.";
        }
        $ins = $mysqli->prepare("INSERT INTO notificaciones (usuario_id, ticket_id, mensaje) VALUES (?,?,?)");
        $ins->bind_param('iis',$cliente_id,$ticket_id,$msg);
        $ins->execute();
        $ins->close();
    }
    header('Location: dashboard_admin.php');
    exit;
}
header('Location: dashboard_admin.php');
