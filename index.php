<?php
// Section 1 - Insert functions and databaseFunctions modules
// Date Creation: 18-03-2017 | Date Modification: 18-03-2017
session_start();
require_once("./functions/functions.php");
require_once("./functions/databaseFunctions.php");

// Section 2 - Connect Database
// Date Creation: 18-03-2017 | Date Modification: 18-03-2017
$pdo = connectDB();

// Section 4 - Login check
// Date Creation: 19-03-2017 | Date Modification: 19-03-2017

$isLogin = loginCheck($pdo);


$level = (!$isLogin) ? 0 : $_SESSION["level"]; // Set level for /modules/menu.php

$MenuInUitloggen = (!$isLogin) ? "Login" : "Uitloggen" ; //Set menu button for /modules/menu.php

// Section 5 - Page Selection using POST PageName and session module.
// Date Creation: 19-03-2017 | Date Modification: 24-03-2017
// If post pageName or module is set then it will check basing off the post pageName key to the user selected page.
// After it found it's value, like: $pageName is the same as Home. It will set local variable called module to the module like home.php from the previous example in the module folder. Module-ception.
// After that it will put local variable module into the session called module.
// If the user is not logged in or doesn't have level 5, then the user is going to be redirected to home, without any message.
if (isset($_POST["pageName"]) && isset($_SESSION["module"])){

	$pageName = key($_POST["pageName"]);

	if ($pageName == "Home") {
		$module = "./modules/home.php";	
	}
	elseif ($pageName == "Films in de Bioscoop" || $pageName == "Film Reserveren") {
		$module = "./modules/availableMovies.php";
	}
	elseif ($pageName == "Films Verwacht") {
		$module = "./modules/expectedMovie.php";
	}
	elseif ($pageName == "Over Ons") {
		$module = "./modules/aboutUs.php";
	}
	elseif ($pageName == "Registreren") {
		$module = "./modules/register.php";
	}
	elseif ($pageName == "Login") {
		$module = "./modules/login.php";
	}
	elseif ($isLogin) {
		if ($level >= 1) {
			if ($pageName == "Reserveren"){
				$module = "./modules/reserveMovies.php";
			}
			elseif ($pageName == "MijnProfiel") {
				$module = "./modules/myProfile.php";
			}
			elseif ($pageName == "Besteloverzicht" || $pageName == "Bestelling Lijst") {
				$module = "./modules/orderList.php";
			}
			elseif ($pageName == "Uitloggen") {
				require_once("./modules/logout.php");
				$module = "./modules/home.php";
			}
		}
		else {
			$module = "./modules/home.php";
		}
		if ($level >= 5) {
			if ($pageName == "Film Toevoegen/Aanpassen/Verwijderen") {
				$module = "./modules/addDeleteorEditmovie.php";
			}
		}
		else {
			$module = "./modules/home.php";	
		}
	}
	$_SESSION["module"] = $module;
}
elseif (!isset($_SESSION["module"])) {
	$_SESSION["module"] = "./modules/home.php";
}
// Section 6 - Insert Homepage
// Date Creation: 18-03-2017 | Date Modification: 18-03-2017
require("homepage.php");
?>