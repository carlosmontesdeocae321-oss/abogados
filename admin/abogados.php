<?php
require_once __DIR__ . '/../inc/nocache.php';
require_once __DIR__ . '/../backend/auth.php';
require_once __DIR__ . '/../backend/db.php';
requireAdmin();
$pdo = getPDO();
$abogados = $pdo->query('SELECT id,nombre,correo,celular,foto,foto_carnet,foto_full,area_practica,descripcion,formacion,experiencia,docencia,publicaciones,distinciones,facebook,instagram,linkedin,twitter,whatsapp,cargo,destacado,fecha_creacion FROM abogados ORDER BY fecha_creacion DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin - Gestión de abogados</title>
  <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700" rel="stylesheet">
  <link rel="stylesheet" href="/css/bootstrap.css?v=20260312">
  <link rel="stylesheet" href="/css/style.css?v=20260312">
  <link rel="stylesheet" href="/css/admin.css?v=20260312">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="shortcut icon" href="/images/ISOTIPO.jpg" type="image/jpeg">
  <script src="/js/jquery.min.js?v=20260312"></script>
  <script src="/js/bootstrap.min.js?v=20260312"></script>
  <style>
  /* Compact cards for abogados list */
  .cards-grid{ display:grid; grid-template-columns:repeat(2,1fr); gap:12px; max-width:1100px; margin:0 auto; }
  .lawyer-card{ display:flex; gap:12px; align-items:flex-start; padding:10px; border:1px solid #ececec; border-radius:8px; background:#fff; }
  .lawyer-card .avatar{ width:48px; height:48px; border-radius:50%; object-fit:cover; flex:0 0 48px }
  .lawyer-meta{ flex:1; min-width:0 }
  .lawyer-name{ font-weight:700; font-family:'Playfair Display', serif; color:#0f2a44; white-space:nowrap; overflow:hidden; text-overflow:ellipsis }
  .lawyer-cargo{ color:#6b7b90; font-size:13px }
  .card-actions{ display:flex; gap:6px; align-items:center }
  .card-actions .btn{ padding:4px 8px; font-size:12px }
  .card-details{ display:none; margin-top:8px; border-top:1px dashed #eee; padding-top:8px; font-size:13px; color:#444 }
  @media(max-width:900px){ .cards-grid{ grid-template-columns:1fr } }
  </style>
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
      <a class="active" href="/admin/abogados.php"><i class="fas fa-user-tie"></i> Gestionar abogados</a>
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
        <h1>Gestion de abogados</h1>
        <p>Administra los perfiles del equipo legal de Estudio Jimenez & Asociados.</p>
      </div>
      <div class="admin-main__actions">
        <button id="btnAdd" class="btn btn-primary">Agregar abogado</button>
        <a href="/admin/dashboard.php" class="btn btn-default">Volver al Dashboard</a>
      </div>
    </header>

    <section class="admin-block">
      <div class="admin-block__head">
        <h3>Lista de abogados</h3>
      </div>
      <div class="table-tools" style="display:flex;justify-content:space-between;align-items:center;margin:12px 0 16px;gap:12px;">
        <div>
          <input id="searchAbogados" class="form-control" placeholder="Buscar por nombre, correo o área" style="min-width:260px;">
        </div>
        <div class="pagination-controls" id="abogadosPagination" aria-hidden="true"></div>
      </div>
      <div class="table-responsive">
        <div class="cards-grid" id="abogadosCards">
        <?php foreach($abogados as $a): ?>
          <?php $thumb = $a['foto_carnet'] ? $a['foto_carnet'] : ($a['foto'] ? $a['foto'] : '/images/user-2.jpg'); ?>
          <div class="lawyer-card" data-id="<?= $a['id'] ?>">
            <img src="<?= $thumb ?>" class="avatar" alt="">
            <div class="lawyer-meta">
              <div class="lawyer-name"><?= htmlspecialchars($a['nombre']) ?></div>
              <div class="lawyer-cargo"><?= htmlspecialchars($a['cargo']) ?></div>
              <div style="font-size:13px;color:#6b7b90;"><?= htmlspecialchars($a['area_practica']) ?></div>
              <div class="card-actions" style="margin-top:8px">
                <button class="btn btn-sm btn-default btn-edit">Editar</button>
                <button class="btn btn-sm btn-info btn-details">Ver</button>
                <button class="btn btn-sm btn-danger btn-delete">Eliminar</button>
              </div>
              <div class="card-details">
                <div style="display:flex;gap:12px;flex-wrap:wrap">
                  <div style="min-width:220px"><strong>Correo:</strong> <div><?= $a['correo'] ? '<a href="mailto:'.htmlspecialchars($a['correo']).'">'.htmlspecialchars($a['correo']).'</a>' : '<em style="color:#999">(vacío)</em>' ?></div></div>
                  <div style="min-width:160px"><strong>Celular:</strong> <div><?= $a['celular'] ? htmlspecialchars($a['celular']) : '<em style="color:#999">(vacío)</em>' ?></div></div>
                  <div style="min-width:120px"><strong>Destacado:</strong> <div><?php
                    if((int)$a['destacado'] === 2) echo 'Segundo socio fundador';
                    else if((int)$a['destacado'] === 1) echo 'Destacado';
                    else echo 'No';
                  ?></div></div>
                </div>
                <div style="margin-top:8px"><strong>Descripción:</strong>
                  <div style="margin-top:6px;color:#333;"><?= $a['descripcion'] ? nl2br(htmlspecialchars($a['descripcion'])) : '<em style="color:#999">(vacío)</em>' ?></div>
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:8px;">
                  <div><strong>Formación</strong>
                    <div style="margin-top:6px;color:#444"><?= $a['formacion'] ? nl2br(htmlspecialchars($a['formacion'])) : '<em style="color:#999">(vacío)</em>' ?></div>
                  </div>
                  <div><strong>Experiencia</strong>
                    <div style="margin-top:6px;color:#444"><?= $a['experiencia'] ? nl2br(htmlspecialchars($a['experiencia'])) : '<em style="color:#999">(vacío)</em>' ?></div>
                  </div>
                  <div><strong>Docencia</strong>
                    <div style="margin-top:6px;color:#444"><?= $a['docencia'] ? nl2br(htmlspecialchars($a['docencia'])) : '<em style="color:#999">(vacío)</em>' ?></div>
                  </div>
                  <div><strong>Publicaciones</strong>
                    <div style="margin-top:6px;color:#444"><?= $a['publicaciones'] ? nl2br(htmlspecialchars($a['publicaciones'])) : '<em style="color:#999">(vacío)</em>' ?></div>
                  </div>
                </div>
                <div style="margin-top:8px"><strong>Distinciones</strong>
                  <div style="margin-top:6px;color:#444"><?= $a['distinciones'] ? nl2br(htmlspecialchars($a['distinciones'])) : '<em style="color:#999">(vacío)</em>' ?></div>
                </div>
                <div style="margin-top:8px"><strong>Redes</strong>
                  <div style="margin-top:6px;display:flex;gap:8px;flex-wrap:wrap">
                    <?= $a['facebook'] ? '<a href="'.htmlspecialchars($a['facebook']).'" target="_blank" class="btn btn-xs btn-outline-primary">FB</a>' : '<span style="color:#999">FB</span>' ?>
                    <?= $a['instagram'] ? '<a href="'.htmlspecialchars($a['instagram']).'" target="_blank" class="btn btn-xs btn-outline-primary">IG</a>' : '<span style="color:#999">IG</span>' ?>
                    <?= $a['linkedin'] ? '<a href="'.htmlspecialchars($a['linkedin']).'" target="_blank" class="btn btn-xs btn-outline-primary">LI</a>' : '<span style="color:#999">LI</span>' ?>
                    <?= $a['twitter'] ? '<a href="'.htmlspecialchars($a['twitter']).'" target="_blank" class="btn btn-xs btn-outline-primary">X</a>' : '<span style="color:#999">X</span>' ?>
                    <?= $a['whatsapp'] ? '<a href="'.htmlspecialchars($a['whatsapp']).'" target="_blank" class="btn btn-xs btn-outline-success">WA</a>' : '<span style="color:#999">WA</span>' ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      </div>
    </section>
  </main>
</div>

<!-- Slide-in panel for Add / Edit (full dashboard stays visible) -->
<div id="abogadoPanel" style="position:fixed;top:60px;right:-100%;width:820px;bottom:0;background:#ffffff;box-shadow: -8px 0 24px rgba(0,0,0,0.12);transition:right .28s ease;z-index:1200;overflow:auto;padding:20px;">
  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
    <h3 id="panelTitle">Agregar abogado</h3>
    <div>
      <button id="panelClose" class="btn btn-light">Cerrar</button>
    </div>
  </div>
  <form id="abogadoPanelForm" enctype="multipart/form-data">
    <input type="hidden" name="id" id="panel_ab_id">
    <div class="form-group"><label>Nombre completo</label><input name="nombre" id="panel_ab_nombre" class="form-control" required></div>
    <div class="form-row">
      <div class="form-group col"><label>Correo electrónico</label><input name="correo" id="panel_ab_correo" class="form-control" type="email"></div>
      <div class="form-group col"><label>Número de celular</label><input name="celular" id="panel_ab_celular" class="form-control"></div>
    </div>
    <div class="form-row">
      <div class="form-group col"><label>Cargo</label><input name="cargo" id="panel_ab_cargo" class="form-control"></div>
      <div class="form-group col"><label>Área de práctica</label><input name="area_practica" id="panel_ab_area" class="form-control"></div>
    </div>
    <div class="form-group">
      <label>Rol destacado</label>
      <select name="destacado" id="panel_ab_destacado" class="form-control">
        <option value="0">Sin destacado</option>
        <option value="1">Destacado (home/cards principales)</option>
        <option value="2">Segundo socio fundador (About)</option>
      </select>
    </div>
    <div class="form-group"><label>Descripción profesional</label><textarea name="descripcion" id="panel_ab_descripcion" class="form-control" rows="4"></textarea></div>
    <div class="form-row">
      <div class="form-group col"><label>Formación</label><textarea name="formacion" id="panel_ab_formacion" class="form-control" rows="3"></textarea></div>
      <div class="form-group col"><label>Experiencia</label><textarea name="experiencia" id="panel_ab_experiencia" class="form-control" rows="3"></textarea></div>
    </div>
    <div class="form-row">
      <div class="form-group col"><label>Docencia</label><textarea name="docencia" id="panel_ab_docencia" class="form-control" rows="2"></textarea></div>
      <div class="form-group col"><label>Publicaciones</label><textarea name="publicaciones" id="panel_ab_publicaciones" class="form-control" rows="2"></textarea></div>
    </div>
    <div class="form-group"><label>Distinciones / Premios</label><textarea name="distinciones" id="panel_ab_distinciones" class="form-control" rows="2"></textarea></div>
    <div class="form-row">
      <div class="form-group col"><label>Facebook (url)</label><input name="facebook" id="panel_ab_facebook" class="form-control" type="url"></div>
      <div class="form-group col"><label>Instagram (url)</label><input name="instagram" id="panel_ab_instagram" class="form-control" type="url"></div>
    </div>
    <div class="form-row">
      <div class="form-group col"><label>LinkedIn (url)</label><input name="linkedin" id="panel_ab_linkedin" class="form-control" type="url"></div>
      <div class="form-group col"><label>Twitter/X (url)</label><input name="twitter" id="panel_ab_twitter" class="form-control" type="url"></div>
    </div>
    <div class="form-row">
      <div class="form-group col"><label>WhatsApp</label><input name="whatsapp" id="panel_ab_whatsapp" class="form-control" type="text"></div>
      <div class="form-group col"><label>Foto de perfil</label><input name="foto_carnet" id="panel_ab_foto_carnet" class="form-control" type="file" accept="image/*"></div>
      <div class="form-group col"><label>Foto completa (Socio Fundador)</label><input name="foto_full" id="panel_ab_foto_full" class="form-control" type="file" accept="image/*"></div>
    </div>
    <div class="form-row">
      <div class="form-group col" style="display:flex;align-items:center;gap:8px;">
        <button type="submit" class="btn btn-primary">Guardar</button>
        <button type="button" id="panelCancel" class="btn btn-light">Cancelar</button>
      </div>
    </div>
  </form>
</div>

  <!-- Scripts: include site scripts so loader and behaviors work -->
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

<!-- Modal -->
<div id="abogadoModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form id="abogadoForm" enctype="multipart/form-data">
      <div class="modal-header">
        <h5 class="modal-title">Agregar/Editar abogado</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="ab_id">
        <div class="form-group"><label>Nombre completo</label><input name="nombre" id="ab_nombre" class="form-control" required></div>
        <div class="form-group"><label>Correo electrónico</label><input name="correo" id="ab_correo" class="form-control" type="email"></div>
        <div class="form-group"><label>Número de celular</label><input name="celular" id="ab_celular" class="form-control"></div>
        <div class="form-group">
          <label>Foto de perfil</label>
          <input name="foto_carnet" id="ab_foto_carnet" class="form-control" type="file" accept="image/*">
          <div style="margin-top:8px;"><img id="ab_preview_carnet" src="/images/user-2.jpg" alt="Perfil" style="max-height:80px; border-radius:4px;"></div>
        </div>
        <div class="form-group"><label>Facebook (url)</label><input name="facebook" id="ab_facebook" class="form-control" type="url" placeholder="https://facebook.com/usuario"></div>
        <div class="form-group"><label>Instagram (url)</label><input name="instagram" id="ab_instagram" class="form-control" type="url" placeholder="https://instagram.com/usuario"></div>
        <div class="form-group"><label>LinkedIn (url)</label><input name="linkedin" id="ab_linkedin" class="form-control" type="url" placeholder="https://linkedin.com/in/usuario"></div>
        <div class="form-group"><label>Twitter/X (url)</label><input name="twitter" id="ab_twitter" class="form-control" type="url" placeholder="https://x.com/usuario"></div>
        <div class="form-group"><label>WhatsApp (número)</label><input name="whatsapp" id="ab_whatsapp" class="form-control" type="text" placeholder="+593999888777"></div>
        <div class="form-group"><label>Cargo</label>
          <select name="cargo" id="ab_cargo" class="form-control">
            <option value="">(Sin cargo)</option>
            <option>CEO / Socio director</option>
            <option>Gerente general</option>
            <option>Socio</option>
            <option>Abogado senior</option>
            <option>Abogado asociado</option>
            <option>Consultor</option>
          </select>
        </div>
        <div class="form-group">
          <label><input type="checkbox" name="destacado" id="ab_destacado" value="1"> Mostrar en las 2 primeras tarjetas</label>
        </div>
        <div class="form-group"><label>Área de práctica</label>
          <select name="area_practica" id="ab_area" class="form-control">
            <option>Derecho civil</option>
            <option>Derecho penal</option>
            <option>Derecho corporativo</option>
            <option>Derecho inmobiliario</option>
            <option>Derecho laboral</option>
            <option>Otro</option>
          </select>
        </div>
        <div class="form-group"><label>Descripción profesional</label><textarea name="descripcion" id="ab_descripcion" class="form-control" rows="4"></textarea></div>
        <div class="form-group"><label>Formación</label><textarea name="formacion" id="ab_formacion" class="form-control" rows="3"></textarea></div>
        <div class="form-group"><label>Experiencia profesional</label><textarea name="experiencia" id="ab_experiencia" class="form-control" rows="3"></textarea></div>
        <div class="form-group"><label>Docencia</label><textarea name="docencia" id="ab_docencia" class="form-control" rows="2"></textarea></div>
        <div class="form-group"><label>Publicaciones</label><textarea name="publicaciones" id="ab_publicaciones" class="form-control" rows="2"></textarea></div>
        <div class="form-group"><label>Distinciones / Premios</label><textarea name="distinciones" id="ab_distinciones" class="form-control" rows="2"></textarea></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
$(function(){
  window.openPanel = function(){
    $('#abogadoPanel').css('right','0');
    $('body').css('overflow','hidden');
  };
  window.closePanel = function(){
    $('#abogadoPanel').css('right','-100%');
    $('body').css('overflow','');
  };

  $('#btnAdd').on('click', function(){
    // reset panel form
    $('#abogadoPanelForm')[0].reset(); $('#panel_ab_id').val(''); $('#panel_ab_foto_carnet').val(''); $('#panel_ab_foto_full').val(''); $('#panelTitle').text('Agregar abogado');
    openPanel();
  });

  $('.btn-edit').on('click', function(){
    var card = $(this).closest('.lawyer-card');
    var id = card.data('id');
    $.get('/backend/abogados.php',{action:'get',id:id}, function(resp){
      if(resp.ok){
        var d = resp.data;
        $('#panel_ab_id').val(d.id);
        $('#panel_ab_nombre').val(d.nombre);
        $('#panel_ab_correo').val(d.correo);
        $('#panel_ab_celular').val(d.celular);
        $('#panel_ab_area').val(d.area_practica);
        $('#panel_ab_descripcion').val(d.descripcion);
        $('#panel_ab_facebook').val(d.facebook || '');
        $('#panel_ab_instagram').val(d.instagram || '');
        $('#panel_ab_linkedin').val(d.linkedin || '');
        $('#panel_ab_twitter').val(d.twitter || '');
        $('#panel_ab_whatsapp').val(d.whatsapp || '');
        $('#panel_ab_cargo').val(d.cargo || '');
        $('#panel_ab_destacado').val((typeof d.destacado !== 'undefined' && d.destacado !== null) ? String(d.destacado) : '0');
        $('#panel_ab_formacion').val(d.formacion || '');
        $('#panel_ab_experiencia').val(d.experiencia || '');
        $('#panel_ab_docencia').val(d.docencia || '');
        $('#panel_ab_publicaciones').val(d.publicaciones || '');
        $('#panel_ab_distinciones').val(d.distinciones || '');
        $('#panelTitle').text('Editar abogado');
        openPanel();
      } else { alert(resp.msg || 'Error'); }
    }, 'json');
  });

  $('.btn-delete').on('click', function(){
    if(!confirm('Eliminar este abogado?')) return;
    var card = $(this).closest('.lawyer-card');
    var id = card.data('id');
    $.post('/backend/abogados.php',{action:'delete',id:id}, function(resp){ if(resp.ok){ card.remove(); } else alert(resp.msg||'Error'); }, 'json');
  });

  // Toggle inline details (compact)
  $('.btn-details').on('click', function(){
    var card = $(this).closest('.lawyer-card');
    var details = card.find('.card-details');
    details.slideToggle(150);
  });

  $('#abogadoForm').on('submit', function(e){
    e.preventDefault();
    var form = new FormData(this);
    form.append('action','save');
    $.ajax({ url:'/backend/abogados.php', data: form, type:'POST', contentType:false, processData:false, dataType:'json', success:function(resp){
      if(resp.ok){ location.reload(); } else { alert(resp.msg||'Error'); }
    }, error:function(){ alert('Error de red'); }
    });
  });
});
</script>

<script>
// panel form submit (same backend endpoint)
$(function(){
  $('#abogadoPanelForm').on('submit', function(e){
    e.preventDefault();
    var form = new FormData(this);
    form.append('action','save');
    $.ajax({ url:'/backend/abogados.php', data: form, type:'POST', contentType:false, processData:false, dataType:'json', success:function(resp){
      if(resp.ok){ closePanel(); location.reload(); } else { alert(resp.msg||'Error'); }
    }, error:function(){ alert('Error de red'); }
    });
  });
  $('#panelClose, #panelCancel').on('click', function(){ closePanel(); });
});
</script>

<!-- details are shown inline within each compact card (.card-details) to avoid scrolling -->

<script>
// Client-side search + simple pagination for admin abogados table
(function($){
  var $container = $('#abogadosCards');
  if(!$container.length) return;
  var $items = $container.find('.lawyer-card');
  var perPage = 10;
  var currentPage = 1;

  // mark all items as matched initially
  $items.addClass('matched');

  function renderPage(page){
    currentPage = page || 1;
    var $matched = $items.filter('.matched');
    var start = (currentPage - 1) * perPage;
    $items.hide();
    $matched.slice(start, start + perPage).show();
    renderPagination(Math.ceil($matched.length / perPage));
  }

  function renderPagination(totalPages){
    var $p = $('#abogadosPagination').empty();
    if(totalPages <= 1) return;
    for(var i=1;i<=totalPages;i++){
      var $btn = $('<button>').text(i).attr('data-page',i).addClass(i===currentPage? 'active' : '');
      $p.append($btn);
    }
    $p.find('button').on('click', function(){ renderPage(parseInt($(this).attr('data-page'),10)); });
  }

  // Search: add/remove .matched instead of show/hide so pagination always computes against the full filtered set
  $('#searchAbogados').on('input', function(){
    var q = $(this).val().toLowerCase().trim();
    if(q === ''){
      $items.addClass('matched');
    } else {
      $items.each(function(){
        var $card = $(this);
        var text = ($card.find('.lawyer-name').text() + ' ' + $card.find('.lawyer-cargo').text() + ' ' + $card.find('.lawyer-meta').text()).toLowerCase();
        if(text.indexOf(q) !== -1) $card.addClass('matched'); else $card.removeClass('matched');
      });
    }
    renderPage(1);
  });

  // Initially show first page
  renderPage(1);
})(jQuery);
</script>

<footer class="admin-page-footer" role="contentinfo">
  <div class="container-fluid">
    <div class="admin-page-footer__inner">
      <span>Panel de administracion - Gestion de abogados</span>
      <span>Alfonso Jimenez & Asociados</span>
    </div>
  </div>
</footer>
</body>
</html>
