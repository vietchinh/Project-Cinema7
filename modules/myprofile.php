<?php
// Section 1 - Get data from database in the table: Klanten (customers)
// Date Creation: 18-03-2017 | Date Modifcation: 21-03-2017
// Data that comes out of the datbase is an associative array.
// All of the data is then put into variables with the english translation of it's dutch counterpart.
	$username = $_SESSION["username"];

	// Section 1.1 - Read customer data and put it into useable variables ( function setup is: readCustomerdata($pdo (required), "full" or "Partial" (required), username (required), fetch mode (optional)) )
	$customerData = readCustomerdata($pdo, "full", $username, PDO::FETCH_ASSOC);

	$FirstName 		= $customerData["Voornaam"];
	$LastName 		= $customerData["Achternaam"];
	$Adres 			= $customerData["Adres"];
	$ZipCode 		= $customerData["Postcode"];
	$City 			= $customerData["Plaats"];
	$TelNr 			= $customerData["TelefoonNr"];
	$Email 			= $customerData["Email"];

	// Section 1.2 - Initialize error fields
	$FnameErr = $LnameErr = $ZipErr = $CityErr = $TelErr = $MailErr = NULL;

// Section 2 - Process changes
// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
// Process changes if the post named changeProfile is set. Aka if the user pushed the button.
if(isset($_POST["changeProfile"])) {

	// Section 2.1 - Filter $_POST for empty fields and then merge into $customerData array.
	// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
	// The merge command is array_intersect. What it primarly does for this case is to find any changes.
	// Any changes will be put into $postFilter as an array. This array won't be used later as it only serves as a change detector.
	// Users is going to be notified that nothing is changed.
	$postNullfilter = array_filter($_POST, "strlen"); //http://php.net/manual/en/function.array-filter.php
	unset($postNullfilter["changeProfile"]);
	$postFilter = array_intersect($postNullfilter, $customerData);

	// Section 2.2 - Validate $customerData array on set parameters.
	// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
	// If the array $postFilter is empty, then it doesn't need to be validated, because there is no changes.
	// If the array $postFilter is not empty, then it goes through validations.
	// The parameters for these validations are: char only, min length, valid dutch postal code, valid dutch telephone number and valid email.
	if (empty($postFilter)){
		
		// Section 2.2.1 - Iterate array $postNullfilter into variables.
		// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
		foreach ($postNullfilter as $key => $value){
			${$key} = $value;
		}
		
		// Section 2.2.2 - checkOnerrors array + filter on null
		// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
		// The values of the keys is either true or false (1 or null).
		// Those values will be filterd and put into new variable named $checkOnnull
		$checkOnerrors = array (
		// Validate the firstname field
		"FnameErrchar" 	  => isCharonly($FirstName),
		"FnameErrlength"  => isMinlength($FirstName, 2),

		// Validate the lastname field
		"LnameErrchar" 	  => isCharonly($LastName),
		"LnameErrlength"  => isMinlength($LastName, 2),

		// Validate the postal code field	
			"ZipErr" 	  => isNLpostalCode($ZipCode),	

		// Validate city field
			"CityErr" 	  => isCharonly($City),

		// Validate telephone field
			"TelErr" 	  => isNLtelNr($TelNr),

		// Validate email field
			"MailErr" 	  => iseEmail($Email)
		
		// These error checks is for later use. As username and password aren't changeable.
		
		// Validate username field
			//"UserErr"   => isUsernameunique($pdo, $username),
		
		// Validate password field
			//"PassErr"   => isMinlength($password, 6),
		
		// Retype password field
			//"RePassErr" => isInputsame($password, $repassword)
		);
		
		$checkOnnull = array_flip(array_keys($checkOnerrors, null));
		
		// Section 2.2.3 - Error Response
		// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
		// Filter $checkOnerrors on null. 
		// If it's empty then it the changes is valid and go on to update the user profile with the changes.
		// If not then it's going to create an error response into the variables that you can find above.
		if(!empty($checkOnnull)) {
			
			
			// Section 2.2.3-1 - Unset not needed variables
			// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
			unset
			( 
			$checkOnnull["FnameErrchar"], 
			$checkOnnull["FnameErrlength"],
			$checkOnnull["LnameErrchar"], 
			$checkOnnull["LnameErrlength"]
			);

			// Section 2.2.3-2 - Error Response
			// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
			$errorResponse = array(

				"ZipErr" 	  => "Uw postcode klopt niet. U moet het volgens het standaard manier schrijven: 1234 AA",	

				"CityErr" 	  => "U moet uw woonplaats schrijven ALLEEN in letters.",

				"TelErr" 	  => "U moet uw telefoon nummer schrijven ALLEEN in cijfers.",

				"MailErr" 	  => "U e-mail address is niet valide."

				// These error responses is for later use. As username and password aren't changeable.
				//"UserErr"   => "Uw gebruikersnaam is al in gebruik.",
				
				//"PassErr"   => "Uw paswoord moet minimaal 6 karakter lang zijn.",

				//"RePassErr" => "Uw opnieuw getypde paswoord is niet hetzelfde."

			);

			$errorResponse = array_intersect_key($errorResponse, $checkOnnull);

			// Dynamic text for first name, last name and repassword.
			if (empty($checkOnerrors["FnameErrchar"]) || empty($checkOnerrors["FnameErrlength"])) {
				if (empty($checkOnerrors["FnameErrchar"]) && empty($checkOnerrors["FnameErrlength"])){
					$errorResponse["FnameErr"] = "U moet uw naam ALLEEN in letters schrijven en het moet minimaal TWEE letters lang zijn.";
				}
				else {
					if (empty($checkOnerrors["FnameErrchar"])) {
						$errorResponse["FnameErr"] = "U moet uw naam ALLEEN in letters schrijven.";
					}
					else {
						$errorResponse["FnameErr"] = "Uw naam moet minimaal TWEE letters lang zijn.";
					}
				}
			}

			if (empty($checkOnerrors["LnameErrchar"]) || empty($checkOnerrors["LnameErrlength"])) {
				if (empty($checkOnerrors["LnameErrchar"]) && empty($checkOnerrors["LnameErrlength"])){
					$errorResponse["LnameErr"] = "U moet uw achternaam ALLEEN in letters schrijven en het moet minimaal TWEE letters lang zijn.";
				}
				else {
					if (empty($checkOnerrors["LnameErrchar"])) {
						$errorResponse["LnameErr"] = "U moet uw achternaam ALLEEN in letters schrijven.";
					}
					else {
						$errorResponse["LnameErr"] = "Uw achternaam moet minimaal TWEE letters lang zijn.";
					}
				}
			}
			
			// Section 2.2.3-2 - Iterate array $errorResponse to variables.
			// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
			foreach ($errorResponse as $key => $value) {
				${$key} = $value; // source  http://stackoverflow.com/questions/9257505/dynamic-variable-names-in-php
			}
			
		}
		else {
			
			// function setup is: updateCustomerdata($pdo (required), username (required), first name (required), last name (required), adres (required), zipcode (required), city (required), telephone number (required), email adres (required) )
			$updateCheck = updateCustomerdata($pdo, $username, $FirstName, $LastName, $Adres, $ZipCode, $City, $TelNr, $Email);
			
			// Section 2.4 - Check whether the data is successfully updated.
			// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017	
			if ($updateCheck ) {
				echo "Uw gegevens zijn succesvol gewijzigd";
			}
			else {
				echo "Er is iets misgegaan met het registeren. Neem contact op met de pagina beheerder.";
			}

		}
	}
	else {
		echo "Er is niks gewijzigd.";
	}
}
require_once("./forms/myProfileform.php");
?>
