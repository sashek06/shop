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
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
  <a class="navbar-brand" href="#">
      <img src="images/controller.svg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
      WoWBoost
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Features</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Pricing</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown link
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
          </ul>
        </li>
      </ul>
	  <!-- <?php
			include('menu.php');
			include('showcase.php');
		?> -->
    </div>
  </div>
</nav>

<table id="main_table" border="0">
	<!-- баннер -->
	<tr>
		<td colspan=2 style="text-align:center">
			<?php
				include('top.php');
			?>
		</td>
	</tr>

	<tr>
		<!-- меню -->
		<td width="270px" class="menu" style="vertical-align:top;">
			<?php
				include('menu.php');
				include('showcase.php');
			?>
		</td>

		<!-- контент -->
		<td width="900px" style="vertical-align:top;">

<p>
Стремитесь к вершинам в World of Warcraft, но не хватает времени и сил для прокачки персонажа? 
</p>
<p>
Наш сайт по бустингу WoW поможет вам достичь желаемых результатов быстро и без лишних усилий! 
</p>
<p>
Мы предлагаем профессиональные услуги по прокачке персонажей, выполнению рейдов и достижений, 
а также продаже игровой валюты. Наши опытные игроки гарантируют безопасность и конфиденциальность ваших данных. 
</p>
<p>
Заказывайте услуги бустинга WoW у нас и получайте удовольствие от игры, не теряя время на монотонную прокачку!
</p>
<img src="images/main.jpg" style="border-radius:5px">
<p>
<a href="about.php">Подробности</a>
</p>
<p>
Спасибо за ваш выбор!
</p>


		</td>
	</tr>

	<!-- подвал -->
	<!-- <tr>
		<td colspan=2 class="sticky-bottom">
			<?php
				include('footer.php');
			?>
		</td>
	</tr> -->

</table>

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