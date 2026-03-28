<!DOCTYPE HTML>
<html>
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Alfonso Jimenez & Asociados</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="author" content="" />

  <!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700" rel="stylesheet">
	
	<!-- Animate.css -->
	<link rel="stylesheet" href="css/animate.css?v=20260312">
	<!-- Icomoon Icon Fonts-->
	<link rel="stylesheet" href="css/icomoon.css?v=20260312">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="css/bootstrap.css?v=20260312">

	<!-- Magnific Popup -->
	<link rel="stylesheet" href="css/magnific-popup.css?v=20260312">

	<!-- Owl Carousel  -->
	<link rel="stylesheet" href="css/owl.carousel.min.css?v=20260312">
	<link rel="stylesheet" href="css/owl.theme.default.min.css?v=20260312">
	<!-- Flexslider  -->
	<link rel="stylesheet" href="css/flexslider.css?v=20260312">
	<!-- Flaticons  -->
	<link rel="stylesheet" href="fonts/flaticon/font/flaticon.css?v=20260312">

	<!-- Theme style  -->
	<link rel="stylesheet" href="css/style.css?v=20260312">
	<!-- Team cards custom styles -->
	<link rel="stylesheet" href="css/team-cards.css?v=20260312">
	<!-- Font Awesome for social icons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<!-- Favicon: use ISOTIPO.jpg for browser tab -->
	<link rel="shortcut icon" href="images/ISOTIPO.jpg" type="image/jpeg">
	<link rel="icon" href="images/ISOTIPO.jpg" type="image/jpeg">
	<link rel="apple-touch-icon" href="images/ISOTIPO.jpg">
	<!-- Leaflet CSS (map sin API key) -->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

	<!-- Modernizr JS -->
	<script src="js/modernizr-2.6.2.min.js?v=20260312"></script>

	<style>
	/* Compact form black/gold card */
	.compact-card{ background:#0b0b0b; color:#d4af37; padding:26px; border-radius:12px; box-shadow:0 18px 40px rgba(0,0,0,0.28); max-width:900px; margin:18px auto; }
	.compact-card .card-title{ font-family:'Playfair Display', serif; font-size:22px; margin:0 0 6px 0; color:#d4af37 }
	.compact-card .card-sub{ color:#d9c989; margin-bottom:14px }
	.compact-card .form-row{ display:flex; gap:12px; align-items:flex-start; flex-wrap:wrap }
	.compact-card label{ color:#d4af37; font-weight:700; font-size:13px; margin-bottom:6px; display:block }
	.compact-card .form-control{ border-radius:8px; border:1px solid #e9e2c7; padding:10px 12px; color:#0b0b0b; background:#fff }
	.compact-card .form-control:focus{ outline:none; box-shadow:0 0 0 3px rgba(212,175,55,0.14); border-color:#d4af37 }
	.btn-gold{ background:#d4af37; color:#0b0b0b; border:none; padding:10px 18px; border-radius:8px; font-weight:700 }
	.btn-gold:hover{ background:#bda04a }
 	/* Modern corporate stats section */
 	.stats-modern{ position:relative; padding:40px 0 44px; background:linear-gradient(145deg, rgba(7,22,42,.92), rgba(10,35,68,.9)), url('images/practice.png') center/cover no-repeat; overflow:hidden; }
	.stats-modern:before{ content:""; position:absolute; inset:0; background:radial-gradient(circle at 18% 18%, rgba(212,175,55,.2), transparent 45%); }
	.stats-modern .container{ position:relative; z-index:2; }
	.stats-head{ text-align:center; margin-bottom:18px; }
	.stats-kicker{ display:inline-block; font-size:11px; text-transform:uppercase; letter-spacing:.18em; color:#d4af37; font-weight:700; }
	.stats-head h3{ margin:8px 0 0; color:#ffffff; font-family:'Playfair Display', serif; font-size:30px; }
	.stats-grid{ display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:0; }
	.stat-card{ position:relative; background:transparent; border:none; border-radius:0; padding:14px 18px; text-align:center; box-shadow:none; transition:transform .22s ease; }
	.stat-card:hover{ transform:translateY(-4px); }
	.stat-card:not(:last-child):after{ content:""; position:absolute; right:0; top:50%; transform:translateY(-50%); width:1px; height:68px; background:rgba(255,255,255,.28); }
	.stat-icon{ width:40px; height:40px; margin:0 auto 10px; border-radius:50%; background:rgba(255,255,255,.12); color:#d4af37; display:flex; align-items:center; justify-content:center; font-size:17px; transition:background .2s ease; }
	.stat-card:hover .stat-icon{ background:rgba(212,175,55,.2); }
	.stat-number{ display:block; font-size:42px; line-height:1; font-weight:800; color:#ffffff; margin-bottom:6px; letter-spacing:.02em; }
	.stat-label{ display:block; font-size:11px; font-weight:700; color:rgba(255,255,255,.82); text-transform:uppercase; letter-spacing:.14em; }
	/* Institutional split section */
	.institutional-modern{ margin-top:-16px; padding:24px 0 8px; background:#f4f7fb; position:relative; z-index:3; }
	.institutional-head{ text-align:center; margin-bottom:14px; }
	.institutional-kicker{ display:inline-block; font-size:10px; letter-spacing:.2em; text-transform:uppercase; color:#3f5f86; font-weight:700; }
	.institutional-head h2{ margin:6px 0 8px; color:#10223a; font-family:'Playfair Display', serif; font-size:28px; line-height:1.2; }
	.institutional-head:after{ content:""; display:block; width:84px; height:2px; margin:0 auto; background:linear-gradient(90deg,#0f4a85,#d4af37); border-radius:99px; }
	.institutional-grid{ display:grid; grid-template-columns:1fr 1fr; gap:12px; }
	.institutional-pane{ border:1px solid #dfe7f2; border-radius:12px; padding:12px 14px; background:#fff; box-shadow:0 8px 18px rgba(16,34,58,.08); transition:transform .2s ease, box-shadow .2s ease; min-height:145px; }
	.institutional-pane:hover{ transform:translateY(-3px); box-shadow:0 14px 24px rgba(16,34,58,.14); }
	.institutional-pane--left{ border-top:3px solid #0f4a85; }
	.institutional-pane--right{ border-top:3px solid #d4af37; }
	.institutional-pane-top{ display:flex; align-items:center; gap:10px; margin-bottom:6px; }
	.institutional-pane-icon{ width:42px; height:42px; border-radius:12px; background:#e9f1fb; color:#0f4a85; display:flex; align-items:center; justify-content:center; font-size:19px; }
	.institutional-pane h3{ margin:0; color:#10223a; font-size:22px; font-family:'Playfair Display', serif; line-height:1.15; }
	.institutional-pane p{ margin:0; color:#4f6077; line-height:1.55; font-size:13.5px; }
	.dept-tags{ display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
	.dept-tag{ display:inline-flex; align-items:center; padding:5px 9px; border-radius:999px; background:#eef3fa; border:1px solid #d8e2f0; color:#284b72; font-size:11px; font-weight:700; transition:all .18s ease; }
	.dept-tag:hover{ background:#dfeaf9; border-color:#c4d6ee; color:#1f3f66; }
	@media (max-width:991px){
	  .stats-grid{ grid-template-columns:repeat(2,minmax(0,1fr)); }
	  .stat-card:nth-child(2n):after{ display:none; }
	  .stat-card:not(:last-child):after{ height:56px; }
	}
	@media (max-width:768px){
	  .institutional-grid{ grid-template-columns:1fr; }
	  .institutional-head h2{ font-size:24px; }
	  .institutional-modern{ margin-top:-8px; padding:18px 0 8px; }
	}
	@media (max-width:576px){
	  .stats-grid{ grid-template-columns:1fr; }
	  .stat-card:after{ display:none !important; }
	  .stat-number{ font-size:34px; }
	  .stats-head h3{ font-size:24px; }
	}

	/* Hero video wrapper styles */
	.hero-video-wrap{ position:absolute; inset:0; z-index:0; overflow:hidden; background:url('images/img_bg_1.jpg') center center / cover no-repeat; }
	.hero-video-wrap video{ width:100%; height:100%; object-fit:cover; display:block; }
	/* soft dark overlay for better text contrast */
	#colorlib-hero .overlay-gradient{ position:absolute; inset:0; z-index:1; background:linear-gradient(180deg, rgba(6,12,28,0.32), rgba(6,12,28,0.44)); }
	/* center the hero text vertically and horizontally */
	#colorlib-hero .slider-text-inner{ position:absolute; inset:0; z-index:2; display:flex; align-items:center; justify-content:center; flex-direction:column; text-align:center; padding:24px; box-sizing:border-box; }
	#colorlib-hero .slider-text-inner h1{ color:#ffffff; font-family:'Playfair Display', serif; font-size:48px; line-height:1.05; margin:0 0 14px; font-weight:700; text-shadow:0 10px 30px rgba(2,6,12,0.55); }
	#colorlib-hero .slider-text-inner h2{ color:rgba(255,255,255,0.92); font-size:20px; margin:0; font-weight:600; text-shadow:0 6px 18px rgba(2,6,12,0.45); }
	@media(max-width:991px){ #colorlib-hero .slider-text-inner h1{ font-size:34px; } #colorlib-hero .slider-text-inner h2{ font-size:16px; } }
	@media(max-width:600px){ #colorlib-hero .slider-text-inner h1{ font-size:26px; } #colorlib-hero .slider-text-inner h2{ font-size:14px; } }

	/* Chatbot: remove visible container/background when hidden and keep only floating avatar */
	/* Chatbot: remove visible container/background when hidden and keep only floating avatar */
	.gototop{ background:transparent !important; box-shadow:none !important; border:none !important; }
	.gototop .chat-toggle{ background:transparent !important; border:none !important; padding:0 !important; display:inline-block; }
	.chat-toggle-img{ width:64px; height:64px; border-radius:50%; display:block; box-shadow:0 12px 30px rgba(2,6,12,0.18); }
	/* when the widget is hidden (aria-hidden="true"), keep it visually invisible to avoid flicker */
	#chatWidget[aria-hidden="true"]{ opacity:0; visibility:hidden; pointer-events:none; transform:translateY(6px); }
	/* when shown, allow default styles (do not forcibly hide) */
	#chatWidget{ transition:opacity .22s ease, transform .22s ease; }
	@media (max-width:768px){ .compact-card{ padding:18px } .compact-card .form-row{ flex-direction:column } }
	</style>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->

	</head>
	<body>
		
	<div class="colorlib-loader"></div>
	
	<div id="page">
	<nav class="colorlib-nav" role="navigation">
		<div class="top-menu">
			<div class="container">
				<div class="row">
					<div class="col-md-2">
						<div id="colorlib-logo"><a href="index.php">
							<picture>
								<source media="(max-width: 768px)" srcset="images/ISOTIPO.jpg">
								<img src="images/logo.png" alt="Bufete logo" style="max-width:100%;height:auto;display:block;">
							</picture>
						</a></div>
					</div>
					<div class="col-md-10 text-right menu-1">
						<ul>
							<li class="active"><a href="index.php">Inicio</a></li>
							<li><a href="practice.php">Servicios</a></li>
							<li><a href="servicios-judiciales.html">Consulta Judiciales</a></li>
							<li><a href="educacion-continua.html">Educacion Continua</a></li>
							<li><a href="about.php">Acerca de Nosotros</a></li>
							<li><a href="contact.php">Contacto</a></li>
							<li class="btn-cta"><a href="#"><span>Solicitar cita</span></a></li>
							<!-- <li class="btn-cta"><a href="#"><span>Sign Up</span></a></li> -->
						</ul>
					</div>
				</div>
				
			</div>
		</div>
	</nav>

	<aside id="colorlib-hero" class="js-fullheight">
		<div class="hero-static js-fullheight" style="position:relative;">
			<div class="hero-video-wrap" aria-hidden="true">
				<video id="hero-bg-video" autoplay muted loop playsinline preload="auto" poster="images/img_bg_1.jpg">
					<source src="uploads/videos/oficina1.mp4" type="video/mp4">
				</video>
			</div>
			<div class="overlay-gradient"></div>
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 text-center js-fullheight slider-text">
						<div class="slider-text-inner">
							<h1>Ayudamos a resolver problemas legales empresariales</h1>
							<h2>¡Tu solución legal comienza aquí!</h2>
						</div>
					</div>
				</div>
			</div>
		</div>
	</aside>

	<div id="intro-bg" class="institutional-modern">
		<div class="container">
			<div class="institutional-head">
				<span class="institutional-kicker">Perfil Institucional</span>
				<h2>Nuestra firma en síntesis</h2>
			</div>
			<div class="institutional-grid">
				<article class="institutional-pane institutional-pane--left">
					<div class="institutional-pane-top">
						<span class="institutional-pane-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></span>
						<h3>Quiénes Somos</h3>
					</div>
					<p>Somos una firma legal multidisciplinaria que integra defensa técnica especializada у estrategia jurídica enfocada en resultados, respaldada por una práctica profesional consolidada desde 2016. Formamos parte de AJYJFLI S.A., lo que fortalece nuestro enfoque integral y compromiso con las soluciones legales efectivas.</p>
				</article>
				<article class="institutional-pane institutional-pane--right">
					<div class="institutional-pane-top">
						<span class="institutional-pane-icon"><i class="fa-solid fa-sitemap" aria-hidden="true"></i></span>
						<h3>Departamentos</h3>
					</div>
					<p>Estructura especializada para cobertura legal integral y acompañamiento estratégico en áreas clave.</p>
					<div class="dept-tags">
						<span class="dept-tag">Jurídico</span>
						<span class="dept-tag">Inmobiliario</span>
						<span class="dept-tag">Jurídico-Contable</span>
						<span class="dept-tag">Cobranzas</span>
						<span class="dept-tag">Compra-Venta de Empresas</span>
						<span class="dept-tag">Comunicación e Imagen</span>
					</div>
				</article>
			</div>
		</div>
	</div>

	<?php
	// Indicadores institucionales: preferir valores desde la base de datos si existe la tabla
	$indicators = [
		['label' => 'Abogados calificados', 'value' => 91, 'icon' => 'fa-user-tie'],
		['label' => 'Clientes confiables', 'value' => 85, 'icon' => 'fa-handshake-angle'],
		['label' => 'Casos exitosos', 'value' => 95, 'icon' => 'fa-scale-balanced'],
		['label' => 'Años de experiencia', 'value' => 98, 'icon' => 'fa-award'],
	];
	try {
		if (file_exists(__DIR__ . '/backend/db.php')) {
			require_once __DIR__ . '/backend/db.php';
			$pdo = getPDO();
			$stmt = $pdo->query('SELECT label, value, icon FROM indicators ORDER BY ord, id');
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if ($rows) {
				$indicators = array_map(function($r){ return ['label' => $r['label'], 'value' => (int)$r['value'], 'icon' => ($r['icon']?:'fa-circle')]; }, $rows);
			}
		}
	} catch (Exception $e) {
		// ignore DB errors and use defaults
	}
	?>

	<section class="stats-modern" id="colorlib-counter">
		<div class="container">
			<div class="stats-head">
				<span class="stats-kicker">Indicadores Institucionales</span>
				<h3>Resultados que respaldan nuestra práctica</h3>
			</div>
			<div class="stats-grid">
				<?php foreach ($indicators as $ind) : ?>
				<article class="stat-card animate-box">
					<?php
					$iconRaw = trim((string)($ind['icon'] ?? ''));
					if (strpos($iconRaw, 'svg:') === 0) {
						$svgPath = '/images/icons/' . substr($iconRaw, 4);
						?>
						<div class="stat-icon"><img src="<?= htmlspecialchars($svgPath) ?>" alt="<?= htmlspecialchars($ind['label']) ?>" style="height:40px;"/></div>
						<?php
					} else {
						?>
						<div class="stat-icon"><i class="fa-solid <?= htmlspecialchars($iconRaw) ?>" aria-hidden="true"></i></div>
						<?php
					}
					?>
					<span class="stat-number" data-counter-target="<?= (int)$ind['value'] ?>">0</span>
					<span class="stat-label"><?= htmlspecialchars($ind['label']) ?></span>
				</article>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<div id="colorlib-content">
		<div class="video colorlib-video" style="background:#f8f8f8;">
			<img src="images/anosdeexperiencia.png" alt="30 años de experiencia" style="width:100%;height:auto;display:block;min-height:420px;object-fit:cover;" />
		</div>
		<div class="choose animate-box">
			<div class="colorlib-heading">
		<h2>10 años de experiencia ofreciendo servicios legales de alta calidad</h2>
<p>
Nuestro equipo de abogados ofrece asesoría legal especializada con un enfoque profesional,
comprometido y estratégico. Brindamos soluciones jurídicas efectivas para proteger los
derechos e intereses de nuestros clientes, acompañándolos en cada etapa de su proceso legal
con ética, transparencia y responsabilidad.
</p>
</div>
			<?php
			// Sección "10 años de experiencia": usar porcentajes fijos separados
			$experience_percents = [91,85,95,98];
			// Tomar etiquetas de los indicadores si están disponibles, sin modificar la sección de indicadores
			$labels = array_values(array_map(function($i){ return $i['label']; }, $indicators));
			for ($i = 0; $i < count($experience_percents); $i++) :
				$percent = (int)$experience_percents[$i];
				if ($percent > 100) $percent = 100;
				$label = isset($labels[$i]) ? $labels[$i] : ('Elemento '.($i+1));
				if (stripos($label, 'Años de experiencia') !== false || stripos($label, 'Anos de experiencia') !== false) continue;
			?>
			<div class="progress">
				<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?= $percent ?>%">
				<?= htmlspecialchars($label) ?> <?= $percent ?>%
				</div>
			</div>
			<?php endfor; ?>
		</div>
	</div>

	<div id="colorlib-practice">
		<div class="container">
			<div class="row animate-box">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading">
						<h2>Nuestros Servicios</h2>
						<p>Brindamos asesoria legal especializada para personas y empresas, con estrategias claras, atencion personalizada y defensa integral en cada etapa del proceso.</p>
				</div>
			</div>

			<div class="services-grid animate-box">
				<article class="service-card">
					<div class="service-card__icon"><i class="fa-solid fa-scale-balanced" aria-hidden="true"></i></div>
					<h3>Derecho Civil</h3>
					<p>Representacion y asesoria en contratos, obligaciones, conflictos patrimoniales y reclamos para proteger tus derechos e intereses.</p>
					<a href="consultar-caso.php" class="service-card__link">Mas informacion <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
				</article>

				<article class="service-card">
					<div class="service-card__icon"><i class="fa-solid fa-gavel" aria-hidden="true"></i></div>
					<h3>Derecho Penal</h3>
					<p>Defensa legal estrategica en todas las etapas del proceso penal, con acompanamiento cercano y enfoque tecnico.</p>
					<a href="consultar-caso.php" class="service-card__link">Mas informacion <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
				</article>

				<article class="service-card">
					<div class="service-card__icon"><i class="fa-solid fa-briefcase" aria-hidden="true"></i></div>
					<h3>Derecho Empresarial</h3>
					<p>Asesoria juridica para empresas en estructuracion, contratos mercantiles, cumplimiento normativo y mitigacion de riesgos.</p>
					<a href="consultar-caso.php" class="service-card__link">Mas informacion <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
				</article>

				<article class="service-card">
					<div class="service-card__icon"><i class="fa-solid fa-house-chimney" aria-hidden="true"></i></div>
					<h3>Derecho Inmobiliario</h3>
					<p>Gestion legal de compraventas, arrendamientos, regularizacion de bienes y resolucion de disputas inmobiliarias.</p>
					<a href="consultar-caso.php" class="service-card__link">Mas informacion <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
				</article>

				<article class="service-card">
					<div class="service-card__icon"><i class="fa-solid fa-building-shield" aria-hidden="true"></i></div>
					<h3>Asesoria Corporativa</h3>
					<p>Soporte permanente para la toma de decisiones legales en directorios, gobierno corporativo y operaciones clave.</p>
					<a href="consultar-caso.php" class="service-card__link">Mas informacion <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
				</article>

				<article class="service-card">
					<div class="service-card__icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></div>
					<h3>Defensa Legal</h3>
					<p>Patrocinio judicial y extrajudicial con estrategias de litigio y negociacion para alcanzar resultados favorables.</p>
					<a href="consultar-caso.php" class="service-card__link">Mas informacion <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a>
				</article>
			</div>

			<div class="text-center animate-box" style="margin-top:32px;">
				<p><a class="btn btn-primary btn-lg btn-learn" href="practice.php">Ver mas <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i></a></p>
			</div>
			</div>
		</div>
	</div>

	<div id="colorlib-started" style="background-image:url(images/img_bg_2.jpg);" data-stellar-background-ratio="0.5">
		<div class="overlay"></div>
		<div class="container">
			<div class="row animate-box">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading colorlib-heading2">
						<h2>10 años de experiencia en diversos casos</h2>
						<p>Ayudamos a las personas a defenderse eficazmente y proteger sus derechos.</p>
						
				</div>
			</div>
		</div>
	</div>
	

	<div id="colorlib-testimonial" class="colorlib-bg-section">
		<div class="container">
			<div class="row animate-box">
				<div class="col-md-6 col-md-offset-3 text-center colorlib-heading">
						<h2>Lo que dicen nuestros clientes</h2>
						<p>Testimonios reales de personas y empresas que confiaron en nuestro equipo para defender sus derechos y resolver sus casos con estrategia y compromiso.</p>
				</div>
			</div>
			<div class="testimonials-grid animate-box">
				<article class="testimonial-card">
					<div class="testimonial-top">
						<div class="testimonial-avatar testimonial-avatar-icon">
							<i class="fas fa-user"></i>
						</div>
						<div class="testimonial-meta">
							<h3>María García</h3>
							<span>Derecho Empresarial</span>
						</div>
					</div>
					<div class="testimonial-stars" aria-label="Calificación 5 de 5">
						<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
					</div>
					<blockquote>
						<p>"El equipo mostró un gran profesionalismo en la negociación de un contrato complejo; lograron un acuerdo favorable que protegió nuestros intereses y nos dio tranquilidad para seguir invirtiendo."</p>
					</blockquote>
				</article>

				<article class="testimonial-card">
					<div class="testimonial-top">
						<div class="testimonial-avatar testimonial-avatar-icon">
							<i class="fas fa-user"></i>
						</div>
						<div class="testimonial-meta">
							<h3>Carlos Méndez</h3>
							<span>Derecho Laboral</span>
						</div>
					</div>
					<div class="testimonial-stars" aria-label="Calificación 5 de 5">
						<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
					</div>
					<blockquote>
						<p>"Nos asesoraron en un conflicto laboral y consiguieron una solución rápida y justa. Su atención fue cercana y clara en cada paso del proceso."</p>
					</blockquote>
				</article>

				<article class="testimonial-card">
					<div class="testimonial-top">
						<div class="testimonial-avatar testimonial-avatar-icon">
							<i class="fas fa-user"></i>
						</div>
						<div class="testimonial-meta">
							<h3>Luisa Paredes</h3>
							<span>Derecho Civil y Regulatorio</span>
						</div>
					</div>
					<div class="testimonial-stars" aria-label="Calificación 5 de 5">
						<span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
					</div>
					<blockquote>
						<p>"La asesoría en cumplimiento regulatorio nos permitió reorganizar procedimientos y evitar sanciones. Su equipo demostró conocimiento técnico y disponibilidad para resolver dudas."</p>
					</blockquote>
				</article>
			</div>
		</div>
	</div>


	<div id="colorlib-about">
		<div class="container">
			<div class="row animate-box">
				<div class="col-md-8 col-md-offset-2 text-center colorlib-heading">
						<h2>Nuestros abogados</h2>
					<p>Equipo de abogados con amplia experiencia en derecho civil, laboral, penal, corporativo, etc... Brindamos asesoría estratégica y representación efectiva, siempre con ética profesional y atención personalizada.</p>
				</div>
			</div>
			<div class="row">
				<div id="home-team" class="home-team" style="width:100%; text-align:center">
					<!-- Home team cards will be injected here by JS -->
					<div style="position:relative; display:inline-block; width:100%; max-width:1140px;">
						<button class="team-arrow team-prev-home" aria-label="Anterior" style="position:absolute; left:-60px; top:50%; transform:translateY(-50%); background:#d4af37; border:none; width:40px; height:40px; border-radius:50%; cursor:pointer; font-size:20px; z-index:10; color:#071018; font-weight:bold;">‹</button>
						<div id="home-cards" class="team-grid" style="width:100%;"></div>
						<button class="team-arrow team-next-home" aria-label="Siguiente" style="position:absolute; right:-60px; top:50%; transform:translateY(-50%); background:#d4af37; border:none; width:40px; height:40px; border-radius:50%; cursor:pointer; font-size:20px; z-index:10; color:#071018; font-weight:bold;">›</button>
					</div>
					<div style="margin-top:40px;">
						<a href="about.php" class="btn btn-primary btn-lg" style="background:#d4af37;border-color:#c9a84a;color:#071018;">Ver todo el equipo →</a>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div id="colorlib-consult">
			<div class="video colorlib-video" data-stellar-background-ratio="0.5">
			<div class="consult-map" data-popup="<div class='map-popup'><div class='map-popup-left'><img src=&quot;images/ISOTIPO.jpg&quot; alt='Logo' /></div><div class='map-popup-right'><h4>Dirección</h4><div class='map-popup-address'>Torres de la Merced, Víctor Manuel Rendón, Guayaquil, piso 20</div><div class='map-popup-coords'>Coordenadas: -2.190182686, -79.881324768</div></div></div>" data-tooltip="Torres de la Merced, piso 20" style="width:100%;height:100%;min-height:480px;"></div>
			<div class="map-address" style="position:absolute;left:20px;bottom:20px;color:#fff;background:rgba(0,0,0,0.45);padding:10px;border-radius:4px;">
				<strong>Dirección:</strong><br>
				Torres de la Merced, Victor Manuel Rendón, Guayaquil, piso 20<br>
				
			</div>
		</div>
		<div class="choose choose-form animate-box">
			<div class="colorlib-heading">
					<h2>Consulta legal gratuita</h2>
			</div>
				<div class="compact-card">
					<div class="card-title">Consulta rápida</div>
					<div class="card-sub">Rellena este formulario y te contactaremos a la brevedad.</div>
					<form action="/backend/consultas.php" method="POST" class="compact-consult" id="compactConsultForm">
									<!-- Hidden fields mapped to backend/admin expected names -->
									<input type="hidden" name="service" id="hidden_service">
									<input type="hidden" name="tipo_servicio" id="hidden_tipo_servicio">
									<input type="hidden" name="nombre" id="hidden_nombre">
									<input type="hidden" name="apellido" id="hidden_apellido">
									<input type="hidden" name="telefono" id="hidden_telefono">
									<input type="hidden" name="email" id="hidden_email">
									<input type="hidden" name="ciudad" id="hidden_ciudad" value="">
									<input type="hidden" name="descripcion" id="hidden_descripcion">
									<input type="hidden" name="mensaje" id="hidden_mensaje">
										<div class="row form-group">
												<div class="col-md-12">
													<label for="fname">Nombre completo</label>
													<input type="text" id="fname" name="fullname" class="form-control" placeholder="Ej: Juan Pérez">
												</div>
										</div>
										<div class="row form-group form-row">
												<div class="col-md-6" style="flex:1 1 48%">
													<label for="telefono">Teléfono</label>
													<input type="tel" id="telefono" name="telefono" class="form-control" placeholder="+593 9 1234 567">
												</div>
												<div class="col-md-6" style="flex:1 1 48%">
													<label for="email">Correo electrónico</label>
													<input type="email" id="email" name="email" class="form-control" placeholder="nombre@dominio.com">
												</div>
										</div>
					<div class="row form-group">
						<div class="col-md-12">
							<select class="form-control" id="topic" name="topic">
								<option value="">Seleccione área de interés</option>
								<option>Derecho Inmobiliario</option>
								<option>Derecho Empresarial</option>
								<option>Lesiones Personales</option>
								<option>Negligencia Médica</option>
								<option>Defensa Penal</option>
							</select>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-md-12">
							<textarea name="message" id="message" cols="30" rows="6" class="form-control" placeholder="Describa su caso con libertad"></textarea>
						</div>
					</div>
										<div class="form-group text-right">
											<input type="submit" value="Solicitar consulta" class="btn-gold btn-compact">
										</div>
									</form>
									</div>

								<style>
									/* Compact form notification (toast) */
									#compactNotification{ position:fixed; top:20px; right:20px; background:#0b0b0b; color:#d4af37; padding:12px 18px; border-radius:8px; box-shadow:0 8px 30px rgba(0,0,0,0.2); opacity:0; transition:opacity .25s ease; z-index:2000; max-width:360px; font-weight:600 }
									#compactNotification.show{ opacity:1 }
									#compactNotification.error{ background:#fff1f0; color:#a00; border:1px solid #e0b4b4 }
								</style>

								<div id="compactNotification" aria-live="polite" role="status" style="display:block"></div>

								<script>
								(function(){
									var form = document.getElementById('compactConsultForm');
									var notif = document.getElementById('compactNotification');
									if(!form) return;

									function showNotif(type, text){
										notif.className = '';
										if(type === 'error') notif.classList.add('error');
										notif.textContent = text;
										notif.classList.add('show');
										setTimeout(function(){ notif.classList.remove('show'); }, 5500);
									}

									form.addEventListener('submit', function(e){
									e.preventDefault();
									var fullname = (document.getElementById('fname')||{}).value || '';
									var telefonoVal = (document.getElementById('telefono')||{}).value || '';
									var emailVal = (document.getElementById('email')||{}).value || '';
									var topic = (document.getElementById('topic')||{}).value || '';
									var message = (document.getElementById('message')||{}).value || '';

									var parts = fullname.trim().split(/\s+/);
									var nombre = parts.slice(0,1).join(' ') || fullname;
									var apellido = parts.slice(1).join(' ') || '';

									document.getElementById('hidden_service').value = topic || 'General';
									document.getElementById('hidden_tipo_servicio').value = topic || 'General';
									document.getElementById('hidden_nombre').value = nombre;
									document.getElementById('hidden_apellido').value = apellido;
									document.getElementById('hidden_telefono').value = telefonoVal;
									document.getElementById('hidden_email').value = emailVal;
									document.getElementById('hidden_descripcion').value = message;
									document.getElementById('hidden_mensaje').value = message;

									var fd = new FormData(form);

										fetch(form.action, {
											method: 'POST',
											body: fd,
											credentials: 'same-origin',
											headers: { 'Accept': 'application/json' }
										}).then(function(res){
											var ct = res.headers.get('content-type') || '';
											if(ct.indexOf('application/json') !== -1){ return res.json(); }
											return res.text().then(function(t){ return { success: true, message: 'Solicitud enviada.' }; });
										}).then(function(data){
											if(data && data.success){
												showNotif('success', data.message || 'Solicitud enviada. Nos comunicaremos contigo pronto.');
												try{ form.reset(); }catch(e){}
											} else {
												showNotif('error', data && data.message ? data.message : 'Error al enviar. Intenta más tarde.');
											}
										}).catch(function(err){
											showNotif('error','Error de red. Intenta de nuevo.');
										});
									});
								})();
								</script>
		</div>
	</div>



	<div id="site-footer" class="include-footer"></div>
	</div>

	<div class="gototop js-top">
		<a href="#" class="chat-toggle" aria-label="Abrir chat"><img src="images/BOTCHAT.png" alt="Chatbot" class="chat-toggle-img"></a>
	</div>
	<div id="chatWidget" class="chat-widget" aria-hidden="true">
		<div class="chat-header">
			<div class="chat-header-left">
				<img src="images/BOTCHAT.png" alt="Alfonsito" class="chat-avatar">
				<div class="chat-title">Alfonsito <small class="chat-sub">Asistente virtual</small></div>
			</div>
			<button class="chat-close" aria-label="Cerrar chat">×</button>
		</div>
		<div class="chat-body">El chat se mostrará aquí cuando esté listo.</div>
		<div class="chat-footer">
			<input type="text" class="chat-input" placeholder="Escribe tu mensaje..." aria-label="Mensaje">
			<button type="button" class="chat-send" aria-label="Enviar">Enviar</button>
		</div>
	</div>
	
	<!-- jQuery -->
	<script src="js/jquery.min.js?v=20260312"></script>
	<!-- jQuery Easing -->
	<script src="js/jquery.easing.1.3.js?v=20260312"></script>
	<!-- Bootstrap -->
	<script src="js/bootstrap.min.js?v=20260312"></script>
	<!-- Waypoints -->
	<script src="js/jquery.waypoints.min.js?v=20260312"></script>
	<!-- Stellar Parallax -->
	<script src="js/jquery.stellar.min.js?v=20260312"></script>
	<!-- Carousel -->
	<script src="js/owl.carousel.min.js?v=20260312"></script>
	<!-- Flexslider -->
	<script src="js/jquery.flexslider-min.js?v=20260312"></script>
	<!-- countTo -->
	<script src="js/jquery.countTo.js?v=20260312"></script>
	<!-- Magnific Popup -->
	<script src="js/jquery.magnific-popup.min.js?v=20260312"></script>
	<script src="js/magnific-popup-options.js?v=20260312"></script>
	<!-- Main -->
	<!-- Leaflet (OpenStreetMap) for the consulta legal map -->
	<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
	<script src="js/leaflet_map.js?v=20260312"></script>
	<script src="js/i18n.js?v=20260312"></script>
	<script src="js/main.js?v=20260312"></script>
	<script src="js/footer-load.js?v=20260319"></script>
	<script>
	(function(){
	  function hideLoader(){
	    var loader = document.querySelector('.colorlib-loader');
	    if(loader){ loader.style.display = 'none'; }
	  }
	  window.addEventListener('load', hideLoader);
	  setTimeout(hideLoader, 3500);

	  var video = document.getElementById('hero-bg-video');
	  if(!video) return;

	  function tryPlay(){
	    var p = video.play();
	    if(p && typeof p.catch === 'function'){
	      p.catch(function(){
	        // Keep poster/fallback background when autoplay is blocked.
	      });
	    }
	  }

	  video.muted = true;
	  video.setAttribute('muted', 'muted');
	  video.setAttribute('playsinline', 'playsinline');
	  video.addEventListener('canplay', tryPlay);
	  document.addEventListener('visibilitychange', function(){
	    if(!document.hidden) tryPlay();
	  });
	  tryPlay();
	})();
	</script>
	<script>
	(function(){
	  var items = document.querySelectorAll('[data-counter-target]');
	  if(!items.length) return;

	  function animateCounter(el){
		var target = parseInt(el.getAttribute('data-counter-target'), 10) || 0;
		var duration = 1300;
		var start = 0;
		var startTime = null;

		function tick(ts){
		  if(!startTime) startTime = ts;
		  var progress = Math.min((ts - startTime) / duration, 1);
		  var eased = 1 - Math.pow(1 - progress, 3);
		  var value = Math.floor(start + (target - start) * eased);
		  el.textContent = value.toString();
		  if(progress < 1){ requestAnimationFrame(tick); }
		}

		requestAnimationFrame(tick);
	  }

	  if('IntersectionObserver' in window){
		var observer = new IntersectionObserver(function(entries){
		  entries.forEach(function(entry){
			if(entry.isIntersecting){
			  animateCounter(entry.target);
			  observer.unobserve(entry.target);
			}
		  });
		}, { threshold: 0.35 });

		items.forEach(function(el){ observer.observe(el); });
	  } else {
		items.forEach(animateCounter);
	  }
	})();
	</script>

	<!-- Home team loader script -->
	<script>
	(function(){
	  function field(obj, name, idxFallback){
		if(!obj) return '';
		if(obj[name]) return obj[name];
		var alt = name.charAt(0).toUpperCase() + name.slice(1);
		if(obj[alt]) return obj[alt];
		if(typeof idxFallback !== 'undefined' && obj[idxFallback]) return obj[idxFallback];
		return '';
	  }
	  function normalizeUrl(value){
		var v = (value || '').toString().trim();
		if(!v) return '';
		if(v.indexOf('http://') === 0 || v.indexOf('https://') === 0) return v;
		return 'https://' + v;
	  }
	  function normalizeWhatsApp(value){
		var v = (value || '').toString().trim();
		if(!v) return '';
		var digits = v.replace(/[^0-9]/g, '');
		if(!digits) return '';
		return 'https://wa.me/' + digits;
	  }
	  function buildCard(a, variant){
		var foto = field(a,'foto',4) || '';
		var nombreRaw = field(a,'nombre',1) || '';
		var areaRaw = field(a,'area_practica',6) || field(a,'area',5) || '';
		var descRaw = field(a,'descripcion',5) || field(a,'descripcion',6) || '';
		var correoRaw = field(a,'correo',2) || '';
		var celularRaw = field(a,'celular',3) || '';
		var cargoRaw = field(a,'cargo') || '';
		var nombre = $('<div>').text(nombreRaw).html();
		var area = $('<div>').text(areaRaw).html();
		var desc = $('<div>').text(descRaw).html();
		var correo = $('<div>').text(correoRaw).html();
		var celular = $('<div>').text(celularRaw).html();
		var cargo = $('<div>').text(cargoRaw).html();
		var facebook = normalizeUrl(field(a,'facebook')||'');
		var instagram = normalizeUrl(field(a,'instagram')||'');
		var linkedin = normalizeUrl(field(a,'linkedin')||'');
		var twitter = normalizeUrl(field(a,'twitter')||'');
		var whatsapp = normalizeWhatsApp(field(a,'whatsapp')||'');
		var card = '';
		card += '<article class="team-card index-lawyer-card'+(variant === 'featured' ? ' team-card--featured' : '')+'">';
		card += '<div class="team-media">';
		if(foto){ card += '<img src="'+foto+'" alt="'+nombre+'" loading="lazy" class="team-img">'; }
		else { card += '<div class="team-avatar" style="background-image:url(images/user-2.jpg)"></div>'; }
		card += '</div>';
		card += '<div class="team-body">';
		card += '<h3 class="team-name">'+nombre+'</h3>';
		if(cargo){ card += '<div class="team-cargo">'+cargo+'</div>'; }
		if(area){ card += '<div class="team-area">'+area+'</div>'; }
		if(desc){ card += '<p class="team-desc">'+desc+'</p>'; }
		var meta = [];
		if(correo){ meta.push('<a href="mailto:'+correo+'">'+correo+'</a>'); }
		if(celular){ meta.push('<a href="tel:'+celular+'">'+celular+'</a>'); }
		if(meta.length){ card += '<div class="team-meta">'+meta.join(' · ')+'</div>'; }
		var socials = [];
		if(facebook) socials.push({type:'facebook', url: facebook});
		if(instagram) socials.push({type:'instagram', url: instagram});
		if(linkedin) socials.push({type:'linkedin', url: linkedin});
		if(twitter) socials.push({type:'twitter', url: twitter});
		if(whatsapp) socials.push({type:'whatsapp', url: whatsapp});
		if(socials.length){
			var iconMap = {facebook: 'fab fa-facebook-f', instagram: 'fab fa-instagram', linkedin: 'fab fa-linkedin-in', twitter: 'fab fa-x', whatsapp: 'fab fa-whatsapp'};
			card += '<div class="team-socials">';
			socials.forEach(function(s){ var iconClass = iconMap[s.type] || 'fas fa-globe'; card += '<a href="'+s.url+'" target="_blank" rel="noopener" aria-label="'+s.type+'" class="team-social-link"><i class="'+iconClass+'"></i></a>'; });
			card += '</div>';
		}
		card += '</div></article>';
		return card;
	  }

	  $(function(){
		var $container = $('#home-cards');
		var $prevBtn = $('.team-prev-home');
		var $nextBtn = $('.team-next-home');
		
		$container.html('<div class="team-loading">Cargando abogados...</div>');
		$.getJSON('backend/abogados.php',{action:'home_cards'})
		.done(function(resp){
			if(!resp || !resp.ok){ $container.html('<div class="team-empty">No fue posible cargar abogados.</div>'); return; }
			var data = (resp.data && resp.data.random) || [];
			if(!Array.isArray(data) || data.length === 0){
				$container.html('<div class="team-empty">No fue posible cargar abogados.</div>');
				return;
			}
			
			// Ordenar alfabéticamente por nombre
			data.sort(function(a, b){
				var nameA = (a.nombre || '').toLowerCase();
				var nameB = (b.nombre || '').toLowerCase();
				return nameA.localeCompare(nameB);
			});
			
			$container.empty();
			var currentIndex = 0;
			var itemsPerPage = 3;
			
			// Función para renderizar 3 abogados actuales
			function renderCards(){
				$container.empty();
				var end = Math.min(currentIndex + itemsPerPage, data.length);
				for(var i = currentIndex; i < end; i++){
					$container.append(buildCard(data[i], 'grid'));
				}
			}
			
			// Renderizar inicialmente
			renderCards();
			
			// Botón siguiente
			$nextBtn.on('click', function(e){
				e.preventDefault();
				currentIndex += itemsPerPage;
				if(currentIndex >= data.length){
					currentIndex = 0;
				}
				renderCards();
			});
			
			// Botón anterior
			$prevBtn.on('click', function(e){
				e.preventDefault();
				currentIndex -= itemsPerPage;
				if(currentIndex < 0){
					currentIndex = Math.max(0, data.length - itemsPerPage);
				}
				renderCards();
			});
		})
		.fail(function(){ $container.html('<div class="team-empty">Error cargando datos.</div>'); });
	  });

	})();
	</script>

	</body>
</html>

