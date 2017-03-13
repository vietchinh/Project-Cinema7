<?php
// beveiliging pagina voor niet geautoriseerde bezoekers
if(LoginCheck($pdo))
{
	// user is ingelogd
	if($_SESSION['level'] >= 5) //pagina alleen zichtbaar voor level 5 of hoger
	{
	//-------------code---------------//	
		
		//init fields
		$title = $description = $duration = $genre = $age = $picture = $price = $type = $status = NULL;

		//init error fields
		$ageErr = $durErr = $priceErr = NULL;

		/* 
		Opdracht PM11 STAP 2 : Film Toevoegen 
		Maak een if statement waarmee je controleert of het formulier is ingevoerd. Zo ja, dan gaan we verder met het valideren van de gegevens en het toevoegen ervan aan de database, Zo nee, Dan wordt het formulier getoond.
		*/
			
		if (isset($_POST["newMovie"])){

			
			/* 
			Opdracht PM11 STAP 3 : Film Toevoegen 
			Lees de formulier gegevens in met de POST methode
			Valideer de ingevoerde gegevens zoals ook gedaan is in de opdrachten registreren en Mijn Profiel.
			*/
			
			$title 		 = $_POST["title"];
			$description = $_POST["description"];
			$duration 	 = $_POST["duration"];
			$genre 		 = $_POST["genre"];
			$age 		 = $_POST["age"];
			$picture 	 = "default.jpg";
			$price 		 = $_POST["price"];
			$type 		 = $_POST["type"];
			$status 	 = $_POST["state"];
	
			$checkOnerrors = array (
				"ageErr" => ctype_digit($age),
				"durErr" => ctype_digit($duration),
				"priceErr" => is_numeric($price)
			);

			if(count(array_keys($checkOnerrors, null)) != 0) {

				// Error response array
				$errorResponse = array(

					"ageErr" 	=> "U moet hier ALLEEN nummers schrijven zonder komma's.",	

					"durErr" 	=> "U moet hier ALLEEN nummers schrijven zonder komma's.",

					"priceErr" 	=> "U moet hier ALLEEN nummers schrijven.",

				);

				$checkOnnull = array_flip(array_keys($checkOnerrors, null));
				$errorResponse = array_intersect_key($errorResponse, $checkOnnull);

				foreach ($errorResponse as $key => $value) {
					${$key} = $value; // source  http://stackoverflow.com/questions/9257505/dynamic-variable-names-in-php
				}

				require_once("./forms/addMovieform.php");

			}
			else {
			}
				$ifSuccess = addNewmovie($pdo, $title, $description, (int)$duration, $genre, (int)$age, $picture, (float)$price, $type, $status);

				if ($ifSuccess) {
					echo "De film is toegevoegd aan de database";
				}
				else {
					echo "Er is iets misgegaan met het toevoegen van de film in de database.";
				}
				require_once("./forms/addMovieform.php");
}
		else {
			require_once("./forms/addMovieform.php");
		}
		
	//-------------code--------------//

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
