<?php
//init fields
$FirstName = $LastName = $Adres = $ZipCode = $City = $TelNr = $Email = $Username = 	$Password = $RetypePassword = NULL;

//init error fields
$FnameErr = $LnameErr = $ZipErr = $CityErr = $TelErr = $MailErr = $UserErr = $PassErr = $RePassErr = NULL;

if(isset($_POST['Registreren']))
{	
	/*
	Opdracht PM08 STAP 2: registreren
	Omschrijving: Lees alle gegevens uit het formulier uit middels de POST methode
	*/
	
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
	
	

	//BEGIN CONTROLES
	/*
	Opdracht PM08 STAP 3: registreren
	Omschrijving: Zorg er voor dat de gegevens worden gevalideerd op de eisen uit de opdracht. Gebruik de hulpvariabele $CheckOnErrors om later te kunnen controleren of er een fout is gevonden. Deze variabele zet je dus op true wanneer je een validatie fout tegenkomt. Voor het valideren kun je gebruik maken van de validatie functies in het bestand functies.php
	*/
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
	//EINDE CONTROLES
	/*
	Opdracht PM08 STAP 4: registreren
	Omschrijving: Controleer hier of er een fout is gevonden middels de CheckOnErrors variabele. Zo ja, dan ziet de gebruiker opnieuw het formulier; zo nee, dan gaan we de gegevens in de database toevoegen.
	*/
	if(count(array_keys($checkOnerrors, null)) != 0) //aanvullen
	{
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

			"UserErr" 	=> "Uw gebruikersnaam is al in gebruik.",
			
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
		
		require_once('./Forms/RegistrerenForm.php');
	}
	else
	{
		//formulier is succesvol gevalideerd

		//maak unieke salt
		$Salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

		//hash het paswoord met de Salt
		$Password = hash('sha512', $Password . $Salt);

		/*
		Opdracht PM08 STAP 5: registreren
		Omschrijving: Maak een prepared statement waarmee de gegevens van de gebruiker in de database worden toegevoegd. LET OP: Level moet 1 zijn! 
		*/
		
		$insertCheck = insertDatacustomer($pdo, $FirstName, $LastName, $Adres, $ZipCode , $City, $TelNr, $Email, $Username, $Password, $Salt);

		/*
		Opdracht PM08 STAP 6: registreren
		Omschrijving: Tot slot geef je de gebruiker de melding dat zijn gegevens zijn toegevoegd.
		*/
		
		if ($insertCheck == true) {
			echo "U bent succesvol geregistreerd! Van harte welkom! U wordt in 5 seconden herleid naar de home pagina";
			RedirectNaarPagina(5, 1);
		}
		else {
			echo "Er is iets misgegaan met het registeren. Neem contact op met de pagina beheerder.";
		}

		
	}
}
else
{
	require_once('./Forms/RegistrerenForm.php');
}
?>
