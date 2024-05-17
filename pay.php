<?php
	/*
	Главная страница
	*/
	header('Content-type: text/html; charset=utf-8');
	error_reporting(E_ALL);
	include('auth.php');
	include('func.php');
	$title='Страница оплаты';
?>
<html data-bs-theme="dark">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
<table id="main_table" border="0">
	<!-- баннер -->
	<tr>
		<td colspan=2 style="text-align:center">
			<?php
				include('top.php');
			?>
		</td>
	</tr>

	<tr>
		<!-- меню -->
		<td width="270px" class="menu" style="vertical-align:top;">
			<?php
				include('menu.php');
				include('showcase.php');
			?>
		</td>

		<!-- контент -->
		<td width="900px" style="vertical-align:top;">



<form class="credit-card" method="POST" action="user_orders.php">
<h4 class="title">Данные карты</h4>
<!-- Card Number -->
<p>
Номер карты:
<input type="text" name="card1" id="card1" class="card-number" pattern="[0-9]{4}" size=4 required> -
<input type="text" name="card2" id="card2" class="card-number" pattern="[0-9]{4}" size=4 required> -
<input type="text" name="card3" id="card3" class="card-number" pattern="[0-9]{4}" size=4 required> -
<input type="text" name="card4" id="card4" class="card-number" pattern="[0-9]{4}" size=4 required><br>
</p>
<p>
Срок действия:
<select name="Month" required>
	<option value="january">01</option>
	<option value="february">02</option>
	<option value="march">03</option>
	<option value="april">04</option>
	<option value="may">05</option>
	<option value="june">06</option>
	<option value="july">07</option>
	<option value="august">08</option>
	<option value="september">09</option>
	<option value="october">10</option>
	<option value="november">11</option>
	<option value="december">12</option>
</select>

<select name="Year" required>
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
</p>
<p>
CVV:
<!-- Card Verification Field -->
<input type="text"placeholder="CVV" required>
</p>

<!-- Buttons -->
<button type="submit" class="proceed-btn">Продолжить</a></button>
</div>
</form>


		</td>
	</tr>

	<!-- подвал -->
	<tr>
		<td colspan=2>
			<?php
				include('footer.php');
			?>
		</td>
	</tr>

</table>

</body>
</html>