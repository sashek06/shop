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
	$con=connect();
	$title='Корзина';
	$table='items';

	if (!in_array($_SESSION['level'], array(10, 2, 1))) { // доступ разрешен только группе пользователей
		header("Location: login.php"); // остальных просим залогиниться
		exit;
	};
?>

<script>

	// вернуть сумму и количество единиц в корзине пользователя
	function get_cart_info() {
		$.ajax({
			url: 'ajax/ajax_get_cart_info.php',
			type: 'POST',
			async: true,
			dataType: "JSON",
			data: {
				user_id: '<?php echo $_SESSION['id']; ?>'
			},
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(response)	{
				$('#cart_info').html('Корзина ('+response.amount+')');
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' +strError);
			}
		});
	};

	// сразу после загрузки страницы выполнить
	$(function() {
		get_cart_info();
		get_cart();
	});

	// обновить таблицу с корзиной
	function get_cart() {
		$.ajax({
			url: 'ajax/ajax_get_cart.php',
			type: 'POST',
			async: true,
			data: {
				user_id: '<?php echo $_SESSION['id']; ?>'
			},
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(response)	{
				$('#cart').html(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' +strError);
			}
		});
	};



	// удаляем товар из корзины пользователя
	function delete_from_cart(id) {
		if (!confirm('Действительно удалить эту позицию?')) return 0;
		var user_id='<?php echo $_SESSION["id"];?>';
		$.ajax({
			url: 'ajax/ajax_delete_from_cart.php',
			type: 'POST',
			async: true,
			data: {
				id: id,
				user_id: user_id
			},
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(response)	{
				if (response=='ok') {
					get_cart_info();
					get_cart();
//					alert('Удалено из корзины!');
				}
				else alert(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' +strError);
			}
		});
	}; //delete_from_cart


	// уменьшаем количество товара в корзине
	function dec_amount_cart(id) {
		var user_id='<?php echo $_SESSION["id"];?>';
		$.ajax({
			url: 'ajax/ajax_dec_cart.php',
			type: 'POST',
			async: true,
			data: {
				id: id,
				user_id: user_id
			},
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(response)	{
				if (response=='ok') {
					get_cart_info();
					get_cart();
				}
				else alert(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' +strError);
			}
		});
	}; //dec_amount_cart

	// увеличиваем количество товара в корзине (если есть в свободном остатке)
	function inc_amount_cart(id) {
		var user_id='<?php echo $_SESSION["id"];?>';
		$.ajax({
			url: 'ajax/ajax_inc_cart.php',
			type: 'POST',
			async: true,
			data: {
				id: id,
				user_id: user_id
			},
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(response)	{
				if (response=='ok') {
					get_cart_info();
					get_cart();
				}
				else alert(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' +strError);
			}
		});
	}; //inc_amount_cart

	// оформление заказа
	function do_order() {
		if (!confirm('Действительно оформить заказ?')) return 0;
		var user_id='<?php echo $_SESSION["id"];?>';
		$.ajax({
			url: 'ajax/ajax_do_order.php',
			type: 'POST',
			async: true,
			data: {
				user_id: user_id
			},
			beforeSend: function() {
			},
			complete: function() {
			},
			success: function(response)	{
				if (response=='ok') {
					get_cart_info();
					get_cart();
					// переадресовать на страницу оплаты
					window.location.href = "pay.php";
				}
				else alert(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' +strError);
			}
		});
	};do_order
</script>

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

<section class="cart mt-5" style="min-height: 100vh;">
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="h1 text-center"><?php echo $title;?></div>
			<div id="cart" width="100%"></div>
			<div class="order__button mt-4">
				<button class="btn btn-primary btn-block" onclick="do_order()">Оформить заказ</button>
			</div>
		</div>
	</div>
</div>
</section>

<?php
    include('footer.php');
?>
