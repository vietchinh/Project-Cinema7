<?php
/*
	functions tree:
	|
	--Section 1 - redirect to a specific page.
	|	|
	|	-- Null
	|
	--Section 2 - Login
	|	|
	|	-- Null
	|
	--Section 3 - User check whether it's a valid customer
	|	|
	|	-- Null
	|
	--Section 4 - Create table with the data from the database in the table: Films (Movies)
	|	|
	|	-- Null
	|
	--Section 5 - Create table with the data from the database in the table: Films (Movies)
	|	|
	|	--Section 5.1 - Validate Email
	|	|
	|	--Section 5.2 - Validate input length
	|	|
	|	--Section 5.3 - Validate dutch postal code
	|	|
	|	--Section 5.4 - Validate dutch telephone number
	|	|
	|	--Section 5.5 - Validate whether input is a input type of char.
	|	|
	|	--Section 5.6 - Validate whether the username is unique
	|	|
	|	--Section 5.7 - Validate whether the input 1 is the same as input2.
*/


// Section 1 - redirect to a specific page.
// Date Creation: 20-03-2017 | Date Modifcation: 20-03-2017
// Using the session named module and header refresh it made it possible to call this function to redirect instanously or in a user set seconds.
function redirectTopage($seconds = 0,$pageName = "home"){
	
	header("refresh:" . $seconds);
	
	$module =  "./modules/" . $pageName . ".php";
	
	$_SESSION["module"] = $module;
	
	if($pageName == "home"){
		return "<br />U wordt binnen ".$seconds." seconden doorgestuurd naar de hoofdpagina.";
	}
}

// Section 2 - Login
// Date Creation: 18-03-2017 | Date Modifcation: 20-03-2017
// A function that create sessions named: customerId, username, level, and loginString.
// The session can be later checked by function logincheck.
function login($pdo, $username, $password) {
	
	// The function setup is: readCustomerdata($pdo (required), "full" or "partial",  an username (required) )
	$userData = readCustomerdata($pdo, "partial", $username);

	// Section 2.1 - Username Check
	// If there is data in variable $userData, then it goes forward to the second check, the password.
	// If not then it notify the user that the username does not exist. Using return "eUsername" (empty username).
	if ($userData) {
		
		// Section 2.1.1 - Password check
		// If the password is the same then the sessions are getting set with the variables from the $userdata object.
		// If not then it notify the user that the password is incorrect. Using return "ePassword" (empty password).
		if (password_verify($password, $userData->Paswoord)) {

			$userBrowser = $_SERVER["HTTP_USER_AGENT"];
			
			$_SESSION["customerId"] = $userData->KlantID;
			$_SESSION["username"] = $userData->Inlognaam;
			$_SESSION["level"] = $userData->Level;
			$_SESSION["loginString"] = hash("sha512", $userData->Paswoord . $userBrowser);
			
			return "success";
		 }
		 else {
			return "ePassword";
		 }
	}
	else {
		return "eUsername";
	}
}

// Section 3 - User check whether it's a valid customer
// Date Creation: 18-03-2017 | Date Modifcation: 20-03-2017
// Returns a value either true or false.
function loginCheck($pdo) {
	
	if (isset($_SESSION["customerId"], $_SESSION["username"], $_SESSION["loginString"])) {
		
        $customerId  = $_SESSION["customerId"];
        $loginString = $_SESSION["loginString"];
        $username 	 = $_SESSION["username"];
		
        // Get the user-agent string of the user.
        $userBrowser = $_SERVER["HTTP_USER_AGENT"];
		
		$userData = readCustomerdata($pdo, "partial", $username);

		if ($userData) {

		    $loginCheck = hash("sha512", $userData->Paswoord . $userBrowser);
			
			if ($loginCheck == $loginString) {
				return true;					
			}
			else {
			   return false;					
			}
        }
		else {
			return false; 			
		}        
    } 
	else {
		return false;
	}
}

// Section 4 - Create table with the data from the database in the table: Films (Movies)
// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
// Function that gets an object and then iterate that object into a table.
function returnTablestatus($fs, $form = false, $available = false){ // fs = fetchStatus
	$table = null;

	foreach ($fs as $object){
		if ($available) {
			$form = "Registeer nu om films te kunnen reserveren! Als je al een account hebt dan log nu in!";
		}
		elseif ($form) {
			$form = "
				<form method='post'>
					<input type='hidden' name='filmTitle' value='$object->Titel'>
					<input type='hidden' name='filmId' value='$object->FilmID'>
					<input type='submit' name='pageName[Reserveren]' value='Reserveren'>
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
							<p> $object->Beschrijving </p>
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
// Section 5 - Form validations.
// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
// Several functions that validate the form based on certain criteria.

	// Section 5.1 - Validate Email
	// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
	// Check whether the input is a valid email format.
	// Returns a boolean.
	function isEmail($input) {
		return (bool)(preg_match("^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$^",$input));
	}


	// Section 5.2 - Validate input length
	// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
	// Check whether the input is equal or larger than 2
	// Returns a boolean.
	function isMinlength($input, $minLength){
		return (strlen($input) >= (int)$minLength);
	}

	// Section 5.3 - Validate dutch postal code
	// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
	// Check whether the input is a valid dutch postal code format.
	// Returns a boolean.
	function isNLpostalCode($input) {
		return (bool)(preg_match("#^[1-9][0-9]{3}\h*[A-Z]{2}$#i", $input));
	}

	// Section 5.4 - Validate dutch telephone number
	// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
	// Check whether the input is a valid dutch telephone number.
	// Returns a boolean.
	function isNLtelNr($input) {
	return (bool)(preg_match("#^0[1-9][0-9]{0,2}-?[1-9][0-9]{5,7}$#", $input) && (strlen(str_replace(array("-", " "), "", $input)) == 10));
	}

	// Section 5.5 - Validate whether input is a input type of char.
	// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
	// Check whether the input is a input type of char.
	// Returns a boolean.
	function isCharonly($input) {
	return (bool)(preg_match("/^[a-zA-Z ]*$/", $input)) ;
	}

	// Section 5.6 - Validate whether the username is unique
	// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
	// Check whether the input is a unique username.
	// Returns a boolean.
	function isUsernameunique($pdo, $input) {
		$userData = readCustomerdata($pdo, "Partial", $input);

		if ($userData) {
			return false;
		}
		else {
			return true;
		}
	}
	
	// Section 5.7 - Validate whether the input 1 is the same as input2.
	// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
	// Check whether the input1 is the same as input2
	// Returns a boolean.
	function isInputsame($input1, $input2) {
		return ((string)$input1 == (string)$input2);
	}
?>