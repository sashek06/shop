<?php
	header('Content-type: text/html; charset=utf-8');
	include "auth.php";
	error_reporting(E_ALL);
	if (!in_array($_SESSION['level'], array(10,2))) { // доступ разрешен только группе пользователей
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
	$title='Пользователи';
	$table='users';
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
		<div class="row justify-content-center my-5">
			<div class="col-md-8 h1 text-center"><?php echo $title;?></div>
			<div class="col-md-12">
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
				`surname`, `name`, `middlename`, `rank`, `level`, `phone`, `address`, `password`, `login`
			FROM `$table`
			WHERE id=$id
		";
		$res=mysqli_query($con, $query) or die(mysqli_error($con));
		$row=mysqli_fetch_array($res);
		$surname=$row['surname'];
		$name=$row['name'];
		$middlename=$row['middlename'];
		$rank=$row['rank'];
		$level=$row['level'];
		$phone=$row['phone'];
		$address=$row['address'];
		$password=$row['password'];
		$login=$row['login'];
	};

	// если надо сохранить (если не пусто)
	if (!empty($_POST['surname'])) {
		$surname=mysqli_real_escape_string($con, trim($_POST['surname']));
		$name=mysqli_real_escape_string($con, trim($_POST['name']));
		$middlename=mysqli_real_escape_string($con, trim($_POST['middlename']));
		$rank=mysqli_real_escape_string($con, trim($_POST['rank']));
		$level=intval(trim($_POST['level']));
		$phone=mysqli_real_escape_string($con, trim($_POST['phone']));
		$address=mysqli_real_escape_string($con, trim($_POST['address']));
		$password=mysqli_real_escape_string($con, trim($_POST['password']));
		$login=mysqli_real_escape_string($con, trim($_POST['login']));

		$fields="
				`surname`='$surname',
				`name`='$name',
				`middlename`='$middlename',
				`rank`='$rank',
				`level`='$level',
				`phone`='$phone',
				`address`='$address',
				`password`='$password',
				`login`='$login'
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
	$admin_delete=$_SESSION['login']=='admin' ? ", CONCAT('<a href=\"$table.php?delete_id=', `$table`.id, '\" $delete_confirm>', 'удалить&nbsp;#', `$table`.id, '</a>') AS 'Удаление'" : '';
	// добавляем возможность редактирования админам
	$admin_edit=$_SESSION['login']=='admin' ? ", CONCAT('<a href=\"$table.php?edit_id=', `$table`.id, '\">', 'редактировать&nbsp;#', `$table`.id, '</a>') AS 'Редактирование'" : '';
	$query="
		SELECT
			`$table`.`id` AS 'Код',
			`$table`.`surname` AS 'Фамилия',
			`$table`.`name` AS 'Имя',
			`$table`.`middlename` AS 'Отчество',
			`$table`.`rank` AS 'Должность',
			`$table`.`address` AS 'Адрес',
			`$table`.`phone` AS 'Телефон',
			`$table`.`login` AS 'Логин',
			`levels`.`descr` AS 'Уровень',
			`$table`.`date_reg` AS 'Дата регистрации',
			`$table`.`email` AS 'Email'
			$admin_delete
			$admin_edit
		FROM
			`$table`, `levels`
		WHERE 1
			AND `$table`.`level`=`levels`.`id`
		ORDER BY `$table`.`surname`
	";

	echo SQLResultTable($query, $con, '');
?>

<?php
	// доступ к редактированию только админу
	if ($_SESSION['login']=='admin') { // if (admin)
?>
			</div>
			<div class="col-md-8">
			<form name="form" action="<?php echo $table?>.php" method="post" class="my-5">
  <div class="mb-3">
    <label for="surname" class="form-label">Фамилия</label>
    <input id="surname" name="surname" type="text" class="form-control" value="<?php if (!empty($surname)) echo $surname; ?>">
  </div>
  <div class="mb-3">
    <label for="name" class="form-label">Имя</label>
    <input id="name" name="name" type="text" class="form-control" value="<?php if (!empty($name)) echo $name; ?>">
  </div>
  <div class="mb-3">
    <label for="middlename" class="form-label">Отчество</label>
    <input id="middlename" name="middlename" type="text" class="form-control" value="<?php if (!empty($middlename)) echo $middlename; ?>">
  </div>
  <div class="mb-3">
    <label for="rank" class="form-label">Должность</label>
    <input id="rank" name="rank" type="text" class="form-control" value="<?php if (!empty($rank)) echo $rank; ?>">
  </div>
  <div class="mb-3">
    <label for="level" class="form-label">Уровень</label>
    <select id="level" name="level" class="form-select">
      <?php
        $query = "
          SELECT `descr`, `id`
          FROM `levels`
          ORDER BY `id`
        ";
        $res = mysqli_query($con, $query) or die(mysqli_error($con));
        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
          $selected = ($level == $row['id']) ? 'selected' : '';
          echo "<option value='{$row['id']}' $selected>{$row['descr']}</option>";
        };
      ?>
    </select>
  </div>
  <div class="mb-3">
    <label for="address" class="form-label">Адрес</label>
    <input id="address" name="address" type="text" class="form-control" value="<?php if (!empty($address)) echo $address; ?>">
  </div>
  <div class="mb-3">
    <label for="phone" class="form-label">Телефон</label>
    <input id="phone" name="phone" type="text" class="form-control" value="<?php if (!empty($phone)) echo $phone; ?>">
  </div>
  <div class="mb-3">
    <label for="password" class="form-label">Пароль</label>
    <input id="password" name="password" type="text" class="form-control" value="<?php if (!empty($password)) echo $password; ?>">
  </div>
  <div class="mb-3">
    <label for="login" class="form-label">Логин</label>
    <input id="login" name="login" type="text" class="form-control" value="<?php if (!empty($login)) echo $login; ?>">
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