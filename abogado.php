<?php
require_once __DIR__ . '/inc/nocache.php';
require_once __DIR__ . '/backend/db.php';
$pdo = getPDO();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if(!$id){
    http_response_code(404);
    echo "<h1>Abogado no encontrado</h1>";
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM abogados WHERE id = ?');
$stmt->execute([$id]);
$a = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$a){
    http_response_code(404);
    echo "<h1>Abogado no encontrado</h1>";
    exit;
}

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
$base = $scheme . '://' . $host;

function norm($f){
    global $base;
    if(!$f) return null;
    $f = trim($f);
    if($f === '') return null;
    if(strpos($f,'http://')===0 || strpos($f,'https://')===0) return $f;
    if($f[0] !== '/') $f = '/'.$f;
    return $base . $f;
}

$foto_full = isset($a['foto_full']) ? norm($a['foto_full']) : null;
$foto_carnet = isset($a['foto_carnet']) ? norm($a['foto_carnet']) : null;
$legacy = isset($a['foto']) ? norm($a['foto']) : null;

$photo = $foto_carnet ?: $legacy ?: $foto_full ?: '/images/user-2.jpg';
?>

<!doctype html>
<html>
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title><?= htmlspecialchars($a['nombre']) ?> — Perfil</title>

<link rel="stylesheet" href="/css/bootstrap.css">
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/team-cards.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

:root{
--navy:#0f2a44;
--gold:#d4af37;
--soft:#f5f8fb;
--muted:#6b7b90;
}

/* NAVBAR */

nav.colorlib-nav{
position:relative;
z-index:9999;
background:white;
box-shadow:0 2px 12px rgba(0,0,0,0.08);
}

/* HERO */

.hero{
background:linear-gradient(120deg,#0f2a44,#07121f);
color:white;
padding:80px 0 50px;
}

.hero-inner{
max-width:1100px;
margin:auto;
padding:0 20px;
}

.hero-title{
font-size:42px;
font-family:'Playfair Display',serif;
color:var(--gold);
margin-bottom:8px;
}

.hero-sub{
font-size:18px;
opacity:.9;
}

/* MAIN */

.profile-main{
max-width:1100px;
margin:auto;
padding:40px 20px;
}

/* GRID */

.profile-card{
display:grid;
grid-template-columns:620px 1fr;
gap:40px;
}

/* FOTO */

.profile-photo img{
width:100%;
height:820px;
object-fit:cover;
object-position:top center;
border-radius:10px;
box-shadow:0 22px 48px rgba(0,0,0,0.12);
background:#fff;
}

/* CONTACTO */

.profile-aside{
background:white;
padding:22px;
border-radius:10px;
margin-top:20px;
box-shadow:0 10px 30px rgba(0,0,0,0.05);
transition:transform .18s ease, box-shadow .18s ease;
}

.profile-aside h4{
color:var(--gold);
margin-bottom:15px;
}

.contact-row{
margin-bottom:10px;
font-size:14px;
}

.contact-row a{
color:var(--gold);
font-weight:600;
text-decoration:none;
}

/* Hover animations for cards and photo */
.section-card, .profile-aside, .profile-photo{
    transition:transform .22s ease, box-shadow .22s ease;
}
.section-card:hover, .profile-aside:hover, .profile-photo:hover{
    transform:translateY(-6px);
    box-shadow:0 28px 60px rgba(0,0,0,0.14);
}
.profile-photo img{ transition:transform .22s ease; }
.profile-photo:hover img{ transform:scale(1.02); }

/* REDES */

.social-row{
margin-top:10px;
}

.social-row a{
width:36px;
height:36px;
display:inline-flex;
align-items:center;
justify-content:center;
background:#f2f5f8;
border-radius:6px;
margin-right:6px;
color:var(--navy);
transition:background .15s ease, transform .12s ease;
}
.social-row a:hover{ background:var(--gold); color:white; transform:translateY(-3px); }

/* BOTONES */

.btn-cta{
background:var(--gold);
color:var(--navy);
padding:10px 16px;
border-radius:6px;
text-decoration:none;
display:inline-block;
margin-top:12px;
box-shadow:0 6px 18px rgba(212,175,55,0.12);
transition:transform .12s ease, box-shadow .12s ease, background .12s ease;
}

.btn-cta:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 28px rgba(15,42,68,0.12);
    background:#c89f2f;
    color:var(--navy);
}

/* META */

.profile-meta h1{
font-size:36px;
margin-bottom:6px;
color:var(--navy);
font-family:'Playfair Display',serif;
}

.cargo{
color:var(--gold);
font-weight:700;
margin-bottom:10px;
}

/* BADGES */

.area-badges{
margin-top:10px;
}

.badge-area{
background:#eef3f8;
padding:6px 12px;
border-radius:20px;
font-size:13px;
margin-right:6px;
display:inline-block;
}

/* BIO */

.profile-intro{
margin-top:20px;
line-height:1.7;
font-size:15px;
}

/* SECTIONS */

.profile-sections{
margin-top:30px;
display:grid;
gap:20px;
}

.section-card{
background:white;
padding:22px;
border-radius:10px;
box-shadow:0 10px 25px rgba(0,0,0,0.05);
}

.section-card h4{
color:var(--navy);
margin-bottom:15px;
}

/* EXPERIENCIA */

.exp-list{
list-style:none;
padding:0;
}

.exp-list li{
margin-bottom:10px;
position:relative;
padding-left:18px;
}

.exp-list li:before{
content:"";
width:8px;
height:8px;
background:var(--gold);
border-radius:50%;
position:absolute;
left:0;
top:7px;
}

/* Collapsible section cards */
.section-card{ overflow:hidden; }
.section-toggle{ display:flex; align-items:center; justify-content:space-between; width:100%; background:transparent; border:none; padding:0; cursor:pointer; }
.section-toggle h4{ margin:0; color:var(--navy); }
.section-toggle .chev{ font-size:18px; color:var(--muted); transition:transform .18s ease; }
.section-card.collapsed .chev{ transform:rotate(-90deg); }
.section-body{ margin-top:12px; }
.section-card.collapsed .section-body{ display:none; }

/* RESPONSIVE */

@media(max-width:1400px){
    .profile-card{ grid-template-columns:520px 1fr; }
    .profile-photo img{ height:680px; }
}

@media(max-width:900px){

    .profile-card{
        grid-template-columns:1fr;
    }

    .profile-photo img{
        height:420px;
    }

    .hero-title{
        font-size:28px;
    }

}

</style>

</head>

<body>

<!-- Navbar copied from index to match site header -->
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

<section class="profile-main">

<div class="profile-card">

<div>

<div class="profile-photo">
<img src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($a['nombre']) ?>">
</div>

<div class="profile-aside">

<h4>Contacto</h4>

<?php if(!empty($a['correo'])): ?>
<div class="contact-row">
<i class="fa-regular fa-envelope"></i>
<a href="mailto:<?= htmlspecialchars($a['correo']) ?>">
<?= htmlspecialchars($a['correo']) ?>
</a>
</div>
<?php endif; ?>

<?php if(!empty($a['celular'])): ?>
<div class="contact-row">
<i class="fa-solid fa-phone"></i>
<a href="tel:<?= htmlspecialchars($a['celular']) ?>">
<?= htmlspecialchars($a['celular']) ?>
</a>
</div>
<?php endif; ?>

<?php if(!empty($a['whatsapp'])): ?>
<?php endif; ?>

<div class="social-row">

<?php if(!empty($a['facebook'])): ?>
<a href="<?= htmlspecialchars($a['facebook']) ?>" target="_blank">
<i class="fab fa-facebook-f"></i>
</a>
<?php endif; ?>

<?php if(!empty($a['instagram'])): ?>
<a href="<?= htmlspecialchars($a['instagram']) ?>" target="_blank">
<i class="fab fa-instagram"></i>
</a>
<?php endif; ?>

<?php if(!empty($a['linkedin'])): ?>
<a href="<?= htmlspecialchars($a['linkedin']) ?>" target="_blank">
<i class="fab fa-linkedin-in"></i>
</a>
<?php endif; ?>

<?php if(!empty($a['whatsapp'])): ?>
<a href="<?= htmlspecialchars($a['whatsapp']) ?>" target="_blank" title="WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>

</div>

<?php if(!empty($a['correo'])): ?>
<a class="btn-cta" href="mailto:<?= htmlspecialchars($a['correo']) ?>">
Contactar
</a>
<?php endif; ?>

</div>

</div>

<div>

<div class="profile-meta">

<h1><?= htmlspecialchars($a['nombre']) ?></h1>

<div class="cargo"><?= htmlspecialchars($a['cargo']) ?></div>

<?php if(!empty($a['area_practica'])): ?>

<div class="area-badges">

<?php foreach(explode(',', $a['area_practica']) as $badge): ?>

<span class="badge-area">
<?= htmlspecialchars(trim($badge)) ?>
</span>

<?php endforeach; ?>

</div>

<?php endif; ?>

<?php if(!empty($a['descripcion'])): ?>

<div class="profile-intro">
<?= nl2br(htmlspecialchars($a['descripcion'])) ?>
</div>

<?php endif; ?>

</div>

<div class="profile-sections">

<?php if(!empty($a['experiencia'])): ?>

<div class="section-card">
    <button class="section-toggle" aria-expanded="true"><h4>Experiencia profesional</h4><span class="chev">▾</span></button>
    <div class="section-body">
        <ul class="exp-list">
            <?php foreach(preg_split('/\r?\n/', $a['experiencia']) as $line): if(trim($line)==='') continue; ?>
                <li><?= htmlspecialchars(trim($line)) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php endif; ?>

<?php if(!empty($a['formacion'])): ?>

<div class="section-card collapsed">
    <button class="section-toggle" aria-expanded="false"><h4>Formación y especializaciones</h4><span class="chev">▾</span></button>
    <div class="section-body">
        <div><?= nl2br(htmlspecialchars($a['formacion'])) ?></div>
    </div>
</div>

<?php endif; ?>

<?php if(!empty($a['publicaciones']) || !empty($a['docencia']) || !empty($a['distinciones'])): ?>

<div class="section-card collapsed">
    <button class="section-toggle" aria-expanded="false"><h4>Otros</h4><span class="chev">▾</span></button>
    <div class="section-body">
        <?php if(!empty($a['docencia'])): ?>
            <div style="margin-bottom:10px;"><strong>Docencia</strong>
                <div><?= nl2br(htmlspecialchars($a['docencia'])) ?></div>
            </div>
        <?php endif; ?>

        <?php if(!empty($a['publicaciones'])): ?>
            <div style="margin-bottom:10px;"><strong>Publicaciones</strong>
                <div><?= nl2br(htmlspecialchars($a['publicaciones'])) ?></div>
            </div>
        <?php endif; ?>

        <?php if(!empty($a['distinciones'])): ?>
            <div><strong>Distinciones</strong>
                <div><?= nl2br(htmlspecialchars($a['distinciones'])) ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php endif; ?>

</div>

</div>

</div>

</section>

<script src="/js/navbar-load.js?v=20260319"></script>
<?php include __DIR__ . '/footer.php'; ?>

</body>
</html>

<script>
// Collapsible sections: toggle on click
document.addEventListener('DOMContentLoaded', function(){
    var toggles = document.querySelectorAll('.section-toggle');
    toggles.forEach(function(btn){
        btn.addEventListener('click', function(e){
            var card = btn.closest('.section-card');
            var expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            if(card) card.classList.toggle('collapsed');
        });
    });
});
</script>