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
	$title='Акции и скидки';
	$table='discounts';
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

<?php
    include('showcase.php');
    include('menu.php');
?>

<section class="discounts">
	<div class="container">
		<div class="h1 text-center mt-5"><?php echo $title;?></div>
		<div class="row justify-content-center my-5">
			<div class="col-12">
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
				`value`, `start`, `stop`, `name`
			FROM `$table`
			WHERE id=$id
		";
		$res=mysqli_query($con, $query) or die(mysqli_error($con));
		$row=mysqli_fetch_array($res);
		$value=$row['value'];
		$start=$row['start'];
		$stop=$row['stop'];
		$name=$row['name'];
	};

	// если надо сохранить (если не пусто)
	if (!empty($_POST['name']) && !empty($_POST['value']) && !empty($_POST['start']) && !empty($_POST['stop'])) {
		$value=abs(floatval(trim($_POST['value'])));
		$start=mysqli_real_escape_string($con, trim($_POST['start']));
		$stop=mysqli_real_escape_string($con, trim($_POST['stop']));
		$name=mysqli_real_escape_string($con, trim($_POST['name']));

		$fields="
				`value`='$value',
				`start`='$start',
				`stop`='$stop',
				`name`='$name'
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
			`$table`.`value` AS 'Значение, %',
			`$table`.`start` AS 'Дата начала',
			`$table`.`stop` AS 'Дата окончания',
			`$table`.`name` AS 'Наименование акции/скидки'
			$admin_delete
			$admin_edit
		FROM
			`$table`
		WHERE 1
		ORDER BY `$table`.`name`
	";

	echo SQLResultTable($query, $con, '');
?>
			</div>
			<div class="col-md-8 mt-5">
<?php
// доступ к редактированию только админу
if ($edit) { // if (admin)
?>
			<form name="form" action="<?php echo $table?>.php" method="post">
  <div class="mb-3">
    <label for="name" class="form-label">Наименование акции/скидки</label>
    <input id="name" name="name" type="text" class="form-control" value="<?php if (!empty($name)) echo $name;?>">
  </div>
  
  <div class="mb-3">
    <label for="value" class="form-label">Размер, %</label>
    <input id="value" name="value" type="text" class="form-control" value="<?php if (!empty($value)) echo $value;?>">
  </div>
  
  <div class="mb-3">
    <label for="start" class="form-label">Дата начала</label>
    <input id="start" name="start" type="text" class="form-control datepicker_air" value="<?php if (!empty($start)) echo $start; else echo date('Y.m.d');?>">
  </div>
  
  <div class="mb-3">
    <label for="stop" class="form-label">Дата окончания</label>
    <input id="stop" name="stop" type="text" class="form-control datepicker_air" value="<?php if (!empty($stop)) echo $stop; else echo date('Y.m.d');?>">
  </div>

  <input name="hidden_edit_id" type="hidden" value="<?php if (!empty($id)) echo $id;?>">

  <div class="mb-3">
    <button id="btn_reset" type="button" class="btn btn-secondary" onclick="btn_reset_click();">Очистить поля</button>
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