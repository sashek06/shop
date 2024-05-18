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
  <div class="container-fluid">
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

<div class="px-4 py-5 my-5 text-center">
    <img class="d-block mx-auto mb-4" src="images/lvlup.webp" alt="" width="200" height="200" style="border-radius: 30%;">
    <h1 class="display-5 fw-bold text-body-emphasis">Эпичный прогресс</h1>
    <div class="col-lg-6 mx-auto">
      <p class="lead mb-4">Ускорьте прогресс в World of Warcraft с нашими бустинг-услугами! Легко достигайте целей как в PvE, так и в PvP. Безопасный и надежный сервис от профессионалов. Присоединяйтесь к тысячам довольных игроков и станьте сильнейшим в Азероте!</p>
      <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
        <button type="button" class="btn btn-primary btn-lg px-4 gap-3">Подробнее</button>
<!--         <button type="button" class="btn btn-outline-secondary btn-lg px-4">Secondary</button> -->
      </div>
    </div>
  </div>

<section class="footer">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php
					include('footer.php');
				?>
			</div>
		</div>
	</div>
</section>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>