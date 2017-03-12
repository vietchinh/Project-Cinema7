<?php
/* Bestand met handige PHP functies */

/*
	Opdracht PM04 STAP 1: Verwacht in de bioscoop
	Omschrijving: Vul de Functie ConnectDb aan zodat er werkelijk een connectie wordt gemaakt. Maak gebruik van de constanten uit het Configuratie.php bestand.
*/
function ConnectDB()
{
	$verbinding = "mysql:hosts=" . HOST . ";DB_NAME=" . DBNAME;
	$db = null;
	
	try {
		return new PDO($verbinding, USERNAME, PASSWORD);
	}
	catch(PDOException $e) {
		return null;
		die("<h1> Database Connection Failed </h1>");
	}
}

/** De functie RedirectNaarPagina
  * optionele parameter PaginaNr. Hiermee kun je de gebruiker naar
  * iedere gewenste pagina doorsturen
  */
function RedirectNaarPagina($Seconds = NULL,$PaginaNr = NULL)
{
	if(!empty($Seconds))
		$Refresh = 'Refresh: '.$Seconds.';URL=';
	else
		$Refresh = 'location:';

	if(!isset($PaginaNr))
	{
		header($Refresh . "index.php");
		return "<br />U wordt binnen ".$Seconds." seconden doorgestuurd naar de hoofdpagina.";
	}
	else
		header($Refresh . "index.php?PaginaNr=".$PaginaNr);
}

/** De functie LoginCheck
  * controleert of de gebruiker is ingelogd
  */
function LoginCheck($pdo) 
{
    // Controleren of Sessie variabelen bestaan
    if (isset($_SESSION['user_id'], $_SESSION['username'],$_SESSION['login_string'])) 
	{
        $KlantID = $_SESSION['user_id'];
        $Login_String = $_SESSION['login_string'];
        $Username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
		
		$sth = fetchCustomerdata($pdo, "KlantID", $KlantID, PDO::PARAM_INT);
		
		// controleren of de klant voorkomt in de DB
		if ($sth->rowCount() == 1) 
		{
			// Variabelen inlezen uit query
			$row = $sth->fetch(PDO::FETCH_ASSOC);

			//check maken
		    $Login_Check = hash('sha512', $row['Paswoord'] . $user_browser);
 
				//controleren of check overeenkomt met sessie
                if ($Login_Check == $Login_String)
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
  function is_email($Invoer)
  {
	 return (bool)(preg_match("^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$^",$Invoer));
   }


  /** Controleert of een string aan de minimum lengte voldoet
  * @return  boolean
  */
  function is_minlength($Invoer, $MinLengte)
  {
	return (strlen($Invoer) >= (int)$MinLengte);
  }

  /** Controleert of invoer een NL postcode is
  * @return  boolean
  */
  function is_NL_PostalCode($Invoer)
  {
	return (bool)(preg_match('#^[1-9][0-9]{3}\h*[A-Z]{2}$#i', $Invoer));
  }

  /** Controleert of invoer een NL telefoonnr is
  * @return  boolean
  */
  function is_NL_Telnr($Invoer)
  {
	return (bool)(preg_match('#^0[1-9][0-9]{0,2}-?[1-9][0-9]{5,7}$#', $Invoer) 
               && (strlen(str_replace(array('-', ' '), '', $Invoer)) == 10));
  }

/** Controleert of invoer alleen uit letters bestaat
  * @return  boolean
  */
  function is_Char_Only($Invoer)
  {
	return (bool)(preg_match("/^[a-zA-Z ]*$/", $Invoer)) ;
  }

/** functie die controleert of een gebruikersnaam wel of niet in de database		  * voorkomt.
  */
  function is_Username_Unique($Invoer, $pdo)
  {
	$sth = fetchCustomerdata($pdo, "Inlognaam", $Invoer, PDO::PARAM_STR);
	
	// controleren of de username voorkomt in de DB
	if ($sth->rowCount() == 1) 
		return false;//username komt voor
	else
		return true;//username komt niet voor
  }
 
// Functie die de paswoord controleert met de opnieuw getypde paswoord.
	function is_Paswoord_Same($password, $retypePassword){
		return ((string)$password == (string)$retypePassword);
	}
// Functie die de status: verwacht uit de database filterd met de titel, beschrijving, duur, genre, leeftijd en type.
	function fetchFilmsstatus($pdo, $status = null){
			$sql = 
				"select
					cinema7.films.Titel,
					cinema7.films.Plaatje,
					cinema7.films.Beschrijving,
					cinema7.films.Duur,
					cinema7.films.Genre,
					cinema7.films.Leeftijd,
					cinema7.films.Type,
					cinema7.films.FilmID,
					cinema7.films.Status
				from 
					cinema7.films
				";
		if (!is_null($status)){
			$sql .= "where cinema7.films.Status = :status";
		}

		$pbe = $pdo->prepare($sql); //pbe = Prepare Bind Execute
		$pbe->bindValue( ":status", $status, PDO::PARAM_STR);
		$pbe->execute();
		return $pbe->fetchAll(PDO::FETCH_OBJ);
	}

// Functie die de object uit de functie hier boven met de foreach een tabel maakt en return.
	function returnTablestatus($fs, $form = false){ // fs = fetchStatus
		
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
							<img src='.\Images\default.jpg' height='140px'>
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
// Functie om dynamisch de gebruikers data opzoekt.
	function fetchCustomerdata($pdo, $searchString, $bindValue, $param, $fetchAll = false) {
		
		$searchString = filter_var(trim($searchString), FILTER_SANITIZE_STRING);
		
		if ($fetchAll == true) {
			$sql = "select 
						cinema7.klanten.Voornaam,
						cinema7.klanten.Achternaam,
						cinema7.klanten.Adres,
						cinema7.klanten.Postcode,
						cinema7.klanten.Plaats,
						cinema7.klanten.TelefoonNr,
						cinema7.klanten.Email";
		}
		else {
			$sql = "
			select
				cinema7.klanten.KlantID,
				cinema7.klanten.Inlognaam,
				cinema7.klanten.Paswoord,
				cinema7.klanten.Salt,
				cinema7.klanten.Level";
		}
		
		$sql .= "
		from
			cinema7.klanten
		where
			cinema7.klanten." . $searchString . " = :search LIMIT 1";
		
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":search", $bindValue, $param);
		$pbe->execute();
		return $pbe;
	}
// Functie die de menu data oproept, door middel van level.
	function fetchMenudata($pdo, $level) {
		$sql = "
		select
			*
		from
			cinema7.menu
		where
			cinema7.menu.Level in (0, :level)";
		
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":level", $level, PDO::PARAM_INT);
		$pbe->execute();
		return $pbe->fetchAll(PDO::FETCH_ASSOC);
	}
// Functie die alle data toevoegt aan de database
	function insertDatacustomer($pdo, $fName, $lName, $adres = null, $zipcode, $city, $telNr, $email, $username, $password, $salt) {
		
		$fName 	  = filter_var(trim($fName), 	FILTER_SANITIZE_STRING);
		$lName 	  = filter_var(trim($lName), 	FILTER_SANITIZE_STRING);
		$adres 	  = filter_var(trim($adres), 	FILTER_SANITIZE_STRING);
		$zipcode  = filter_var(trim($zipcode),  FILTER_SANITIZE_STRING);
		$city 	  = filter_var(trim($city), 	FILTER_SANITIZE_STRING);
		$telNr 	  = filter_var(trim($telNr), 	FILTER_SANITIZE_NUMBER_INT);
		$email 	  = filter_var(trim($email), 	FILTER_SANITIZE_STRING);
		$username = filter_var(trim($username), FILTER_SANITIZE_STRING);
		$password = filter_var(trim($password), FILTER_SANITIZE_STRING);
		
		$sql = "
			INSERT INTO
			cinema7.klanten(
				Voornaam, 
				Achternaam,
				Adres,
				Postcode,
				Plaats,
				TelefoonNr,
				Email,
				Inlognaam,
				Paswoord,
				Salt,
				Level )
			VALUES
			(	:fName, 
				:lName, 
				:adres, 
				:zipcode, 
				:city, 
				:telNr, 
				:email, 
				:username,
				:password,
				:salt,
				1
				)";
		
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":fName", 	 $fName, 	PDO::PARAM_STR);
		$pbe->bindValue(":lName", 	 $lName, 	PDO::PARAM_STR);
		$pbe->bindValue(":adres", 	 $adres, 	PDO::PARAM_STR);
		$pbe->bindValue(":zipcode",  $zipcode,  PDO::PARAM_STR);
		$pbe->bindValue(":city", 	 $city,     PDO::PARAM_STR);
		$pbe->bindValue(":telNr",    $telNr,    PDO::PARAM_INT);
		$pbe->bindValue(":email", 	 $email,    PDO::PARAM_STR);
		$pbe->bindValue(":username", $username, PDO::PARAM_STR);
		$pbe->bindValue(":password", $password, PDO::PARAM_STR);
		$pbe->bindValue(":salt", 	 $salt, 	PDO::PARAM_STR);
		$pbe->execute();
		
		$check = fetchCustomerdata($pdo, "Inlognaam", $username, PDO::PARAM_STR);
		
		return ($check->rowCount() == 1) ? true : false;
		
	} 
// Functie die het gebruikers gegevens wijzigd.
	function updateCustomerdata($pdo, $customerId = null, $fName = null, $lName = null, $adres = null, $zipcode = null, $city = null, $telNr = null, $email = null) {
		
		$customerId = filter_var(trim($customerId), FILTER_SANITIZE_NUMBER_INT);
		$fName 	  	= filter_var(trim($fName), 		FILTER_SANITIZE_STRING);
		$lName 	  	= filter_var(trim($lName), 		FILTER_SANITIZE_STRING);
		$adres 	  	= filter_var(trim($adres), 		FILTER_SANITIZE_STRING);
		$zipcode  	= filter_var(trim($zipcode),	FILTER_SANITIZE_STRING);
		$city 	  	= filter_var(trim($city), 		FILTER_SANITIZE_STRING);
		$telNr 	  	= filter_var(trim($telNr), 		FILTER_SANITIZE_NUMBER_INT);
		$email 	  	= filter_var(trim($email), 		FILTER_SANITIZE_STRING);
		
		$sql = "
			UPDATE
				cinema7.klanten
			SET
				cinema7.klanten.Voornaam   = :fName,
				cinema7.klanten.Achternaam = :lName,
				cinema7.klanten.Adres	   = :adres,
				cinema7.klanten.Postcode   = :zipcode,
				cinema7.klanten.Plaats	   = :city,
				cinema7.klanten.TelefoonNr = :telNr,
				cinema7.klanten.Email	   = :email
			WHERE
				cinema7.klanten.KlantID = :customerId";
				
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":customerId",  $customerId, PDO::PARAM_INT);
		$pbe->bindValue(":fName", 	 	$fName, 	 PDO::PARAM_STR);
		$pbe->bindValue(":lName", 	 	$lName,    	 PDO::PARAM_STR);
		$pbe->bindValue(":adres", 	 	$adres, 	 PDO::PARAM_STR);
		$pbe->bindValue(":zipcode",  	$zipcode,  	 PDO::PARAM_STR);
		$pbe->bindValue(":city", 	 	$city,     	 PDO::PARAM_STR);
		$pbe->bindValue(":telNr",    	$telNr,    	 PDO::PARAM_INT);
		$pbe->bindValue(":email", 	 	$email,    	 PDO::PARAM_STR);
		$pbe->execute();
		
	}
?>