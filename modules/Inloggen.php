<?php
function login($username, $password, $pdo)
{
	/*
	Opdracht PM07 STAP 4: Inlogsysteem
	Omschrijving: Maak een prepared statement waarbij je de gegevens van de klant ophaalt
	*/
	
	$sth = fetchCustomerdata($pdo, "Inlognaam", $username, PDO::PARAM_STR);
	
	/*
	Opdracht PM07 STAP 5: Inlogsysteem
	Omschrijving: Voorzie de komende regels van commentaar, zoals in de opdracht gevraagd wordt.
	*/

	if ($sth->rowCount() == 1) 
	{
		// Variabelen inlezen uit query
		$row = $sth->fetch(PDO::FETCH_ASSOC);

		$password = hash('sha512', $password . $row['Salt']);
		
		if ($row["Paswoord"] == $password)
		{

			$user_browser = $_SERVER['HTTP_USER_AGENT'];

			/*
			Opdracht PM07 STAP 6: Inlogsysteem
			Omschrijving: Vul tot slot de sessie met de juiste gegevens
			*/
			$_SESSION['user_id'] = $row["KlantID"];
			$_SESSION['username'] = $row["Inlognaam"];
			$_SESSION['level'] = $row["Level"];
			$_SESSION['login_string'] = hash('sha512', $password . $user_browser);
			
			// Login successful.
			return "Success";
		 } 
		 else 
		 {
			// password incorrect
			return "ePassword"; // Error Password
		 }
	}
	else
	{
		// username bestaat niet
		return "eUsername"; // Error Username
	}
}

//begin pagina

//het knopje inloggen van het formulier is ingedrukt.
if(isset($_POST['Inloggen']))
{
	
	/*
	Opdracht PM07 STAP 2: Inlogsysteem
	Omschrijving: Lees de formulier gegevens uit middels de post methode. 
	*/
	$username = $_POST["username"];
	$password = $_POST["password"];


	/*
	Opdracht PM07 STAP 3: Inlogsysteem
	Omschrijving: Roep de functie login aan en geef de 3 correcte paramteres mee aan de functie. Middels een if statement kun je vervolgens controleren of de gebruiker is ingelogd en de juiste boodschap weergeven
	*/
	if (!$username){
		require_once('./Forms/InloggenForm.php');
		echo "<h1> Voer uw gebruikersnaam in. </h1>";		
	}
	elseif (!$password){
		require_once('./Forms/InloggenForm.php');
		echo "<h1> Voer uw paswoord in. </h1>";		
	}
	else {
		$login = login($username, $password, $pdo);
	if ($login == "ePassword") {
			require_once('./Forms/InloggenForm.php');
			echo "<h1> U heeft uw password verkeerd ingevoerd, controleer uw password. </h1>";
		}
		elseif ($login == "eUsername"){
			require_once('./Forms/InloggenForm.php');
			echo "<h1> U heeft uw gebruikersnaam verkeerd ingevoerd of uw gebruikersnaam bestaat niet, controleer uw gebruikersnaam. </h1>";
		}
		else {
			echo "<h1> Welcome op cinema7, $username.";
			echo RedirectNaarPagina(5);
		}
	}

}
else
{
	//er is nog niet op het knopje gedrukt, het formulier wordt weergegeven
	require_once("./Forms/InloggenForm.php");
}
?>