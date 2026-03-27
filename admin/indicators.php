<?php
require_once __DIR__ . '/../inc/nocache.php';
require_once __DIR__ . '/../backend/auth.php';
require_once __DIR__ . '/../backend/db.php';
requireAdmin();
$pdo = getPDO();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin - Gestionar Indicadores</title>
  <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700" rel="stylesheet">
  <link rel="stylesheet" href="/css/animate.css">
  <link rel="stylesheet" href="/css/icomoon.css">
  <link rel="stylesheet" href="/css/bootstrap.css">
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

        <!-- Icon picker modal -->
        <div class="modal fade" id="iconPickerModal" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header"><h5 class="modal-title">Seleccionar icono</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
              <div class="modal-body">
                <div style="margin-bottom:10px;display:flex;gap:8px;align-items:center">
                  <input type="file" id="iconUploadFile" accept="image/svg+xml">
                  <button class="btn btn-sm btn-primary" id="btnUploadIcon" type="button">Subir SVG</button>
                </div>
                <div id="iconsGrid" style="display:grid;grid-template-columns:repeat(auto-fit,60px);gap:10px;align-items:center"></div>
              </div>
              <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>
            </div>
          </div>
        </div>
      <nav class="admin-sidebar__nav">
        <a href="/admin/dashboard.php"><i class="fas fa-chart-line"></i> Dashboard</a>
        <a href="/admin/abogados.php"><i class="fas fa-user-tie"></i> Gestionar abogados</a>
        <a href="/admin/faqs.php"><i class="fas fa-question-circle"></i> Gestionar FAQs</a>
        <a class="active" href="/admin/indicators.php"><i class="fas fa-chart-bar"></i> Gestionar Indicadores</a>
        <a href="/admin/usuarios.php"><i class="fas fa-user-shield"></i> Usuarios admin</a>
        <a href="/admin/dashboard.php#sec-citas"><i class="fas fa-calendar-check"></i> Solicitudes de cita</a>
        <a href="/admin/dashboard.php#sec-consultas"><i class="fas fa-comments"></i> Consultas</a>
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
          <h1>Gestionar Indicadores Institucionales</h1>
          <p>Edite los valores mostrados en la página principal.</p>
        </div>
        <div class="admin-main__actions">
          <button id="btnAddIndicator" class="btn btn-primary">Nuevo indicador</button>
        </div>
      </header>

      <section class="admin-block">
        <div class="admin-block__body">
          <div class="table-responsive">
            <table class="table table-striped" id="indicatorsTable">
              <thead><tr><th>ID</th><th>Etiqueta</th><th>Icon</th><th>Valor</th><th>Acciones</th></tr></thead>
              <tbody><tr><td colspan="5">Cargando...</td></tr></tbody>
            </table>
          </div>
        </div>
      </section>
    </main>
  </div>

  <div class="modal fade" id="indicatorModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <form id="indicatorForm" class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Indicador</h5><button type="button" class="close" data-dismiss="modal">&times;</button></div>
        <div class="modal-body">
          <input type="hidden" name="id">
          <div class="form-group"><label>Etiqueta</label><input name="label" class="form-control" required></div>
          <div class="form-group">
            <label>Icono</label>
            <div style="display:flex;gap:8px;align-items:center">
              <input name="icon" class="form-control" placeholder="fa-user-tie o svg:indicators/star.svg">
              <button type="button" id="btnChooseIcon" class="btn btn-secondary">Elegir icono</button>
            </div>
            <small class="form-text text-muted">Puedes usar una clase de FontAwesome (ej. <em>fa-user-tie</em>) o seleccionar un SVG.</small>
            <div id="selectedIconPreview" style="margin-top:8px"></div>
          </div>
          <div class="form-group"><label>Valor</label><input name="value" type="number" class="form-control" required></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-primary">Guardar</button></div>
      </form>
    </div>
  </div>

  <footer class="admin-page-footer" role="contentinfo"><div class="admin-page-footer__inner"><span>Panel administrativo</span><span>&copy; <?php echo date('Y'); ?></span></div></footer>

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
  <script>
  (function($){
    function load(){
      $.getJSON('/backend/indicators.php?action=list').done(function(data){
        var $t = $('#indicatorsTable tbody').empty();
        if(!data || !data.length){ $t.append('<tr><td colspan="5">No hay indicadores.</td></tr>'); return; }
        data.forEach(function(r){
          var id = r.id || 0;
          var tr = $('<tr>').attr('data-id',id)
            .append($('<td>').text(id))
            .append($('<td>').text(r.label))
            .append($('<td>').text(r.icon||''))
            .append($('<td>').append($('<input type="number" class="form-control input-val">').val(r.value)))
            .append($('<td>').html('<button class="btn btn-sm btn-primary btn-save">Guardar</button> <button class="btn btn-sm btn-danger btn-delete">Eliminar</button>'));
          $t.append(tr);
        });
      }).fail(function(){ $('#indicatorsTable tbody').html('<tr><td colspan="5">Error cargando.</td></tr>'); });
    }

    $(function(){ load();
      $(document).on('click', '#btnAddIndicator', function(){
        $('#indicatorForm')[0].reset(); $('#indicatorForm input[name="id"]').val(''); $('#indicatorModal').modal('show');
      });

      $(document).on('click', '.btn-save', function(){
        var $tr = $(this).closest('tr');
        var id = $tr.data('id');
        var val = $tr.find('.input-val').val();
        if(!id || id==0){ alert('Este indicador no está en la base de datos. Usa "Nuevo indicador" para crearlo.'); return; }
        $.post('/backend/indicators.php?action=update', {id:id, value: val}).done(function(resp){ if(resp && resp.success){ alert('Guardado'); load(); } else { alert('Error'); } }).fail(function(){ alert('Error servidor'); });
      });

      $(document).on('click', '.btn-delete', function(){
        if(!confirm('Eliminar indicador?')) return; var id = $(this).closest('tr').data('id'); if(!id){ alert('No'); return; }
        $.post('/backend/indicators.php?action=delete', {id:id}).done(function(resp){ if(resp && resp.success){ load(); } else { alert('Error'); } }).fail(function(){ alert('Error servidor'); });
      });

      $('#indicatorForm').on('submit', function(e){ e.preventDefault(); var fd = $(this).serialize(); var id = $(this).find('input[name="id"]').val(); var action = id? 'update' : 'create';
        if(action==='update'){ $.post('/backend/indicators.php?action=update', {id:id, value: $(this).find('input[name="value"]').val(), label: $(this).find('input[name="label"]').val(), icon: $(this).find('input[name="icon"]').val()}).done(function(r){ if(r && r.success){ $('#indicatorModal').modal('hide'); load(); } else alert('Error'); }).fail(function(){ alert('Error servidor'); });
        } else { $.post('/backend/indicators.php?action=create', $(this).serialize()).done(function(r){ if(r && r.success){ $('#indicatorModal').modal('hide'); load(); } else alert('Error: '+(r && r.error)); }).fail(function(){ alert('Error servidor'); }); }
      });

      // Icon picker logic
      function fetchIcons(){
        $('#iconsGrid').html('Cargando...');
        $.getJSON('/backend/icons.php?action=list').done(function(list){
          var $g = $('#iconsGrid').empty();
          if(!list || !list.length){ $g.html('<div>No hay iconos</div>'); return; }
          list.forEach(function(it){
            var $b = $('<button class="btn btn-light" style="width:56px;height:56px;padding:6px;border:1px solid #eee;border-radius:6px;"></button>');
            var $img = $('<img>').attr('src', it.url).css({width:'100%',height:'100%'});
            $b.append($img);
            $b.on('click', function(){
              var val = 'svg:indicators/' + it.file;
              $('#indicatorForm input[name="icon"]').val(val);
              $('#selectedIconPreview').html('<img src="'+it.url+'" style="height:36px">');
              $('#iconPickerModal').modal('hide');
            });
            $g.append($b);
          });
        }).fail(function(){ $('#iconsGrid').html('<div>Error cargando iconos</div>'); });
      }

      $(document).on('click', '#btnChooseIcon', function(){
        // append modal to body so it stacks above the current modal
        $('#iconPickerModal').appendTo('body').modal('show');
        // adjust z-index so the picker/modal/backdrop appear above the indicator modal
        setTimeout(function(){
          var $back = $('.modal-backdrop').last();
          $('#iconPickerModal').css('z-index', 1060);
          $back.css('z-index', 1059);
        }, 0);
        fetchIcons();
      });

      $(document).on('click', '#btnUploadIcon', function(){
        var f = document.getElementById('iconUploadFile').files[0];
        if(!f){ alert('Selecciona un archivo SVG'); return; }
        var fd = new FormData(); fd.append('file', f);
        $.ajax({ url: '/backend/icons.php?action=upload', method: 'POST', data: fd, processData: false, contentType: false }).done(function(r){ if(r && r.success){ fetchIcons(); } else { alert('Error: '+(r && r.error)); } }).fail(function(){ alert('Error servidor'); });
      });

      // preview when typing icon value
      $(document).on('input', 'input[name="icon"]', function(){
        var v = $(this).val()||'';
        if(v.indexOf('svg:')===0){ var path = '/images/icons/' + v.substring(4); $('#selectedIconPreview').html('<img src="'+path+'" style="height:36px">'); }
        else { $('#selectedIconPreview').html('<i class="fa-solid '+$('<div>').text(v).html()+'" style="font-size:24px"></i>'); }
      });

    });
  })(jQuery);
  </script>
</body>
</html>
