<?php
// Section 1 - Initialize variables
// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
// You may notic the error fields variable has different way of writing. This ONLY applies to error fields. It's for better readability.
$firstName = $lastName = $adres = $zipcode = $city = $telNr = $email = $username = 	$password = $reTypepassword = $insertCheck = NULL;

// Error Fields
$FnameErr = $LnameErr = $ZipErr = $cityErr = $TelErr = $MailErr = $UserErr = $PassErr = $RePassErr = NULL;

// Section 2 - Validate form from ./forms/registerForm.php
// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
// If the user has pushed the button, then the form goes through validations.
// The parameters for these validations are: char only, min length, valid dutch postal code, valid dutch telephone number and valid email.
if(isset($_POST["register"])) {	

	// Section 2.2 - Initialize data
	// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
	$firstName 		= $_POST["firstName"];
	$lastName 		= $_POST["lastName"];
	$adres 			= $_POST["adres"];
	$zipcode 		= $_POST["zipcode"];
	$city 			= $_POST["city"];
	$telNr 			= $_POST["telNr"];
	$email 			= $_POST["email"];
	$username 		= $_POST["username"];
	$password 		= $_POST["password"];
	$reTypepassword = $_POST["reTypepassword"];
	
	
	// Section 2.3 - checkOnerrors array + filter on null
	// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
	// The values of the keys is either true or false (1 or null).
	// Those values will be filterd. Keys returned, flip those keys and then put that array into his new home named $checkOnnull.
	$checkOnerrors = array (
	// Validate the firstname field
	"FnameErrchar" 	  => isCharonly($firstName),
	"FnameErrlength"  => isMinlength($firstName, 2),

	// Validate the lastname field
	"LnameErrchar" 	  => isCharonly($lastName),
	"LnameErrlength"  => isMinlength($lastName, 2),

	// Validate the postal code field	
		"ZipErr" 	  => isNLpostalCode($zipCode),	

	// Validate city field
		"CityErr" 	  => isCharonly($city),

	// Validate telephone field
		"TelErr" 	  => isNLtelNr($telNr),

	// Validate email field
		"MailErr" 	  => iseEmail($email),

	// Validate username field
		"UserErr"     => isUsernameunique($pdo, $username),
	
	// Validate password field
		"PassErr"     => isMinlength($password, 6),
	
	// Retype password field
		"RePassErr"   => isInputsame($password, $repassword)
	);
	
	$checkOnnull = array_flip(array_keys($checkOnerrors, null));
	
	// Section 2.4 - Error Response
	// If $checkOnnull is empty or equal to 0 then it will create a new user.
	// If $checkOnnull is larger than 0 then it's going to create an error response into the variables that you can find above.	
	if(count($checkOnnull) != 0) {
		
		// Section 2.4.1 - Unset not needed variables
		// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017		
		unset
		( 
		$checkOnnull["FnameErrchar"], 
		$checkOnnull["FnameErrlength"],
		$checkOnnull["LnameErrchar"], 
		$checkOnnull["LnameErrlength"]
		);

		// Section 2.4.2 - Error Response
		// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017
		$errorResponse = array(
				
			"ZipErr" 	=> "Uw postcode klopt niet. U moet het volgens het standaard manier schrijven: 1234 AA",	

			"CityErr" 	=> "U moet uw woonplaats schrijven ALLEEN in letters.",

			"TelErr" 	=> "U moet uw telefoon nummer schrijven ALLEEN in cijfers",
			
			"MailErr" 	=> "U e-mail address is niet valide",

			"UserErr" 	=> ($username == "Admin" || $username == "admin" || $username == "Root" || $username == "root") ? "U bent niet de eigenaar van deze website" : "Uw gebruikersnaam is al in gebruik.",
			
			"PassErr"   => "Uw paswoord moet minimaal 6 karakter lang zijn",
			
			"RePassErr" => "Uw opnieuw getypde paswoord is niet hetzelfde."
			
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
		
		// Section 2.4.3 - Iterate array $errorResponse to variables.
		// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017		
		foreach ($errorResponse as $key => $value) {
			${$key} = $value; // source  http://stackoverflow.com/questions/9257505/dynamic-variable-names-in-php
		}
		
	}
	else {
		$password = password_hash($password, PASSWORD_DEFAULT);
		
		// function setup is: createCustomerdata($pdo (required), username (required), first name (required), last name (required), adres (required), zipcode (required), city (required), telephone number (required), email adres (required), password (required) )
		$insertCheck = createCustomerdata($pdo, $firstName, $lastName, $adres, $zipcode , $city, $telNr, $email, $username, $password);
		
		// Section 2.5 - Check whether the data is successfully inserted.
		// Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017	
		if ($insertCheck) {
			echo "U bent succesvol geregistreerd! Van harte welkom!";
			echo redirectTopage(5);
		}
		else {
			echo "Er is iets misgegaan met het registeren. Neem contact op met de pagina beheerder.";
		}
	}
}
(!$insertCheck) ? require_once("./forms/registerForm.php") : null;
?>
