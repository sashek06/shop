<?php
	header('Content-type: text/html; charset=utf-8');
	include "auth.php";
	error_reporting(E_ALL);
	if (!in_array($_SESSION['level'], array(10,2))) { // доступ разрешен только группе пользователей
		header("Location: login.php"); // остальных просим залогиниться
		exit;
	};
	$edit=in_array($_SESSION['level'], array(10, 2)) ? true : false;

	/*
	Скрипт-редактор
	*/
	include "database.php";
	include "func.php";
	include "scripts.php";
	$con=connect();
	$free_remains=1000;
	$title="Товары со свободным остатком &leq;$free_remains";
	$table='products';
?>
<html data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
<script>
	function btn_reset_click() {
		$('input').val('');
	};
</script>

<style>
	input{
		width:100%;
	}
</style>

</head>

<body>
<table id="main_table">
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
		<td width="300px" class="menu2">
			<?php
				include('menu.php');
			?>
		</td>

		<!-- контент -->
		<td width="900px" class="content">

<h1><?php echo $title;?></h1>
<?php
	$query="
		SELECT q.* FROM(
		SELECT
			`$table`.`id` AS 'Код',
			`$table`.`name` AS 'Наименование',
			SUBSTRING(`$table`.`descr`, 1, 100) AS 'Описание', # первые 100 символов строки
			`categories`.`name` AS 'Категория',
			`$table`.`amount` AS 'Количество',
			IFNULL(ROUND(SUM(`items`.`amount`)),0) AS 'Бронировано',
			`$table`.`amount`- IFNULL(ROUND(SUM(`items`.`amount`)),0) AS 'Свободный остаток'
		FROM
			`$table`
		LEFT JOIN
			`items` ON `items`.`product_id`=`products`.`id`
		LEFT JOIN
			`categories` ON `categories`.`id`=`$table`.`cat_id`
		LEFT JOIN
			`discounts` ON `discounts`.`id`=`$table`.`discount_id`
		WHERE 1
		GROUP BY `$table`.`id`
		ORDER BY `$table`.`name`
		LIMIT 50
		) AS q
		WHERE `q`.`Свободный остаток`<=$free_remains;
	";

	echo SQLResultTable($query, $con, '');
?>
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