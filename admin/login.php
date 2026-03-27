<?php
require_once __DIR__ . '/../inc/nocache.php';
require_once __DIR__ . '/../backend/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email) $errors[] = 'Email requerido';
    if (!$password) $errors[] = 'Contraseña requerida';
    if (empty($errors)){
        if (adminLogin($email, $password)){
          $return = '/admin/dashboard.php';
          if (!empty($_GET['return'])){
            $r = $_GET['return'];
            // basic validation: must be a local path
            if (is_string($r) && strlen($r) && $r[0] === '/' && strpos($r, '://') === false){
              $return = $r;
            }
          }
          header('Location: ' . $return); exit;
        } else {
          $errors[] = 'Credenciales inválidas';
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin - Login</title>
  <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700|Source+Sans+3:400,600,700" rel="stylesheet">
  <link rel="stylesheet" href="/css/animate.css">
  <link rel="stylesheet" href="/css/icomoon.css">
  <link rel="stylesheet" href="/css/bootstrap.css">
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="shortcut icon" href="/images/ISOTIPO.jpg" type="image/jpeg">
  <script src="/js/modernizr-2.6.2.min.js"></script>
</head>
<body class="admin-auth-page">
  <div class="admin-auth-bg" aria-hidden="true"></div>
  <main class="admin-auth-wrap" role="main">
    <section class="admin-auth-card">
      <header class="admin-auth-head">
        <img src="/images/logo.png" alt="Estudio Jimenez & Asociados" class="admin-auth-logo">
        <h1>Panel Administrativo</h1>
        <p>Acceso exclusivo para administradores</p>
      </header>

      <?php if (!empty($errors)): ?>
        <div class="admin-auth-errors" role="alert"><?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?></div>
      <?php endif; ?>

      <form method="post" action="" class="admin-auth-form">
        <div class="admin-input-group">
          <label for="login-email">Email</label>
          <div class="admin-input-wrap">
            <i class="fas fa-envelope"></i>
            <input id="login-email" type="email" name="email" required class="form-control" autocomplete="username">
          </div>
        </div>

        <div class="admin-input-group">
          <label for="login-password">Contraseña</label>
          <div class="admin-input-wrap">
            <i class="fas fa-lock"></i>
            <input id="login-password" type="password" name="password" required class="form-control" autocomplete="current-password">
          </div>
        </div>

        <button class="btn admin-auth-btn" type="submit">Iniciar sesion</button>
      </form>

      <footer class="admin-auth-foot">
        <span>&copy; Panel administrativo</span>
        <span>Acceso restringido</span>
      </footer>
    </section>
  </main>

  <script src="/js/jquery.min.js"></script>
  <script src="/js/jquery.easing.1.3.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/main.js"></script>
</body>
</html>