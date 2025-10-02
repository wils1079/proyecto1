<?php
// dashboard_tec.php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id']) || $_SESSION['perfil'] !== 'Técnico') {
    header('Location: login.php'); exit;
}
$tid = $_SESSION['user_id'];
// tickets assigned to this tech
$stmt = $mysqli->prepare("SELECT t.*, u.nombre as cliente_nombre FROM tickets t JOIN usuarios u ON t.usuario_id = u.id WHERE t.asignado_a = ? ORDER BY t.creado_at DESC");
$stmt->bind_param('i',$tid);
$stmt->execute();
$tickets = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Dashboard Técnico</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
  <h2>Técnico: <?=htmlspecialchars($_SESSION['nombre'])?></h2>
  <p><a href="logout.php" class="btn-sm">Cerrar sesión</a></p>

  <section>
    <h3>Tickets asignados</h3>
    <?php if ($tickets->num_rows === 0): ?>
      <p>No tienes tickets asignados.</p>
    <?php else: ?>
      <table class="tabla">
        <thead><tr><th>ID</th><th>Título</th><th>Cliente</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php while ($t = $tickets->fetch_assoc()): ?>
          <tr>
            <td><?=$t['id']?></td>
            <td><?=htmlspecialchars($t['titulo'])?></td>
            <td><?=htmlspecialchars($t['cliente_nombre'])?></td>
            <td><?=htmlspecialchars($t['estado'])?></td>
            <td>
              <form style="display:inline" action="tec_cambiar_estado.php" method="POST">
                <input type="hidden" name="ticket_id" value="<?=$t['id']?>">
                <select name="estado">
                  <option value="en_progreso" <?= $t['estado']=='en_progreso' ? 'selected':'' ?>>en_progreso</option>
                  <option value="finalizado" <?= $t['estado']=='finalizado' ? 'selected':'' ?>>finalizado</option>
                </select>
                <button class="btn-sm" type="submit">Actualizar</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>

  <p><a href="index.php">← Volver</a></p>
</div>
</body>
</html>
