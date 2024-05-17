<html data-bs-theme="dark">
<head>
	<title>Регистрация</title>
	<meta charset="UTF-8">
	<link href="style.css" rel="stylesheet" />
</head>

<body>
<table id="main_table" border="0">
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
		<td class='menu2'>
			<?php
				include('menu.php');
			?>
		</td>
		<!-- контент -->
		<td class="content">

<div align="center" >

<form name="form" action="reg.php" method="post" style="width:600px; text-align:center">

<?php
	/*
	Скрипт регистрации юзера
	*/
	header('Content-type: text/html; charset=utf-8');
	include "database.php";
	include "func.php";
	include "styles.php";
	include "scripts.php";
	$con=connect();

	// если надо сохранить (если не пусто фио, логин и пароль)
	if (!empty($_POST['surname']) && !empty($_POST['login']) && !empty($_POST['password']) ) {
		$surname=mysqli_real_escape_string($con, trim($_POST['surname']));
		$name=mysqli_real_escape_string($con, trim($_POST['name']));
		$middlename=mysqli_real_escape_string($con, trim($_POST['middlename']));
//		$rank=mysqli_real_escape_string($con, trim($_POST['rank']));
//		$level=intval(trim($_POST['level']));
		$phone=mysqli_real_escape_string($con, trim($_POST['phone']));
		$address=mysqli_real_escape_string($con, trim($_POST['address']));
		$password=mysqli_real_escape_string($con, trim($_POST['password']));
		$login=mysqli_real_escape_string($con, trim($_POST['login']));

		$fields="
				`surname`='$surname',
				`name`='$name',
				`middlename`='$middlename',
				`rank`='',
				`level`='1',
				`phone`='$phone',
				`address`='$address',
				`password`='$password',
				`login`='$login'
		";

		$query="
			SELECT COUNT(*)
			FROM `users`
			WHERE 1
				AND `login`='$login'
		";
		$res=mysqli_query($con, $query) or die(mysqli_error($con));
		if (mysqli_fetch_array($res, MYSQLI_BOTH)[0]) {
			echo '<p>Пользователь с таким логином уже существует!</p>';
		}
		else {
			$query="
				INSERT INTO `users`
				SET
					$fields
			";
			$res=mysqli_query($con, $query);
			if ($res) {
				echo '<p>Регистрация прошла успешно!
				<a href="login.php"><u>Авторизуйтесь в системе</u></a>
				</p>';
			}
			else {
				die(mysqli_error($con));
			};
		};
	}
	else if (!empty($_POST['btn_submit'])){
		echo '<p>Введите ФИО, логин и пароль!</p>';
	};
?>
	<table>
		<tr>
			<td>Фамилия</td>
			<td>
				<input id="surname" name="surname" type="text" pattern="[A-Za-zА-Яа-яЁё]{1,}" value="<?php if (!empty($surname)) echo $surname;?>" required>
			</td>
		</tr>

		<tr>
			<td>Имя</td>
			<td>
				<input id="name" name="name" type="text" pattern="[A-Za-zА-Яа-я]{1,}" value="<?php if (!empty($name)) echo $name;?>" required>
			</td>
		</tr>

		<tr>
			<td>Отчество</td>
			<td>
				<input id="middlename" name="middlename" type="text" pattern="[A-Za-zА-Яа-я]{1,}" value="<?php if (!empty($middlename)) echo $middlename;?>">
			</td>
		</tr>

		<tr>
			<td>Адрес</td>
			<td>
				<input id="address" name="address" type="text" pattern="[A-Za-zА-Яа-я0-9 ,.]+" value="<?php if (!empty($address)) echo $address;?>">
			</td>
		</tr>

		<tr>
			<td>Телефон</td>
			<td>
				<input id="phone" name="phone" type="text" pattern="\d+" value="<?php if (!empty($phone)) echo $phone;?>" required>
			</td>
		</tr>

		<tr>
			<td>Логин</td>
			<td>
				<input id="login" name="login" type="text" value="<?php if (!empty($login)) echo $login;?>" required>
			</td>
		</tr>

		<tr>
			<td>Пароль</td>
			<td>
				<input id="password" name="password" type="text" value="<?php if (!empty($password)) echo $password;?>" required>
			</td>
		</tr>

	<tr>
		<td colspan='2'>
			<button id="btn_reset"  type="reset">Очистить поля</button>
			<button id="btn_submit" name="btn_submit" type="submit">Сохранить</button>
		</td>
	</tr>
	</table>

</form>
</div>

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