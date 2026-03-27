<?php
require_once __DIR__ . '/../inc/nocache.php';
require_once __DIR__ . '/../backend/auth.php';
require_once __DIR__ . '/../backend/db.php';
requireAdmin();
$pdo = getPDO();

// Get Socio Fundador (first lawyer - ID 1 or by order)
$socioFundador = null;
try {
	$stmt = $pdo->query('SELECT id, nombre, correo, celular, foto, foto_full, cargo, area_practica, descripcion, facebook, instagram, linkedin, twitter, whatsapp FROM abogados ORDER BY id ASC LIMIT 1');
	$socioFundador = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

// stats
$totalConsults = $pdo->query('SELECT COUNT(*) as c FROM consultas_clientes')->fetchColumn();
$totalClients = $pdo->query('SELECT COUNT(DISTINCT email) as c FROM consultas_clientes')->fetchColumn();
$totalPubs = 0;
try{ $totalPubs = (int)$pdo->query('SELECT COUNT(*) FROM publicaciones')->fetchColumn(); }catch(Exception $e){}
$totalCitas = 0;
try{ $totalCitas = (int)$pdo->query('SELECT COUNT(*) FROM citas_consulta')->fetchColumn(); }catch(Exception $e){}
$recent = $pdo->query('SELECT id, nombre, email, telefono, tipo_servicio, mensaje, fecha FROM consultas_clientes ORDER BY fecha DESC LIMIT 8')->fetchAll();
$latest = isset($recent[0]) ? $recent[0] : null;
// recent appointments
$appointments = [];
try{ $appointments = $pdo->query('SELECT id, nombre, telefono, email, tipo_consulta, fecha_preferida, hora_preferida, mensaje, fecha_creacion FROM citas_consulta ORDER BY fecha_creacion DESC LIMIT 8')->fetchAll(); }catch(Exception $e){}

?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin - Dashboard</title>
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
        <a class="active" href="/admin/dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="#sec-socio-fundador" id="btn-open-socio-sidebar" style="color:#d4af37;"><i class="fas fa-crown"></i> Socio Fundador</a>
        <a href="/admin/abogados.php"><i class="fas fa-user-tie"></i> Gestionar abogados</a>
        <a href="/admin/indicators.php"><i class="fas fa-chart-bar"></i> Gestionar Indicadores</a>
        <a href="/admin/usuarios.php"><i class="fas fa-user-shield"></i> Usuarios admin</a>
        <a href="/admin/faqs.php"><i class="fas fa-question-circle"></i> Gestionar Alfonsito</a>
        <a href="#sec-citas"><i class="fas fa-calendar-check"></i> Solicitudes de cita</a>
        <a href="#sec-consultas"><i class="fas fa-comments"></i> Consultas</a>
        <a href="/backend/logout.php"><i class="fas fa-right-from-bracket"></i> Cerrar sesion</a>

        <div class="admin-sidebar__section-title">Sitio web</div>
        <a href="/index.php"><i class="fas fa-house"></i> Inicio</a>
        <a href="/practice.php"><i class="fas fa-scale-balanced"></i> Servicios</a>
        <a href="/servicios-judiciales.html"><i class="fas fa-gavel"></i> Servicios Judiciales</a>
        <a href="/educacion-continua.html"><i class="fas fa-graduation-cap"></i> Educacion Continua</a>
        <a href="/won.php"><i class="fas fa-trophy"></i> Casos Ganados</a>
        <a href="/about.php"><i class="fas fa-users"></i> Acerca de Nosotros</a>
        <a href="/contact.php"><i class="fas fa-envelope"></i> Contacto</a>
        <a href="/"><i class="fas fa-calendar-plus"></i> Solicitar cita</a>
      </nav>
    </aside>

    <main class="admin-main">
      <header class="admin-main__header">
        <div>
          <h1>Dashboard de Estudio Jimenez & Asociados</h1>
          <p>Monitorea consultas, citas y actividad del sitio en tiempo real.</p>
        </div>
        <div class="admin-main__actions">
          <a href="#sec-socio-fundador" id="btn-open-socio" class="btn btn-primary" style="background:#d4af37;border-color:#b9983a;"><i class="fas fa-crown"></i> Socio Fundador</a>
          <a href="/admin/publicaciones.php" class="btn btn-primary">Publicaciones</a>
          <a href="/admin/indicators.php" class="btn btn-primary">Indicadores</a>
          <a href="/admin/abogados.php" class="btn btn-primary">Abogados</a>
          <a href="/admin/usuarios.php" class="btn btn-primary">Usuarios admin</a>
        </div>
      </header>

      <section class="kpi-grid">
        <article class="kpi-card">
          <div class="kpi-icon"><i class="fas fa-inbox"></i></div>
          <div class="kpi-label">Total consultas</div>
          <div class="kpi-value"><?php echo intval($totalConsults); ?></div>
        </article>
        <article class="kpi-card">
          <div class="kpi-icon"><i class="fas fa-calendar-check"></i></div>
          <div class="kpi-label">Citas programadas</div>
          <div class="kpi-value"><?php echo intval($totalCitas); ?></div>
        </article>
        <article class="kpi-card">
          <div class="kpi-icon"><i class="fas fa-user-group"></i></div>
          <div class="kpi-label">Clientes unicos</div>
          <div class="kpi-value"><?php echo intval($totalClients); ?></div>
        </article>
        <article class="kpi-card">
          <div class="kpi-icon"><i class="fas fa-book-open"></i></div>
          <div class="kpi-label">Publicaciones</div>
          <div class="kpi-value"><?php echo intval($totalPubs); ?></div>
        </article>
        <article class="kpi-card">
          <div class="kpi-icon"><i class="fas fa-bolt"></i></div>
          <div class="kpi-label">Actividad reciente</div>
          <div class="kpi-value"><?php echo intval(count($recent)); ?></div>
        </article>
      </section>

      <section id="sec-socio-fundador" class="admin-block" style="display:none;background:linear-gradient(135deg,rgba(212,175,55,0.08),rgba(15,42,68,0.04)); border:2px solid #d4af37; border-left:6px solid #d4af37;">
        <div class="admin-block__head">
          <h3><i class="fas fa-crown" style="color:#d4af37;margin-right:8px;"></i>Socio Fundador</h3>
          <p style="font-size:13px;color:#6b7b90;margin-top:6px;">Gestiona los datos y foto del Socio Fundador que aparece en la sección especial de "Acerca de Nosotros"</p>
        </div>
        <div style="background:#fff;padding:20px;border-radius:8px;">
          <div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
            <button type="button" id="btn-close-socio" class="btn btn-sm" style="background:#f3f4f6;border:1px solid #d0d7e2;color:#0f2a44;"><i class="fas fa-times"></i> Cerrar sección</button>
          </div>
          <?php if($socioFundador): ?>
            <form id="socioForm" enctype="multipart/form-data" style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
              <input type="hidden" name="id" value="<?= $socioFundador['id'] ?>">
              
              <div style="grid-column:1/-1;">
                <div style="display:grid;grid-template-columns:200px 1fr;gap:16px;align-items:start;">
                  <div>
                    <img id="socio-foto-preview" src="<?= $socioFundador['foto_full'] ?: $socioFundador['foto'] ?: '/images/user-2.jpg' ?>" alt="<?= htmlspecialchars($socioFundador['nombre']) ?>" style="width:100%;border-radius:8px;border:2px solid #d4af37;">
                    <div style="margin-top:12px;">
                      <label style="display:block;font-weight:700;color:#0f2a44;margin-bottom:6px;font-size:13px;">Foto Completa</label>
                      <input type="file" name="foto_full" id="socio-foto-full" class="form-control" accept="image/*" style="font-size:12px;">
                      <small style="color:#999;display:block;margin-top:4px;">Para la sección destacada de Socio Fundador</small>
                    </div>
                  </div>
                  <div>
                    <div class="form-group">
                      <label>Nombre completo</label>
                      <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($socioFundador['nombre']) ?>" required>
                    </div>
                    <div class="form-group">
                      <label>Cargo</label>
                      <input type="text" name="cargo" class="form-control" value="<?= htmlspecialchars($socioFundador['cargo'] ?: '') ?>">
                    </div>
                    <div class="form-group">
                      <label>Área de práctica</label>
                      <input type="text" name="area_practica" class="form-control" value="<?= htmlspecialchars($socioFundador['area_practica'] ?: '') ?>">
                    </div>
                  </div>
                </div>
              </div>

              <div>
                <label>Correo electrónico</label>
                <input type="email" name="correo" class="form-control" value="<?= htmlspecialchars($socioFundador['correo'] ?: '') ?>">
              </div>

              <div>
                <label>Número de celular</label>
                <input type="text" name="celular" class="form-control" value="<?= htmlspecialchars($socioFundador['celular'] ?: '') ?>">
              </div>

              <div style="grid-column:1/-1;">
                <label>Descripción profesional</label>
                <textarea name="descripcion" class="form-control" rows="4"><?= htmlspecialchars($socioFundador['descripcion'] ?: '') ?></textarea>
              </div>

              <div>
                <label>Facebook (URL)</label>
                <input type="url" name="facebook" class="form-control" value="<?= htmlspecialchars($socioFundador['facebook'] ?: '') ?>" placeholder="https://facebook.com/usuario">
              </div>

              <div>
                <label>Instagram (URL)</label>
                <input type="url" name="instagram" class="form-control" value="<?= htmlspecialchars($socioFundador['instagram'] ?: '') ?>" placeholder="https://instagram.com/usuario">
              </div>

              <div>
                <label>LinkedIn (URL)</label>
                <input type="url" name="linkedin" class="form-control" value="<?= htmlspecialchars($socioFundador['linkedin'] ?: '') ?>" placeholder="https://linkedin.com/in/usuario">
              </div>

              <div>
                <label>Twitter/X (URL)</label>
                <input type="url" name="twitter" class="form-control" value="<?= htmlspecialchars($socioFundador['twitter'] ?: '') ?>" placeholder="https://x.com/usuario">
              </div>

              <div>
                <label>WhatsApp (número)</label>
                <input type="text" name="whatsapp" class="form-control" value="<?= htmlspecialchars($socioFundador['whatsapp'] ?: '') ?>" placeholder="+593999888777">
              </div>

              <div style="grid-column:1/-1;">
                <label>Foto de perfil (carnet)</label>
                <input type="file" name="foto_carnet" class="form-control" accept="image/*">
                <small style="color:#999;display:block;margin-top:4px;">Foto que se muestra en el grid del equipo</small>
              </div>

              <div style="grid-column:1/-1;border-top:1px solid #eee;padding-top:16px;margin-top:16px;">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar cambios</button>
                <span id="socio-save-msg" style="margin-left:12px;font-size:13px;"></span>
              </div>
            </form>
          <?php else: ?>
            <p class="hint">No hay Socio Fundador registrado aún. Debe crear al menos un abogado primero.</p>
          <?php endif; ?>
        </div>
      </section>

      <section id="sec-citas" class="admin-block">
        <div class="admin-block__head">
          <h3>Solicitudes de cita</h3>
        </div>
        <?php if(empty($appointments)): ?>
          <p class="hint">No hay solicitudes recientes.</p>
        <?php else: ?>
          <div class="appointments-grid">
            <?php foreach($appointments as $a): ?>
              <article class="appointment-card" data-id="<?php echo intval($a['id']); ?>">
                <div>
                  <strong><?php echo htmlspecialchars($a['nombre']); ?></strong>
                  <div class="appointment-meta">
                    <?php echo htmlspecialchars($a['tipo_consulta'] ?: 'General'); ?> • <?php echo htmlspecialchars($a['fecha_preferida']); ?> <?php echo htmlspecialchars($a['hora_preferida']); ?>
                  </div>
                </div>
                <div class="appointment-snippet"><?php echo htmlspecialchars(mb_strimwidth($a['mensaje'],0,140,'...')); ?></div>
                <div class="appointment-actions">
                  <button class="btn btn-sm btn-view" data-nombre="<?php echo htmlspecialchars($a['nombre'], ENT_QUOTES); ?>" data-email="<?php echo htmlspecialchars($a['email'], ENT_QUOTES); ?>" data-telefono="<?php echo htmlspecialchars($a['telefono'], ENT_QUOTES); ?>" data-servicio="<?php echo htmlspecialchars($a['tipo_consulta'], ENT_QUOTES); ?>" data-mensaje="<?php echo htmlspecialchars($a['mensaje'], ENT_QUOTES); ?>" data-fecha="<?php echo htmlspecialchars($a['fecha_creacion'], ENT_QUOTES); ?>"><i class="fas fa-eye"></i> Ver</button>
                  <button class="btn btn-sm btn-danger btn-delete-cita" data-id="<?php echo intval($a['id']); ?>"><i class="fas fa-trash"></i> Eliminar</button>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>

      <section id="sec-consultas" class="admin-block">
        <div class="admin-block__head">
          <h3>Consultas recientes</h3>
        </div>
        <div class="modern-table admin-table-modern">
          <table class="table">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Servicio</th>
                <th>Mensaje</th>
                <th>Fecha</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($recent as $r): ?>
                <tr>
                  <td data-label="Nombre"><?php echo htmlspecialchars($r['nombre']); ?></td>
                  <td data-label="Email"><?php echo htmlspecialchars($r['email']); ?></td>
                  <td data-label="Telefono"><?php echo htmlspecialchars($r['telefono']); ?></td>
                  <td data-label="Servicio"><?php echo htmlspecialchars($r['tipo_servicio']); ?></td>
                  <td class="msg" data-label="Mensaje"><?php echo htmlspecialchars(mb_strimwidth($r['mensaje'],0,110,'...')); ?></td>
                  <td data-label="Fecha"><?php echo htmlspecialchars($r['fecha']); ?></td>
                  <td>
                    <div class="table-actions">
                      <button class="btn btn-primary btn-sm btn-view" data-nombre="<?php echo htmlspecialchars($r['nombre'], ENT_QUOTES); ?>" data-email="<?php echo htmlspecialchars($r['email'], ENT_QUOTES); ?>" data-telefono="<?php echo htmlspecialchars($r['telefono'], ENT_QUOTES); ?>" data-servicio="<?php echo htmlspecialchars($r['tipo_servicio'], ENT_QUOTES); ?>" data-mensaje="<?php echo htmlspecialchars($r['mensaje'], ENT_QUOTES); ?>" data-fecha="<?php echo htmlspecialchars($r['fecha'], ENT_QUOTES); ?>"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-danger btn-sm btn-delete" data-id="<?php echo intval($r['id']); ?>"><i class="fas fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <section id="sec-actividad" class="admin-block">
        <div class="admin-block__head">
          <h3>Actividad reciente</h3>
        </div>
        <ul class="activity-list">
          <?php foreach(array_slice($recent,0,4) as $r): ?>
            <li><i class="fas fa-envelope"></i> Nuevo mensaje recibido de <strong><?php echo htmlspecialchars($r['nombre']); ?></strong></li>
          <?php endforeach; ?>
          <?php foreach(array_slice($appointments,0,2) as $a): ?>
            <li><i class="fas fa-calendar-plus"></i> Nueva cita programada por <strong><?php echo htmlspecialchars($a['nombre']); ?></strong></li>
          <?php endforeach; ?>
          <li><i class="fas fa-user-plus"></i> Gestion de abogados disponible en el modulo de equipo.</li>
          <li><i class="fas fa-pen-nib"></i> Publicaciones disponibles para administrar.</li>
        </ul>
      </section>

      <!-- FAQ management moved to separate admin/faqs.php -->
    </main>
  </div>

    <!-- Modal: Mostrar consulta -->
    <div class="modal fade" id="consultaModal" tabindex="-1" role="dialog" aria-labelledby="consultaModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="consultaModalLabel">Consulta</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p><strong>Nombre:</strong> <span id="m-nombre"></span></p>
            <p><strong>Email:</strong> <span id="m-email"></span> — <strong>Teléfono:</strong> <span id="m-telefono"></span></p>
            <p><strong>Servicio:</strong> <span id="m-servicio"></span></p>
            <p><strong>Fecha:</strong> <span id="m-fecha"></span></p>
            <hr>
            <div id="m-mensaje" style="white-space:pre-wrap"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal: Edit/Create FAQ -->
    <div class="modal fade" id="faqModal" tabindex="-1" role="dialog" aria-labelledby="faqModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <form id="faqForm" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="faqModalLabel">FAQ</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="id" />
            <div class="form-group">
              <label>Pregunta</label>
              <input type="text" name="question" class="form-control" required />
            </div>
            <div class="form-group">
              <label>Respuesta</label>
              <textarea name="answer" class="form-control" rows="4" required></textarea>
            </div>
            <div class="form-group">
              <label>Keywords (coma-separadas)</label>
              <input type="text" name="keywords" class="form-control" />
              <small class="form-text text-muted">Palabras clave que el bot usa para reconocer esta pregunta. Ejemplo: <em>consulta, cita, abogada</em></small>
            </div>
            <div class="form-group">
              <label>Follow-up suggestions</label>
              <div class="followups-container"></div>
              <small class="form-text text-muted">Opciones rápidas que se mostrarán como botones después de la respuesta principal para guiar al usuario a temas relacionados. Cada follow-up contiene una pregunta corta y su respuesta.</small>
              <div style="margin-top:8px"><button type="button" id="addFollowup" class="btn btn-secondary">Agregar follow-up</button></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>

    <footer class="admin-page-footer" role="contentinfo">
      <div class="admin-page-footer__inner">
        <span>Panel administrativo - Alfonso Jimenez & Asociados</span>
        <span>&copy; <?php echo date('Y'); ?> Todos los derechos reservados</span>
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
      // Enables contextual chatbot behavior for admin guidance on this page
      window.chatbotContext = 'admin-dashboard';
    </script>
    <script src="/js/main.js"></script>
    <script>
    (function($){
      function renderFaqs(list){
        var $tbody = $('#faqsTable tbody');
        $tbody.empty();
        if(!list || !list.length){ $tbody.append('<tr><td colspan="6">No hay FAQs registradas.</td></tr>'); return; }
        list.forEach(function(f){
          var shortAns = (f.answer||'').substring(0,120).replace(/\n/g,' ');
          var row = '<tr data-id="'+f.id+'">'
            +'<td>'+f.id+'</td>'
            +'<td>'+$('<div>').text(f.question).html()+'</td>'
            +'<td>'+$('<div>').text(shortAns).html()+'</td>'
            +'<td>'+$('<div>').text(f.keywords||'').html()+'</td>'
            +'<td>'+(f.followups?f.followups.length:0)+'</td>'
            +'<td><div class="table-actions"><button class="btn btn-sm btn-primary btn-edit-faq" data-id="'+f.id+'"><i class="fas fa-edit"></i></button> '
            +'<button class="btn btn-sm btn-danger btn-delete-faq" data-id="'+f.id+'"><i class="fas fa-trash"></i></button></div></td>'
            +'</tr>';
          $tbody.append(row);
        });
      }

      function loadFaqs(){
        $.getJSON('/backend/faqs.php?action=list').done(function(data){ renderFaqs(data); }).fail(function(){ $('#faqsTable tbody').html('<tr><td colspan="6">Error cargando FAQs.</td></tr>'); });
      }

      function openFaqModal(obj){
        var modal = $('#faqModal');
        modal.find('input[name="id"]').val(obj.id||'');
        modal.find('input[name="question"]').val(obj.question||'');
        modal.find('textarea[name="answer"]').val(obj.answer||'');
        modal.find('input[name="keywords"]').val(obj.keywords||'');
        var container = modal.find('.followups-container').empty();
        (obj.followups||[]).forEach(function(fu){
          var item = $('<div class="fu-item" style="display:flex;gap:8px;margin-bottom:8px">')
            .append('<input class="form-control fu-question" placeholder="Pregunta" value="'+$('<div>').text(fu.question).html()+'" />')
            .append('<input class="form-control fu-answer" placeholder="Respuesta" value="'+$('<div>').text(fu.answer).html()+'" />')
            .append('<button class="btn btn-danger btn-remove-fu">Eliminar</button>');
          container.append(item);
        });
        if((obj.followups||[]).length===0){
          // add one empty
          container.append('<div class="fu-item" style="display:flex;gap:8px;margin-bottom:8px"><input class="form-control fu-question" placeholder="Pregunta" /><input class="form-control fu-answer" placeholder="Respuesta" /><button class="btn btn-danger btn-remove-fu">Eliminar</button></div>');
        }
        modal.modal('show');
      }

      $(function(){
        loadFaqs();

        $(document).on('click', '#btnNewFaq', function(){ openFaqModal({followups:[]}); });

        $(document).on('click', '.btn-edit-faq', function(){
          var id = $(this).data('id');
          $.getJSON('/backend/faqs.php?action=get&id='+encodeURIComponent(id)).done(function(d){ openFaqModal(d); }).fail(function(){ alert('No se pudo cargar FAQ'); });
        });

        $(document).on('click', '.btn-delete-faq', function(){
          if(!confirm('Eliminar FAQ?')) return;
          var id = $(this).data('id');
          $.post('/backend/faqs.php?action=delete',{id:id}).done(function(resp){ if(resp && resp.success){ loadFaqs(); } else { alert(resp && resp.error ? resp.error : 'Error'); } }).fail(function(){ alert('Error servidor'); });
        });

        // modal: add followup
        $(document).on('click', '#addFollowup', function(){
          var container = $('#faqModal .followups-container');
          container.append('<div class="fu-item" style="display:flex;gap:8px;margin-bottom:8px"><input class="form-control fu-question" placeholder="Pregunta" /><input class="form-control fu-answer" placeholder="Respuesta" /><button class="btn btn-danger btn-remove-fu">Eliminar</button></div>');
        });

        $(document).on('click', '.btn-remove-fu', function(){ $(this).closest('.fu-item').remove(); });

        // submit faq form
        $(document).on('submit', '#faqForm', function(e){
          e.preventDefault();
          var $f = $(this);
          var id = $f.find('input[name="id"]').val();
          var payload = { question: $f.find('input[name="question"]').val(), answer: $f.find('textarea[name="answer"]').val(), keywords: $f.find('input[name="keywords"]').val() };
          var fups = [];
          $('#faqModal .fu-item').each(function(){ var q = $(this).find('.fu-question').val()||''; var a = $(this).find('.fu-answer').val()||''; if(q||a) fups.push({question:q, answer:a}); });
          payload.followups = JSON.stringify(fups);
          var action = id ? 'update' : 'create';
          if(id) payload.id = id;
          $.post('/backend/faqs.php?action='+action, payload).done(function(resp){ if(resp && resp.success){ $('#faqModal').modal('hide'); loadFaqs(); } else { alert((resp && resp.error) || 'Error'); } }).fail(function(){ alert('Error servidor'); });
        });
      });
    })(jQuery);
    </script>
    <script>
    (function($){
      $(document).ready(function(){
        $(document).on('click', '.btn-view', function(e){
          e.preventDefault();
          var $b = $(this);
          var nombre = $b.data('nombre') || '';
          var email = $b.data('email') || '';
          var telefono = $b.data('telefono') || '';
          var servicio = $b.data('servicio') || '';
          var mensaje = $b.data('mensaje') || '';
          var fecha = $b.data('fecha') || '';

          $('#m-nombre').text(nombre);
          $('#m-email').text(email);
          $('#m-telefono').text(telefono);
          $('#m-servicio').text(servicio);
          $('#m-fecha').text(fecha);
          // escape then convert newlines to <br>
          var escaped = $('<div/>').text(mensaje).html();
          $('#m-mensaje').html(escaped.replace(/\n/g,'<br>'));

          $('#consultaModal').modal('show');
        });

        // Delete cita (appointments) - support removing either <li> or .appointment-card
        $(document).on('click', '.btn-delete-cita', function(e){
          e.preventDefault();
          var $b = $(this);
          var id = $b.data('id');
          if (!id) return;
          if (!confirm('¿Eliminar esta solicitud de cita?')) return;
          $.ajax({
            url: '/backend/delete_cita.php',
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(resp){
              if (resp && resp.success){
                var $card = $b.closest('li');
                if (!$card || !$card.length) $card = $b.closest('.appointment-card');
                $card.fadeOut(200, function(){ $(this).remove(); });
              } else {
                alert((resp && resp.error) ? resp.error : 'No se pudo eliminar.');
              }
            },
            error: function(){ alert('Error al solicitar el borrado.'); }
          });
        });

        // Delete consulta
        $(document).on('click', '.btn-delete', function(e){
          e.preventDefault();
          var $b = $(this);
          var id = $b.data('id');
          if (!id) return;
          if (!confirm('¿Eliminar esta consulta? Esta acción no se puede revertir.')) return;
          $.ajax({
            url: '/backend/delete_consulta.php',
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(resp){
              if (resp && resp.success){
                // remove row from table
                $b.closest('tr').fadeOut(200, function(){ $(this).remove(); });
              } else {
                alert((resp && resp.error) ? resp.error : 'No se pudo eliminar.');
              }
            },
            error: function(xhr){
              alert('Error al solicitar el borrado.');
            }
          });
        });
      });
    })(jQuery);
    </script>

    <!-- Socio Fundador Form Handler -->
    <script>
    $(function(){
      var $socioSection = $('#sec-socio-fundador');

      function openSocioSection(){
        $socioSection.stop(true, true).slideDown(180, function(){
          this.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
      }

      function closeSocioSection(){
        $socioSection.stop(true, true).slideUp(180);
      }

      $('#btn-open-socio, #btn-open-socio-sidebar').on('click', function(e){
        e.preventDefault();
        openSocioSection();
      });

      $('#btn-close-socio').on('click', function(e){
        e.preventDefault();
        closeSocioSection();
      });

      if (window.location.hash === '#sec-socio-fundador') {
        openSocioSection();
      }

      // Handle preview for foto_full
      $('#socio-foto-full').on('change', function(){
        var file = this.files[0];
        if(file && file.type.match('image.*')){
          var reader = new FileReader();
          reader.onload = function(e){
            $('#socio-foto-preview').attr('src', e.target.result);
          };
          reader.readAsDataURL(file);
        }
      });

      // Handle form submit
      $('#socioForm').on('submit', function(e){
        e.preventDefault();
        var $form = $(this);
        var $msg = $('#socio-save-msg');
        var formData = new FormData(this);
        formData.append('action', 'save-socio');

        $msg.text('Guardando...').css('color', '#666');

        $.ajax({
          url: '/backend/abogados.php',
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          dataType: 'json',
          success: function(resp){
            if(resp && resp.ok){
              $msg.text('✓ Guardado correctamente').css('color', '#28a745');
              setTimeout(function(){ $msg.text(''); }, 3000);
              // Refresh foto preview if nuevo upload
              if(document.getElementById('socio-foto-full').files.length > 0){
                // Force reload
              }
            } else {
              $msg.text('Error: ' + (resp && resp.msg ? resp.msg : 'Desconocido')).css('color', '#dc3545');
            }
          },
          error: function(){
            $msg.text('Error de conexión').css('color', '#dc3545');
          }
        });
      });
    });
    </script>

  </body>
  </html>