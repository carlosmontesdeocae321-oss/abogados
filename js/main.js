;(function () {
	
	'use strict';

	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
			BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
			iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
			Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
			Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
			any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	};

	var mobileMenuOutsideClick = function() {

		$(document).click(function (e) {
	    var container = $("#colorlib-offcanvas, .js-colorlib-nav-toggle");
	    if (!container.is(e.target) && container.has(e.target).length === 0) {

	    	if ( $('body').hasClass('offcanvas') ) {

    			$('body').removeClass('offcanvas');
    			$('.js-colorlib-nav-toggle').removeClass('active');
				
	    	}
	    
	    	
	    }
		});

	};


	var offcanvasMenu = function() {

		// Insert offcanvas container with logo at the top so the mobile menu shows branding
		$('#page').prepend('<div id="colorlib-offcanvas"><div id="offcanvas-logo"><a href="index.php"><img src="images/logo.png" alt="Logo" style="max-width:140px;height:auto;display:block;margin:18px 0 12px 0;"/></a></div></div>');
		$('#page').prepend('<a href="#" class="js-colorlib-nav-toggle colorlib-nav-toggle colorlib-nav-white"><i></i></a>');
		var clone1 = $('.menu-1 > ul').clone();
		$('#colorlib-offcanvas').append(clone1);
		var clone2 = $('.menu-2 > ul').clone();
		$('#colorlib-offcanvas').append(clone2);

		$('#colorlib-offcanvas .has-dropdown').addClass('offcanvas-has-dropdown');
		$('#colorlib-offcanvas')
			.find('li')
			.removeClass('has-dropdown');

		// Hover dropdown menu on mobile
		$('.offcanvas-has-dropdown').mouseenter(function(){
			var $this = $(this);

			$this
				.addClass('active')
				.find('ul')
				.slideDown(500, 'easeOutExpo');				
		}).mouseleave(function(){

			var $this = $(this);
			$this
				.removeClass('active')
				.find('ul')
				.slideUp(500, 'easeOutExpo');				
		});


		$(window).resize(function(){

			if ( $('body').hasClass('offcanvas') ) {

    			$('body').removeClass('offcanvas');
    			$('.js-colorlib-nav-toggle').removeClass('active');
				
	    	}
		});
	};


	var burgerMenu = function() {

		$('body').on('click', '.js-colorlib-nav-toggle', function(event){
			var $this = $(this);


			if ( $('body').hasClass('overflow offcanvas') ) {
				$('body').removeClass('overflow offcanvas');
			} else {
				$('body').addClass('overflow offcanvas');
			}
			$this.toggleClass('active');
			event.preventDefault();

		});
	};

	var fullHeight = function() {

		if ( !isMobile.any() ) {
			$('.js-fullheight').css('height', $(window).height());
			$(window).resize(function(){
				$('.js-fullheight').css('height', $(window).height());
			});
		}

	};

// Ensure the map column (.video) matches the form column height so map fills available space
var syncConsultHeights = function() {
	try {
		var $video = $('#colorlib-consult .video');
		var $form = $('#colorlib-consult .choose-form');
		if ($video.length && $form.length) {
			// compute height and apply as min-height on video so map (absolutely positioned) fills it
			var formH = $form.outerHeight();
			$video.css('min-height', formH + 'px');
			// dispatch custom event so leaflet maps can invalidate size
			var evt = document.createEvent('Event'); evt.initEvent('consult_resize', true, true);
			document.dispatchEvent(evt);
		}
	} catch (e) { console.warn('syncConsultHeights error', e); }
};

// run on load and resize (debounced)
$(window).on('load', function(){ setTimeout(syncConsultHeights, 200); });
var _syncTimer = null;
$(window).on('resize', function(){ clearTimeout(_syncTimer); _syncTimer = setTimeout(syncConsultHeights, 200); });

// Corporate toast helper: inject minimal styles and show Bootstrap toasts
var ensureToastStyles = function(){
	if (document.getElementById('siteToastStyles')) return;
	var s = document.createElement('style'); s.id = 'siteToastStyles';
	s.innerHTML = '\n.toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 1060; display:flex; flex-direction:column; gap:10px; }\n.toast { box-shadow: 0 10px 36px rgba(0,0,0,0.18); border-radius:10px; overflow:hidden; opacity:0; transform:translateY(-8px); transition:opacity .22s ease, transform .22s ease; max-width:380px; border:1px solid rgba(0,0,0,0.08); background: #0b0b0b; color: #fff; }\n.toast.show{ opacity:1; transform:translateY(0); }\n.toast-gold .toast-header{ background: linear-gradient(90deg,#d4af37,#bda04a); color:#0b0b0b; border-bottom:1px solid rgba(0,0,0,0.08); padding:8px 12px; display:flex; align-items:center; justify-content:space-between }\n.toast-gold .toast-body{ background:transparent; color:#ffffff; padding:12px }\n.toast .close{ background:transparent; border:none; color:#111; font-size:16px; line-height:1; }\n';
	document.head.appendChild(s);
};

var showToast = function(message, title){
	try{
		ensureToastStyles();
		var container = document.getElementById('toastContainer');
		if (!container){
			container = document.createElement('div'); container.id = 'toastContainer'; container.className = 'toast-container';
			document.body.appendChild(container);
		}
		var id = 'toast-' + Date.now();
		var html = '' +
			'<div id="'+id+'" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="6000">' +
				'<div class="toast-header toast-gold" style="display:flex;align-items:center;">' +
					'<strong class="mr-auto">'+ (title||'Notificación') +'</strong>' +
					'<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>' +
				'</div>' +
				'<div class="toast-body">'+ (message||'') +'</div>' +
			'</div>';
		container.insertAdjacentHTML('beforeend', html);
		var el = document.getElementById(id);
		if (!el) return;
		// close handler
		var btn = el.querySelector('.close');
		var removeFn = function(){
			try{ el.classList.remove('show'); }catch(e){}
			setTimeout(function(){ try{ el.remove(); }catch(e){} }, 300);
		};
		if (btn){ btn.addEventListener('click', function(e){ e.preventDefault(); removeFn(); }, {passive:true}); }
		// show with a small delay so transitions apply
		requestAnimationFrame(function(){ el.classList.add('show'); });
		// auto hide after 6s
		var delay = 6000;
		setTimeout(removeFn, delay);
	} catch(e){ console.warn('showToast error', e); }
};

// Appointment modal: inject modal HTML and wire handlers for site-wide "Solicitar cita" button
var appointmentModal = function(){
	try{
		// append modal markup once
		if (!document.getElementById('appointmentModal')){
			var modalHtml = '\n<div class="modal fade" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel" aria-hidden="true">\n  <div class="modal-dialog modal-lg" role="document">\n    <div class="modal-content">\n      <div class="modal-header">\n        <h5 class="modal-title" id="appointmentModalLabel">Solicitar consulta legal</h5>\n        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">\n          <span aria-hidden="true">&times;</span>\n        </button>\n      </div>\n      <div class="modal-body">\n        <p>Agende una consulta con uno de nuestros abogados. Un miembro del equipo se pondrá en contacto para confirmar su cita.</p>\n        <form id="appointmentForm">\n          <div class="form-row">\n            <div class="form-group col-md-12">\n              <label>Nombre completo</label>\n              <input type="text" name="nombre" class="form-control" required />\n            </div>\n            <div class="form-group col-md-6">\n              <label>Teléfono</label>\n              <input type="tel" name="telefono" class="form-control" />\n            </div>\n            <div class="form-group col-md-6">\n              <label>Email</label>\n              <input type="email" name="email" class="form-control" />\n            </div>\n            <div class="form-group col-md-6">\n              <label>Tipo de consulta</label>\n              <select name="tipo_consulta" class="form-control">\n                <option value="Civil">Civil</option>\n                <option value="Penal">Penal</option>\n                <option value="Corporativo">Corporativo</option>\n                <option value="Inmobiliario">Inmobiliario</option>\n                <option value="Otro">Otro</option>\n              </select>\n            </div>\n            <div class="form-group col-md-3">\n              <label>Fecha preferida</label>\n              <input type="date" name="fecha_preferida" class="form-control" />\n            </div>\n            <div class="form-group col-md-3">\n              <label>Hora preferida</label>\n              <input type="time" name="hora_preferida" class="form-control" />\n            </div>\n            <div class="form-group col-md-12">\n              <label>Mensaje (opcional)</label>\n              <textarea name="mensaje" rows="4" class="form-control"></textarea>\n            </div>\n          </div>\n          <div class="text-right">\n            <button type="submit" class="btn btn-primary">Agendar consulta</button>\n          </div>\n        </form>\n        <div id="appointmentNotification" style="display:none;margin-top:12px"></div>\n      </div>\n    </div>\n  </div>\n</div>\n';
			document.body.insertAdjacentHTML('beforeend', modalHtml);
		}

		// intercept site-wide 'Solicitar cita' links inside .btn-cta
		document.addEventListener('click', function(e){
			var el = e.target;
			// walk up to anchor if inner element clicked
			while(el && el !== document){ if(el.tagName === 'A' && el.closest('.btn-cta')) break; el = el.parentNode; }
			if (!el || el === document) return;
			var text = (el.textContent || '').trim();
			if (text.toLowerCase().indexOf('solicitar cita') !== -1 || el.closest('.btn-cta')){
				e.preventDefault();
				$('#appointmentModal').modal('show');
			}
		}, false);

		// handle form submit
		$(document).on('submit', '#appointmentForm', function(ev){
			ev.preventDefault();
			var $f = $(this);
			var data = new FormData(this);
			fetch('/backend/citas.php', { method: 'POST', body: data, credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
			.then(function(res){ return res.json(); })
			.then(function(json){
				if (json && json.success){
					$('#appointmentModal').modal('hide');
					showToast('Cita solicitada correctamente.', '¡Hecho!');
					try{ $f[0].reset(); }catch(e){}
				} else if (json && json.errors){
					showToast((json.errors||[]).join('\n'), 'Error');
				} else {
					showToast('Error al enviar la solicitud. Intente de nuevo.', 'Error');
				}
			}).catch(function(){ showToast('Error de red. Intenta más tarde.', 'Error'); });
		});

	} catch(e){ console.warn('appointmentModal error', e); }
};

// initialize appointment modal setup on DOM ready
$(document).ready(function(){ appointmentModal(); });



	var contentWayPoint = function() {
		var i = 0;
		$('.animate-box').waypoint( function( direction ) {

			if( direction === 'down' && !$(this.element).hasClass('animated-fast') ) {
				
				i++;

				$(this.element).addClass('item-animate');
				setTimeout(function(){

					$('body .animate-box.item-animate').each(function(k){
						var el = $(this);
						setTimeout( function () {
							var effect = el.data('animate-effect');
							if ( effect === 'fadeIn') {
								el.addClass('fadeIn animated-fast');
							} else if ( effect === 'fadeInLeft') {
								el.addClass('fadeInLeft animated-fast');
							} else if ( effect === 'fadeInRight') {
								el.addClass('fadeInRight animated-fast');
							} else {
								el.addClass('fadeInUp animated-fast');
							}

							el.removeClass('item-animate');
						},  k * 200, 'easeInOutExpo' );
					});
					
				}, 100);
				
			}

		} , { offset: '85%' } );
	};


	var dropdown = function() {

		$('.has-dropdown').mouseenter(function(){

			var $this = $(this);
			$this
				.find('.dropdown')
				.css('display', 'block')
				.addClass('animated-fast fadeInUpMenu');

		}).mouseleave(function(){
			var $this = $(this);

			$this
				.find('.dropdown')
				.css('display', 'none')
				.removeClass('animated-fast fadeInUpMenu');
		});

	};


	var goToTop = function() {

		$('.js-gotop').on('click', function(event){
			
			event.preventDefault();

			$('html, body').animate({
				scrollTop: $('html').offset().top
			}, 500, 'easeInOutExpo');
			
			return false;
		});

		$(window).scroll(function(){

			var $win = $(window);
			if ($win.scrollTop() > 200) {
				$('.js-top').addClass('active');
			} else {
				$('.js-top').removeClass('active');
			}

		});
	
	};


	var chatWidget = function() {


		var $toggle = $('.gototop a, .chat-toggle');



		// Mutation observer to log when the chat widget's `open` class changes
		try {
			var chatEl = document.getElementById('chatWidget');
			if (chatEl) {
				var mo = new MutationObserver(function(mutations){
					mutations.forEach(function(m){
						if (m.attributeName === 'class') {
							try { console.log('CHAT DEBUG: #chatWidget class changed:', chatEl.className); } catch(e) {}
						}
					});
				});
				mo.observe(chatEl, { attributes: true, attributeFilter: ['class'] });
			}
		} catch(e) { console.warn('CHAT DEBUG: MutationObserver setup failed', e); }

			// cached initial FAQs so we can re-render them when followups finish
			var cachedFaqs = null;
			// Chat suggestion history stack: stores HTML of previous suggestion lists
			var chatHistory = [];

			function pushSuggestions($body){
				try {
					var snaps = $body.find('.faq-list, .followup-list').map(function(){ return $(this).prop('outerHTML'); }).get().join('');
					if (!snaps) return;
					chatHistory.push(snaps);
					// don't append UI here; back button is injected when suggestions are restored
				} catch(e){ console.warn('pushSuggestions error', e); }
			}

			function restoreSuggestions($body){
				try {
					if (!chatHistory.length) return;
					var last = chatHistory.pop();
					// remove existing suggestion lists and back button
					$body.find('.faq-list, .followup-list, .chat-back-wrap').remove();
					$body.append(last);
					// inject an inline back button inside the restored suggestion container
					var $lastList = $body.find('.faq-list, .followup-list').last();
					if ($lastList.length) {
						$lastList.prepend('<div class="chat-back-inline" style="text-align:right;margin-bottom:6px;"><button class="chat-back" style="background:transparent;border:none;color:#1f497a;font-weight:700;font-size:13px;cursor:pointer;">\u2190 Atrás</button></div>');
					}
					// if history now empty, remove any extra wrappers
					if (!chatHistory.length) $body.find('.chat-back-inline').remove();
					$body.scrollTop($body.prop('scrollHeight'));
				} catch(e){ console.warn('restoreSuggestions error', e); }
			}
			function renderInitialFaqs($body){
				try {
					// remove any existing suggestion lists before re-rendering
					$body.find('.faq-list, .followup-list').remove();
					if (!cachedFaqs || !cachedFaqs.length) return;
					var $list = $('<div class="faq-list" style="margin-top:10px;"></div>');
					cachedFaqs.slice(0,6).forEach(function(f){
						// include a small inline SVG icon and the question text
						var icon = '<svg width="18" height="18" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">'
								+ '<path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10z" fill="none" stroke="#2c3e50" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>'
								+ '<path d="M9 9h6" fill="none" stroke="#2c3e50" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>'
								+ '</svg>';
						// encode answer/followups into attributes so they survive HTML restore
						var translatedAnswer = window.translateChatbotText ? window.translateChatbotText(f.answer || '') : (f.answer || '');
						var encodedAnswer = encodeURIComponent(JSON.stringify(translatedAnswer));
						var encodedFups = encodeURIComponent(JSON.stringify(f.followups || []));
						var translatedQuestion = window.translateChatbotText ? window.translateChatbotText(f.question || '') : (f.question || '');
						var btnHtml = '<button class="faq-btn" data-answer="'+ encodedAnswer +'" data-followups="'+ encodedFups + '"><span class="faq-icon">'+icon+'</span><span class="faq-text">'+$('<div>').text(translatedQuestion).html()+'</span></button>';
						var $btn = $(btnHtml);
						$list.append($btn);
					});
					$body.append($list);
					$body.scrollTop($body.prop('scrollHeight'));
				} catch (e) { console.warn('renderInitialFaqs error', e); }
			}

			// helper: build bot response HTML including optional answer and a contact button
			function makeBotHtml(answer){
				try{
					var safe = answer ? String(answer) : '';
					var bookImg = '<img src="images/book-icon.svg" alt="" style="width:16px;height:16px;flex:0 0 16px;display:block;" />';
					var btnHtml = '<a href="contact.php" class="chat-contact-btn" aria-label="Más información">'+bookImg+'<span>Más información</span></a>';
					var inner = safe ? ('<div class="chat-bot-text">'+safe+'</div>') : '';
					return '<div class="chat-bot" style="margin-top:8px;padding:8px;background:#fff;border-radius:8px;border:1px solid #eee;">'+inner+'<div style="margin-top:8px;">'+btnHtml+'</div></div>';
				} catch(e){ console.warn('makeBotHtml error', e); return '<div class="chat-bot">Para mayor información, <a href="contact.php">contáctanos</a>.</div>'; }
			}

			// Manage first-time display and dismissal using localStorage, but if this load is a reload
			// (Ctrl+R / Ctrl+F5) we want the vibration/badge to reappear until user closes again.
			var isReload = false;
			try {
				var navEntries = performance.getEntriesByType && performance.getEntriesByType('navigation');
				if (navEntries && navEntries.length) {
					isReload = navEntries[0].type === 'reload';
				} else if (performance.navigation) {
					isReload = performance.navigation.type === 1; // fallback
				}
			} catch (e) { isReload = false; }

			var dismissed = false;
			var seen = false;
			try { dismissed = localStorage.getItem('alfonsito_dismissed') === '1'; } catch (e) { dismissed = false; }
			try { seen = localStorage.getItem('alfonsito_seen') === '1'; } catch (e) { seen = false; }

			// If this load was a reload, ignore previous dismissed/seen so the badge/vibrate shows again
			if (isReload) {
				dismissed = false;
				seen = false;
			}

			// If dismissed previously (and not a reload), hide badge and don't vibrate.
			if (dismissed) {
				$toggle.removeClass('vibrate').addClass('chat-dismissed');
			} else if (!seen && !$('#chatWidget').hasClass('open')) {
				// first time on site (or after reload): vibrate and show badge, then mark as seen so it only happens once per visit
				$toggle.addClass('vibrate');
				try { localStorage.setItem('alfonsito_seen','1'); } catch (e) {}
			} else {
				// subsequent visits and not dismissed: do not vibrate
				$toggle.removeClass('vibrate');
			}

		$(document).on('click', '.chat-toggle', function(e){
			try { console.log('CHAT DEBUG: chat-toggle clicked, target:', e.target); } catch(_) {}
			e.preventDefault();
			var $w = $('#chatWidget');
			var $thisToggle = $(this);
			$w.toggleClass('open');
			// Move focus out of the widget before hiding for accessibility
			try {
				if (!$w.hasClass('open')) {
					var active = document.activeElement;
					if (active && $w[0].contains(active)) {
						// Prefer returning focus to the toggle that opened it
						try { $this.focus(); } catch (e) { try { active.blur(); } catch(e2) {} }
					}
				}
			} catch (e) { /* ignore focus errors */ }
			$w.attr('aria-hidden', !$w.hasClass('open'));

			// toggle vibration and badge: keep vibration/badge even when open (user requested)
			if ($w.hasClass('open')) {
				// position chat widget next to the clicked toggle when possible
				try {
					var rect = this.getBoundingClientRect();
					var chatWidth = $w.outerWidth();
					var chatHeight = $w.outerHeight();
					// Position to the left of the toggle, vertically centered with a small upward offset
					var left = Math.round(rect.left - chatWidth - 12);
					var top = Math.round(rect.top + (rect.height / 2) - (chatHeight / 2) - 60);
					// Apply user-requested nudge: move up ~4 lines (~64px) and right ~3 lines (~48px)
					var nudgeUp = 112; // pixels (previous 64 + 3 lines ≈ 48)
					var nudgeRight = 112; // pixels (previous 48 + 4 lines ≈ 64)
					top = top - nudgeUp;
					left = left + nudgeRight;
					// if not enough space on the left, place to the right
					if (left < 12) {
						left = Math.round(rect.right + 12);
					}
					// clamp top within viewport
					if (top < 12) top = 12;
					if (top + chatHeight > window.innerHeight - 12) top = Math.max(12, window.innerHeight - 12 - chatHeight);
					$w.css({position: 'fixed', left: left + 'px', top: top + 'px', right: 'auto', bottom: 'auto'});
				} catch (err) {
					// fallback: leave default bottom-right
					$w.css({position: 'fixed', right: '20px', bottom: '90px', left: 'auto', top: 'auto'});
				}

				// show welcome message once when first opened
				try {
					if (!$w.data('welcomed')) {
						var $body = $w.find('.chat-body');
						var welcomeHtml = '<div class="welcome"><p>Hola, soy <strong>Alfonsito</strong>, el asistente virtual de <em>Alfonso Jimenez & Asociados</em>.</p>' +
							'<p>Bienvenido a nuestro sitio. Puedo ayudarte a encontrar información sobre nuestros servicios, agendar una consulta o darte los datos de contacto.</p>' +
							'<p class="signature">¿En qué puedo ayudarte hoy?</p></div>';
						$body.html(welcomeHtml);
						// load canned FAQs and render quick-reply buttons from API
						var fetchWithTimeout = function(url, ms){
							return Promise.race([
								fetch(url),
								new Promise(function(_, reject){ setTimeout(function(){ reject(new Error('timeout')) }, ms); })
							]);
						};

						fetchWithTimeout('/api/faqs.php', 3000).then(function(res){
							if (res && res.ok) return res.json();
							throw new Error('API error');
						}).then(function(faqs){
							console.log('CHAT DEBUG: /api/faqs returned', faqs);
							if (!faqs || !faqs.length) return;
							// cache initial faqs so we can render them again later
							cachedFaqs = faqs;
							// initial render using shared renderInitialFaqs
							renderInitialFaqs($body);

							// click handlers for faq buttons (delegated at document level so restored HTML works)
							$(document).off('click.chatFaq').on('click.chatFaq', '#chatWidget .faq-btn', function(){
								var $btn = $(this); var q = $btn.text();
								var $body = $('#chatWidget .chat-body');
								// save current suggestions so user can go back
								pushSuggestions($body);
								// remove the original faq-list so new followups appear below the answer
								$body.find('.faq-list').remove();
								// retrieve stored faq data from attributes (works after HTML restore)
								var ansRaw = $btn.attr('data-answer');
								var fupsRaw = $btn.attr('data-followups');
								var a = '';
								var fobjFups = [];
								try { if (ansRaw) a = JSON.parse(decodeURIComponent(ansRaw)); } catch(e){ a = decodeURIComponent(ansRaw||''); }
								try { if (fupsRaw) fobjFups = JSON.parse(decodeURIComponent(fupsRaw)); } catch(e){ fobjFups = []; }
								$body.append('<div class="chat-user" style="margin-top:10px;padding:8px;background:#f1f1f1;border-radius:8px;">'+
									'<strong>Tú:</strong> '+$('<div>').text(q).html()+'</div>');
								if (!a) {
									// fetch the answer from the API if not present
									console.log('CHAT DEBUG: no answer on faq object, fetching via /api/chatbot.php for', q);
									fetch('/api/chatbot.php', {
										method: 'POST',
										headers: { 'Content-Type': 'application/json' },
										body: JSON.stringify({ message: q })
									}).then(function(res){ if (res && res.ok) return res.json(); throw new Error('API error'); })
									.then(function(json){
										if (json && json.answer) {
											var translatedAnswer = window.translateChatbotText ? window.translateChatbotText(json.answer) : json.answer;
											$body.append(makeBotHtml(translatedAnswer));
											console.log('CHAT DEBUG: appended bot answer (fetched):', translatedAnswer);
											if (json && json.followups && json.followups.length) {
												renderFollowups($body, json.followups);
											} else {
												// if no followups, restore initial FAQ list after small delay to avoid race
												setTimeout(function(){ renderInitialFaqs($body); }, 160);
											}
											$body.scrollTop($body.prop('scrollHeight'));
										} else {
											// No answer from API: show contact button
											try {
												var bookImg = '<img src="images/book-icon.svg" alt="" style="width:16px;height:16px;flex:0 0 16px;display:block;" />';
												var moreInfoText = window.translateChatbotText ? window.translateChatbotText("Más información") : "Más información";
												var forMoreText = window.translateChatbotText ? window.translateChatbotText("Para mayor información,") : "Para mayor información,";
												var btnHtml = '<a href="contact.php" style="display:inline-flex;align-items:center;gap:8px;padding:8px 10px;background:#f7fbff;border:1px solid #e6eef8;border-radius:6px;text-decoration:none;color:#1f497a;font-weight:600;">'+bookImg+'<span>'+moreInfoText+'</span></a>';
												$body.append('<div class="chat-bot" style="margin-top:8px;padding:8px;background:#fff;border-radius:8px;border:1px solid #eee;">'+forMoreText+' '+btnHtml+'</div>');
												setTimeout(function(){ renderInitialFaqs($body); $body.scrollTop($body.prop('scrollHeight')); }, 160);
											} catch(e) { console.warn('contact button render failed', e); }
										}
									}).catch(function(){
										// On error, show contact button as fallback
										try {
											    var bookImgErr = '<img src="images/book-icon.svg" alt="" style="width:16px;height:16px;flex:0 0 16px;display:block;" />';
											var moreInfoErrText = window.translateChatbotText ? window.translateChatbotText("Más información") : "Más información";
											var forMoreErrText = window.translateChatbotText ? window.translateChatbotText("Para mayor información,") : "Para mayor información,";
											var btnHtmlErr = '<a href="contact.php" style="display:inline-flex;align-items:center;gap:8px;padding:8px 10px;background:#f7fbff;border:1px solid #e6eef8;border-radius:6px;text-decoration:none;color:#1f497a;font-weight:600;">'+bookImgErr+'<span>'+moreInfoErrText+'</span></a>';
											$body.append('<div class="chat-bot" style="margin-top:8px;padding:8px;background:#fff;border-radius:8px;border:1px solid #eee;">'+forMoreErrText+' '+btnHtmlErr+'</div>');
											setTimeout(function(){ renderInitialFaqs($body); $body.scrollTop($body.prop('scrollHeight')); }, 160);
										} catch(e) { console.warn('contact button fallback failed', e); }
									});
								} else {
									$body.append(makeBotHtml(a));
									console.log('CHAT DEBUG: appended bot answer (faq):', a);
									$body.scrollTop($body.prop('scrollHeight'));
									// render followups attached to this canned answer (if any)
									var fups = fobjFups || [];
									if (fups && fups.length) {
										renderFollowups($body, fups);
									} else {
										// no followups: restore initial FAQ list after short delay
										setTimeout(function(){ renderInitialFaqs($body); }, 160);
									}
								}
							});
						}).catch(function(err){
							// Try fallback static JSON if API fails or times out
							console.warn('CHAT DEBUG: /api/faqs failed, attempting fallback', err);
							fetch('/api/faqs.json').then(function(res){ if (res && res.ok) return res.json(); throw new Error('fallback error'); })
							.then(function(faqs){
								if (!faqs || !faqs.length) { $body.append(makeBotHtml('Lo sentimos, el servicio no está disponible.')); return; }
								cachedFaqs = faqs;
								renderInitialFaqs($body);
							}).catch(function(){
								$body.append(makeBotHtml('Lo sentimos, el servicio no está disponible.'));
							});
						});

						// helper to render follow-up suggestion buttons
						function renderFollowups($body, followups){
							try {
								if (!followups || !followups.length) return;
								console.log('CHAT DEBUG: renderFollowups called with', followups);
								var $cont = $('<div class="followup-list" style="margin-top:8px;display:flex;flex-wrap:wrap;gap:6px;"></div>');
								followups.forEach(function(fu){
									var icon = '<svg width="16" height="16" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">'
											+ '<circle cx="12" cy="12" r="9" fill="none" stroke="#2c3e50" stroke-width="1.2"/>'
											+ '<path d="M12 8v.5" stroke="#2c3e50" stroke-width="1.6" stroke-linecap="round"/>'
											+ '<path d="M12 11.2c0 1.2-1.5 1.6-1.5 2.4" stroke="#2c3e50" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>'
											+ '</svg>';
									// encode followup answer/followups into attributes
									var translatedAns = window.translateChatbotText ? window.translateChatbotText(fu.answer || '') : (fu.answer || '');
									var encAns = encodeURIComponent(JSON.stringify(translatedAns));
									var encF = encodeURIComponent(JSON.stringify(fu.followups || []));
									var translatedQuestion = window.translateChatbotText ? window.translateChatbotText(fu.question || '') : (fu.question || '');
									var btnHtml = '<button class="followup-btn" data-answer="'+encAns+'" data-followups="'+encF+'"><span class="faq-icon">'+icon+'</span><span class="faq-text">'+$('<div>').text(translatedQuestion).html()+'</span></button>';
									var $b = $(btnHtml);
									$cont.append($b);
								});
								$cont.hide();
								// if there's history, inject an inline back button inside this followup container
								if (chatHistory && chatHistory.length) {
									var atrasText = window.translateChatbotText ? window.translateChatbotText("Atrás") : "Atrás";
									$cont.prepend('<div class="chat-back-inline" style="text-align:right;margin-bottom:6px;"><button class="chat-back" style="background:transparent;border:none;color:#1f497a;font-weight:700;font-size:13px;cursor:pointer;">\u2190 '+atrasText+'</button></div>');
								}
								$body.append($cont);
								$cont.fadeIn(180);

								// back button handler (delegated to document to ensure it works)
								$(document).off('click.chatBack').on('click.chatBack', '#chatWidget .chat-back', function(e){
									e.preventDefault();
									var $b = $('#chatWidget .chat-body');
									restoreSuggestions($b);
								});
								// ensure view scrolls to show followups
								$body.scrollTop($body.prop('scrollHeight'));
								// delegated click handler (document-level so restored HTML works)
								$(document).off('click.chatFollow').on('click.chatFollow', '#chatWidget .followup-btn', function(){
									var $btn = $(this);
									var $body = $('#chatWidget .chat-body');
									// save current suggestions so user can go back
									pushSuggestions($body);
									var q = $btn.text();
									// read encoded attributes (works after HTML restore)
									var ansRaw = $btn.attr('data-answer');
									var nextRaw = $btn.attr('data-followups');
									var a = '';
									var next = [];
									try { if (ansRaw) a = JSON.parse(decodeURIComponent(ansRaw)); } catch(e){ a = decodeURIComponent(ansRaw||''); }
									try { if (nextRaw) next = JSON.parse(decodeURIComponent(nextRaw)); } catch(e){ next = []; }
									$body.append('<div class="chat-user" style="margin-top:10px;padding:8px;background:#f1f1f1;border-radius:8px;"><strong>Tú:</strong> '+$('<div>').text(q).html()+'</div>');
									$body.append(makeBotHtml(a));
									console.log('CHAT DEBUG: appended bot answer (followup):', a);
									$body.find('.followup-list').remove();
									if (next && next.length) {
										renderFollowups($body, next);
									} else {
										// no more followups: restore initial FAQs after short delay
										setTimeout(function(){ try { renderInitialFaqs($body); } catch(e){ console.warn('renderInitialFaqs missing', e); } }, 160);
									}
									$body.scrollTop($body.prop('scrollHeight'));
								});
							} catch (e) { console.warn('renderFollowups error', e); }
						}
						$w.data('welcomed', true);
					}
				} catch (err2) {
					console.warn('No se pudo mostrar el mensaje de bienvenida', err2);
				}
			} else {
				// User closed via toggle: treat as dismissal — do not re-enable vibrate and hide badge permanently
				try { localStorage.setItem('alfonsito_dismissed','1'); } catch (e) {}
				$toggle.removeClass('vibrate').addClass('chat-dismissed');
				$thisToggle.removeClass('chat-open');
				// Move focus out before hiding
				try { var active2 = document.activeElement; if (active2 && $w[0].contains(active2)) { try { $thisToggle.focus(); } catch(e){ active2.blur(); } } } catch(e){}
				// restore default placement
				$w.css({position: 'fixed', right: '20px', bottom: '90px', left: 'auto', top: 'auto'});
			}

			// close button inside widget
			$(document).off('click.chatClose').on('click.chatClose', '#chatWidget .chat-close', function(e){
				try { console.log('CHAT DEBUG: chat-close clicked, target:', e.target); } catch(_) {}
				try { e.stopPropagation(); } catch(_) {}
				// If the close button has focus, move it to the toggle before hiding
				try {
					var active3 = document.activeElement;
					if (active3 && $w[0].contains(active3)) {
						try { $('.chat-toggle').first().focus(); } catch(e) { active3.blur(); }
					}
				} catch(e) {}
				// Close the widget
				$('#chatWidget').removeClass('open').attr('aria-hidden', true);
				// Mark dismissed so vibrate and badge stay hidden until reload
				try { localStorage.setItem('alfonsito_dismissed','1'); } catch (err) {}
				$toggle.removeClass('vibrate').addClass('chat-dismissed');
				$('.chat-toggle').removeClass('chat-open');
				$('#chatWidget').css({position: 'fixed', right: '20px', bottom: '90px', left: 'auto', top: 'auto'});
			});

			// Capture-phase click logger to help debug unexpected closings (temporary)
			try {
				document.addEventListener('click', function(evt){
					try { console.log('CHAT DEBUG capture click:', evt.target && (evt.target.className || evt.target.tagName)); } catch(_) {}
				}, true);
			} catch(e) { console.warn('CHAT DEBUG: capture click logger failed', e); }

			// attach simple send button behaviour (aesthetic only)
			$(document).off('click.chatSend').on('click.chatSend', '#chatWidget .chat-send', function(e){
				try { e.preventDefault(); e.stopPropagation(); } catch(ev) {}
				try { console.log('CHAT DEBUG: send button clicked'); } catch(_) {}
				var $input = $('#chatWidget .chat-input');
				var txt = $input.val().trim();
				if (!txt) return;
				var $body = $('#chatWidget .chat-body');
				// append user message visually
				$body.append('<div class="chat-user" style="margin-top:10px;padding:8px;background:#f1f1f1;border-radius:8px;">'+
					'<strong>Tú:</strong> '+ $('<div>').text(txt).html() +'</div>');
				$input.val('');
				$body.scrollTop($body.prop('scrollHeight'));

				// Contextual admin assistant for dashboard pages
				var context = (window.chatbotContext || '').toString().toLowerCase();
				if (context === 'admin-dashboard') {
					var q = txt.toLowerCase();
					var adminAnswer = '';
					if (/agregar|crear|nuevo|abogado/.test(q)) {
						adminAnswer = 'Para agregar abogados:<br>1) En el menu lateral entra a <strong>Gestionar abogados</strong>.<br>2) Haz clic en <strong>Agregar abogado</strong>.<br>3) Completa nombre, correo, celular, area, cargo y redes.<br>4) Presiona <strong>Guardar</strong>.<br><br>Tip: Si marcas <strong>destacado</strong>, el abogado puede aparecer primero en la web.';
					} else if (/publica|blog|entrada|post/.test(q)) {
						adminAnswer = 'Para publicaciones:<br>1) Abre <strong>Administrar publicaciones</strong>.<br>2) Crea o edita el contenido.<br>3) Guarda cambios y verifica en la seccion publica.';
					} else if (/cita|solicitud|agenda/.test(q)) {
						adminAnswer = 'En <strong>Solicitudes de cita</strong> puedes:<br>- Ver detalle con el boton <strong>Ver</strong>.<br>- Eliminar registros con <strong>Eliminar</strong>.<br>- Revisar fecha preferida y tipo de consulta rapidamente.';
					} else if (/consulta|mensaje|correo/.test(q)) {
						adminAnswer = 'En <strong>Consultas recientes</strong> se listan nombre, email, telefono, servicio y mensaje.<br>Usa el icono de ojo para abrir el detalle completo.';
					} else if (/menu|navega|sitio|inicio|servicio|contacto|about|acerca/.test(q)) {
						adminAnswer = 'Desde el menu lateral puedes navegar al sitio publico: <strong>Inicio, Servicios, Casos Ganados, Acerca de Nosotros y Contacto</strong>.';
					} else if (/dashboard|panel|kpi|estadistica/.test(q)) {
						adminAnswer = 'Este dashboard muestra KPIs de consultas, citas, clientes y publicaciones. Debajo veras citas, consultas y actividad reciente para gestion rapida.';
					}

					if (adminAnswer) {
						$body.append(makeBotHtml(adminAnswer));
						$body.scrollTop($body.prop('scrollHeight'));
						return;
					}
				}

				// Query API for a matched answer; if none, show contact button
				try {
					fetch('/api/chatbot.php', {
						method: 'POST',
						headers: { 'Content-Type': 'application/json' },
						body: JSON.stringify({ message: txt })
					}).then(function(res){ if (res && res.ok) return res.json(); throw new Error('API error'); })
					.then(function(json){
						if (json && json.answer) {
							$body.append(makeBotHtml(json.answer));
							if (json.followups && json.followups.length) renderFollowups($body, json.followups);
							$body.scrollTop($body.prop('scrollHeight'));
						} else {
							// no answer -> show contact button
							try {
								var bookImg = '<img src="images/book-icon.svg" alt="" style="width:16px;height:16px;flex:0 0 16px;display:block;" />';
											var btnHtml = '<a href="contact.php" style="display:inline-flex;align-items:center;gap:8px;padding:8px 10px;background:#f7fbff;border:1px solid #e6eef8;border-radius:6px;text-decoration:none;color:#1f497a;font-weight:600;">'+bookImg+'<span>Más información</span></a>';
								$body.append('<div class="chat-bot" style="margin-top:8px;padding:8px;background:#fff;border-radius:8px;border:1px solid #eee;">Para mayor información, '+btnHtml+'</div>');
								$body.scrollTop($body.prop('scrollHeight'));
							} catch(err) { console.warn('contact button render failed', err); }
						}
					}).catch(function(){
						try {
							var bookImgErr = '<img src="images/book-icon.svg" alt="" style="width:16px;height:16px;flex:0 0 16px;display:block;" />';
							var btnHtmlErr = '<a href="contact.php" style="display:inline-flex;align-items:center;gap:8px;padding:8px 10px;background:#f7fbff;border:1px solid #e6eef8;border-radius:6px;text-decoration:none;color:#1f497a;font-weight:600;">'+bookImgErr+'<span>Más información</span></a>';
							$body.append('<div class="chat-bot" style="margin-top:8px;padding:8px;background:#fff;border-radius:8px;border:1px solid #eee;">Para mayor información, '+btnHtmlErr+'</div>');
							$body.scrollTop($body.prop('scrollHeight'));
						} catch(err) { console.warn('contact button fallback failed', err); }
					});
				} catch(e) { console.warn('chat send query error', e); }
			});
			return false;
		});

	};


	// Loading page
	var loaderPage = function() {
		$(".colorlib-loader").fadeOut("slow");
	};

	var counter = function() {
		$('.js-counter').countTo({
			 formatter: function (value, options) {
	      return value.toFixed(options.decimals);
	    },
		});
	};

	var counterWayPoint = function() {
		if ($('#colorlib-counter').length > 0 ) {
			$('#colorlib-counter').waypoint( function( direction ) {
										
				if( direction === 'down' && !$(this.element).hasClass('animated') ) {
					setTimeout( counter , 400);					
					$(this.element).addClass('animated');
				}
			} , { offset: '90%' } );
		}
	};

	var parallax = function() {

		if ( !isMobile.any() ) {
			$(window).stellar({
				horizontalScrolling: false,
				hideDistantElements: false, 
				responsive: true

			});
		}
	};

	var testimonialCarousel = function(){
		
		var owl = $('.owl-carousel-fullwidth');
		owl.owlCarousel({
			items: 1,
			loop: true,
			margin: 0,
			nav: false,
			dots: true,
			smartSpeed: 800,
			autoHeight: true
		});
	};

	var sliderMain = function() {
		
	  	$('#colorlib-hero .flexslider').flexslider({
			animation: "fade",
			slideshowSpeed: 5000,
			directionNav: true,
			start: function(){
				setTimeout(function(){
					$('.slider-text').removeClass('animated fadeInUp');
					$('.flex-active-slide').find('.slider-text').addClass('animated fadeInUp');
				}, 500);
			},
			before: function(){
				setTimeout(function(){
					$('.slider-text').removeClass('animated fadeInUp');
					$('.flex-active-slide').find('.slider-text').addClass('animated fadeInUp');
				}, 500);
			}

	  	});

	  	$('#colorlib-hero .flexslider .slides > li').css('height', $(window).height());	
	  	$(window).resize(function(){
	  		$('#colorlib-hero .flexslider .slides > li').css('height', $(window).height());	
	  	});

	};

	
	$(function(){
		mobileMenuOutsideClick();
		offcanvasMenu();
		burgerMenu();
		contentWayPoint();
		sliderMain();
		dropdown();
		goToTop();
		chatWidget();
		loaderPage();
		counterWayPoint();
		counter();
		parallax();
		testimonialCarousel();
		fullHeight();
	});


}());