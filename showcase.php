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

echo '
<html data-bs-theme="dark">
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
  <div class="container">
  <a class="navbar-brand" href="index.php">
      <img src="images/controller.svg" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
      WoWBoost
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-between" id="navbarNavDropdown">
		<ul class="navbar-nav">
            <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Категории</a>
            <ul class="dropdown-menu">';

$res = mysqli_query($con, $query) or die(mysqli_error($con));
while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
  $sub_query = "
        SELECT
            `name`, `id` AS `sub_cat_id`
        FROM
            `categories`
        WHERE
            `categories`.`parent` = " . $row['cat_id'] . "
    ";
  $sub_res = mysqli_query($con, $sub_query) or die(mysqli_error($con));

  // Если есть подкатегории
  if (mysqli_num_rows($sub_res) > 0) {
    echo '<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="view.php?cat_id=' . $row['cat_id'] . '">' . htmlspecialchars($row['name']) . '</a>';
    echo '<ul class="dropdown-menu">';
    while ($sub_row = mysqli_fetch_array($sub_res, MYSQLI_ASSOC)) {
      echo '<li><a class="dropdown-item" href="view.php?cat_id=' . $sub_row['sub_cat_id'] . '">' . htmlspecialchars($sub_row['name']) . '</a></li>';
    }
    echo '</ul></li>';
  } else {
    // Если нет подкатегорий
    echo '<li><a class="dropdown-item" href="view.php?cat_id=' . $row['cat_id'] . '">' . htmlspecialchars($row['name']) . '</a></li>';
  }
}

echo '
</ul></li>
</ul>
<ul class="navbar-nav">';

echo $showcase;
?>

<style>
  .dropdown-submenu {
    position: relative;
  }

  .dropdown-submenu .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: -1px;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var dropdowns = document.querySelectorAll('.dropdown-submenu');
    dropdowns.forEach(function(dropdown) {
      dropdown.addEventListener('mouseenter', function() {
        var submenu = this.querySelector('.dropdown-menu');
        if (submenu) {
          submenu.classList.add('show');
        }
      });

      dropdown.addEventListener('mouseleave', function() {
        var submenu = this.querySelector('.dropdown-menu');
        if (submenu) {
          submenu.classList.remove('show');
        }
      });
    });
  });
</script>