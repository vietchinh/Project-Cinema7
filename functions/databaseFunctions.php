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
	function fetchDatabase($pdo, $select, $bindValue = null, $param = PDO::PARAM_STR){
	
	// Sources for getting enum tables:
	// http://stackoverflow.com/questions/2350052/how-can-i-get-enum-possible-values-in-a-mysql-database
	// http://stackoverflow.com/questions/614238/how-can-i-rename-a-single-column-in-a-table-at-select
	
	// section 2.1 - Select	
		$sql = "
			select";
		if ($select == "movie" || $select == "allMovie") {
			$sql .= "
				cinema7.films.Titel,
				cinema7.films.Plaatje,
				cinema7.films.Beschrijving,
				cinema7.films.Duur,
				cinema7.films.Genre,
				cinema7.films.Leeftijd,
				cinema7.films.Type,
				cinema7.films.Prijs,
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
		elseif ($select == "customerData") {
			$sql .= "
				cinema7.klanten.Voornaam,
				cinema7.klanten.Achternaam,
				cinema7.klanten.Adres,
				cinema7.klanten.Postcode,
				cinema7.klanten.Plaats,
				cinema7.klanten.TelefoonNr,
				cinema7.klanten.Email
			";		
		}
		elseif ($select == "enum") {
			$sql .= "
				substring(COLUMN_TYPE,7)
				AS
				enum
			";
		}
	// Section 2.2 - from
		$sql .= "
			from
		";
		if ($select == "movie" || $select == "allMovie") {
			$sql .= "
				cinema7.films
			";
		}
		elseif ($select == "menu") {
			$sql .= "
				cinema7.menu
			";
		}
		elseif ($select == "login" || $select == "customerData") {
			$sql .= "
				cinema7.klanten	
			";
		}
		elseif ($select == "enum") {
			$sql .= "
				INFORMATION_SCHEMA.COLUMNS
			";
		}
	// Section 2.3 - Where or any other valid syntax
	// name date summary
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
		elseif ($select == "login" || $select == "customerData") {
			$sql .= "
				where
					cinema7.klanten.Inlognaam = :search
			";
		}
		elseif ($select == "enum") {
			$sql .= "
				WHERE 
					TABLE_SCHEMA = 'cinema7' 
					AND 
					TABLE_NAME = 'films' 
					AND 
					COLUMN_NAME = :columnName
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
			$pbe->bindValue( ":level", $bindValue, $param);
		}
		elseif ($select == "login" || $select == "customerData") {
			$pbe->bindValue(":search", $bindValue, $param);
		}
		elseif ($select == "enum") {
			$pbe->bindValue(":columnName", $bindValue, $param);
		}

		// Section 2.4.3 - Execute
		$pbe->execute();
		
		// Section 2.4.4 - Dynamic Return
		if ($select == "login" || $select == "enum") {
			return $pbe->fetch(PDO::FETCH_OBJ);
		}
		elseif ($select == "customerData"){
			return $pbe->fetch(PDO::FETCH_ASSOC);
		}
		else {
			return $pbe->fetchAll(PDO::FETCH_OBJ);
		}
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
		
		$check = fetchDatabase($pdo, "login", $username, "Inlognaam");
		
		return ($check) ? true : false;
		
	} 
// Functie die het gebruikers gegevens wijzigd.
	function updateCustomerdata($pdo, $username = null, $fName = null, $lName = null, $adres = null, $zipcode = null, $city = null, $telNr = null, $email = null) {
		
		$username 	= filter_var(trim($username), 	FILTER_SANITIZE_STRING);
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
				cinema7.klanten.Inlognaam = :username";
				
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":username",  	$username, 	 PDO::PARAM_STR);
		$pbe->bindValue(":fName", 	 	$fName, 	 PDO::PARAM_STR);
		$pbe->bindValue(":lName", 	 	$lName,    	 PDO::PARAM_STR);
		$pbe->bindValue(":adres", 	 	$adres, 	 PDO::PARAM_STR);
		$pbe->bindValue(":zipcode",  	$zipcode,  	 PDO::PARAM_STR);
		$pbe->bindValue(":city", 	 	$city,     	 PDO::PARAM_STR);
		$pbe->bindValue(":telNr",    	$telNr,    	 PDO::PARAM_INT);
		$pbe->bindValue(":email", 	 	$email,    	 PDO::PARAM_STR);
		$pbe->execute();
		
	}
	function add_Remove_or_Edit_movies($pdo, $addRemoveorEdit, $movieId, $title = null, $description = null, $duration = null, $genre = null, $age = null, $picture = null, $price = null, $type = null, $state = null) {

		$movieId  	 = filter_var(trim($movieId),  	  FILTER_SANITIZE_NUMBER_INT);
		$title 	  	 = filter_var(trim($title),       FILTER_SANITIZE_STRING);
		$description = filter_var(trim($description), FILTER_SANITIZE_STRING);
		$genre  	 = filter_var(trim($genre), 	  FILTER_SANITIZE_STRING);
		$picture 	 = filter_var(trim($picture), 	  FILTER_SANITIZE_STRING);
		$type 		 = filter_var(trim($type), 		  FILTER_SANITIZE_STRING);
		$state 		 = filter_var(trim($state), 	  FILTER_SANITIZE_STRING);
		
		if ($addRemoveorEdit == "add") {
			$sql = "
				INSERT INTO
				cinema7.films(
					Titel, 
					Beschrijving,
					Duur,
					Genre,
					Leeftijd,
					Plaatje,
					Prijs,
					Type,
					Status )
				VALUES
				(	:title, 
					:description, 
					:duration, 
					:genre, 
					:age,
					:picture,
					:price, 
					:type, 
					:state
					)";
		}
		elseif ($addRemoveorEdit == "remove") {
			$sql = "
				DELETE FROM
				cinema7.films
				WHERE
				cinema7.films.FilmID = :movieId";
		}
		else {
			$sql = "
				UPDATE
					cinema7.films
				SET
					cinema7.films.Titel		   = :title,
					cinema7.films.Beschrijving = :description,
					cinema7.films.Duur	   	   = :duration,
					cinema7.films.Genre   	   = :genre,
					cinema7.films.Leeftijd	   = :age,
					cinema7.films.Plaatje 	   = :picture,
					cinema7.films.Prijs	   	   = :price,
					cinema7.films.Type 		   = :type,
					cinema7.films.Status 	   = :state
				WHERE
					cinema7.films.FilmID = :movieId";
		}
		

		$pbe = $pdo->prepare($sql);
		
		if ($addRemoveorEdit != "remove"){
			$pbe->bindValue(":title", 	 	$title, 	  PDO::PARAM_STR);
			$pbe->bindValue(":description", $description, PDO::PARAM_STR);
			$pbe->bindValue(":duration", 	$duration, 	  PDO::PARAM_INT);
			$pbe->bindValue(":genre",  		$genre,  	  PDO::PARAM_STR);
			$pbe->bindValue(":age", 	 	$age,   	  PDO::PARAM_INT);
			$pbe->bindValue(":picture",    	$picture,     PDO::PARAM_STR);
			$pbe->bindValue(":price", 		$price,    	  PDO::PARAM_STR);
			$pbe->bindValue(":type", 		$type, 		  PDO::PARAM_STR);
			$pbe->bindValue(":state", 		$state, 	  PDO::PARAM_STR);
		}
		
		if ($addRemoveorEdit != "add") {
			$pbe->bindValue(":movieId", $movieId, PDO::PARAM_INT);
		}
		
		$check = $pbe->execute();
		
		return $check;
	}
?>