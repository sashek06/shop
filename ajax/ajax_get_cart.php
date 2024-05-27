<?php
// Скрипт возвращает таблицу товаров в корзине
header('Content-Type: text/html; charset=utf-8');
include "../database.php";
include "../func.php";
error_reporting(E_ALL);

$result = array('sum' => 0, 'amount' => 0);

if (!empty($_POST['user_id'])) {
    $user_id = abs(intval(trim($_POST['user_id'])));
} else {
    die('Не указан идентификатор пользователя');
}

// Подключаемся к БД
$con = connect();
$table = 'items';

$query = "
    SELECT
        `$table`.`id`,
        `$table`.`ord_id`,
        `products`.`name`,
        `products`.`id` AS `product_id`,
        `$table`.`amount`,
        `$table`.`price`
    FROM
        `$table`
    LEFT JOIN
        `products` ON `products`.`id` = `$table`.`product_id`
    LEFT JOIN
        `users` ON `users`.`id` = `$table`.`user_id`
    WHERE
        `users`.`id` = '$user_id'
        AND `$table`.`ord_id` = 0
    LIMIT 50;
";

$sum = 0;
$amount = 0;
$res = mysqli_query($con, $query) or die(mysqli_error($con));

if (!mysqli_num_rows($res)) {
    die('<h3>Корзина пуста</h3>');
}

echo '
<table class="table table-striped">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Наименование</th>
        <th scope="col">Количество</th>
        <th scope="col">Цена за 1ед.</th>
        <th scope="col">Сумма</th>
        <th scope="col">Действия</th>
    </tr>
    </thead>
    <tbody>
';

$rowNumber = 1;
while ($row = mysqli_fetch_assoc($res)) {
    $productSum = round($row['price'] * $row['amount'], 2);
    $sum += $productSum;
    $amount += $row['amount'];
    
    $fname = '../upload/' . $row['product_id'] . '.jpg';
    if (!file_exists($fname)) {
        $fname = '../upload/0.jpg';
    }
    
    echo "
    <tr>
        <th scope='row' class='align-middle'>{$rowNumber}</th>
        <td class='align-middle'>{$row['name']}</td>
        <td class='align-middle'>
            <button onclick='dec_amount_cart({$row['id']})' title='Уменьшить на 1' type='button' class='btn btn-dark'>-</button>
            {$row['amount']}
            <button onclick='inc_amount_cart({$row['id']})' title='Увеличить на 1' type='button' class='btn btn-dark'>+</button>
        </td>
        <td class='align-middle'>{$row['price']}</td>
        <td class='align-middle'>{$productSum}</td>
        <td class='align-middle'>
            <button onclick='delete_from_cart({$row['id']})' title='Удалить' type='button' class='btn btn-outline-danger'>Удалить</button>
        </td>
    </tr>
    ";
    $rowNumber++;
}

echo "
<tr>
    <td colspan='6' class='align-middle'><b>Итого: $sum рублей</b></td>
</tr>
</tbody></table>";
?>
