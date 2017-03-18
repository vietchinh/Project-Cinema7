<?php
// Section 1 - Login check
// Check whether the user is logged in or not.
if(LoginCheck($pdo)) {
	
	// Section 2 - User permission level check.
	// This page is only visible to user that has level 5 or higher.
	if($_SESSION['level'] >= 5) {
		
		//init fields
		$title = $description = $duration = $genre = $age = $picture = $price = $type = $status = $ifSuccess = NULL;

		//init error fields
		$durErr = $priceErr = NULL;
		
		//
		if (isset($_POST["newMovie"]) || isset($_POST["changeMovie"])){

			
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
				"durErr" => ctype_digit($duration),
				"priceErr" => is_numeric($price)
			);

			if(count(array_keys($checkOnerrors, null)) != 0) {

				// Error response array
				$errorResponse = array(

					"durErr" 	=> "U moet hier ALLEEN nummers schrijven zonder komma's.",

					"priceErr" 	=> "U moet hier ALLEEN nummers schrijven.",

				);

				$checkOnnull = array_flip(array_keys($checkOnerrors, null));
				$errorResponse = array_intersect_key($errorResponse, $checkOnnull);

				foreach ($errorResponse as $key => $value) {
					${$key} = $value; // source  http://stackoverflow.com/questions/9257505/dynamic-variable-names-in-php
				}
			}
			else {
				if ( isset($_POST["changeMovie"])){
					$movieId = $_POST["movieId"];
					$ifSuccess = add_Remove_or_Edit_movies($pdo, "edit", $movieId, $title, $description, (int)$duration, $genre, (int)$age, $picture, (float)$price, $type, $status);
					$responseMessage = "De film is aangepast.";
				}
				else {
					$ifSuccess = add_Remove_or_Edit_movies($pdo, "add", null, $title, $description, (int)$duration, $genre, (int)$age, $picture, (float)$price, $type, $status);
					$responseMessage = "De film is toegevoegd aan de database.";
				}
			}
		}
		elseif (isset($_POST["deleteMovie"])) {
			$movieId = $_POST["movieId"];
			$ifSuccess = add_Remove_or_Edit_movies($pdo, "remove", $movieId);
			$responseMessage = "De film is verwijderd.";
		}
		
		if (isset($_POST["newMovie"]) || isset($_POST["changeMovie"]) || isset($_POST["deleteMovie"])){
			if ($ifSuccess) {
				echo $responseMessage;
			}
			else {
				echo "Er is iets misgegaan met het toevoegen van de film in de database.";
			}
		}
		
		require_once("./forms/addMovieform.php");
		
	}
	else{
		//user heeft niet het correcte level
		echo 'U heeft niet de juiste bevoegdheid voor deze pagina.';
		RedirectNaarPagina(5);//redirect naar home
	}
}
else {
	//user is niet ingelogd
	RedirectNaarPagina(NULL,98);//instant redirect naar inlogpagina
}
?>
