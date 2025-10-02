<?php
// registro.php - formulario para crear usuario (envía a guardar.php)
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="card">
    <h2>Registro de Cliente</h2>
    <form action="guardar.php" method="POST">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="email" name="correo" placeholder="Correo" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="hidden" name="perfil" value="Cliente">
        <button type="submit">Registrar</button>
    </form>
    <p><a href="login.php">← Volver al login</a></p>
  </div>
</body>
</html>
