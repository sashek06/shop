<?php
	header('Content-type: text/html; charset=utf-8');
	include "auth.php";
	error_reporting(E_ALL);
	if (!in_array($_SESSION['level'], array(10, 2))) { // доступ разрешен только группе пользователей
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
	$title='Категории';
	$table='categories';
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

<section class="categories">
	<div class="container">
		<div class="h1 col-12 text-center mt-5"><?php echo $title;?></div>
		<div class="row justify-content-center my-5">
			<div class="col-md-12">
			<?php
	// если надо удалить
	if (!empty($_GET['delete_id'])) {
		$id=intval($_GET['delete_id']);

		// каскадное удаление из содержимого заказов
		$query="
			DELETE FROM `items`
			WHERE
				product_id IN (
					SELECT id
					FROM products
					WHERE cat_id=$id
				)
		";
		mysqli_query($con, $query) or die(mysqli_error($con));

		// каскадное удаление из каталога товаров
		$query="
			DELETE FROM `products`
			WHERE cat_id=$id
		";
		mysqli_query($con, $query) or die(mysqli_error($con));

		// удаление категории
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
				`name`, `descr`, `parent`
			FROM `$table`
			WHERE id=$id
		";
		$res=mysqli_query($con, $query) or die(mysqli_error($con));
		$row=mysqli_fetch_array($res);
		$descr=$row['descr'];
		$name=$row['name'];
		$parent=$row['parent'];
	};

	// если надо сохранить (если не пусто)
	if (!empty($_POST['name'])) {
		$name=mysqli_real_escape_string($con, trim($_POST['name']));
		$descr=mysqli_real_escape_string($con, trim($_POST['descr']));
		$parent=intval(trim($_POST['parent']));

		$fields="
				`name`='$name',
				`descr`='$descr',
				`parent`='$parent'
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
			`$table`.`name` AS 'Наименование',
			SUBSTRING(`$table`.`descr`, 1, 100) AS 'Описание', # первые 100 символов строки
			`cat`.`name` AS 'Категория'
			$admin_delete
			$admin_edit
		FROM
			`$table`
		LEFT JOIN
			`$table` AS `cat` ON `cat`.`id`=`$table`.`parent`
		WHERE 1
			AND `$table`.`id`<>0
		ORDER BY `$table`.`name`
		LIMIT 50;
	";

	echo SQLResultTable($query, $con, '');
?>
			</div>
<?php
// доступ к редактированию только админу
if ($edit) { // if (admin)
?>
			<div class="col-md-8">
				<form name="form" action="<?php echo $table?>.php" method="post" class="my-5">
  <div class="mb-3">
    <label for="name" class="form-label">Наименование</label>
    <textarea id="name" name="name" class="form-control"><?php if (!empty($name)) echo $name; ?></textarea>
  </div>
  <div class="mb-3">
    <label for="descr" class="form-label">Описание</label>
    <textarea id="descr" name="descr" class="form-control"><?php if (!empty($descr)) echo $descr; ?></textarea>
  </div>
  <div class="mb-3">
    <label for="parent" class="form-label">Категория</label>
    <select id="parent" name="parent" class="form-select">
      <?php
        $query = "
          SELECT `descr`, `name`, `id`, `parent`
          FROM `categories`
          ORDER BY `name`
        ";
        $res = mysqli_query($con, $query) or die(mysqli_error($con));
        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
          $selected = ($parent == $row['id']) ? 'selected' : '';
          echo "<option value='{$row['id']}' $selected>{$row['name']}</option>";
        }
      ?>
    </select>
  </div>
  <input name="hidden_edit_id" type="hidden" value="<?php if (!empty($id)) echo $id; ?>">
  <div class="d-flex justify-content-between">
    <button id="btn_reset" onclick="btn_reset_click();" type="button" class="btn btn-secondary">Очистить поля</button>
    <button id="btn_submit" name="btn_submit" type="submit" class="btn btn-primary">Сохранить</button>
  </div>
</form>
			</div>
		</div>
	</div>
</section>

<?php
	}; // if (admin)
?>

<?php
    include('footer.php');
?>