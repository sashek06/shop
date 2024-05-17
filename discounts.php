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

<?php
	// доступ к редактированию только админу
	if ($edit) { // if (admin)
?>
<form name="form" action="<?php echo $table?>.php" method="post">
	<table>
		<tr>
			<th colspan="2">
				<p>Редактор <?php if (!empty($id)) echo "(редактируется строка с кодом $id)";?></p>
			</th>
		</tr>

		<tr>
			<td>Наименование акции/скидки</td>
			<td>
				<input id="name" name="name" type="text" value="<?php if (!empty($name)) echo $name;?>">
			</td>
		</tr>

		<tr>
			<td>Размер, %</td>
			<td>
				<input id="value" name="value" type="text" value="<?php if (!empty($value)) echo $value;?>">
			</td>
		</tr>

		<tr>
			<td>Дата начала</td>
			<td>
				<input id="start" name="start" class="datepicker_air" type="text" value="<?php if (!empty($start)) echo $start; else echo date('Y.m.d');?>">
			</td>
		</tr>

		<tr>
			<td>Дата окончания</td>
			<td>
				<input id="stop" name="stop" class="datepicker_air" type="text" value="<?php if (!empty($stop)) echo $stop; else echo date('Y.m.d');?>">
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