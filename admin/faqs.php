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
  <title>Admin - Gestionar FAQs</title>
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
        <a class="active" href="/admin/faqs.php"><i class="fas fa-question-circle"></i> Gestionar FAQs</a>
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
          <h1>Gestionar FAQ (Chatbot-Alfonsito)</h1>
          <p>Crear y administrar preguntas y respuestas que utiliza el asistente.</p>
        </div>
        <div class="admin-main__actions">
          <button id="btnNewFaqTop" class="btn btn-primary">Nuevo FAQ</button>
        </div>
      </header>

      <section class="admin-block">
        <div class="admin-block__body" style="margin-top:12px">
          <div class="modern-table admin-table-modern">
            <table class="table" id="faqsTable">
              <thead>
                <tr><th>ID</th><th>Pregunta</th><th>Respuesta</th><th>Keywords</th><th>Followups</th><th>Acciones</th></tr>
              </thead>
              <tbody>
                <tr><td colspan="6">Cargando...</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

    </main>
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
        container.append('<div class="fu-item" style="display:flex;gap:8px;margin-bottom:8px"><input class="form-control fu-question" placeholder="Pregunta" /><input class="form-control fu-answer" placeholder="Respuesta" /><button class="btn btn-danger btn-remove-fu">Eliminar</button></div>');
      }
      modal.modal('show');
    }

    $(function(){
      loadFaqs();

      $(document).on('click', '#btnNewFaq, #btnNewFaqTop', function(){ openFaqModal({followups:[]}); });

      $(document).on('click', '.btn-edit-faq', function(){
        var id = $(this).data('id');
        $.getJSON('/backend/faqs.php?action=get&id='+encodeURIComponent(id)).done(function(d){ openFaqModal(d); }).fail(function(){ alert('No se pudo cargar FAQ'); });
      });

      $(document).on('click', '.btn-delete-faq', function(){
        if(!confirm('Eliminar FAQ?')) return;
        var id = $(this).data('id');
        $.post('/backend/faqs.php?action=delete',{id:id}).done(function(resp){ if(resp && resp.success){ loadFaqs(); } else { alert(resp && resp.error ? resp.error : 'Error'); } }).fail(function(){ alert('Error servidor'); });
      });

      $(document).on('click', '#addFollowup', function(){
        var container = $('#faqModal .followups-container');
        container.append('<div class="fu-item" style="display:flex;gap:8px;margin-bottom:8px"><input class="form-control fu-question" placeholder="Pregunta" /><input class="form-control fu-answer" placeholder="Respuesta" /><button class="btn btn-danger btn-remove-fu">Eliminar</button></div>');
      });

      $(document).on('click', '.btn-remove-fu', function(){ $(this).closest('.fu-item').remove(); });

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
</body>
</html>
