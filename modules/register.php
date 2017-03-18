<?php
//init fields
$FirstName = $LastName = $Adres = $ZipCode = $City = $TelNr = $Email = $Username = 	$Password = $RetypePassword = NULL;

//init error fields
$FnameErr = $LnameErr = $ZipErr = $CityErr = $TelErr = $MailErr = $UserErr = $PassErr = $RePassErr = NULL;

if(isset($_POST["registerRf"])) {	

	$FirstName 		= $_POST["FirstName"];
	$LastName 		= $_POST["LastName"];
	$Adres 			= $_POST["Adres"];
	$ZipCode 		= $_POST["ZipCode"];
	$City 			= $_POST["City"];
	$TelNr 			= $_POST["TelNr"];
	$Email 			= $_POST["Email"];
	$Username 		= $_POST["Username"];
	$Password 		= $_POST["Password"];
	$RetypePassword = $_POST["RetypePassword"];
	
	
	$checkOnerrors = array (
	//controleer het voornaam veld
	"FnameErrchar" 	 => is_Char_Only($FirstName),
	"FnameErrlength" => is_minlength($FirstName, 2),

	//controleer het achternaam veld
	"LnameErrchar" 	 => is_Char_Only($LastName),
	"LnameErrlength" => is_minlength($LastName, 2),
	
	//controleer het postcode veld	
		"ZipErr" 	 => is_NL_PostalCode($ZipCode),	

	//controleer het plaats veld
		"CityErr" 	 => is_Char_Only($City),

	//controleer het telnr veld
		"TelErr" 	 => is_NL_Telnr($TelNr),
	
	//controleer het email veld
		"MailErr" 	 => is_email($Email),

	//controleer het username veld
		"UserErr" 	 => is_Username_Unique($Username, $pdo),
	
	//controleer het paswoord veld
		"PassErr" 	 => is_minlength($Password, 6),
	
	//controleer het retype paswoord veld
	   "RePassErr"   => is_Paswoord_Same($Password, $RetypePassword)
	
	);
	
	if(count(array_keys($checkOnerrors, null)) != 0) {
		$checkOnnull = array_flip(array_keys($checkOnerrors, null));
		
		unset
		( 
		$checkOnnull["FnameErrchar"], 
		$checkOnnull["FnameErrlength"],
		$checkOnnull["LnameErrchar"], 
		$checkOnnull["LnameErrlength"]
		);

		// Error response array
		$errorResponse = array(
				
			"ZipErr" 	=> "Uw postcode klopt niet. U moet het volgens het standaard manier schrijven: 1234 AA",	

			"CityErr" 	=> "U moet uw woonplaats schrijven ALLEEN in letters.",

			"TelErr" 	=> "U moet uw telefoon nummer schrijven ALLEEN in cijfers",
			
			"MailErr" 	=> "U e-mail address is niet valide",

			"UserErr" 	=> ($Username == "Admin" || $Username == "admin" || $Username == "Root" || $Username == "root") ? "U bent niet de eigenaar van deze website" : "Uw gebruikersnaam is al in gebruik.",
			
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
		
		foreach ($errorResponse as $key => $value) {
			${$key} = $value; // source  http://stackoverflow.com/questions/9257505/dynamic-variable-names-in-php
		}
		
		require_once("./forms/registerForm.php");
	}
	else
	{
		//formulier is succesvol gevalideerd

		//hash het paswoord met de Salt
		$Password = password_hash($Password, PASSWORD_DEFAULT);

		/*
		Opdracht PM08 STAP 5: registreren
		Omschrijving: Maak een prepared statement waarmee de gegevens van de gebruiker in de database worden toegevoegd. LET OP: Level moet 1 zijn! 
		*/
		
		$insertCheck = insertCustomerdata($pdo, $FirstName, $LastName, $Adres, $ZipCode , $City, $TelNr, $Email, $Username, $Password);
		
		/*
		Opdracht PM08 STAP 6: registreren
		Omschrijving: Tot slot geef je de gebruiker de melding dat zijn gegevens zijn toegevoegd.
		*/
		
		if ($insertCheck == true) {
			echo "U bent succesvol geregistreerd! Van harte welkom! U wordt in 5 seconden herleid naar de home pagina";
			RedirectToPage(5, 1);
		}
		else {
			echo "Er is iets misgegaan met het registeren. Neem contact op met de pagina beheerder.";
		}
	}
}
else
{
	require_once("./forms/registerForm.php");
}
?>
