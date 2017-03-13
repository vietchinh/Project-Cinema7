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
			
			// Section 1 - Modular Page Buttons
			
			$pageNr = (isset($_GET["pageNr"])) ? $_GET["pageNr"] : 1;
			
			switch($pageNr){
				case 1:
					require_once("./modules/home.php");
					break;
				case 2:
					require_once("./modules/reserveMovie.php");
					break;
				case 3:
					require_once("./modules/expectedMovie.php");
					break;
				case 4:
					require_once("./modules/OverOns.php");
					break;
				case 5:
				// registerLf = Register loginForm; It's the register button on the login page. registerRf = Register registerForm It's the register button on the register page.
					if (isset($_POST["registerLf"]) || isset($_POST["registerRf"])) { 
						$module = "./modules/register.php";
					}
					elseif (!$isLogin) {
						$module = "./modules/login.php";
					}
					else {
						$module = "./modules/logout.php";
					}
					require_once($module);
					break;
				case 6:
					require_once("./modules/myprofile.php");
					break;
				case 7:
					require_once("./modules/Besteloverzicht.php");
					break;				
				case 8:
					require_once("./modules/addMovie.php");
					break;				
				case 9:
					require_once("./modules/FilmAanpassenVerwijderen.php");
					break;
			}
		?>
	</main>
</div>
</body>