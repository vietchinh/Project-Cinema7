<?php
session_start();
require('./Configuratie.php');
require('./Modules/Functies.php');

/*
	Opdracht PM04 STAP 2: Verwacht in de bioscoop
	Omschrijving: Roep de functie ConnectDB aan en stop het resultaat in de variabele $pdo
*/
$pdo = connectDB();


/*
	Opdracht PM03 STAP 2: Bioscoop Modulair
	Omschrijving: Lees de variabele pagina in middels de GET methode
*/
if (isset($_GET["pageNr"])){
	$pageNr = $_GET["pageNr"];
}
else {
	$_GET["pageNr"] = 1;
	$pageNr = $_GET["pageNr"];
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="Style.css" />
	<title>Cinema 7</title>
</head>
<body>
<header>
	<img src="./Images/logo.jpg" id="Logo" alt="Cinema 7 Logo" />
</header>
<div id="MenuWrapper">
	<nav>
		<?php
			require('./Modules/Menu.php');
		?>
	</nav>
</div>
<div id="MainWrapper">

	<div id="Banner"></div>
	<main>
		<?php
			/*
				Opdracht PM03 STAP 3: Bioscoop Modulair
				Omschrijving: Maak een switch-statement waarin aan de hand van
							  het pagina nr de juiste module wordt geladen
			*/
			switch($pageNr){
				case 1:
					require_once("./Modules/Home.php");
					break;
				case 2:
					require_once("./Modules/Reserveren.php");
					break;
				case 3:
					require_once("./Modules/Verwacht.php");
					break;
				case 4:
					require_once("./Modules/OverOns.php");
					break;
				case 5:
					if ($isRegister) {
						$module = "./Modules/Registreren.php";
					}
					elseif (!$isLogin) {
						$module = "./Modules/Inloggen.php";
					}
					else {
						$module = "./Modules/Uitloggen.php";
					}
					require_once($module);
					break;
				case 6:
					require_once("./Modules/MijnProfiel.php");
					break;
				case 7:
					require_once("./Modules/Besteloverzicht.php");
					break;				
				case 8:
					require_once("./Modules/FilmToevoegen.php");
					break;				
				case 9:
					require_once("./Modules/FilmAanpassenVerwijderen.php");
					break;
			}
		?>
	</main>
</div>
</body>