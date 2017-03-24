<?php
// Extra Information: All the sql strings are align under each other even if there is a "If Else" statements. This is for reading clarity. Atleast for me.
// in $sql you'll notic lower case and upper case strings. The upper case are for sql syntax. It also serve as readability enhancement.
// Functions and sub sections are orderd based on how many actions is needed basing on CRUD.
// For example Movies has Create Update and Delete, if there is no other section that has more than 3 actions, then the functions for movie will be moved at the top of the sub sections, like Section 2.1.

/*
	databaseFunctions tree:
	|
	--Section 1 - Connect Database
	|	|
	|	-- Null
	|
	--Section 2 - Create (Crud)
	|	|
	|	--Section 2.1 - Create Movie
	|	|
	|	--Section 2.2 - Create Customerdata
	|	|
	|	--Section 2.2 - Create Reservation
	|	|
	|	--Section 2.3 - Create Reservation_ShowID
	|
	--Section 3 - Read   (cRud)
	|	|
	|	--Section 3.1 - Read the enum in the table films (movies)
	|	|
	|	--Section 3.2 - Read Movie
	|	|
	|	--Section 3.3 - Read Customer Data
	|	|
	|	--Section 3.4 - Read Show Data + Movie Price, Picture and Title
	|	|
	|	--Section 3.5 - Read Hall ID from table "vertoningen"
	|	|
	|	--Section 3.6 - Read Menu Data
	|
	--Section 4 - Update (crUd)
	|	|
	|	--Section 4.1 - Update Movie
	|	|
	|	--Section 4.2 - Update Customerdata
	|
	--Section 5 - Delete (cruD)
		|
		--Section 5.1 - Delete Movie
*/

// Section 1 - Connect Database
// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
// A function that connects to the database. For debugging use PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION. IF THIS IS GOING TO BE IN A COMMERCIAL USE, PLEASE CHANGE THE VARIABLE. thanks me.
// Default fetch mode is ASSOC aka an associative array from the database.
// Emulation is off. Suggested at https://phpdelusions.net/pdo#emulation. 
// To quote the writer: "It's hard to decide which mode have to be preferred, but for usability sake I would rather turn it OFF, to avoid a hassle with LIMIT clause. Other issues could be considered negligible in comparison."
function ConnectDB() {
	
	// Source: https://phpdelusions.net/pdo#dsn
	
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

// Section 2 - Create (Crud)
	// Section 2.1 - Create Movie
	// Date Creation: 21-03-2017 | Date Modifcation: 21-03-2017
	function createMovie($pdo, $title, $description, $duration, $genre, $age, $picture, $price, $type, $state) {
		
		$title 	  	 = filter_var(trim($title),       FILTER_SANITIZE_STRING);
		$description = filter_var(trim($description), FILTER_SANITIZE_STRING);
		$genre  	 = filter_var(trim($genre), 	  FILTER_SANITIZE_STRING);
		$picture 	 = filter_var(trim($picture), 	  FILTER_SANITIZE_STRING);
		$type 		 = filter_var(trim($type), 		  FILTER_SANITIZE_STRING);
		$state 		 = filter_var(trim($state), 	  FILTER_SANITIZE_STRING);
		
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

		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":title", 	 	$title, 	  PDO::PARAM_STR);
		$pbe->bindValue(":description", $description, PDO::PARAM_STR);
		$pbe->bindValue(":duration", 	$duration, 	  PDO::PARAM_INT);
		$pbe->bindValue(":genre",  		$genre,  	  PDO::PARAM_STR);
		$pbe->bindValue(":age", 	 	$age,   	  PDO::PARAM_INT);
		$pbe->bindValue(":picture",    	$picture,     PDO::PARAM_STR);
		$pbe->bindValue(":price", 		$price,    	  PDO::PARAM_STR);
		$pbe->bindValue(":type", 		$type, 		  PDO::PARAM_STR);
		$pbe->bindValue(":state", 		$state, 	  PDO::PARAM_STR);		
		
		return $pbe->execute();
	}
	
	// Section 2.2 - Create Customerdata
	// Date Creation: 18-03-2017 | Date Modifcation: 21-03-2017
	function createCustomerdata($pdo, $fName, $lName, $adres, $zipcode, $city, $telNr, $email, $username, $password) {
		
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
		return $pbe->execute();
	}
	
	// Section 2.3 - Create Reservation
	// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
	function createReservation($pdo, $clientId) {
		$sql = "
			INSERT INTO
				cinema7.reserveringen
				(
					KlantID
				)
			VALUES
				(
					:clientId
				)";

		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":clientId",  $clientId,  PDO::PARAM_INT);
		$pbe->execute();
		return $pdo->lastInsertId();
	}

	// Section 2.4 - Create Reservation_ShowID
	// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
	function createReservationshowId($pdo, $reservationId, $showId, $tickets) {

			$sql = "
				INSERT INTO
				cinema7.reserveringen_vertoningen
				(
					ReserveringsID,
					VertoningsID,
					AantalKaartjes
				)
				VALUES
				(
					:reservationId,
					:showId,
					:tickets
				)";

			$pbe = $pdo->prepare($sql);
			$pbe->bindValue(":reservationId",  $reservationId, PDO::PARAM_INT);
			$pbe->bindValue(":showId", 		   $showId, 	   PDO::PARAM_INT);
			$pbe->bindValue(":tickets",  	   $tickets,  	   PDO::PARAM_INT);
			return $pbe->execute();
	}

// Section 3 - Read (cRud)
	// Section 3.1 - Read the enum in the table films (movies)
	// Date Creation: 20-03-2017 | Date Modifcation: 20-03-2017
	function readEmovies($pdo, $column){ // readEmovies = readEnummovies
		// Sources for getting enum tables:
		// http://stackoverflow.com/questions/2350052/how-can-i-get-enum-possible-values-in-a-mysql-database
		// http://stackoverflow.com/questions/614238/how-can-i-rename-a-single-column-in-a-table-at-select	
		$sql = "
			SELECT
				substring(COLUMN_TYPE,7)
				AS
				enum
			FROM
				INFORMATION_SCHEMA.COLUMNS
			WHERE 
				TABLE_SCHEMA = 'cinema7' 
				AND 
				TABLE_NAME = 'films' 
				AND 
				COLUMN_NAME = :columnName";
		
		$pbe = $pdo->prepare($sql); //pbe = Prepare Bind Execute
		$pbe->bindValue(":columnName", $column, PDO::PARAM_STR);
		$pbe->execute();
		return $pbe->fetch(PDO::FETCH_OBJ);
	}
	
	// Section 3.2 - Read Movie
	// Date Creation: 20-03-2017 | Date Modifcation: 20-03-2017
	function readMovies($pdo, $where = false, $state = null) {
			$sql = "
			SELECT
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
			FROM
				cinema7.films
			";
		if ($where){
			$sql .= "
			WHERE
				cinema7.films.Status = :state
			";
		}
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":state", $state, PDO::PARAM_STR);
		$pbe->execute();
		return $pbe->fetchAll(PDO::FETCH_OBJ);
	}
	
	// Section 3.3 - Read Customer Data
	// Date Creation: 20-03-2017 | Date Modification: 21-03-2017
	function readCustomerdata($pdo, $fp, $username, $fetch = PDO::FETCH_OBJ) { // $fp = full or partial. To indicate whether to get ALL the customer data or partial.
		
		$username = filter_var(trim($username), FILTER_SANITIZE_STRING);
		
		if ($fp == "full") {
			$sql = "
			SELECT
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
			SELECT
				cinema7.klanten.KlantID,
				cinema7.klanten.Inlognaam,
				cinema7.klanten.Paswoord,
				cinema7.klanten.Level";
		}

		$sql .= "
			FROM
				cinema7.klanten
			WHERE
				cinema7.klanten.Inlognaam = :username";
		
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":username", $username, PDO::PARAM_STR);
		$pbe->execute();
		return $pbe->fetch($fetch);
	}
	
	// Section 3.4 - Read Show Data + Movie Price, Picture and Title
	// Date Creation: 18-03-2017 | Date Modification: 23-03-2017
	// Get all data from table "vertoningen" and then left join with table "films". Specificly the title price and picture from table "films" are left joined with table "vertoningen".
	// All the data is then orderd by hall number (ZaalNR) and time (Tijd).
	function readShowdata($pdo, $movieId) {
		
		$sql = "
			SELECT
				cinema7.films.Titel,
				cinema7.films.Prijs,
				cinema7.films.Plaatje,
				cinema7.vertoningen.ZaalNR,
				cinema7.vertoningen.Datum,
				cinema7.vertoningen.Tijd,
				cinema7.vertoningen.VertoningsID
			FROM
				cinema7.vertoningen
			LEFT OUTER JOIN
				cinema7.films
			ON
				cinema7.vertoningen.FilmID = cinema7.films.FilmID
			WHERE
				cinema7.vertoningen.FilmID = :movieId
			ORDER BY
				cinema7.vertoningen.ZaalNR AND cinema7.vertoningen.Tijd";
		
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":movieId", $movieId, PDO::PARAM_INT);
		$pbe->execute();
		return $pbe->fetchAll(PDO::FETCH_ASSOC);
	}
	
	// Section 3.5 - Read Hall ID from table "vertoningen"
	// Date Creation: 18-03-2017 | Date Modification: 23-03-2017
	// Get all hall number (ZaalNR) from table "vertoningen", based on movieId (FilmID), orderd by hall number.
	function readShowhallId($pdo, $movieId){
		
		$sql = "
			SELECT DISTINCT
				cinema7.vertoningen.ZaalNR
			FROM
				cinema7.vertoningen
			WHERE
				cinema7.vertoningen.FilmID = :movieId
			ORDER BY
				cinema7.vertoningen.ZaalNR
		";
		
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":movieId", $movieId, PDO::PARAM_INT);
		$pbe->execute();
		return $pbe->fetchAll(PDO::FETCH_OBJ);		
		
	}
	
	// Section 3.6 - Read Menu Data
	// Date Creation: N/A | Date Modification: 21-03-2017
	function readMenudata($pdo, $level) {
		$sql = "
		SELECT
			*
		FROM
			cinema7.menu
		WHERE
			cinema7.menu.Level BETWEEN 0 AND :level";
		
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":level", $level, PDO::PARAM_INT);
		$pbe->execute();
		return $pbe->fetchAll(PDO::FETCH_OBJ);
	}

// Section 4 - Update (crUd)
	// Section 4.1 - Update Movie
	// Date Creation: 20-03-2017 | Date Modifcation: 20-03-2017
	function updateMovie($pdo, $movieId, $title, $description, $duration, $genre, $age, $picture, $price, $type, $state) {
		
		$movieId  	 = filter_var(trim($movieId),  	  FILTER_SANITIZE_NUMBER_INT);
		$title 	  	 = filter_var(trim($title),       FILTER_SANITIZE_STRING);
		$description = filter_var(trim($description), FILTER_SANITIZE_STRING);
		$genre  	 = filter_var(trim($genre), 	  FILTER_SANITIZE_STRING);
		$picture 	 = filter_var(trim($picture), 	  FILTER_SANITIZE_STRING);
		$type 		 = filter_var(trim($type), 		  FILTER_SANITIZE_STRING);
		$state 		 = filter_var(trim($state), 	  FILTER_SANITIZE_STRING);
		
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
		
		$pbe = $pdo->prepare($sql);
		
		$pbe->bindValue(":movieId", 	$movieId, 	  PDO::PARAM_INT);
		$pbe->bindValue(":title", 	 	$title, 	  PDO::PARAM_STR);
		$pbe->bindValue(":description", $description, PDO::PARAM_STR);
		$pbe->bindValue(":duration", 	$duration, 	  PDO::PARAM_INT);
		$pbe->bindValue(":genre",  		$genre,  	  PDO::PARAM_STR);
		$pbe->bindValue(":age", 	 	$age,   	  PDO::PARAM_INT);
		$pbe->bindValue(":picture",    	$picture,     PDO::PARAM_STR);
		$pbe->bindValue(":price", 		$price,    	  PDO::PARAM_STR);
		$pbe->bindValue(":type", 		$type, 		  PDO::PARAM_STR);
		$pbe->bindValue(":state", 		$state, 	  PDO::PARAM_STR);
		
		return $pbe->execute();
	}
	
	// Section 4.2 - Update Customerdata
	// Date Creation: 18-03-2017 | Date Modifcation: 21-03-2017
	function updateCustomerdata($pdo, $username, $fName, $lName, $adres, $zipcode, $city, $telNr, $email) {
		
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
		return $pbe->execute();
	}

// Section 5 - Delete (cruD)
	// Section 5.1 - Delete Movie
	// Date Creation: 20-03-2017 | Date Modifcation: 24-03-2017
	function deleteMovie($pdo, $movieId) {
		
		$sql = "DELETE FROM cinema7.films WHERE cinema7.films.FilmID = :movieId";
		
		$pbe = $pdo->prepare($sql);
		$pbe->bindValue(":movieId", $movieId, PDO::PARAM_INT);
		
		$check = $pbe->execute();
		
		$sql = " OPTIMIZE TABLE cinema7.films";
		$pbe = $pdo->prepare($sql);
		$pbe->execute();			


		$sql = " ALTER TABLE cinema7.films AUTO_INCREMENT = 1";

		$pbe = $pdo->prepare($sql);
		$pbe->execute();
		
		return $check;
	}
?>