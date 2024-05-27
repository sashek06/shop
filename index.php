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
        <div class="btn-group" role="group" aria-label="Basic example">
        <?php
            // Запрос для получения категорий
            $query = "SELECT id, name, descr FROM categories WHERE id <> 0 ORDER BY name";
            $result = mysqli_query($con, $query) or die(mysqli_error($con));

            // Проход по результатам и вывод каждой категории
            while ($row = mysqli_fetch_assoc($result)) {
                echo '
                <button type="button" class="btn btn-primary">
                <a href="view.php?cat_id=' . $row['id'] . '" class="text-decoration-none text-body" style="display: inline-block;">
                    <div class="h3 mb-0 py-4">' . $row['name'] . '</div>
                </a>
                </button>';
            }
            ?>
            </div>
    </div>
</div>
</section>

<?php
    include('footer.php');
?>