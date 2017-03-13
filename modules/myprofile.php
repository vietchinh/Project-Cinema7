<?php
// beveiliging pagina voor niet geautoriseerde bezoekers
if(LoginCheck($pdo))
{
	// user is ingelogd
	if($_SESSION['level'] >= 1) //pagina alleen zichtbaar voor level 1 of hoger
	{
		/* ===============CODE================== */

		/*  
			Opdracht PM10 STAP 1 : Mijn Profiel 
			Omschrijving: maak een prepared statement waarmee je de gegevens van de gebruiker ophaald. Zijn/Haar KlantId dien je te verkrijgen uit de sessie zodat je de juiste gegevens er bij kan terugvinden
		*/

			$username = $_SESSION["username"];

			$customerData = fetchDatabase($pdo, "customerData", $username);

		/*  
			Opdracht PM10 STAP 2 : Mijn Profiel 
			Omschrijving: Zet de gegevens uit de database over naar de juiste variabelen zodat ze in het formulier bestand kunnen worden gebruikt. Roep vervolgens het formulier aan.
		*/
			$FirstName 		= $customerData["Voornaam"];
			$LastName 		= $customerData["Achternaam"];
			$Adres 			= $customerData["Adres"];
			$ZipCode 		= $customerData["Postcode"];
			$City 			= $customerData["Plaats"];
			$TelNr 			= $customerData["TelefoonNr"];
			$Email 			= $customerData["Email"];
		
		//init error fields
		$FnameErr = $LnameErr = $ZipErr = $CityErr = $TelErr = $MailErr = NULL;

		if(isset($_POST['Wijzigen']))
		{
			/*  
				Opdracht PM10 STAP 3 : Mijn Profiel 
				Omschrijving: Lees de formulier gegevens in met de POST methode
			*/
			
			//hFirstName = hiddenFirstName aka it's using the hidden post value instead the empty main one.
			$postNullfilter = array_filter($_POST, "strlen"); //http://php.net/manual/en/function.array-filter.php
			unset($postNullfilter["Wijzigen"]);
			$postFilter = array_intersect($postNullfilter, $customerData);
			
			if (empty($postFilter)){
			//BEGIN CONTROLES

				/*  
					Opdracht PM10 STAP 4 : Mijn Profiel 
					Omschrijving: Valideer de ingevoerde gegevens weer met de zelfde voorwaarden als in de opdracht registreren.
				*/
				
				foreach ($postNullfilter as $key => $value){
					${$key} = $value;
				}
				
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
				
				);
				//EINDE CONTROLES

				/*  
					Opdracht PM10 STAP 5 : Mijn Profiel 
					Omschrijving: Vul aan, net als in de opdracht registreren
				*/

				if(!empty(array_keys($checkOnerrors, null))) {
					
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

						"TelErr" 	=> "U moet uw telefoon nummer schrijven ALLEEN in cijfers.",
						
						"MailErr" 	=> "U e-mail address is niet valide.",

						"UserErr" 	=> "Uw gebruikersnaam is al in gebruik.",
						
						"PassErr"   => "Uw paswoord moet minimaal 6 karakter lang zijn.",
						
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
					require_once("./Forms/MijnProfielForm.php");
				}
				else
				{
					//formulier is succesvol gevalideerd

					/*  
					Opdracht PM10 STAP 5 : Mijn Profiel 
					Omschrijving: maak een prepared statement waarmee je de gegevens van de gebruiker middels een UPDATE in de database aanpast. 
					*/
					
					updateCustomerdata($pdo, $username, $FirstName, $LastName, $Adres, $ZipCode, $City, $TelNr, $Email);
					
					/*  
					Opdracht PM10 STAP 6 : Mijn Profiel 
					Wanneer dit is gelukt krijgt de gebruiker hiervan een melding op het scherm
					*/
					
					echo "Uw gegevens zijn succesvol gewijzigd";
					
					require_once("./forms/myProfileform.php");

				}
			}
			else {
				echo "Er is niks gewijzigd.";
				require_once("./forms/myProfileform.php");
			}
		}
		else
		{	
			require_once("./forms/myProfileform.php");
		}
		/* ===============CODE================== */
	}
	else
	{
		//user heeft niet het correcte level
		echo 'U heeft niet de juiste bevoegdheid voor deze pagina.';
		RedirectNaarPagina(5);//redirect naar home
	}
}
else
{
	//user is niet ingelogd
	RedirectNaarPagina(NULL,98);//instant redirect naar inlogpagina
}
?>
