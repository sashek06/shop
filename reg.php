<html data-bs-theme="dark">

<head>
	<title>Регистрация</title>
	<meta charset="UTF-8">
	<link href="style.css" rel="stylesheet" />
</head>

<body>
	<section class="reg d-flex align-items-center" style="min-height: 100vh;">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-4">
					<form name="form" action="reg.php" method="post">
						<?php
						header('Content-type: text/html; charset=utf-8');
						include "database.php";
						include "func.php";
						include "styles.php";
						include "scripts.php";
						$con = connect();

						if (!empty($_POST['login']) && !empty($_POST['password']) && !empty($_POST['phone']) && !empty($_POST['email'])) {
							$login = mysqli_real_escape_string($con, trim($_POST['login']));
							$password = password_hash(mysqli_real_escape_string($con, trim($_POST['password'])), PASSWORD_BCRYPT);
							$phone = mysqli_real_escape_string($con, trim($_POST['phone']));
							$email = mysqli_real_escape_string($con, trim($_POST['email']));

							$fields = "
                        `login`='$login',
                        `password`='$password',
                        `phone`='$phone',
                        `email`='$email',
                        `rank`='',
                        `level`='1'
                ";

							$query = "
                    SELECT COUNT(*)
                    FROM `users`
                    WHERE 1
                        AND `login`='$login'
                ";
							$res = mysqli_query($con, $query) or die(mysqli_error($con));
							if (mysqli_fetch_array($res, MYSQLI_BOTH)[0]) {
								echo '<p class="text-danger">Пользователь с таким логином уже существует!</p>';
							} else {
								$query = "
                        INSERT INTO `users`
                        SET
                            $fields
                    ";
								$res = mysqli_query($con, $query);
								if ($res) {
									echo '<p class="text-success">Регистрация прошла успешно!
                        <a href="login.php"><u>Авторизуйтесь в системе</u></a>
                        </p>';
								} else {
									die(mysqli_error($con));
								}
							}
						} else if (!empty($_POST['btn_submit'])) {
							echo '<p class="text-danger">Введите логин, пароль, телефон и email!</p>';
						}
						?>
						<div class="mb-3">
							<label for="login" class="form-label">Логин</label>
							<input type="text" class="form-control" id="login" name="login" value="<?php if (!empty($login)) echo $login; ?>" required>
						</div>
						<div class="mb-3">
							<label for="password" class="form-label">Пароль</label>
							<input type="password" class="form-control" id="password" name="password" value="<?php if (!empty($password)) echo $password; ?>" required>
						</div>
						<div class="mb-3">
							<label for="phone" class="form-label">Телефон</label>
							<input type="text" class="form-control" id="phone" name="phone" pattern="\d+" value="<?php if (!empty($phone)) echo $phone; ?>" required>
						</div>
						<div class="mb-3">
							<label for="email" class="form-label">Email</label>
							<input type="email" class="form-control" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>" required>
						</div>
						<button type="reset" class="btn btn-secondary">Очистить поля</button>
						<button type="submit" class="btn btn-primary" name="btn_submit">Сохранить</button>
					</form>
				</div>
			</div>
		</div>
	</section>
</body>

</html>