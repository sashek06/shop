<?php
	/*
	Скрипт авторизации юзера
	*/
	header('Content-type: text/html; charset=utf-8');
	if (session_id() == '') session_start();
	include "database.php";
	include "func.php";
	$con=connect();
?>

<html data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
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

<?php

	if(isset($_POST['submit'])){
		$login=htmlentities(trim($_POST['user']), ENT_QUOTES, 'UTF-8');
		$password=htmlentities(trim($_POST['pass']), ENT_QUOTES, 'UTF-8');
		// ищем такой логин и пароль
		$query="
			SELECT id, login, level
			FROM users
			WHERE 1
				AND (users.login='$login'	OR users.id='$login')
				AND (
					users.password=MD5('$password')
					OR users.password='$password'
				)
			LIMIT 1
		";
		$res=mysqli_query($con, $query) or die(mysqli_error($con));
		$row=mysqli_fetch_array($res, MYSQLI_ASSOC);
		if (
			(!empty($row['id']))
		)	{ //успешный вход

			$_SESSION['login'] =  $row['login'];
			$_SESSION['level'] =  $row['level'];
			$_SESSION['id'] =  $row['id'];
			header("Location: index.php");
			exit;
		}
		else
			echo '<p>Логин или пароль неверны!</p>';
	};
?>
<html data-bs-theme="dark">
<head>
	<title>Авторизация</title>
	<meta charset="UTF-8">
	<link href="style.css" rel="stylesheet" />
</head>

<body>
<div align="center" >
<form id="loginForm" method="post" style="width:500px; text-align:center">
	<div class="field">
		Имя пользователя:
		<div class="input"><input type="text" name="user" value="" id="login" /></div>
	</div>

	<div class="field">
		Пароль:
		<div class="input"><input type="password" name="pass" value="" id="pass" /></div>
	</div>

	<div class="submit">
		<button type="submit" name="submit">Войти</button>
	</div>

	<p">
	Нет логина и пароля? <a href="reg.php">Регистрация</a>
	</p>
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