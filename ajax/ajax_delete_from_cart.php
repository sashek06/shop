<?php
	# скрипт добавляет в корзину
	include "../database.php";
	include "../func.php";
	error_reporting(E_ALL);

	// все ли данные введены?
	if (empty($_POST['user_id']) || empty($_POST['id'])) {
			die('Не все данные выбраны'); // если нет, выходим
	};

	// подключаемся к БД
	$con=connect();

	$id=abs(intval($_POST['id']));
	$user_id=abs(intval($_POST['user_id']));

	// удаляем
	$query="
		DELETE FROM `items`
		WHERE 1
      	AND `user_id`='$user_id'
      	AND `id`=$id
		LIMIT 1
	";

	$res=mysqli_query($con, $query);
	if (!$res) {
		die(mysqli_error($con));
	};

	echo 'ok';
?>