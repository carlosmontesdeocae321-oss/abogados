(function(){
  function loadNavbar(){
    fetch('inc/navbar.html', {cache: 'no-cache'})
      .then(function(resp){ if(!resp.ok) throw new Error('Network error'); return resp.text(); })
      .then(function(html){
        var targets = document.querySelectorAll('.include-navbar, #site-navbar');
        if(!targets || !targets.length) return;
        targets.forEach(function(t){ t.innerHTML = html; });
        try{
          var path = (location.pathname||'').split('/').pop() || 'index.php';
          var links = document.querySelectorAll('.include-navbar a, #site-navbar a');
          links.forEach(function(a){
            var href = a.getAttribute('href') || '';
            var filename = href.split('/').pop();
            var li = a.closest('li');
            if(!li) return;
            li.classList.remove('active');
            if(filename === path || (path === '' && (filename === 'index.php' || filename === '')) ){
              li.classList.add('active');
            }
          });
        }catch(e){ console.error(e); }
      })
      .catch(function(err){ console.error('Failed to load navbar:', err); });
  }
  if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', loadNavbar); else loadNavbar();
})();