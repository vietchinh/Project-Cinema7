<?php
// beveiliging pagina voor niet geautoriseerde bezoekers
if(LoginCheck($pdo))
{
	// user is ingelogd
	if($_SESSION['level'] >= 1) //pagina alleen zichtbaar voor level 1 of hoger
	{
		/* ===============CODE================== */

		
		if(!empty($_SESSION['BestelBeveiliging']))
		{
			/*
			Opdracht PM16 STAP 3A: Besteloverzicht (deel 2)
			Omschrijving: Lees de sessie bestelling uit
			*/
			

			/*
			Opdracht PM16 STAP 3B: Besteloverzicht (deel 2)
			Omschrijving: voeg een nieuw KlantId toe aan de tabel reserveringen.Vraag vervolgens het door de database gegenereerde reserveringsnummer op
			*/
			//query opbouwen
			




			/*
			Opdracht PM16 STAP 3C: Besteloverzicht (deel 2)
			Omschrijving: loop middels een for-loop door het array bestelling en vul de tabel vertoningen_reserveringen met de correcte gegevens.
			*/
			




			/*
			Opdracht PM16 STAP 3D: Besteloverzicht (deel 2)
			Omschrijving: geef de gebruiker de juiste melding op het scherm wanneer bovenstaande succesvol is uitgevoerd. Maak daarnaast een link naar de homepagina
			*/
			


			
			//verwijderd de session variabele met bestelgegevens -- DO NOT REMOVE!
			unset($_SESSION['bestelling']);
			unset($_SESSION['BestelBeveiliging']);
		}
		else
			echo '<img src="./Images/EasterEgg.jpg" alt="Dennis Nedry" />';
		
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
