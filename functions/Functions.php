<?php

/** De functie RedirectNaarPagina
  * optionele parameter PaginaNr. Hiermee kun je de gebruiker naar
  * iedere gewenste pagina doorsturen
  */
function RedirectToPage($Seconds = NULL,$PaginaNr = NULL)
{
	if(!empty($Seconds))
		$Refresh = "Refresh: ".$Seconds.";URL=";
	else
		$Refresh = "location:";

	if(!isset($PaginaNr))
	{
		header($Refresh . "index.php");
		return "<br />U wordt binnen ".$Seconds." seconden doorgestuurd naar de hoofdpagina.";
	}
	else
		header($Refresh . "index.php?PaginaNr=".$PaginaNr);
}
function login($username, $password, $pdo) {
	
	$userData = fetchDatabase($pdo, "login", $username, "Inlognaam");
	
	/*
	Opdracht PM07 STAP 5: Inlogsysteem
	Omschrijving: Voorzie de komende regels van commentaar, zoals in de opdracht gevraagd wordt.
	*/
	
	if ($userData) 
	{
		// Variabelen inlezen uit query

		$password = hash("sha512", $password . $userData->Salt);
		
		if ($userData->Paswoord == $password)
		{

			$userBrowser = $_SERVER["HTTP_USER_AGENT"];

			/*
			Opdracht PM07 STAP 6: Inlogsysteem
			Omschrijving: Vul tot slot de sessie met de juiste gegevens
			*/
			$_SESSION["userId"] = $userData->KlantID;
			$_SESSION["username"] = $userData->Inlognaam;
			$_SESSION["level"] = $userData->Level;
			$_SESSION["loginString"] = hash("sha512", $password . $userBrowser);
			
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
/** De functie LoginCheck
  * controleert of de gebruiker is ingelogd
  */
function LoginCheck($pdo) 
{
    // Controleren of Sessie variabelen bestaan
    if (isset($_SESSION["userId"], $_SESSION["username"],$_SESSION["loginString"])) 
	{
        $clientId = $_SESSION["userId"];
        $loginString = $_SESSION["loginString"];
        $username = $_SESSION["username"];
 
        // Get the user-agent string of the user.
        $userBrowser = $_SERVER["HTTP_USER_AGENT"];
		
		$userData = fetchDatabase($pdo, "login", $clientId, "KlantID", PDO::PARAM_INT);
		
		// controleren of de klant voorkomt in de DB
		if ($userData) {

			//check maken
		    $loginCheck = hash("sha512", $userData->Paswoord . $userBrowser);
 
				//controleren of check overeenkomt met sessie
                if ($loginCheck == $loginString)
					return true;
                else 
                   return false;
         } else 
              return false;         
     } else 
          return false;
}

/* Functies voor validatie van Form Fields */

/** Controleert een email adres op geldigheid
  * @return  boolean
  */
  function is_email($input)
  {
	 return (bool)(preg_match("^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$^",$input));
   }


  /** Controleert of een string aan de minimum lengte voldoet
  * @return  boolean
  */
  function is_minlength($input, $MinLengte)
  {
	return (strlen($input) >= (int)$MinLengte);
  }

  /** Controleert of invoer een NL postcode is
  * @return  boolean
  */
  function is_NL_PostalCode($input)
  {
	return (bool)(preg_match("#^[1-9][0-9]{3}\h*[A-Z]{2}$#i", $input));
  }

  /** Controleert of invoer een NL telefoonnr is
  * @return  boolean
  */
  function is_NL_Telnr($input)
  {
	return (bool)(preg_match("#^0[1-9][0-9]{0,2}-?[1-9][0-9]{5,7}$#", $input) 
               && (strlen(str_replace(array("-", " "), "", $input)) == 10));
  }

/** Controleert of invoer alleen uit letters bestaat
  * @return  boolean
  */
  function is_Char_Only($input)
  {
	return (bool)(preg_match("/^[a-zA-Z ]*$/", $input)) ;
  }

/** functie die controleert of een gebruikersnaam wel of niet in de database		  * voorkomt.
  */
  function is_Username_Unique($input, $pdo)
  {
	$userData = fetchDatabase($pdo, "login", $input, "Inlognaam");
	
	// controleren of de username voorkomt in de DB
	if ($userData || $input == "admin") 
		return false;//username komt voor
	else
		return true;//username komt niet voor
  }
 
// Functie die de paswoord controleert met de opnieuw getypde paswoord.
	function is_Paswoord_Same($password, $retypePassword){
		return ((string)$password == (string)$retypePassword);
	}

	// Functie die de object uit de fetchDatabase omzet in een tabel voor de tabel films
	function returnTablestatus($fs, $form = false){ // fs = fetchStatus
		$table = null;
		foreach ($fs as $object){
			if ($form == true) {
				$form = "
					<form method='post' action='./Modules/Data.Tijden.php'>
						<input type='hidden' name='filmID' value='$object->FilmID'>
						<input type='submit' name='reserveren' value='Reserveren'>
					</form>";
			}
			$table .= "
				<div id='main'>
					<h1>$object->Titel</h1>
					<div class='displayIflex'>
						<div>
							<img src='../Project-Cinema7-img/default.jpg' height='140px'>
							<div id='beschrijving'>
								<h4> Beschrijving: </h4>
								<p> $object->Beschrijving
							</div>
							<div id='infoBar' class='displayIflex'>
								<h4> Duur: </h4>
								<p> $object->Duur </p>
								<h4> Genre: </h4>
								<p> $object->Genre </p>
								<h4> Leeftijd: </h4>
								<p> $object->Leeftijd </p></div>								
							</div>
						</div>
						$form
					</div>";
		}
		return $table;
	}
?>