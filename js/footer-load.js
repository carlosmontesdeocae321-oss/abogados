(function(){
  function loadFooter(){
    // If the page already contains a footer, avoid injecting to prevent duplicates
    if (document.querySelector('#colorlib-footer')) return;

    fetch('inc/footer.html', {cache: 'no-cache'})
      .then(function(resp){ if(!resp.ok) throw new Error('Network error'); return resp.text(); })
      .then(function(html){
        var targets = document.querySelectorAll('.include-footer, #site-footer');
        if(!targets || !targets.length) return;
        targets.forEach(function(t){ t.innerHTML = html; });
      })
      .catch(function(err){ console.error('Failed to load footer:', err); });
  }
  if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', loadFooter); else loadFooter();
})();