<?php
header('Content-type: text/html; charset=utf-8');
include "auth.php";
error_reporting(E_ALL);

/*
	Скрипт - просмотр и редактор корзины, осуществление заказа
	*/
include "database.php";
include "func.php";
include "scripts.php";
$con = connect();
$title = 'Корзина';
$table = 'items';

if (!in_array($_SESSION['level'], array(10, 2, 1))) { // доступ разрешен только группе пользователей
	header("Location: login.php"); // остальных просим залогиниться
	exit;
};
?>

<script>
	// сразу после загрузки страницы выполнить
	$(function() {
		get_cart();
	});



	// удаляем товар из корзины пользователя
	function delete_from_cart(id) {
		if (!confirm('Действительно удалить эту позицию?')) return 0;
		var user_id = '<?php echo $_SESSION["id"]; ?>';
		$.ajax({
			url: 'ajax/ajax_delete_from_cart.php',
			type: 'POST',
			async: true,
			data: {
				id: id,
				user_id: user_id
			},
			beforeSend: function() {},
			complete: function() {},
			success: function(response) {
				if (response == 'ok') {
					get_cart_info();
					get_cart();
					//					alert('Удалено из корзины!');
				} else alert(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' + strError);
			}
		});
	}; //delete_from_cart


	// уменьшаем количество товара в корзине
	function dec_amount_cart(id) {
		var user_id = '<?php echo $_SESSION["id"]; ?>';
		$.ajax({
			url: 'ajax/ajax_dec_cart.php',
			type: 'POST',
			async: true,
			data: {
				id: id,
				user_id: user_id
			},
			beforeSend: function() {},
			complete: function() {},
			success: function(response) {
				if (response == 'ok') {
					get_cart_info();
					get_cart();
				} else alert(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' + strError);
			}
		});
	}; //dec_amount_cart

	// увеличиваем количество товара в корзине (если есть в свободном остатке)
	function inc_amount_cart(id) {
		var user_id = '<?php echo $_SESSION["id"]; ?>';
		$.ajax({
			url: 'ajax/ajax_inc_cart.php',
			type: 'POST',
			async: true,
			data: {
				id: id,
				user_id: user_id
			},
			beforeSend: function() {},
			complete: function() {},
			success: function(response) {
				if (response == 'ok') {
					get_cart_info();
					get_cart();
				} else alert(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' + strError);
			}
		});
	}; //inc_amount_cart

	// оформление заказа
	function do_order() {
		if (!confirm('Действительно оформить заказ?')) return 0;
		var user_id = '<?php echo $_SESSION["id"]; ?>';
		$.ajax({
			url: 'ajax/ajax_do_order.php',
			type: 'POST',
			async: true,
			data: {
				user_id: user_id
			},
			beforeSend: function() {},
			complete: function() {},
			success: function(response) {
				if (response == 'ok') {
					get_cart_info();
					get_cart();
					// переадресовать на страницу оплаты
					window.location.href = "pay.php";
				} else alert(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' + strError);
			}
		});
	};
	do_order
</script>

<html data-bs-theme="dark">

<head>
	<meta charset="utf-8">
	<title><?php echo $title; ?></title>
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
			<td width="300px" style="vertical-align:top">
				<?php
				include('menu.php');
				include('showcase.php');
				?>
			</td>

			<!-- контент -->
			<td style="vertical-align:top; width:900px">

				<h1><?php echo $title; ?></h1>
				<div id="cart" width="100%"></div>
				<button onclick="do_order()"><input type=image src="images/ok.gif">Оформить заказ</button>
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