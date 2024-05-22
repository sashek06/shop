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

<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<script>
		function btn_reset_click() {
			$('input').val('');
		};
	</script>
</head>

<?php
    include('showcase.php');
    include('menu.php');
?>

<section class="user-orders mt-5 pt-5" style="min-height: 100vh;">
	<div class="container">
		<h1><?php echo $title;?></h1>
		<?php
			if (!empty($_REQUEST['Month']) && !empty($_REQUEST['Year'])
					&& !empty($_REQUEST['card1'])
					&& !empty($_REQUEST['card2'])
					&& !empty($_REQUEST['card3'])
					&& !empty($_REQUEST['card4'])
			) {
				echo "<p class='alert alert-success'>Оплата проведена успешно!</p>";
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
				die('<h3 class="alert alert-warning">У вас нет заказов</h3>');
			}
			else {
				echo '
				<table class="table table-striped">
				<thead class="thead-dark">
					<tr>
					<th scope="col">№ заказа</th>
					<th scope="col">Дата</th>
					<th scope="col">Статус</th>
					<th scope="col">Действие</th>
					</tr>
				</thead>
				<tbody>
				';
				while ($row=mysqli_fetch_array($res, MYSQLI_BOTH)) {
					echo "
					<tr>
					<th scope='row'>$row[id]</th>
					<td>$row[dt]</td>
					<td>$row[descr]</td>
					<td><a class='btn btn-primary' href='user_items.php?ord_id=$row[id]'>Просмотр</a></td>
					</tr>
					";
				};
				echo "
				</tbody>
				</table>";
			};
		?>
	</div>
</section>

<?php
    include('footer.php');
?>