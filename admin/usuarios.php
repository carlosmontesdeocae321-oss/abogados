<?php
require_once __DIR__ . '/../inc/nocache.php';
require_once __DIR__ . '/../backend/auth.php';
require_once __DIR__ . '/../backend/db.php';
requireAdmin();
$pdo = getPDO();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = trim($_POST['action'] ?? '');

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if ($name === '') $errors[] = 'El nombre es requerido.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalido.';
        if (strlen($password) < 8) $errors[] = 'La contrasena debe tener al menos 8 caracteres.';
        if ($password !== $passwordConfirm) $errors[] = 'Las contrasenas no coinciden.';

        if (empty($errors)) {
            $check = $pdo->prepare('SELECT id FROM usuarios_admin WHERE email = ? LIMIT 1');
            $check->execute([$email]);
            if ($check->fetch()) {
                $errors[] = 'Ya existe un usuario administrador con ese email.';
            } else {
                $hash = password_hash($password, PASSWORD_BCRYPT);
                $ins = $pdo->prepare('INSERT INTO usuarios_admin (name, email, password_hash) VALUES (?, ?, ?)');
                $ins->execute([$name, $email, $hash]);
                header('Location: /admin/usuarios.php?notice=created');
                exit;
            }
        }
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $errors[] = 'Usuario invalido.';
        } elseif (!empty($_SESSION['admin_id']) && $id === (int)$_SESSION['admin_id']) {
            $errors[] = 'No puedes eliminar tu propio usuario mientras estas autenticado.';
        } else {
            $del = $pdo->prepare('DELETE FROM usuarios_admin WHERE id = ? LIMIT 1');
            $del->execute([$id]);
            header('Location: /admin/usuarios.php?notice=deleted');
            exit;
        }
    }
}

$notice = trim($_GET['notice'] ?? '');
$admins = $pdo->query('SELECT id, name, email, created_at FROM usuarios_admin ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin - Usuarios</title>
  <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700" rel="stylesheet">
  <link rel="stylesheet" href="/css/animate.css">
  <link rel="stylesheet" href="/css/icomoon.css">
  <link rel="stylesheet" href="/css/bootstrap.css">
  <link rel="stylesheet" href="/css/magnific-popup.css">
  <link rel="stylesheet" href="/css/owl.carousel.min.css">
  <link rel="stylesheet" href="/css/owl.theme.default.min.css">
  <link rel="stylesheet" href="/css/flexslider.css">
  <link rel="stylesheet" href="/fonts/flaticon/font/flaticon.css">
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="shortcut icon" href="/images/ISOTIPO.jpg" type="image/jpeg">
  <script src="/js/modernizr-2.6.2.min.js"></script>
</head>
<body>
  <div class="colorlib-loader"></div>
  <div class="admin-dashboard">
    <aside class="admin-sidebar">
      <div class="admin-sidebar__brand">
        <img src="/images/ISOTIPO.jpg" alt="Estudio Jimenez & Asociados" class="admin-sidebar__logo">
        <div>
          <div class="admin-sidebar__title">Estudio J&A</div>
          <div class="admin-sidebar__sub">Panel administrativo</div>
        </div>
      </div>
      <nav class="admin-sidebar__nav">
        <a href="/admin/dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="/admin/abogados.php"><i class="fas fa-user-tie"></i> Gestionar abogados</a>
        <a href="/admin/indicators.php"><i class="fas fa-chart-bar"></i> Gestionar Indicadores</a>
        <a href="/admin/faqs.php"><i class="fas fa-question-circle"></i> Gestionar FAQs</a>
        <a class="active" href="/admin/usuarios.php"><i class="fas fa-user-shield"></i> Usuarios admin</a>
        <a href="/admin/dashboard.php#sec-citas"><i class="fas fa-calendar-check"></i> Solicitudes de cita</a>
        <a href="/admin/dashboard.php#sec-consultas"><i class="fas fa-comments"></i> Consultas</a>
        <a href="/backend/logout.php"><i class="fas fa-right-from-bracket"></i> Cerrar sesion</a>

        <div class="admin-sidebar__section-title">Sitio web</div>
        <a href="/index.php"><i class="fas fa-house"></i> Inicio</a>
        <a href="/practice.php"><i class="fas fa-scale-balanced"></i> Servicios</a>
        <a href="/won.php"><i class="fas fa-trophy"></i> Casos Ganados</a>
        <a href="/about.php"><i class="fas fa-users"></i> Acerca de Nosotros</a>
        <a href="/contact.php"><i class="fas fa-envelope"></i> Contacto</a>
        <a href="/"><i class="fas fa-calendar-plus"></i> Solicitar cita</a>
      </nav>
    </aside>

    <main class="admin-main">
      <header class="admin-main__header">
        <div>
          <h1>Usuarios administradores</h1>
          <p>Crea accesos para que otros administradores gestionen dashboard, abogados y publicaciones.</p>
        </div>
      </header>

      <?php if ($notice === 'created'): ?>
        <div class="alert alert-success" role="alert" style="margin-bottom:16px;">Usuario administrador creado correctamente.</div>
      <?php elseif ($notice === 'deleted'): ?>
        <div class="alert alert-success" role="alert" style="margin-bottom:16px;">Usuario administrador eliminado correctamente.</div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-danger" role="alert" style="margin-bottom:16px;">
          <?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?>
        </div>
      <?php endif; ?>

      <section class="admin-block">
        <div class="admin-block__head">
          <h3>Crear usuario admin</h3>
        </div>
        <form method="post" action="" class="row" style="margin:0;">
          <input type="hidden" name="action" value="create">
          <div class="col-md-6" style="padding-left:0;">
            <div class="form-group">
              <label>Nombre</label>
              <input type="text" name="name" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6" style="padding-right:0;">
            <div class="form-group">
              <label>Email</label>
              <input type="email" name="email" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6" style="padding-left:0;">
            <div class="form-group">
              <label>Contrasena</label>
              <input type="password" name="password" class="form-control" minlength="8" required>
            </div>
          </div>
          <div class="col-md-6" style="padding-right:0;">
            <div class="form-group">
              <label>Confirmar contrasena</label>
              <input type="password" name="password_confirm" class="form-control" minlength="8" required>
            </div>
          </div>
          <div class="col-md-12" style="padding:0; margin-top:6px;">
            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus"></i> Crear usuario</button>
          </div>
        </form>
      </section>

      <section class="admin-block">
        <div class="admin-block__head">
          <h3>Administradores registrados</h3>
        </div>

        <div class="table-responsive">
          <table class="table table-striped table-bordered modern-table admin-table">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Fecha de alta</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($admins as $u): ?>
              <tr>
                <td><?php echo htmlspecialchars($u['name'] ?: 'Sin nombre'); ?></td>
                <td><?php echo htmlspecialchars($u['email']); ?></td>
                <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                <td>
                  <?php if (!empty($_SESSION['admin_id']) && (int)$u['id'] === (int)$_SESSION['admin_id']): ?>
                    <span class="hint">Sesion actual</span>
                  <?php else: ?>
                    <form method="post" action="" onsubmit="return confirm('¿Eliminar este usuario administrador?');" style="display:inline;">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?php echo (int)$u['id']; ?>">
                      <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Eliminar</button>
                    </form>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <footer class="admin-page-footer" role="contentinfo">
    <div class="admin-page-footer__inner">
      <span>Panel de administracion - Usuarios</span>
      <span>&copy; <?php echo date('Y'); ?> Alfonso Jimenez & Asociados</span>
    </div>
  </footer>

  <script src="/js/jquery.min.js"></script>
  <script src="/js/jquery.easing.1.3.js"></script>
  <script src="/js/bootstrap.min.js"></script>
  <script src="/js/jquery.waypoints.min.js"></script>
  <script src="/js/jquery.stellar.min.js"></script>
  <script src="/js/owl.carousel.min.js"></script>
  <script src="/js/jquery.flexslider-min.js"></script>
  <script src="/js/jquery.countTo.js"></script>
  <script src="/js/jquery.magnific-popup.min.js"></script>
  <script src="/js/magnific-popup-options.js"></script>
  <script src="/js/leaflet_map.js"></script>
  <script src="/js/main.js"></script>
</body>
</html>
