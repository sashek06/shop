<?php
	header('Content-type: text/html; charset=utf-8');
	include "auth.php";
	error_reporting(E_ALL);
	if (!in_array($_SESSION['level'], array(10, 2))) { // доступ разрешен только группе пользователей
		header("Location: login.php"); // остальных просим залогиниться
		exit;
	};
	$edit=false;

	/*
	Скрипт-редактор
	*/
	include "database.php";
	include "func.php";
	include "scripts.php";
	$con=connect();
	$title='Содержимое заказов';
	$table='items';

	$dt1=!empty($_REQUEST['dt1']) ? htmlentities($_REQUEST['dt1']) : '';
	$dt2=!empty($_REQUEST['dt2']) ? htmlentities($_REQUEST['dt2']) : '';
	$filter_period= (!empty($dt1) && !empty($dt2)) ? " AND orders.dt BETWEEN '$dt1' AND '$dt2' " : '';


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
	$(document).ready(function() {
		$('select').select2();
	});

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

<form>
		Период с <input name="dt1" id="dt1" type="date" value="<?php echo $dt1?>" style="width:200px">
		по <input name="dt2" id="dt2" type="date" value="<?php echo $dt2?>" style="width:200px">
		<input type="submit" value="ОК" style="width:100px">
</form>

<h1><?php echo $title;?></h1>
<?php
	// если надо удалить
	if (!empty($_GET['delete_id'])) {
		$id=intval($_GET['delete_id']);
		$query="
			DELETE FROM `$table`
			WHERE id=$id
		";
		mysqli_query($con, $query) or die(mysqli_error($con));
	};

	// если надо редактировать, загружаем данные
	if (!empty($_GET['edit_id'])) {
		$id=intval($_GET['edit_id']);
		$query="
			SELECT
				`ord_id`, `product_id`, `amount`, `price`, `user_id`
			FROM `$table`
			WHERE id=$id
		";
		$res=mysqli_query($con, $query) or die(mysqli_error($con));
		$row=mysqli_fetch_array($res);
		$ord_id=$row['ord_id'];
		$product_id=$row['product_id'];
		$amount=$row['amount'];
		$price=$row['price'];
		$user_id=$row['user_id'];
	};

	// если надо сохранить (если не пусто)
	if (!empty($_POST['product_id']) && !empty($_POST['user_id'])) {
		$ord_id=abs(intval(trim($_POST['ord_id'])));;
		$product_id=abs(intval(trim($_POST['product_id'])));;
		$amount=abs(floatval(trim($_POST['amount'])));
		$price=abs(floatval(trim($_POST['price'])));
		$user_id=abs(intval(trim($_POST['user_id'])));;

		$fields="
				`ord_id`='$ord_id',
				`product_id`='$product_id',
				`amount`='$amount',
				`price`='$price',
				`user_id`='$user_id'
		";

		// если надо сохранить отредактированное
		if (!empty($_REQUEST['hidden_edit_id'])) {
			$id=intval($_REQUEST['hidden_edit_id']);
			$query="
				UPDATE `$table`
				SET
					$fields
				WHERE
					id=$id
			";
		}
		else { // добавление новой строки
			$query="
				INSERT INTO `$table`
				SET
					$fields
			";
		};

		mysqli_query($con, $query) or die(mysqli_error($con));
		if (!$id) $id=mysqli_insert_id($con);

	};

	if (isset($_POST['btn_submit'])) // была нажата кнопка сохранить - не надо больше отображать id
		$id=0;

	// добавляем возможность удаления админам
	$delete_confirm="onClick=\"return window.confirm(\'Подтверждаете удаление?\');\"";
	$admin_delete=$edit ? ", CONCAT('<a href=\"$table.php?delete_id=', `$table`.id, '\" $delete_confirm>', 'удалить&nbsp;#', `$table`.id, '</a>') AS 'Удаление'" : '';
	// добавляем возможность редактирования админам
	$admin_edit=$edit ? ", CONCAT('<a href=\"$table.php?edit_id=', `$table`.id, '\">', 'редактировать&nbsp;#', `$table`.id, '</a>') AS 'Редактирование'" : '';
	$query="
		SELECT
			`$table`.`id` AS 'Код',
			`$table`.`ord_id` AS '№ заказа',
			`orders`.`dt` AS 'Дата заказа',
			`products`.`name` AS 'Товар',
			`$table`.`price` AS 'Цена',
			`$table`.`amount` AS 'Количество',
			CONCAT(`users`.`login`, ' (', `users`.`surname`, `users`.`name`, `users`.`middlename`, ') ', `users`.`phone`) AS 'Пользователь'
			$admin_delete
			$admin_edit
		FROM
			`$table`
		LEFT JOIN
			`products` ON `products`.`id`=`$table`.`product_id`
		LEFT JOIN
			`users` ON `users`.`id`=`$table`.`user_id`
		LEFT JOIN
			`orders` ON `orders`.`id`=`$table`.`ord_id`
		WHERE 1
			$filter_period
	";

	echo SQLResultTable($query, $con, '');
?>

<?php
	// доступ к редактированию только админу
	if ($_SESSION['login']=='admin') { // if (admin)
?>
<form name="form" action="<?php echo $table?>.php" method="post">
	<table>
		<tr>
			<th colspan="2">
				<p>Редактор <?php if (!empty($id)) echo "(редактируется строка с кодом $id)";?></p>
			</th>
		</tr>

		<tr>
			<td>№ заказа</td>
			<td>
				<input id="ord_id" name="ord_id" type="text" value="<?php if (!empty($ord_id)) echo $ord_id;?>">
			</td>
		</tr>

		<tr>
			<td>Количество</td>
			<td>
				<input id="amount" name="amount" type="text" value="<?php if (!empty($amount)) echo $amount;?>">
			</td>
		</tr>

		<tr>
			<td>Товар</td>
			<td>
				<select id="product_id" name="product_id">
					<?php
						$query="
							SELECT `id`, `name`
							FROM `products`
							ORDER BY `name`
						";
						$res=mysqli_query($con, $query) or die(mysqli_error($con));
						while ($row=mysqli_fetch_array($res, MYSQLI_ASSOC)) {
							$selected= ($product_id==$row['id']) ? 'selected' : '';
							echo "
								<option value='$row[id]' $selected>$row[name]</option>
							";
						};
					?>
				</select>
			</td>
		</tr>

		<tr>
			<td>Цена за 1ед. </td>
			<td>
				<input id="price" name="price" type="text" value="<?php if (!empty($price)) echo $price;?>">
			</td>
		</tr>

		<tr>
			<td>Пользователь</td>
			<td>
				<input id="user_id" name="user_id" type="text" value="<?php if (!empty($user_id)) echo $user_id;?>">
			</td>
		</tr>

	<input name="hidden_edit_id" type="hidden" value="<?php if (!empty($id)) echo $id;?>">

	<tr>
		<td colspan='2'>
			<button id="btn_reset" onclick="btn_reset_click();">Очистить поля</button>
			<button id="btn_submit" name="btn_submit" type="submit">Сохранить</button>
		</td>
	</tr>
	</table>

</form>
<?php
	}; // if (admin)
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