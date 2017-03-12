<?php
// Section 1 - Connect Database
function ConnectDB() {
	
	// Source: https://phpdelusions.net/pdo#why
	
	$host = "localhost";
	$db   = "cinema7";
	$user = "user";
	$pass = "userpermission";
	$charset = "utf8";

	$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; //Data Source Name = DNS
	$opt = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false,
	];
	
	try {
		return new PDO($dsn, $user, $pass, $opt);
	}
	catch(PDOException $e) {
		return null;
		die("<h1> Database Connection Failed </h1>");
	}
}

// Section 2 - Database Fetch
	function fetchDatabase($pdo, $select, $bindValue = null, $searchString = null, $param = PDO::PARAM_STR){
		
	// section 2.1 - Select	
		$sql = "
			select";
		if ($select == "movie") {
			$sql .= "
				cinema7.films.Titel,
				cinema7.films.Plaatje,
				cinema7.films.Beschrijving,
				cinema7.films.Duur,
				cinema7.films.Genre,
				cinema7.films.Leeftijd,
				cinema7.films.Type,
				cinema7.films.FilmID,
				cinema7.films.Status
			";
		}
		elseif ($select == "menu") {
			$sql .= "
				*
			";
		}
		elseif ($select == "login"){
			$sql .= "
				cinema7.klanten.KlantID,
				cinema7.klanten.Inlognaam,
				cinema7.klanten.Paswoord,
				cinema7.klanten.Level";
		}
	// Section 2.2 - from
		$sql .= "
			from
		";
		if ($select == "movie") {
			$sql .= "
				cinema7.films
			";
		}
		elseif ($select == "menu") {
			$sql .= "
				cinema7.menu
			";
		}
		elseif ($select == "login") {
			$sql .= "
				cinema7.klanten	
			";
		}
	// Section 2.3 - Where or any other valid syntax
		if ($select == "movie") {
			$sql .= "
				where 
					cinema7.films.Status = :status
			";
		}
		elseif ($select == "menu") {
			$sql .= "
				where
			";
			if ($bindValue < 5) {
				$sql .= "
					cinema7.menu.Level in (0, :level)
				";
			}
			else {
				$sql .= "
					cinema7.menu.Level in (0, 1, :level)
				";
			}
		}
		elseif ($select == "login") {
			$sql .= "
				where
					cinema7.klanten." . $searchString . " = :search
			";
		}
		
	// Section 2.4 - Prepare Bind Execute Return
		// Section 2.4.1 - Prepare
		$pbe = $pdo->prepare($sql); //pbe = Prepare Bind Execute

		// Section 2.4.2 - Dynamic Bind Value
		if ($select == "movie") {
			$pbe->bindValue( ":status", $bindValue, $param);
		}
		elseif ($select == "menu") {
			$pbe->bindValue( ":level", $bindValue, PDO::PARAM_INT);
		}
		elseif ($select == "login") {
			$pbe->bindValue(":search", $bindValue, $param);
		}

		// Section 2.4.3 - Execute
		$pbe->execute();
		
		// Section 2.4.4 - Dynamic Return
		if ($select == "movie" || $select == "menu") {
			return $pbe->fetchAll(PDO::FETCH_OBJ);
		}
		elseif ($select == "login") {
			return $pbe->fetch(PDO::FETCH_OBJ);
		}
	}

// Functie om dynamisch de gebruikers data opzoekt.
	/*function fetchCustomerdata($pdo, $searchString, $bindValue, $param, $fetchAll = false) {
		
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
		
		$pbe->execute();
		return $pbe;
	}*/
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
	function insertCustomerdata($pdo, $fName, $lName, $adres = null, $zipcode, $city, $telNr, $email, $username, $password) {
		
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
		$pbe->execute();
		
		//$check = fetchDatabase($pdo, "login", $username, "Inlognaam");
		
		//return ($check) ? true : false;
		
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