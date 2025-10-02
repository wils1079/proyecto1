<?php
// dashboard_cliente.php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id']) || $_SESSION['perfil'] !== 'Cliente') {
    header('Location: login.php');
    exit;
}
$uid = $_SESSION['user_id'];

// fetch tickets for this user
$stmt = $mysqli->prepare("SELECT t.*, u.nombre AS tecnico_nombre FROM tickets t LEFT JOIN usuarios u ON t.asignado_a = u.id WHERE t.usuario_id = ? ORDER BY t.creado_at DESC");
$stmt->bind_param('i', $uid);
$stmt->execute();
$tickets = $stmt->get_result();
$stmt->close();

// fetch technicians for assignment options
$techs = $mysqli->query("SELECT id, nombre FROM usuarios WHERE perfil = 'Técnico' ORDER BY nombre ASC");

// fetch notifications
$notif_stmt = $mysqli->prepare("SELECT id, mensaje, leido, creado_at FROM notificaciones WHERE usuario_id = ? ORDER BY creado_at DESC");
$notif_stmt->bind_param('i',$uid);
$notif_stmt->execute();
$notifs = $notif_stmt->get_result();
$notif_stmt->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Dashboard Cliente</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
  <h2>Hola, <?=htmlspecialchars($_SESSION['nombre'])?> (Cliente)</h2>
  <p><a href="logout.php" class="btn-sm">Cerrar sesión</a></p>

  <section>
    <h3>Crear ticket</h3>
    <form action="guardar_ticket.php" method="POST">
      <input type="hidden" name="user_id" value="<?=$uid?>">
      <input type="text" name="titulo" placeholder="Título" required>
      <textarea name="descripcion" placeholder="Descripción" rows="4"></textarea>
      <label>Asignar a técnico (opcional)</label>
      <select name="asignado_a">
        <option value="">-- Sin asignar --</option>
        <?php while ($r = $techs->fetch_assoc()): ?>
          <option value="<?=$r['id']?>"><?=htmlspecialchars($r['nombre'])?></option>
        <?php endwhile; ?>
      </select>
      <button type="submit">Crear ticket</button>
    </form>
  </section>

  <section>
    <h3>Tus tickets</h3>
    <?php if ($tickets->num_rows === 0): ?>
      <p>No has creado tickets aún.</p>
    <?php else: ?>
      <table class="tabla">
        <thead><tr><th>ID</th><th>Título</th><th>Estado</th><th>Técnico</th><th>Creado</th><th>Acciones</th></tr></thead>
        <tbody>
        <?php while ($t = $tickets->fetch_assoc()): ?>
          <tr>
            <td><?=$t['id']?></td>
            <td><?=htmlspecialchars($t['titulo'])?></td>
            <td><?=htmlspecialchars($t['estado'])?></td>
            <td><?= $t['tecnico_nombre'] ? htmlspecialchars($t['tecnico_nombre']) : 'Sin asignar' ?></td>
            <td><?=$t['creado_at']?></td>
            <td>
              <form style="display:inline" action="eliminar_ticket.php" method="POST" onsubmit="return confirm('Eliminar ticket?');">
                <input type="hidden" name="ticket_id" value="<?=$t['id']?>">
                <button class="btn-sm" type="submit">Eliminar</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </section>

  <section>
    <h3>Notificaciones</h3>
    <?php if ($notifs->num_rows === 0): ?>
      <p>No hay notificaciones.</p>
    <?php else: ?>
      <ul>
      <?php while ($n = $notifs->fetch_assoc()): ?>
        <li style="<?= $n['leido'] ? 'opacity:0.7' : 'font-weight:600' ?>">
          <?=htmlspecialchars($n['mensaje'])?> <small class="muted">(<?=$n['creado_at']?>)</small>
          <?php if (!$n['leido']): ?>
            <a href="marcar_leido.php?id=<?=$n['id']?>">Marcar leído</a>
          <?php endif; ?>
        </li>
      <?php endwhile; ?>
      </ul>
    <?php endif; ?>
  </section>

  <p><a href="index.php">← Volver</a></p>
</div>
</body>
</html>
