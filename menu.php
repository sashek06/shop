<?php
$menu = '
	';

// $menu.='<h2><a href="index.php"><img src="images/home.png" width="20px">Главная</a> </h2>';
// $menu.='<h2><a href="view.php"><img src="images/showcase.png" height="18px">Витрина</a></h2>';
// $menu.='<h2><a href="contacts.php"><img src="images/mail.png" width="20px">Реквизиты</a> </h2>';
// $menu.='<h2><a href="about.php"><img src="images/about.png" width="20px">О нас</a> </h2>';

	// $menu.='<li class="nav-item">
	// <a class="nav-link" href="index.php">Главная</a>
	// </li>';

// меню по уровням доступа: 10 - админ и т.д.
if (isset($_SESSION['level'])) {
	if (in_array($_SESSION['level'], array(10))) $menu .= '<li class="nav-item dropdown">
		<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
			'.$_SESSION['login'].'
		</a>
		<ul class="dropdown-menu beads">';
	if (in_array($_SESSION['level'], array(10))) $menu .= '<li> <a class="dropdown-item" href="users.php">Пользователи</a> </li>';
	if (in_array($_SESSION['level'], array(10, 2))) $menu .= '<li> <a class="dropdown-item" href="categories.php">Категории</a> </li>';
	if (in_array($_SESSION['level'], array(10, 2))) $menu .= '<li> <a class="dropdown-item" href="products.php">Товары</a> </li>';
	if (in_array($_SESSION['level'], array(10, 2))) $menu .= '<li> <a class="dropdown-item" href="discounts.php">Акции и скидки</a> </li>';
	if (in_array($_SESSION['level'], array(10, 2))) $menu .= '<li> <a class="dropdown-item" href="orders.php">Заказы</a> </li>';
	if (in_array($_SESSION['level'], array(10, 2))) $menu .= '<li> <a class="dropdown-item" href="items.php">Содержимое заказов</a> </li>';
	if (in_array($_SESSION['level'], array(10, 2))) $menu .= '<li> <a class="dropdown-item" href="report_products.php">Малые остатки</a> </li>';
	if (in_array($_SESSION['level'], array(10))) $menu .= '
					</ul>
					</li>
					';
	};

	if ( !isset($_SESSION['level'])) $menu.='<li class="nav-item">
	<a class="nav-link" href="login.php">Авторизация</a>
	</li>';
	if ( !isset($_SESSION['level'])) $menu.='<li class="nav-item">
	<a class="nav-link" href="reg.php">Регистрация</a>
	</li></ul></div></div></nav>';
	else {
		$menu.='<li class="nav-item"><a class="nav-link" href="?do=exit">Выйти</a></li>';
		$menu.='<li class="nav-item"><a class="nav-link" href="cart.php"><div id="cart_info">Корзина</div></a></li></ul></div></div></nav>';
		// if ( in_array($_SESSION['level'], array(2,1)) ) $menu.='<li class="nav-item><a class="nav-link" href="user_orders.php"><img src="images/order.png" height="18px">Заказы</a></li></ul></div></div></nav>';
	};
/* 	else {
		$menu.=$_SESSION['login'].' <a href="?do=exit" style="text-decoration:none; color:white">(выйти)</a>';
		$menu.='<h2><a href="cart.php"><img src="images/cart2.svg" width="30px" height="24px"></a></h2>';
		if ( in_array($_SESSION['level'], array(2,1)) ) $menu.='<h2><a href="user_orders.php"><img src="images/order.png" height="18px">Заказы</a></h2>';
	}; */



/* 	// меню по уровням доступа: 10 - админ и т.д.
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
	}; */

echo $menu;
