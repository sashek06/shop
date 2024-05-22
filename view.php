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
    $con=connect();
    $title='Товары';
    $table='products';
?>

<script>
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
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(response) {
                $('#cart_info').html('Корзина ('+response.amount+')');
            },
            error: function(objAJAXRequest, strError) {
                alert('Произошла ошибка! Тип ошибки: ' +strError);
            }
        });
    };

    // сразу после загрузки страницы выполнить
    $(function() {
        get_cart_info();
    });

    // добавлям товар в корзину пользователю
    function to_cart(id) {
        var user_id='<?php echo $_SESSION["id"];?>';
        $.ajax({
            url: 'ajax/ajax_add_to_cart.php',
            type: 'POST',
            async: true,
            data: {
                id: id,
                user_id: user_id
            },
            beforeSend: function() {
            },
            complete: function() {
            },
            success: function(response) {
                if (response=='ok') {
                    get_cart_info();
                    alert('Добавлено в корзину!');
                }
                else alert(response);
            },
            error: function(objAJAXRequest, strError) {
                alert('Произошла ошибка! Тип ошибки: ' +strError);
            }
        });
    };
</script>

<?php
    include('showcase.php');
    include('menu.php');
?>

<section class="cart mt-5" style="min-height: 100vh;">
<div class="container">
	<div class="h1 text-center mb-5">
	<div class="row justify-content-center">
		<div class="col-md-8">
			
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
                    $new="<div><img src='images/new.png' style='width:70px'></div>";
                }
                else {
                    $new='';
                };

                if ($row['discount_value']) { // цена со скидкой
                    $price_new=number_format (round($row['price']*(1-$row['discount_value']/100), 2), 2, '.', '');
                    $price_str="
                        <font style='color: #888; font-size:x-small; text-decoration:line-through'>$row[price]$valuta</font>
                        <img src='images/discount.png' height='24px' title='Скидка'>
                        $price_new$valuta
                    ";
                    $price_str=trim($price_str);
                }
                else {
                    $price_str="$row[price]$valuta";
                };

                // обрезать описание, если оно очень длинное
                if (mb_strlen($row['descr'], 'UTF-8')>50) {
                    $descr=mb_substr($row['descr'], 0, 50, 'UTF-8').'...';
                }
                else {
                    $descr=$row['descr'];
                };

                echo "
                <div class='col-md-6'>
                    <div class='card' style='width: 18rem;'>
                        <img src='$fname' class='card-img-top' alt='$row[name]' style='cursor:pointer;' onclick='to_cart($row[id]);'>
                        <div class='card-body'>
                            <h5 class='card-title'>$row[name]</h5>
                            <p class='card-text'>$price_str</p>
							<div class='buttons d-flex justify-content-around'>
								<a href='card.php?product_id=$row[id]' class='btn btn-primary'>Подробнее</a>
                            	<button onclick='to_cart($row[id]);' class='btn btn-success'>В корзину</button>
							</div>
                        </div>
                        $new
                    </div>
                </div>
                ";
            };
        };
    };
    echo '</div></div>';
?>
		</div>
	</div>
</div>
</section>

<?php
    include('footer.php');
?>
