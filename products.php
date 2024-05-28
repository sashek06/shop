<?php
header('Content-type: text/html; charset=utf-8');
include "auth.php";
error_reporting(E_ALL);
if (!in_array($_SESSION['level'], array(10, 2))) { // доступ разрешен только группе пользователей
	header("Location: login.php"); // остальных просим залогиниться
	exit;
};
$edit = in_array($_SESSION['level'], array(10, 2)) ? true : false;

/*
	Скрипт-редактор
	*/
include "database.php";
include "func.php";
include "scripts.php";
$con = connect();
$title = 'Товары';
$table = 'products';
?>

<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<script>
		function btn_reset_click() {
			$('input').val('');
		};
	</script>

	<style>
		input {
			width: 100%;
		}
	</style>

</head>

<?php
include('showcase.php');
include('menu.php');
?>

<table id="main_table">

<section class="product-table">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-12 h1 mt-5 text-center""><?php echo $title;?></div>
			<div class="col-12 overflow-scroll">
			<?php
	// если надо удалить
	if (!empty($_GET['delete_id'])) {
		$id=intval($_GET['delete_id']);

						// каскадное удаление из содержимого заказов
						$query = "
			DELETE FROM `items`
			WHERE
				product_id=$id
		";
						mysqli_query($con, $query) or die(mysqli_error($con));

						$query = "
			DELETE FROM `$table`
			WHERE id=$id
		";
						mysqli_query($con, $query) or die(mysqli_error($con));
					};

					// если надо редактировать, загружаем данные
					if (!empty($_GET['edit_id'])) {
						$id = intval($_GET['edit_id']);
						$query = "
			SELECT
				`name`, `descr`, `cat_id`, `price`, `discount_id`, `amount`
			FROM `$table`
			WHERE id=$id
		";
						$res = mysqli_query($con, $query) or die(mysqli_error($con));
						$row = mysqli_fetch_array($res);
						$descr = $row['descr'];
						$name = $row['name'];
						$cat_id = $row['cat_id'];
						$price = $row['price'];
						$discount_id = $row['discount_id'];
						$amount = $row['amount'];
					};

					// если надо сохранить (если не пусто)
					if (!empty($_POST['name'])) {
						$name = mysqli_real_escape_string($con, trim($_POST['name']));
						$descr = mysqli_real_escape_string($con, trim($_POST['descr']));
						$cat_id = intval(trim($_POST['cat_id']));
						$price = mysqli_real_escape_string($con, trim($_POST['price']));
						$discount_id = intval(trim($_POST['discount_id']));
						$amount = intval(trim($_POST['amount']));


						$fields = "
				`name`='$name',
				`descr`='$descr',
				`cat_id`='$cat_id',
				`price`='$price',
				`discount_id`='$discount_id',
				`amount`='$amount'
		";


						// если надо сохранить отредактированное
						if (!empty($_REQUEST['hidden_edit_id'])) {
							$id = intval($_REQUEST['hidden_edit_id']);
							$query = "
				UPDATE `$table`
				SET
					$fields
				WHERE
					id=$id
			";
						} else { // добавление новой строки
							$query = "
				INSERT INTO `$table`
				SET
					$fields
			";
						};

						mysqli_query($con, $query) or die(mysqli_error($con));
						if (!$id) $id = mysqli_insert_id($con);

						// если была произведена отправка формы
						if (isset($_FILES['file'])) {
							// проверяем, можно ли загружать изображение
							$check = can_upload($_FILES['file']);

							if ($check === true) {
								// загружаем изображение на сервер
								make_upload($_FILES['file'], $id);
								echo "<strong>Файл успешно загружен!</strong>";
							} else {
								// выводим сообщение об ошибке
								echo "<strong>$check</strong>";
							}
						};
					};

					if (isset($_POST['btn_submit'])) // была нажата кнопка сохранить - не надо больше отображать id
						$id = 0;

					// добавляем возможность удаления админам
					$delete_confirm = "onClick=\"return window.confirm(\'Подтверждаете удаление?\');\"";
					$admin_delete = $edit ? ", CONCAT('<a href=\"$table.php?delete_id=', `$table`.id, '\" $delete_confirm>', 'удалить&nbsp;#', `$table`.id, '</a>') AS 'Удаление'" : '';
					// добавляем возможность редактирования админам
					$admin_edit = $edit ? ", CONCAT('<a href=\"$table.php?edit_id=', `$table`.id, '\">', 'редактировать&nbsp;#', `$table`.id, '</a>') AS 'Редактирование'" : '';
					$query = "
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
			`$table`.`amount`- IFNULL(ROUND(SUM(`items`.`amount`)),0) AS 'Свободный остаток'
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
				</div>
			</div>
	</section>

	<?php
	// доступ к редактированию только админу
	if ($edit) { // if (admin)
	?>

		<section class="add-form">
			<div class="container">
				<div class="row justify-content-center">
					<form class="col-6" name="form" action="<?php echo $table ?>.php" method="post" enctype="multipart/form-data">
						<div class="h2 my-3">
							<p>Редактор <?php if (!empty($id)) echo "(редактируется строка с кодом $id)"; ?></p>
						</div>

						<div class="mb-3">
							<label for="name" class="form-label">Наименование</label>
							<input type="text" class="form-control" id="name" name="name" value="<?php if (!empty($name)) echo $name; ?>">
						</div>

						<div class="mb-3">
							<label for="descr" class="form-label">Описание</label>
							<textarea class="form-control" id="descr" name="descr" rows="3"><?php if (!empty($descr)) echo $descr; ?></textarea>
						</div>

						<div class="mb-3">
							<label for="price" class="form-label">Цена за 1ед.</label>
							<input type="text" class="form-control" id="price" name="price" value="<?php if (!empty($price)) echo $price; ?>">
						</div>

						<div class="mb-3">
							<label for="cat_id" class="form-label">Категория</label>
							<select class="form-control" id="cat_id" name="cat_id">
								<?php
								$query = "
							SELECT `id`, `name`, `descr`
							FROM `categories`
							WHERE id<>0
							ORDER BY `name`
						";
								$res = mysqli_query($con, $query) or die(mysqli_error($con));
								while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
									$selected = ($cat_id == $row['id']) ? 'selected' : '';
									echo "
								<option value='$row[id]' $selected>$row[name]</option>
							";
								}
								?>
							</select>
						</div>

						<div class="mb-3">
							<label for="amount" class="form-label">Количество</label>
							<input type="text" class="form-control" id="amount" name="amount" value="<?php if (!empty($amount)) echo $amount; ?>">
						</div>

						<div class="mb-3">
							<label for="discount_id" class="form-label">Скидка или акция</label>
							<select class="form-control" id="discount_id" name="discount_id">
								<option value='0'>нет скидки</option>
								<?php
								$query = "
							SELECT `id`, `name`, `value`
							FROM `discounts`
							WHERE 1
							AND NOW() BETWEEN `start` AND `stop`
							ORDER BY `name`
						";
								$res = mysqli_query($con, $query) or die(mysqli_error($con));
								while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
									$selected = ($discount_id == $row['id']) ? 'selected' : '';
									echo "
								<option value='$row[id]' $selected>$row[name] ($row[value]%)</option>
							";
								}
								?>
							</select>
						</div>

						<div class="mb-3">
							<label for="file" class="form-label">Фото</label>
							<input type="file" class="form-control" id="file" name="file">
						</div>

						<input name="hidden_edit_id" type="hidden" value="<?php if (!empty($id)) echo $id; ?>">

						<div class="mb-3">
							<button type="reset" class="btn btn-secondary" onclick="btn_reset_click();">Очистить поля</button>
							<button type="submit" class="btn btn-primary" name="btn_submit">Сохранить</button>
						</div>
					</form>
				</div>
			</div>
		</section>

	<?php
	}; // if (admin)
	?>

	</td>
	</tr>
</table>

<?php
include('footer.php');
?>