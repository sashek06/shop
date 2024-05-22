<?php
/*
Главная страница
*/
header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL);
include('auth.php');
include('func.php');
$title = 'Страница оплаты';
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<?php
    include('showcase.php');
    include('menu.php');
?>

<section class="checkout mt-5 pt-5" style="min-height: 100vh;">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-8 ">
				<form class="credit-card" method="POST" action="user_orders.php">
					<h4 class="title">Данные карты</h4>
					<div class="mb-3">
						<label for="card1" class="form-label">Номер карты</label>
						<div class="d-flex">
							<input type="text" name="card1" id="card1" class="form-control me-1" pattern="[0-9]{4}" maxlength="4" required>
							<input type="text" name="card2" id="card2" class="form-control me-1" pattern="[0-9]{4}" maxlength="4" required>
							<input type="text" name="card3" id="card3" class="form-control me-1" pattern="[0-9]{4}" maxlength="4" required>
							<input type="text" name="card4" id="card4" class="form-control" pattern="[0-9]{4}" maxlength="4" required>
						</div>
					</div>
					<div class="mb-3">
						<label for="expiration" class="form-label">Срок действия</label>
						<div class="d-flex">
							<select name="Month" class="form-select me-1" required>
								<option value="01">01</option>
								<option value="02">02</option>
								<option value="03">03</option>
								<option value="04">04</option>
								<option value="05">05</option>
								<option value="06">06</option>
								<option value="07">07</option>
								<option value="08">08</option>
								<option value="09">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
							</select>
							<select name="Year" class="form-select" required>
								<option value="2023">2023</option>
								<option value="2024">2024</option>
								<option value="2025">2025</option>
								<option value="2026">2026</option>
								<option value="2027">2027</option>
								<option value="2028">2028</option>
								<option value="2029">2029</option>
								<option value="2030">2030</option>
								<option value="2031">2031</option>
								<option value="2032">2032</option>
							</select>
						</div>
					</div>
					<div class="mb-3">
						<label for="cvv" class="form-label">CVV</label>
						<input type="text" name="cvv" id="cvv" class="form-control" required>
					</div>
					<button type="submit" class="btn btn-primary">Продолжить</button>
				</form>
			</div>
		</div>
	</div>
</section>

<?php
    include('footer.php');
?>
