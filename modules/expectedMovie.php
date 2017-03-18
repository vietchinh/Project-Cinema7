<?php
/*
	Opdracht PM04 STAP 3: Verwacht in de bioscoop
	Omschrijving: Voer een query uit middels een prepared statement
*/
	$fw = fetchDatabase($pdo, "movie", "Verwacht"); // fw = fetchVerwacht. Functies.php lijn 144. Functie die de status: verwacht uit de database filterd met de titel, beschrijving, duur, genre, leeftijd en type.
/*
	Opdracht PM04 STAP 4: Verwacht in de bioscoop
	Omschrijving: Zorg er voor dat het result van de query netjes op het scherm wordt getoond.
*/
	echo returnTablestatus($fw); // Functies.php lijn 164. Functie die de object uit de functie hier boven met de foreach een tabel maakt en return.
?>