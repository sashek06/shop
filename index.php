<?php
	/*
	Главная страница
	*/
	header('Content-type: text/html; charset=utf-8');
	error_reporting(E_ALL);
	include('auth.php');
	include('func.php');
	$title='Главная';
?>

<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<?php
    include('showcase.php');
    include('menu.php');
?>

<section class="main d-flex flex-column justify-content-center" style="min-height: 100vh;">
<div class="container">
    <div class="title h1 text-center">Добро пожаловать на наш Boost-WoW сервис!</div>
    <div class="title h3 text-body-secondary text-center mt-2 mb-5">Мы предлагаем лучшие услуги по прокачке и улучшению вашего игрового персонажа.</div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="px-2 py-2 text-center main__card" style="transition: transform 0.5s ease-in-out; transform-origin: center top;">
                <a href="gear.php" class="text-decoration-none text-body" style="display: inline-block;">
                    <!-- <img class="d-block mx-auto mb-4" src="images/raids.webp" alt="Снаряжение" width="200" height="200" style="border-radius: 30%;"> -->
                    <h1 class="display-6">PvE</h1>
                    <p class="lead mb-4">Максимальный прогресс в PVE за минимальное время.</p>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="px-2 py-2 text-center main__card" style="transition: transform 0.5s ease-in-out; transform-origin: center top;">
                <a href="rep.php" class="text-decoration-none text-body">
                    <!-- <img class="d-block mx-auto mb-4" src="images/pvp.webp" alt="Репутация" width="200" height="200" style="border-radius: 30%;"> -->
                    <h1 class="display-6">PvP</h1>
                    <p class="lead mb-4">Повышайте рейтинг в PVP с нашей помощью!</p>
                </a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="px-2 py-2 text-center main__card" style="transition: transform 0.5s ease-in-out; transform-origin: center top;">
                <a href="achiv.php" class="text-decoration-none text-body">
                    <!-- <img class="d-block mx-auto mb-4" src="images/gold.webp" alt="Достижения" width="200" height="200" style="border-radius: 30%;"> -->
                    <h1 class="display-6">Золото</h1>
                    <p class="lead mb-4">Больше золота – больше возможностей в игре."</p>
                </a>
            </div>
        </div>
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="button" class="btn btn-primary">

            </button>
            <button type="button" class="btn btn-primary">

            </button>
            <button type="button" class="btn btn-primary">

            </button>
        </div>
    </div>
</div>
</section>

<?php
    include('footer.php');
?>