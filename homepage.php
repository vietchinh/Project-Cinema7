<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<title>Cinema 7</title>
</head>
<body>
<header>
	<img src="../Project-Cinema7-img/logo.jpg" id="Logo" alt="Cinema 7 Logo" />
</header>
<div id="MenuWrapper">
	<nav>
		<?php
			require_once("./modules/menu.php");
		?>
	</nav>
</div>
<div id="MainWrapper">
	<div id="Banner"></div>
	<main>
		<?php
			//$module = $_SESSION["module"];
			echo "<pre>";
			print_r($_POST);
			print_r($_SESSION);
			echo "</pre>";
			require_once($_SESSION["module"]);
		?>
	</main>
</div>
</body>