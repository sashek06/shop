<?php
header('Content-type: text/html; charset=utf-8');
include "auth.php";
error_reporting(E_ALL);
/*
	if (!in_array($_SESSION['level'], array(10))) { // доступ разрешен только группе пользователей
		header("Location: login.php"); // остальных просим залогиниться
		exit;
	};
	*/

/*
	Скрипт - просмотр товаров в категории
	*/
include "database.php";
include "func.php";
include "styles.php";
include "scripts.php";
$con = connect();
$title = 'Товары';
$table = 'products';
?>


<?php

?>

<section class="cart mt-5" style="min-height: 100vh;">
<div class="container">
	<div class="h1 text-center">		
<?php
    $cat_id=empty($_GET['cat_id']) ? '' : abs(intval($_GET['cat_id']));
    if ($cat_id) {
        // если выбрана категория
        $query="
            SELECT name
            FROM categories
            WHERE 1
                AND id=$cat_id
        ";
        $res=mysqli_query($con, $query) or die(mysqli_error($con));
        $row=mysqli_fetch_array($res);
        $cat_name=$row['name'];
        echo "$cat_name</div>";
    };
    $filter_cat= $cat_id==0 ? '' : "AND `$table`.`cat_id`='$cat_id'"; // если категория не выбрана, показать все товары
    $query="
    SELECT t.*
    FROM (
        SELECT
            `$table`.`id`,
            `$table`.`name`,
            `$table`.`descr`,
            `categories`.`name` AS `category`,
            `$table`.`price`,
            `$table`.`weight`,
            `$table`.`length`,
            `$table`.`width`,
            `$table`.`height`,
            `$table`.`amount`- IFNULL(ROUND(SUM(`items`.`amount`)),0) AS 'amount',
    #       `$table`.`amount`,
            `discounts`.`value` AS `discount_value`,
            TIMESTAMPDIFF(DAY, `$table`.`date_add`, NOW()) AS `delta`
        FROM
            `$table`
        LEFT JOIN
            `items` ON `items`.`product_id`=`products`.`id`
        LEFT JOIN
            `categories` ON `categories`.`id`=`$table`.`cat_id`
        LEFT JOIN
            `discounts` ON `discounts`.`id`=`$table`.`discount_id` AND NOW() BETWEEN `discounts`.`start` AND `discounts`.`stop`
        WHERE 1
            $filter_cat
        GROUP BY `$table`.`id`
        ORDER BY `$table`.`name`
        LIMIT 50) AS t
    WHERE amount>0;
    ";
    $res=mysqli_query($con, $query) or die(mysqli_error($con));

    // собираем данные в массив
    $a=array();
    while ($row=mysqli_fetch_array($res, MYSQLI_ASSOC)) {
        $a[]=$row;
    };

    // вывод в таблицу
    $in_row=3; // сколько столбцов товаров
    // количество строк
    $row_count=ceil(count($a)/$in_row);
    echo '<div class="row justify-content-center">';
    for($i=1; $i<=$row_count; $i++) {
        for($j=1; $j<=$in_row; $j++) {
            $ind=($i-1)*$in_row+$j-1;
            if (isset($a[$ind])) {
                $row=$a[$ind];
                $fname='images/'.$row['id'].'.jpg';
                if (!file_exists($fname)) { // если нет файла, показать "НЕТ ФОТО"
                    $fname='images/stock-goods.webp';
                };

                if ($row['delta']<30) { // товар добавлен меньше 30 дней назад, т.е. это новинка
                    $new="";
                }
                else {
                    $new='';
                };

				if ($row['discount_value']) { // цена со скидкой
					$price_new=number_format (round($row['price']*(1-$row['discount_value']/100), 2), 2, '.', '');
					$price_str="
						<font style='color: #888; font-size:x-small; text-decoration:line-through'>$row[price]$valuta</font>
						<img src='images/discount.png' height='24px' title='Скидка'>
						<font style='color: #000;'>$price_new$valuta</font>
					";
					$price_str=trim($price_str);
				}
				else {
					$price_str="<font style='color: #000;'>$row[price]$valuta</font>";
				};

				// обрезать описание, если оно очень длинное
				if (mb_strlen($row['descr'], 'UTF-8')>50) {
					$descr=mb_substr($row['descr'], 0, 50, 'UTF-8').'...';
				}
				else {
					$descr=$row['descr'];
				};
				echo "
				<td style='width:400px; height:400px'>
					$new
					Наименование: <b><a href='card.php?product_id=$row[id]'>$row[name]</a></b><br>
					Описание: $descr<br>
					Цена: $price_str<br>
					Осталось: $row[amount] шт.<br>
					<img src=\"$fname\" width=\"250px\" height=\"250px\" style='cursor:pointer;' onclick='to_cart($row[id]);'><br>
					<button onclick='to_cart($row[id]);'>В корзину</button>
				</td>
				";
			};
		};
		echo "</tr>";
	};
	echo '</table>';


?>

		</td>
	</tr>

</table>

<?php
include('footer.php');
?>
