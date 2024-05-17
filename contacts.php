<?php
	/*
	Контакты
	*/
	header('Content-type: text/html; charset=utf-8');
	error_reporting(E_ALL);
	include('auth.php');
	include('func.php');
	$title='Реквизиты';
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
		<td width="900px"   style="vertical-align:top;">

			<h1>Реквизиты</h1>
						<p>
						ООО "Бустинг"
						</p>

						<p>
						Юридический адрес: 123112, город Москва, ул. Ленина, 16-203
						</p>

						<p>
						ИНН: 1234567890
						</p>
						
						<p>
						КПП: 7777777777 
						</p>
						
						<p>
						ОГРН: 1654545772163
						</p>


            </div>

		</td>
	</tr>

	<!-- подвал -->
	<tr>
		<td colspan=2>
			<?php
				include('footer.php');
			?>
		</td>
	</tr>

</table>

</body>
</html>