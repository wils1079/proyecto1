<?php
// dashboard_admin.php
session_start();
require_once 'conexion.php';
if (!isset($_SESSION['user_id']) || $_SESSION['perfil'] !== 'Administrador') {
    header('Location: login.php'); exit;
}
// fetch all tickets
$tickets = $mysqli->query("SELECT t.*, cu.nombre as cliente_nombre, tu.nombre as tecnico_nombre FROM tickets t LEFT JOIN usuarios cu ON t.usuario_id = cu.id LEFT JOIN usuarios tu ON t.asignado_a = tu.id ORDER BY t.creado_at DESC");

// fetch users by role
$clientes = $mysqli->query("SELECT id,nombre,correo FROM usuarios WHERE perfil = 'Cliente' ORDER BY nombre");
$tecnicos = $mysqli->query("SELECT id,nombre,correo FROM usuarios WHERE perfil = 'Técnico' ORDER BY nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Dashboard Administrador</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
  <h2>Administrador: <?=htmlspecialchars($_SESSION['nombre'])?></h2>
  <p><a href="logout.php" class="btn-sm">Cerrar sesión</a></p>

  <section>
    <h3>Tickets (todos)</h3>
    <table class="tabla">
      <thead><tr><th>ID</th><th>Título</th><th>Cliente</th><th>Técnico</th><th>Estado</th><th>Acciones</th></tr></thead>
      <tbody>
      <?php while ($t = $tickets->fetch_assoc()): ?>
        <tr>
          <td><?=$t['id']?></td>
          <td><?=htmlspecialchars($t['titulo'])?></td>
          <td><?=htmlspecialchars($t['cliente_nombre'])?></td>
          <td><?= $t['tecnico_nombre'] ? htmlspecialchars($t['tecnico_nombre']) : 'Sin asignar' ?></td>
          <td><?=htmlspecialchars($t['estado'])?></td>
          <td>
            <form style="display:inline" action="admin_asignar.php" method="POST">
              <input type="hidden" name="ticket_id" value="<?=$t['id']?>">
              <select name="asignado_a">
                <option value="">--Sin asignar--</option>
                <?php
                // reset pointer to list tecnicos
                $tecnicos->data_seek(0);
                while ($r = $tecnicos->fetch_assoc()):
                ?>
                  <option value="<?=$r['id']?>" <?= $t['asignado_a']==$r['id'] ? 'selected' : '' ?>><?=htmlspecialchars($r['nombre'])?></option>
                <?php endwhile; ?>
              </select>
              <button class="btn-sm" type="submit">Asignar</button>
            </form>

            <form style="display:inline" action="admin_estado.php" method="POST">
              <input type="hidden" name="ticket_id" value="<?=$t['id']?>">
              <select name="estado">
                <option value="abierto" <?= $t['estado']=='abierto' ? 'selected':'' ?>>abierto</option>
                <option value="en_progreso" <?= $t['estado']=='en_progreso' ? 'selected':'' ?>>en_progreso</option>
                <option value="finalizado" <?= $t['estado']=='finalizado' ? 'selected':'' ?>>finalizado</option>
                <option value="cerrado" <?= $t['estado']=='cerrado' ? 'selected':'' ?>>cerrado</option>
              </select>
              <button class="btn-sm" type="submit">Cambiar</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </section>

  <section>
    <h3>Clientes</h3>
    <table class="tabla">
      <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Acciones</th></tr></thead>
      <tbody>
      <?php while ($c = $clientes->fetch_assoc()): ?>
        <tr>
          <td><?=$c['id']?></td>
          <td><?=htmlspecialchars($c['nombre'])?></td>
          <td><?=htmlspecialchars($c['correo'])?></td>
          <td>
            <form style="display:inline" action="admin_eliminar_usuario.php" method="POST" onsubmit="return confirm('Eliminar usuario?');">
              <input type="hidden" name="user_id" value="<?=$c['id']?>">
              <button class="btn-sm" type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </section>

  <section>
    <h3>Técnicos</h3>
    <form action="admin_crear_tecnico.php" method="POST">
      <input type="text" name="nombre" placeholder="Nombre técnico" required>
      <input type="email" name="correo" placeholder="Correo técnico" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <button type="submit">Crear técnico</button>
    </form>
    <table class="tabla">
      <thead><tr><th>ID</th><th>Nombre</th><th>Correo</th><th>Acciones</th></tr></thead>
      <tbody>
      <?php
      $tecnicos->data_seek(0);
      while ($t = $tecnicos->fetch_assoc()): ?>
        <tr>
          <td><?=$t['id']?></td>
          <td><?=htmlspecialchars($t['nombre'])?></td>
          <td><?=htmlspecialchars($t['correo'])?></td>
          <td>
            <form style="display:inline" action="admin_eliminar_usuario.php" method="POST" onsubmit="return confirm('Eliminar técnico?');">
              <input type="hidden" name="user_id" value="<?=$t['id']?>">
              <button class="btn-sm" type="submit">Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </section>

  <p><a href="index.php">← Volver</a></p>
</div>
</body>
</html>
