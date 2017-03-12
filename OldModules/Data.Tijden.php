<?php
// beveiliging pagina voor niet geautoriseerde bezoekers
if(LoginCheck($pdo))
{
	// user is ingelogd
	if($_SESSION['level'] >= 1) //pagina alleen zichtbaar voor level 1 of hoger
	{
		/* ===============CODE================== */
		

		if(isset($_POST['BestelOverzicht']))
		{

			/*
			Opdracht PM14 STAP 2: Film Data/Tijden
			Omschrijving: lees de gegevens uit het verstuurde formulier in (POST) en lees het FilmId in middels de GET methode. Maak vervolgens een associatief array met de naam Film aan en vul deze met de gegevens zoals deze in de opdracht gevraagd worden.
			*/
			



			/*
			Opdracht PM14 STAP 3: Film Data/Tijden
			Omschrijving: controleer of de sessie bestelling bestaat. Zo ja, lees het array bestelling uit deze sessie in. Zo nee, maak een nieuw leeg array met de naam bestelling. Voeg vervolgens het array film toe aan het array bestelling en zet alles terug in de sessie bestelling. Stuur de gebruiker daarna door naar de pagina besteloverzicht middels een header refresh
			*/
			


		}
		else
		{
			/*
			Opdracht PM14 STAP 1: Film Data/Tijden
			Omschrijving: Lees middels een SELECT query de tabel vertoningen uit, specifiek voor de film die door de gebruiker is aangeklikt. Hiervoor dien je het FilmId middels de GET methode uit te lezen. Geef vervolgens alle gegevens netjes weer in een tabel met achter iedere regel een radio button. Onderaan maak je een select Field waarin de waarden 1-10 staan. De gebruiker kan hier het aantal kaartjes kiezen. Tot slot geef je bovenaan de titel van de film weer en de prijs van de film. Kijk in opdracht voor een verkorte sql query (JOIN)
			*/
			



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
