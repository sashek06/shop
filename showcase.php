<?php
error_reporting(E_ALL);

// Предположим, что функция connect() определена где-то еще
// function connect() {
//     // ваша логика для подключения к базе данных
// }

$con = connect();
$showcase = '';
$query = "
    SELECT
        `name`, `parent`, `id` AS `cat_id`
    FROM
        `categories`
    WHERE
        `categories`.`parent` = 0
        AND `categories`.`id` <> 0
";

echo '<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
      Категории
    </a>
    <ul class="dropdown-menu">';

$res = mysqli_query($con, $query) or die(mysqli_error($con));
while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
    echo '<li><a class="dropdown-item" id="cats_style" href="view.php?cat_id=' . $row['cat_id'] . '">' . htmlspecialchars($row['name']) . '</a></li>';
}

echo '</ul></li>';

echo $showcase;
?>
