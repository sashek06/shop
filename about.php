<?php
	/*
	Главная страница
	*/
	header('Content-type: text/html; charset=utf-8');
	error_reporting(E_ALL);
	include('auth.php');
	include('func.php');
	$title='Главная';
?>
<html data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
  <div class="container">
  <a class="navbar-brand" href="#">
      <img src="images/controller.svg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
      WoWBoost
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarNavDropdown">
		<ul class="navbar-nav">
			<?php
				include('showcase.php');
			?>
		</ul>
		<ul class="navbar-nav">
			<?php
				include('menu.php');
			?>
		</ul>
    </div>
  </div>
</nav>

<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
      	<div class="col-10 col-sm-8 col-lg-6">
       		<img src="images/raids.webp" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy" style="border-radius: 30%;">
		</div>
		<div class="col-lg-6">
        	<h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Рейды и достижения</h1>
        	<p class="lead">Пройдите эпические рейды и заработайте престижные достижения с помощью наших профессионалов. Получите редкие трофеи и выполните уникальные задания быстро и эффективно!</p>
		</div>
	</div>
</div>

<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row align-items-center g-5 py-5">
      	<div class="col-10 col-sm-8 col-lg-6">
       		<img src="images/gold.webp" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy" style="border-radius: 30%;">
		</div>
		<div class="col-lg-6">
        	<h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Золото и игровое время</h1>
        	<p class="lead">Увеличьте свои запасы золота и продлите игровое время с нашими безопасными и надежными услугами. Будьте всегда готовы к новым приключениям в Азероте!</p>
		</div>
	</div>
</div>

<div class="container col-xxl-8 px-4 py-5">
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
      	<div class="col-10 col-sm-8 col-lg-6">
       		<img src="images/safety.webp" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy" style="border-radius: 30%;">
		</div>
		<div class="col-lg-6">
        	<h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3">Безопасность и надежность</h1>
        	<p class="lead">Мы гарантируем полную безопасность и надежность всех наших услуг. Ваш аккаунт и данные находятся под надежной защитой благодаря современным методам шифрования и строгим стандартам безопасности.</p>
		</div>
	</div>
</div>

  

<footer class="footer mt-auto py-3 bg-body-tertiary">
  <div class="container">
    <span class="text-body-secondary">
		<?php
			include('footer.php');
		?>
	</span>
  </div>
</footer>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>