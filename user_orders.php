<?php
	header('Content-type: text/html; charset=utf-8');
	include "auth.php";
	error_reporting(E_ALL);
	if (!in_array($_SESSION['level'], array(10,2,1))) { // доступ разрешен только группе пользователей
		header("Location: login.php"); // остальных просим залогиниться
		exit;
	};

	/*
	Скрипт показывает таблицу заказов пользователя
	*/
	include "database.php";
	include "func.php";
	include "scripts.php";
	$con=connect();
	$title='Заказы';
	$table='orders';
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
	if (!empty($_REQUEST['Month']) && !empty($_REQUEST['Year'])
			&& !empty($_REQUEST['card1'])
			&& !empty($_REQUEST['card2'])
			&& !empty($_REQUEST['card3'])
			&& !empty($_REQUEST['card4'])
	) {
		echo "<p>Оплата проведена успешно!</p>";
	};
	$query="
		SELECT
			`$table`.`id`,
			DATE_FORMAT(`$table`.`dt`, '%d.%m.%Y %H:%i:%s') AS `dt`,
			`statuses`.`descr`
		FROM
			`$table`
		LEFT JOIN
			`statuses` ON `statuses`.`id`=`$table`.`status`
		WHERE 1
			AND `$table`.`user_id`=".$_SESSION['id']."
		ORDER BY `$table`.`dt` DESC
	";

	$res=mysqli_query($con, $query) or die(mysqli_error($con));

	if (!mysqli_num_rows($res)) {
		die('<h3>У вас нет заказов</h3>');
	}
	else {
		echo '
		<table border=0 style="width:900px">
			<thead>
			<tr>
				<td>№ заказа</td>
				<td>Дата</td>
				<td colspan="2">Статус</td>
			</tr>
			</thead>
			<tbody>
		';
		while ($row=mysqli_fetch_array($res, MYSQLI_BOTH)) {
			echo "
			<tr>
				<td>$row[id]</td>
				<td>$row[dt]</td>
				<td>$row[descr]</td>
				<td><a href='user_items.php?ord_id=$row[id]'>Просмотр</a></td>
			</tr>
			";
		};
		echo "
		</tbody></table>";
	};
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