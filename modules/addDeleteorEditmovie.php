<?php
// Section 1 - Initialize fields
// Date Creation: 18-03-2017 | Date Modifcation: 20-03-2017

$title = $description = $duration = $genre = $age = $picture = $price = $type = $status = $ifSuccess = NULL;

//Error Fields
$durErr = $priceErr = NULL;

// Section 2 - Change or add new movie.
// Date Creation: 18-03-2017 | Date Modifcation: 20-03-2017
// If the user create or change movie, the field duration is going to be checked on whether the input is not a decimal and the field price is going to be checked on whether it's not a char.
// These checks is actually just a fail safe, because the user if it's in his best interest to remove the limits and change the type.
// Given the user sanity, I predict that, what I described above, won't happen.
// After the validations, it will check whether the user has pushed change movie or create movie.
// From there it's either update the table "Films" or insert a new row with variables in table "Films"
if (isset($_POST["newMovie"]) || isset($_POST["changeMovie"])){

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
			$ifSuccess = updateMovie($pdo, $movieId, $title, $description, (int)$duration, $genre, (int)$age, $picture, (float)$price, $type, $status);
			$responseMessage = "De film is aangepast.";
		}
		else {
			$ifSuccess = createMovie($pdo, $title, $description, (int)$duration, $genre, (int)$age, $picture, (float)$price, $type, $status);
			$responseMessage = "De film is toegevoegd aan de database.";
		}
	}
}
// Section 3 - Delete Movie
// Date Creation: 18-03-2017 | Date Modifcation: 20-03-2017
// Delete movie based on movie ID and then set variable $responseMessage.
elseif (isset($_POST["deleteMovie"])) {
	$movieId = $_POST["movieId"];
	$ifSuccess = deleteMovie($pdo, $movieId);
	$responseMessage = "De film is verwijderd.";
}
// Section 4 - Succes or failure response
// Date Creation: 18-03-2017 | Date Modifcation: 20-03-2017
// It's a safety net if variable $ifSucces is empty. Which indicate that there is something wrong getting the variables into the database.
// $ifSuccess is set on each CRUD action.
if (isset($_POST["newMovie"]) || isset($_POST["changeMovie"]) || isset($_POST["deleteMovie"])){
	if ($ifSuccess) {
		echo $responseMessage;
	}
	else {
		echo "Er is iets misgegaan met het toevoegen van de film in de database.";
	}
}
// Section 5 - Insert a fancy form
// Date Creation: 18-03-2017 | Date Modifcation: 18-03-2017
require_once("./forms/addChangeremoveMovieform.php");
?>