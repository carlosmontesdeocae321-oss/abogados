$<?php
require_once __DIR__ . '/../inc/nocache.php';
require_once __DIR__ . '/../backend/auth.php';
require_once __DIR__ . '/../backend/db.php';
requireAdmin();
$pdo = getPDO();

// simple list of publicaciones
$pubs = $pdo->query('SELECT id, titulo, categoria, fecha, imagen FROM publicaciones ORDER BY fecha DESC')->fetchAll();

$isEditing = false;
$currentAdminName = trim($_SESSION['admin_name'] ?? '');
$editData = [
  'id' => 0,
  'titulo' => '',
  'categoria' => '',
  'subtitulo' => '',
  'autor' => '',
  'estado' => 'Publicado',
  'tiempo_lectura' => '',
  'etiquetas' => '',
  'fuente_url' => '',
  'imagen_alt' => '',
  'fecha' => date('Y-m-d'),
  'descripcion' => '',
  'imagen' => ''
];

if (!empty($_GET['edit'])) {
  $editId = (int) $_GET['edit'];
  if ($editId > 0) {
    $stmtEdit = $pdo->prepare('SELECT id, titulo, descripcion, imagen, categoria, fecha FROM publicaciones WHERE id = ? LIMIT 1');
    $stmtEdit->execute([$editId]);
    $row = $stmtEdit->fetch();

    if ($row) {
      $isEditing = true;
      $editData['id'] = (int) $row['id'];
      $editData['titulo'] = (string) ($row['titulo'] ?? '');
      $editData['categoria'] = (string) ($row['categoria'] ?? '');
      $editData['fecha'] = (string) ($row['fecha'] ?? date('Y-m-d'));
      $editData['imagen'] = (string) ($row['imagen'] ?? '');

      $rawDescripcion = (string) ($row['descripcion'] ?? '');
      $content = $rawDescripcion;
      if (preg_match('/^\[META\]\R(.*?)\R\[\/META\]\R?\R?(.*)$/s', $rawDescripcion, $m)) {
        $metaBlock = trim((string) $m[1]);
        $content = (string) $m[2];
        $metaLines = preg_split('/\R/', $metaBlock);
        foreach ($metaLines as $line) {
          $line = trim((string) $line);
          if ($line === '' || strpos($line, ':') === false) {
            continue;
          }
          [$key, $value] = array_map('trim', explode(':', $line, 2));
          $keyLower = strtolower($key);
          if ($keyLower === 'subtitulo') $editData['subtitulo'] = $value;
          if ($keyLower === 'autor') $editData['autor'] = $value;
          if ($keyLower === 'estado') $editData['estado'] = $value;
          if ($keyLower === 'etiquetas') $editData['etiquetas'] = $value;
          if ($keyLower === 'tiempo de lectura') $editData['tiempo_lectura'] = $value;
          if ($keyLower === 'fuente') $editData['fuente_url'] = $value;
          if ($keyLower === 'alt imagen') $editData['imagen_alt'] = $value;
        }
      }
      $editData['descripcion'] = trim($content);

      // Author is always the logged-in admin for new edits/publications.
      if ($currentAdminName !== '') {
        $editData['autor'] = $currentAdminName;
      }
    }
  }
}

if ($currentAdminName !== '') {
  $editData['autor'] = $currentAdminName;
}

$notice = trim($_GET['notice'] ?? '');
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin - Publicaciones</title>
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
        <a href="/admin/usuarios.php"><i class="fas fa-user-shield"></i> Usuarios admin</a>
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
          <h1>Publicaciones de Estudio Jimenez & Asociados</h1>
          <p>Crea articulos con metadatos editoriales para mejorar calidad y organizacion.</p>
        </div>
      </header>

      <?php if ($notice === 'created'): ?>
        <div class="alert alert-success" role="alert" style="margin-bottom:16px;">Publicacion creada correctamente.</div>
      <?php elseif ($notice === 'updated'): ?>
        <div class="alert alert-success" role="alert" style="margin-bottom:16px;">Publicacion actualizada correctamente.</div>
      <?php elseif ($notice === 'deleted'): ?>
        <div class="alert alert-success" role="alert" style="margin-bottom:16px;">Publicacion eliminada correctamente.</div>
      <?php endif; ?>

      <section class="admin-block">
        <div class="admin-block__head">
          <h3><?php echo $isEditing ? 'Editar publicacion' : 'Crear publicacion'; ?></h3>
        </div>
        <form method="post" action="/backend/publicaciones.php?action=<?php echo $isEditing ? 'update' : 'create'; ?>" enctype="multipart/form-data" class="row" style="margin:0;">
          <input type="hidden" name="return_to" value="/admin/publicaciones.php">
          <?php if ($isEditing): ?>
            <input type="hidden" name="id" value="<?php echo (int) $editData['id']; ?>">
          <?php endif; ?>
          <div class="col-md-8" style="padding-left:0;">
            <div class="form-group"><label>Titulo</label><input type="text" name="titulo" required class="form-control" value="<?php echo htmlspecialchars($editData['titulo']); ?>"></div>
          </div>
          <div class="col-md-4" style="padding-right:0;">
            <div class="form-group"><label>Categoria</label><input type="text" name="categoria" class="form-control" placeholder="Civil, Penal, Laboral..." value="<?php echo htmlspecialchars($editData['categoria']); ?>"></div>
          </div>
          <div class="col-md-8" style="padding-left:0;">
            <div class="form-group"><label>Subtitulo</label><input type="text" name="subtitulo" class="form-control" placeholder="Resumen corto del enfoque del articulo" value="<?php echo htmlspecialchars($editData['subtitulo']); ?>"></div>
          </div>
          <div class="col-md-4" style="padding-right:0;">
            <div class="form-group">
              <label>Autor</label>
              <input type="text" class="form-control" value="<?php echo htmlspecialchars($editData['autor']); ?>" readonly>
              <small class="hint">Se toma automaticamente del usuario administrador en sesion.</small>
            </div>
          </div>
          <div class="col-md-4" style="padding-left:0;">
            <div class="form-group"><label>Fecha</label><input type="date" name="fecha" value="<?php echo htmlspecialchars($editData['fecha']); ?>" class="form-control"></div>
          </div>
          <div class="col-md-4">
            <div class="form-group"><label>Estado</label>
              <select name="estado" class="form-control">
                <option value="Publicado" <?php echo $editData['estado'] === 'Publicado' ? 'selected' : ''; ?>>Publicado</option>
                <option value="Borrador" <?php echo $editData['estado'] === 'Borrador' ? 'selected' : ''; ?>>Borrador</option>
              </select>
            </div>
          </div>
          <div class="col-md-4" style="padding-right:0;">
            <div class="form-group"><label>Tiempo de lectura</label><input type="text" name="tiempo_lectura" class="form-control" placeholder="5 min" value="<?php echo htmlspecialchars($editData['tiempo_lectura']); ?>"></div>
          </div>
          <div class="col-md-6" style="padding-left:0;">
            <div class="form-group"><label>Etiquetas</label><input type="text" name="etiquetas" class="form-control" placeholder="contratos, empresas, cumplimiento" value="<?php echo htmlspecialchars($editData['etiquetas']); ?>"></div>
          </div>
          <div class="col-md-6" style="padding-right:0;">
            <div class="form-group"><label>Fuente / URL de referencia</label><input type="url" name="fuente_url" class="form-control" placeholder="https://..." value="<?php echo htmlspecialchars($editData['fuente_url']); ?>"></div>
          </div>
          <div class="col-md-8" style="padding-left:0;">
            <div class="form-group">
              <label>Multimedia (varias imagenes o 1 video)<?php echo $isEditing ? ' - opcional para reemplazar' : ''; ?></label>
              <input type="file" name="medios[]" accept="image/*,video/*" multiple class="form-control">
              <small class="hint">Regla: puedes subir varias imagenes o un solo video, pero no ambos.</small>
            </div>
          </div>
          <div class="col-md-4" style="padding-right:0;">
            <div class="form-group"><label>Texto alternativo (si es imagen)</label><input type="text" name="imagen_alt" class="form-control" placeholder="Descripcion de la imagen" value="<?php echo htmlspecialchars($editData['imagen_alt']); ?>"></div>
          </div>
          <?php if ($isEditing && !empty($editData['imagen'])): ?>
            <div class="col-md-12" style="padding:0; margin-top:-2px; margin-bottom:8px;">
              <div class="hint">Media actual: <code><?php echo htmlspecialchars($editData['imagen']); ?></code></div>
            </div>
          <?php endif; ?>
          <div class="col-md-12" style="padding:0;">
            <div class="form-group"><label>Descripcion / Contenido</label><textarea name="descripcion" rows="7" class="form-control" placeholder="Desarrolla el contenido del articulo..."><?php echo htmlspecialchars($editData['descripcion']); ?></textarea></div>
          </div>
          <div class="col-md-12" style="padding:0; margin-top:6px; display:flex; gap:10px; flex-wrap:wrap;">
            <button type="submit" class="btn btn-primary"><?php echo $isEditing ? 'Guardar cambios' : 'Crear publicacion'; ?></button>
            <?php if ($isEditing): ?>
              <a class="btn btn-default" href="/admin/publicaciones.php">Cancelar edicion</a>
            <?php endif; ?>
          </div>
        </form>
      </section>

      <section class="admin-block">
        <div class="admin-block__head">
          <h3>Publicaciones creadas</h3>
        </div>
        <?php foreach($pubs as $p): ?>
          <article class="case-card pub-item" style="margin-bottom:12px;display:flex;gap:12px;align-items:center">
            <div style="width:140px">
              <?php if ($p['imagen']): ?>
                <?php $ext = strtolower(pathinfo($p['imagen'], PATHINFO_EXTENSION)); ?>
                <?php if (in_array($ext, ['mp4','webm','mov','m4v'])): ?>
                  <video src="/<?php echo htmlspecialchars($p['imagen']); ?>" style="width:100%;height:90px;object-fit:cover;border-radius:6px" muted controls></video>
                <?php else: ?>
                  <img src="/<?php echo htmlspecialchars($p['imagen']); ?>" style="width:100%;height:90px;object-fit:cover;border-radius:6px" alt="">
                <?php endif; ?>
              <?php else: ?>
                <div style="width:100%;height:90px;background:#f1f1f1;border-radius:6px"></div>
              <?php endif; ?>
            </div>
            <div style="flex:1">
              <h4 style="margin:0 0 6px 0"><?php echo htmlspecialchars($p['titulo']); ?></h4>
              <div class="hint"><?php echo htmlspecialchars($p['categoria']); ?> - <?php echo htmlspecialchars($p['fecha']); ?></div>
            </div>
            <div>
              <a href="/admin/publicaciones.php?edit=<?php echo (int) $p['id']; ?>" class="btn btn-default" style="margin-right:6px;"><i class="fas fa-pen"></i> Editar</a>
              <form method="post" action="/backend/publicaciones.php?action=delete">
                <input type="hidden" name="id" value="<?php echo intval($p['id']); ?>">
                <input type="hidden" name="return_to" value="/admin/publicaciones.php">
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</button>
              </form>
            </div>
          </article>
        <?php endforeach; ?>
      </section>
    </main>
  </div>

  <footer class="admin-page-footer" role="contentinfo">
    <div class="admin-page-footer__inner">
      <span>Panel de administracion - Publicaciones</span>
      <span>&copy; <?php echo date('Y'); ?> Alfonso Jimenez & Asociados</span>
    </div>
  </footer>

    <!-- Scripts -->
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
    <script>
      window.chatbotContext = 'admin-dashboard';
    </script>
    <script src="/js/main.js"></script>

  </body>
  </html>