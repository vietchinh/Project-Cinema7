<?php
if(isset($_POST["reserve"]))  {

	/*
	Opdracht PM14 STAP 2: Film Data/Tijden
	Omschrijving: lees de gegevens uit het verstuurde formulier in (POST) en lees het FilmId in middels de GET methode. Maak vervolgens een associatief array met de naam Film aan en vul deze met de gegevens zoals deze in de opdracht gevraagd worden.
	*/
		unset($_SESSION["order"]);
	
		$filmId 			   			   = $_POST["filmId"];
		$_SESSION["availableReservations"] = fetchDatabase($pdo, "reserve", $filmId, PDO::PARAM_INT);
	
		$availableReservations			   = $_SESSION["availableReservations"];
		$hallId 			   			   = fetchDatabase($pdo, "hallId", $filmId, PDO::PARAM_INT);
		
		echo "
		<form method='POST' id='reserveRadio'>
			<h1> {$availableReservations[0]["Titel"]} <h1>
			<h4> Prijs per kaartje: {$availableReservations[0]["Prijs"]} </h4>";
		foreach($hallId as $key) {
			echo "
			<h3> Zaal {$key->ZaalNR}: </h3>";
			foreach (array_keys(array_column($availableReservations, "ZaalNR"), $key->ZaalNR) as $key) {
				echo "
				<input type='radio' name='showId' value='{$availableReservations[$key]["VertoningsID"]}' >{$availableReservations[$key]["Datum"]} {$availableReservations[$key]["Tijd"]}<br />";
			}
		}
		echo "
			<h4> Aantal Kaartjes: </h4>
			<select name='tickets'>";
			for ($a = 1; $a <= 10; $a++){
				echo "<option value='$a'>$a</option>";
			}
		echo"
			</select>
			<br />
			<br />
			<input type='submit' name='submitReserve' value='Reserveren'>
		</form>";

	/*
	Opdracht PM14 STAP 3: Film Data/Tijden
	Omschrijving: controleer of de sessie bestelling bestaat. Zo ja, lees het array bestelling uit deze sessie in. Zo nee, maak een nieuw leeg array met de naam bestelling. Voeg vervolgens het array film toe aan het array bestelling en zet alles terug in de sessie bestelling. Stuur de gebruiker daarna door naar de pagina besteloverzicht middels een header refresh
	*/



}
else {
	/*
	Opdracht PM14 STAP 1: Film Data/Tijden
	Omschrijving: Lees middels een SELECT query de tabel vertoningen uit, specifiek voor de film die door de gebruiker is aangeklikt. Hiervoor dien je het FilmId middels de GET methode uit te lezen. Geef vervolgens alle gegevens netjes weer in een tabel met achter iedere regel een radio button. Onderaan maak je een select Field waarin de waarden 1-10 staan. De gebruiker kan hier het aantal kaartjes kiezen. Tot slot geef je bovenaan de titel van de film weer en de prijs van de film. Kijk in opdracht voor een verkorte sql query (JOIN)
	*/
	
	$availableReservations	= $_SESSION["availableReservations"];
	$username 				= $_SESSION["username"];
	$showId					= $_POST["showId"];
	$avrIdkey 				= array_flip(array_keys(array_column($availableReservations, "VertoningsID"), $showId )); // avrid = available reservation key. An index key for the mention array.
	
	$availableReservations[key($avrIdkey)]["Kaartjes"] = $_POST["tickets"];
	
	
	$_SESSION["order"][$username] = $availableReservations[key($avrIdkey)];
	
	unset($_SESSION["availableReservations"]);
	
	
	header("location: index.php?pageNr=7");
}	
?>