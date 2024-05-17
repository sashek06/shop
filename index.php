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
</body>
</html>