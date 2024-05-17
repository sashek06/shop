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
	$title='Товары';
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
	// если надо удалить
	if (!empty($_GET['delete_id'])) {
		$id=intval($_GET['delete_id']);

		// каскадное удаление из содержимого заказов
		$query="
			DELETE FROM `items`
			WHERE
				product_id=$id
		";
		mysqli_query($con, $query) or die(mysqli_error($con));

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
				`name`, `descr`, `cat_id`, `price`, `discount_id`, `amount`, `weight`
			FROM `$table`
			WHERE id=$id
		";
		$res=mysqli_query($con, $query) or die(mysqli_error($con));
		$row=mysqli_fetch_array($res);
		$descr=$row['descr'];
		$name=$row['name'];
		$cat_id=$row['cat_id'];
		$price=$row['price'];
		$discount_id=$row['discount_id'];
		$amount=$row['amount'];
		$weight=$row['weight'];
	};

	// если надо сохранить (если не пусто)
	if (!empty($_POST['name'])) {
		$name=mysqli_real_escape_string($con, trim($_POST['name']));
		$descr=mysqli_real_escape_string($con, trim($_POST['descr']));
		$cat_id=intval(trim($_POST['cat_id']));
		$price=mysqli_real_escape_string($con, trim($_POST['price']));
		$discount_id=intval(trim($_POST['discount_id']));
		$amount=intval(trim($_POST['amount']));
		$weight=intval(trim($_POST['weight']));

		$fields="
				`name`='$name',
				`descr`='$descr',
				`cat_id`='$cat_id',
				`price`='$price',
				`discount_id`='$discount_id',
				`amount`='$amount',
				`weight`='$weight'
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

		// если была произведена отправка формы
		if(isset($_FILES['file'])) {
			// проверяем, можно ли загружать изображение
			$check = can_upload($_FILES['file']);

			if($check === true){
				// загружаем изображение на сервер
				make_upload($_FILES['file'], $id);
				echo "<strong>Файл успешно загружен!</strong>";
			}
			else{
				// выводим сообщение об ошибке
				echo "<strong>$check</strong>";
			}
		};
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
			CONCAT('<image src=\"upload/', `$table`.`id`, '.jpg\" style=\"width:32px; height:32px\" alt=\"нет фото\">', `$table`.`name`) AS 'Наименование',
			SUBSTRING(`$table`.`descr`, 1, 100) AS 'Описание', # первые 100 символов строки
			`categories`.`name` AS 'Категория',
			`$table`.`price` AS 'Цена',
			`discounts`.`value` AS 'Размер скидки',
			`discounts`.`name` AS 'Наименование акции/скидки',
			`$table`.`amount` AS 'Количество',
			IFNULL(ROUND(SUM(`items`.`amount`)),0) AS 'Бронировано',
			`$table`.`amount`- IFNULL(ROUND(SUM(`items`.`amount`)),0) AS 'Свободный остаток',
			`$table`.`weight` AS 'Масса'
			$admin_delete
			$admin_edit
		FROM
			`$table`
		LEFT JOIN
			`items` ON `items`.`product_id`=`products`.`id`
#		LEFT JOIN
#			`orders` ON (orders.id=items.ord_id #AND orders.status=0)
		LEFT JOIN
			`categories` ON `categories`.`id`=`$table`.`cat_id`
		LEFT JOIN
			`discounts` ON `discounts`.`id`=`$table`.`discount_id`
		WHERE 1
		GROUP BY `$table`.`id`
		ORDER BY `$table`.`name`
		LIMIT 50;
	";

	echo SQLResultTable($query, $con, '');
?>

<?php
	// доступ к редактированию только админу
	if ($edit) { // if (admin)
?>
<form name="form" action="<?php echo $table?>.php" method="post" enctype="multipart/form-data">
	<table>
		<tr>
			<th colspan="2">
				<p>Редактор <?php if (!empty($id)) echo "(редактируется строка с кодом $id)";?></p>
			</th>
		</tr>

		<tr>
			<td>Наименование</td>
			<td>
				<input type="text" id="name" name="name" value="<?php if (!empty($name)) echo $name;?>">
			</td>
		</tr>

		<tr>
			<td>Описание</td>
			<td>
				<textarea id="descr" name="descr" type="textarea"><?php if (!empty($descr)) echo $descr;?></textarea>
			</td>
		</tr>

		<tr>
			<td>Цена за 1ед. </td>
			<td>
				<input type="text" id="price" name="price" value="<?php if (!empty($price)) echo $price;?>">
			</td>
		</tr>

		<tr>
			<td>Категория</td>
			<td>
				<select id="cat_id" name="cat_id">
					<?php
						$query="
							SELECT `id`, `name`, `descr`
							FROM `categories`
							WHERE
								id<>0
							ORDER BY `name`
						";
						$res=mysqli_query($con, $query) or die(mysqli_error($con));
						while ($row=mysqli_fetch_array($res, MYSQLI_ASSOC)) {
							$selected= ($cat_id==$row['id']) ? 'selected' : '';
							echo "
								<option value='$row[id]' $selected>$row[name]</option>
							";
						};
					?>
				</select>
			</td>
		</tr>

		<tr>
			<td>Количество</td>
			<td>
				<input type="text" id="amount" name="amount" value="<?php if (!empty($amount)) echo $amount;?>">
			</td>
		</tr>

<!--
		<tr>
			<td>Масса</td>
			<td>
				<input type="text" id="weight" name="weight" value="<?php if (!empty($weight)) echo $weight;?>">
			</td>
		</tr>
-->

		<tr>
			<td>Скидка или акция</td>
			<td>
				<select id="discount_id" name="discount_id">
					<option value='0'>нет скидки</option>
					<?php
						$query="
							SELECT `id`, `name`, `value`
							FROM `discounts`
							WHERE	1
								AND NOW() BETWEEN `start` AND `stop`
							ORDER BY `name`
						";
						$res=mysqli_query($con, $query) or die(mysqli_error($con));
						while ($row=mysqli_fetch_array($res, MYSQLI_ASSOC)) {
							$selected= ($discount_id==$row['id']) ? 'selected' : '';
							echo "
								<option value='$row[id]' $selected>$row[name] ($row[value]%)</option>
							";
						};
					?>
				</select>
			</td>
		</tr>

		<tr>
			<td>Фото</td>
			<td>
				<input type="file" name="file">
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