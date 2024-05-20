<link rel="stylesheet" type="text/css" href="style.css">

<link rel="stylesheet" href="css/jquery-ui.css">
<script src="js/jquery-1.12.4.js"></script>
<script src="js/jquery-ui.js"></script>

<!-- choose a theme file -->
<link rel="stylesheet" href="css/theme.blue.css">
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>


<!-- tablesorter widgets (optional) -->
<script type="text/javascript" src="js/jquery.tablesorter.widgets.js"></script>

<!-- tablesorter.pager -->
<link rel="stylesheet" href="css/jquery.tablesorter.pager.min.css">
<script type="text/javascript" src="js/jquery.tablesorter.pager.min.js"></script>

<script>
	$(function() {
		$("#myTable").tablesorter({
			widgets: ["zebra", "filter"]
		}); //.tablesorterPager({container: $("#pager")}); ;
		$('#departement').select2();
		$('.datepicker_air').datepicker({
			dateFormat: "yyyy.mm.dd",
			timepicker: true
		})
	});
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
			beforeSend: function() {},
			complete: function() {},
			success: function(response) {
				$('#cart_info').html('Корзина (' + response.amount + ')');
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' + strError);
			}
		});
	};

	// сразу после загрузки страницы выполнить
	$(function() {
		get_cart_info();
	});

	// добавлям товар в корзину пользователю
	function to_cart(id) {
		var user_id = '<?php echo $_SESSION["id"]; ?>';
		$.ajax({
			url: 'ajax/ajax_add_to_cart.php',
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
					alert('Добавлено в корзину!');
				} else alert(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' + strError);
			}
		});

	};

	// обновить таблицу с корзиной
	function get_cart() {
		$.ajax({
			url: 'ajax/ajax_get_cart.php',
			type: 'POST',
			async: true,
			data: {
				user_id: '<?php echo $_SESSION['id']; ?>'
			},
			beforeSend: function() {},
			complete: function() {},
			success: function(response) {
				$('#cart').html(response);
			},
			error: function(objAJAXRequest, strError) {
				alert('Произошла ошибка! Тип ошибки: ' + strError);
			}
		});
	};
</script>

<!-- Select2 -->
<link href="css/select2.min.css" rel="stylesheet" />
<script src="js/select2.min.js"></script>

<!-- Air Datepicker -->
<link href="css/datepicker.min.css" rel="stylesheet" type="text/css">
<script src="js/datepicker.min.js"></script>