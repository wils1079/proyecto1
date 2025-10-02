<?php
// login.php
session_start();
require_once 'conexion.php';

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = $mysqli->prepare("SELECT id, nombre, password, perfil FROM usuarios WHERE correo = ?");
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows === 1) {
        $u = $res->fetch_assoc();
        if (password_verify($password, $u['password'])) {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['nombre'] = $u['nombre'];
            $_SESSION['perfil'] = $u['perfil'];
            // redirect by profile
            if ($u['perfil'] === 'Administrador') {
                header('Location: dashboard_admin.php');
            } elseif ($u['perfil'] === 'Técnico') {
                header('Location: dashboard_tec.php');
            } else {
                header('Location: dashboard_cliente.php');
            }
            exit;
        } else {
            $error = "Credenciales inválidas.";
        }
    } else {
        $error = "Usuario no encontrado.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Login - Mesa de ayuda</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="card">
  <h2>Iniciar sesión</h2>
  <?php if (!empty($error)): ?><div class="alert"><?=$error?></div><?php endif; ?>
  <form method="POST" action="login.php">
    <input type="email" name="email" placeholder="Correo" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
  </form>
  <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
  <p><a href="index.php">← Volver</a></p>
</div>
</body>
</html>
