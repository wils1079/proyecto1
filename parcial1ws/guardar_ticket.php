<?php
// guardar_ticket.php - guarda el ticket en la tabla tickets
require_once 'conexion.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$user_id = intval($_POST['user_id'] ?? 0);
$titulo = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$asignado = ($_POST['asignado_a'] ?? '') === '' ? NULL : intval($_POST['asignado_a']);

if ($user_id <= 0 || $titulo === '') {
    die('Datos incompletos o inválidos.');
}

$ins = $mysqli->prepare("INSERT INTO tickets (usuario_id, titulo, descripcion, asignado_a) VALUES (?,?,?,?)");
$ins->bind_param('issi',$user_id,$titulo,$descripcion,$asignado);
if ($ins->execute()) {
    $ticket_id = $ins->insert_id;
    $ins->close();
    // notify client that ticket created (optional)
    $msg = "Se creó el ticket #$ticket_id.";
    $n = $mysqli->prepare("INSERT INTO notificaciones (usuario_id, ticket_id, mensaje) VALUES (?,?,?)");
    $n->bind_param('iis',$user_id,$ticket_id,$msg);
    $n->execute();
    $n->close();
    // if assigned to tech, notify tech
    if ($asignado) {
        $techmsg = "Se te asignó el ticket #$ticket_id.";
        $nt = $mysqli->prepare("INSERT INTO notificaciones (usuario_id, ticket_id, mensaje) VALUES (?,?,?)");
        $nt->bind_param('iis',$asignado,$ticket_id,$techmsg);
        $nt->execute();
        $nt->close();
    }
    header('Location: dashboard_cliente.php');
    exit;
} else {
    $ins->close();
    die('Error al guardar ticket: ' . $mysqli->error);
}
