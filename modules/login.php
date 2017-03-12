<?php
//begin pagina

//het knopje inloggen van het formulier is ingedrukt.
if(isset($_POST["login"])) {
	
	/*
	Opdracht PM07 STAP 2: Inlogsysteem
	Omschrijving: Lees de formulier gegevens uit middels de post methode. 
	*/
	$username = $_POST["username"];
	$password = $_POST["password"];

	print_r($_POST);
	/*
	Opdracht PM07 STAP 3: Inlogsysteem
	Omschrijving: Roep de functie login aan en geef de 3 correcte paramteres mee aan de functie. Middels een if statement kun je vervolgens controleren of de gebruiker is ingelogd en de juiste boodschap weergeven
	*/
	if (!$username){
		require_once("./forms/loginForm.php");
		echo "<h1> Voer uw gebruikersnaam in. </h1>";		
	}
	elseif (!$password){
		require_once("./forms/loginForm.php");
		echo "<h1> Voer uw paswoord in. </h1>";		
	}
	else {
		$login = login($pdo, $username, $password);
		print_r($login);
		if ($login == "ePassword") {
			require_once("./forms/loginForm.php");
			echo "<h1> U heeft uw password verkeerd ingevoerd, controleer uw password. </h1>";
		}
		elseif ($login == "eUsername"){
			require_once("./forms/loginForm.php");
			echo "<h1> U heeft uw gebruikersnaam verkeerd ingevoerd of uw gebruikersnaam bestaat niet, controleer uw gebruikersnaam. </h1>";
		}
		else {
			echo "<h1> Welcome op cinema7, $username.";
			echo RedirectToPage(5);
		}
	}
}
else
{
	//er is nog niet op het knopje gedrukt, het formulier wordt weergegeven
	require_once("./forms/loginForm.php");
}
?>