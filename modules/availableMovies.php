<?php
if(isset($_POST["deleteOrder"])){
	$username = $_SESSION["username"];
	unset($_SESSION["order"][$username]);
}
/*
	Opdracht PM05 STAP 1: Reserveren
	Omschrijving: Voer een query uit middels een prepared statement
*/

	$fi = fetchDatabase($pdo, "movie", "InBios");


/*
	Opdracht PM05 STAP 2: Reserveren
	Omschrijving: Zorg er voor dat het result van de query netjes op het scherm wordt getoond. Zorg er voor dat er een knopje "reserveren" is waarmee je doorgestuurd wordt naar de reserveren pagina
*/
    echo (isset($_SESSION['level']) && $_SESSION['level'] >= 1) ? returnTablestatus($fi, true) : returnTablestatus($fi, false, true);
?>