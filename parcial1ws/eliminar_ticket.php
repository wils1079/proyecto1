<?php
// eliminar_ticket.php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id']) || $_SESSION['perfil'] !== 'Cliente') {
    header('Location: login.php'); exit;
}
$uid = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticket_id = intval($_POST['ticket_id'] ?? 0);
    if ($ticket_id <= 0) die('ID inválido.');
    // verify ownership
    $stmt = $mysqli->prepare("SELECT usuario_id FROM tickets WHERE id = ?");
    $stmt->bind_param('i',$ticket_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 0) { $stmt->close(); die('Ticket no existe.'); }
    $t = $res->fetch_assoc();
    $stmt->close();
    if ($t['usuario_id'] != $uid) die('No puedes eliminar este ticket.');
    $del = $mysqli->prepare("DELETE FROM tickets WHERE id = ?");
    $del->bind_param('i',$ticket_id);
    $del->execute();
    $del->close();
    header('Location: dashboard_cliente.php');
    exit;
}
header('Location: dashboard_cliente.php');
