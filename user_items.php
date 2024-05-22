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
	$ord_id=abs(intval(trim($_GET['ord_id'])));

	$title="Просмотр заказа №$ord_id";
	$table='items';
?>
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

<?php
    include('showcase.php');
    include('menu.php');
?>

<section class="order-item mt-5 pt-5" style="min-height: 100vh;">
	<div class="container">
		<h1><?php echo $title;?></h1>
		<?php
		$query="
			SELECT
				`$table`.`id`,
				`$table`.`ord_id`,
				`products`.`name`,
				`$table`.`amount`,
				`$table`.`price`
			FROM
				`$table`
			LEFT JOIN
				`products` ON `products`.`id`=`$table`.`product_id`
			LEFT JOIN
				`users` ON `users`.`id`=`$table`.`user_id`
			WHERE 1
				AND `users`.`id`='".$_SESSION['id']."'
				AND `items`.`ord_id`='$ord_id'
		";

		$sum=0;
		$amount=0;
		$res=mysqli_query($con, $query) or die(mysqli_error($con));

		echo '
		<table class="table table-striped">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Наименование</th>
					<th scope="col">Количество</th>
					<th scope="col">Цена за 1ед.</th>
					<th scope="col">Сумма</th>
				</tr>
			</thead>
			<tbody>
		';
		while ($row=mysqli_fetch_array($res, MYSQLI_BOTH)) {
			$sum+=round($row['price']*$row['amount'], 2);
			$amount+=$row['amount'];
			echo "
			<tr>
				<td>$row[name]</td>
				<td>$row[amount]</td>
				<td>$row[price]</td>
				<td>".(round($row['price']*$row['amount'], 2))."</td>
			</tr>
			";
		};
		echo "
		<tr>
			<td colspan='4'>
				<b>Итого: единиц $amount на сумму $sum</b>
			</td>
		</tr>
		</tbody></table>";
		?>
	</div>
</section>

<?php
    include('footer.php');
?>
