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
		if (!empty($row['id'])) { // успешный вход
			$_SESSION['login'] =  $row['login'];
			$_SESSION['level'] =  $row['level'];
			$_SESSION['id'] =  $row['id'];
			header("Location: index.php");
			exit;
		} else {
			echo '<p>Логин или пароль неверны!</p>';
		}
	}
?>

<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<?php
    include('showcase.php');
    include('menu.php');
?>

<section class="login__form d-flex align-items-center" style="min-height: 100vh;">
	<div class="container">
		<div class="row justify-content-center">
			<form id="loginForm" method="post" class="col-4">
				<div class="field mb-3">
					<label class="form-label">Имя пользователя</label>
					<div class="input"><input type="text" name="user" value="" id="login" class="form-control" /></div>
				</div>
				<div class="field mb-3">
					<label class="form-label">Пароль</label>
					<div class="input"><input type="password" name="pass" value="" id="pass" class="form-control" /></div>
				</div>
				<div class="submit d-flex justify-content-center">
					<button type="submit" name="submit" class="btn btn-primary">Войти</button>
				</div>
			</form>
		</div>
	</div>
</section>


<?php
    include('footer.php');
?>
