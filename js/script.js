// Сохраните значение user_id в переменной JavaScript
var user_id = '<?php echo $_SESSION["id"]; ?>';

// вернуть сумму и количество единиц в корзине пользователя
function get_cart_info() {
	$.ajax({
		url: 'ajax/ajax_get_cart_info.php',
		type: 'POST',
		async: true,
		dataType: "JSON",
		data: {
			user_id: user_id // Используем переменную user_id
		},
		beforeSend: function() {
		},
		complete: function() {
		},
		success: function(response) {
			$('#cart_info').html('Корзина (' + response.amount + ')');
		},
		error: function(objAJAXRequest, strError) {
			alert('Произошла ошибка! Тип ошибки: ' + strError);
		}
	});
}

// сразу после загрузки страницы выполнить
$(function() {
	get_cart_info();
});

// добавляем товар в корзину пользователю
function to_cart(id) {
	$.ajax({
		url: 'ajax/ajax_add_to_cart.php',
		type: 'POST',
		async: true,
		data: {
			id: id,
			user_id: user_id // Используем переменную user_id
		},
		beforeSend: function() {
		},
		complete: function() {
		},
		success: function(response) {
			if (response == 'ok') {
				get_cart_info();
				alert('Добавлено в корзину!');
			} else {
				alert(response);
			}
		},
		error: function(objAJAXRequest, strError) {
			alert('Произошла ошибка! Тип ошибки: ' + strError);
		}
	});
}
