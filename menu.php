<?php
	$menu= '
	';

	// меню по уровням доступа: 10 - админ и т.д.
	if (isset($_SESSION['level'])) {
		if ( in_array($_SESSION['level'], array(10)) ) $menu.='<h2>Административная часть</h2><ul class="beads">';
		if ( in_array($_SESSION['level'], array(10)) ) $menu.='<li> <a href="users.php">Пользователи</a> </li>';
		if ( in_array($_SESSION['level'], array(10,2)) ) $menu.='<li> <a href="categories.php">Категории</a> </li>';
		if ( in_array($_SESSION['level'], array(10,2)) ) $menu.='<li> <a href="products.php">Товары</a> </li>';
		if ( in_array($_SESSION['level'], array(10,2)) ) $menu.='<li> <a href="discounts.php">Акции и скидки</a> </li>';
		if ( in_array($_SESSION['level'], array(10,2)) ) $menu.='<li> <a href="orders.php">Заказы</a> </li>';
		if ( in_array($_SESSION['level'], array(10,2)) ) $menu.='<li> <a href="items.php">Содержимое заказов</a> </li>';
		if ( in_array($_SESSION['level'], array(10,2)) ) $menu.='<li> <a href="report_products.php">Малые остатки</a> </li>';
		if ( in_array($_SESSION['level'], array(10)) ) $menu.='</ul><hr>';
	};

	if ( !isset($_SESSION['level'])) $menu.='<li> <a href="login.php">Авторизация</a> </li>';
	if ( !isset($_SESSION['level'])) $menu.='<li> <a href="reg.php">Регистрация</a> </li>';
	else {		$menu.='Вы вошли под логином '.$_SESSION['login'].' <a href="?do=exit"><button><img src="images/exit.png" height="18px"> Выход</button></a>';
		$menu.='<h2><a href="cart.php"><img src="images/cart.png" width="64px" height="64px"><div id="cart_info">Корзина</div></a> </h2>';
		if ( in_array($_SESSION['level'], array(2,1)) ) $menu.='<h2><a href="user_orders.php"><img src="images/order.png" height="18px">Заказы</a></h2>';
	};
	$menu.='<h2><a href="index.php"><img src="images/home.png" width="20px">Главная</a> </h2>';
	$menu.='<h2><a href="view.php"><img src="images/showcase.png" height="18px">Витрина</a></h2>';
	$menu.='<h2><a href="contacts.php"><img src="images/mail.png" width="20px">Реквизиты</a> </h2>';
	$menu.='<h2><a href="about.php"><img src="images/about.png" width="20px">О нас</a> </h2>';
	echo $menu;
?>