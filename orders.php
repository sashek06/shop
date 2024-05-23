<?php
	header('Content-type: text/html; charset=utf-8');
	include "auth.php";
	error_reporting(E_ALL);
	if (!in_array($_SESSION['level'], array(10, 2))) { // доступ разрешен только группе пользователей
		header("Location: login.php"); // остальных просим залогиниться
		exit;
	};

	/*
	Скрипт-редактор
	*/
	include "database.php";
	include "func.php";
	include "scripts.php";
	$con=connect();
	$title='Заказы';
	$table='orders';
	$edit=in_array($_SESSION['level'], array(10, 2)) ? true : false;

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

<style>
	input{
		width:100%;
	}
</style>

</head>

<?php
    include('showcase.php');
    include('menu.php');
?>

<section class="orders">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8 h1 text-center"><?php echo $title;?></div>
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
				`user_id`, `dt`, `status`
			FROM `$table`
			WHERE id=$id
		";
		$res=mysqli_query($con, $query) or die(mysqli_error($con));
		$row=mysqli_fetch_array($res);
		$user_id=$row['user_id'];
		$dt=$row['dt'];
		$status=$row['status'];
	};

	// если надо сохранить (если не пусто)
	if (isset($_POST['status']) && !empty($_POST['user_id'])) {
		$user_id=abs(intval(trim($_POST['user_id'])));
		$dt=mysqli_real_escape_string($con, trim($_POST['dt']));
		$dt=str_replace('.', '-', $dt);
		$status=abs(floatval(trim($_POST['status'])));


/*
		// минусуем остатки при закрытии, удалении, отмене, ожидании заказа
		if (!empty($_REQUEST['hidden_edit_id']) && $status>0) {
			$id=intval($_REQUEST['hidden_edit_id']);
			// узнаем статус этого заказа
	echo '<pre>';
echo			$buf_query="
				SELECT status
				FROM orders
				WHERE id=$id
			";
	echo '</pre>';
			$buf_res=mysqli_query($con, $buf_query) or die(mysqli_error($con));
			$old_status=mysqli_fetch_array($buf_res, MYSQLI_BOTH)[0];
			var_dump($old_status);


			// если статус "открыт", значит, надо автоотминусовать остатки, забронированные в этом заказе
			if ($old_status==0) {
	echo '<pre>';
echo			$buf_query="
					UPDATE
						products, items
					SET
						products.amount=products.amount-items.amount
					WHERE 1
						AND items.ord_id=$id
						AND products.id=items.product_id
				";
	echo '</pre>';
				$buf_res=mysqli_query($con, $buf_query) or die(mysqli_error($con));
			};
		};
*/


		$fields="
			`user_id`='$user_id',
			`dt`='$dt',
			`status`='$status'
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
			`$table`.`id` AS '№ заказа',
			`users`.`login` AS 'логин пользователя',
			`$table`.`dt` AS 'Дата',
			`statuses`.`descr` AS 'Статус'
			$admin_delete
			$admin_edit
		FROM
			`$table`
		LEFT JOIN
			`statuses` ON `statuses`.`id`=`$table`.`status`
		LEFT JOIN
			`users` ON `users`.`id`=`$table`.`user_id`
		WHERE 1
		LIMIT 50;
	";

	echo SQLResultTable($query, $con, '');
?>

<?php
	// доступ к редактированию только админу
	if ($edit) { // if (admin)
?>
			<div class="col-md-8">
			<form class="my-5" name="form" action="<?php echo $table?>.php" method="post">
  <div class="mb-3">
    <label for="user_id" class="form-label">Пользователь</label>
    <input id="user_id" name="user_id" type="text" class="form-control" value="<?php if (!empty($user_id)) echo $user_id;?>">
  </div>
  <div class="mb-3">
    <label for="dt" class="form-label">Дата</label>
    <input id="dt" name="dt" type="text" class="form-control datepicker_air" value="<?php if (!empty($dt)) echo $dt; else echo date('Y.m.d H:i:s');?>">
  </div>
  <div class="mb-3">
    <label for="status" class="form-label">Статус</label>
    <select id="status" name="status" class="form-select">
      <?php
        $query = "
          SELECT `id`, `descr`
          FROM `statuses`
          ORDER BY `id`
        ";
        $res = mysqli_query($con, $query) or die(mysqli_error($con));
        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
          $selected = ($status == $row['id']) ? 'selected' : '';
          echo "
            <option value='{$row['id']}' $selected>{$row['descr']}</option>
          ";
        };
      ?>
    </select>
  </div>
  <input name="hidden_edit_id" type="hidden" value="<?php if (!empty($id)) echo $id;?>">
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