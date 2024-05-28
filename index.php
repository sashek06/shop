<?php
/*
    Главная страница
    */
header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL);
include('auth.php');
include "func.php";
include "scripts.php";
$con = connect();
$title = 'Главная';

?>

<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1a1a1a;
            /* Темный фон для всего сайта */
            color: #f8f9fa;
            /* Светлый цвет текста */
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            padding: 50px 0;
            background-color: #282828;
            /* Темный фон для заголовка */
        }

        .header h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .header p {
            font-size: 24px;
            color: #ccc;
            /* Более светлый текст для описания */
        }

        .main__card {
            transition: transform 0.5s ease-in-out;
            transform-origin: center top;
            background-color: #333;
            /* Темный фон для карточек */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            /* Тени для карточек */
            margin-bottom: 30px;
        }

        .main__card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.7);
            /* Более сильные тени при наведении */
        }

        .main__card h1 {
            font-size: 28px;
            margin-bottom: 10px;
            color: #f8f9fa;
        }

        .main__card p {
            font-size: 16px;
            color: #ccc;
        }

        .container {
            padding: 20px;
        }

        .categories {
            padding: 20px 0;
            background-color: #1a1a1a;
        }
    </style>
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
</section>

<section class="main d-flex flex-column justify-content-center" style="min-height: 100vh;">
    <div class="container">
        <!-- Здесь можно добавить дополнительный контент -->
    </div>
</section>

<?php
include('footer.php');
?>