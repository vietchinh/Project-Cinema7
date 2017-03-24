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
			// Refer to index.php to get more info on $_SESSION["module"];
			$module = $_SESSION["module"];
			require_once($module);
		?>
	</main>
</div>
</body>