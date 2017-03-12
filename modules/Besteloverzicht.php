<?php
// beveiliging pagina voor niet geautoriseerde bezoekers
if(LoginCheck($pdo))
{
	// user is ingelogd
	if($_SESSION['level'] >= 1) //pagina alleen zichtbaar voor level 1 of hoger
	{
		/* ===============CODE================== */
		
		// == VERWIJDER DIT NIET!!! Dit is beveiliging voor de site.
		if(isset($_GET['Bestel']) && $_GET['Bestel'])
		{
			// == VERWIJDER DIT NIET!!! Dit is beveiliging voor de site.
			$_SESSION['BestelBeveiliging'] = true;
			header("location:index.php?PaginaNr=11");
		}
		// == VERWIJDER DIT NIET!!! Dit is beveiliging voor de site.
		
		if(isset($_SESSION['bestelling']))
		{
			/*
			Opdracht PM15 STAP 1: Besteloverzicht (deel 1)
			Omschrijving: lees de SESSION bestelling uit
			*/
			

			
			/*
			Opdracht PM16 STAP 2: Besteloverzicht (deel 2)
			Omschrijving: Zorg er middels een for-loop voor dat het aantal kaartjes wordt ge-update in het array bestelling. Plaats deze daarna terug in de sessie
			*/
			if(isset($_POST['Wijzigen']))
			{
				


			}
			
			/*
			Opdracht PM16 STAP 1: Besteloverzicht (deel 2)
			Omschrijving: Zorg ervoor dat de film met de door de gebruiker aangeklikte vertoningsId uit het array bestelling wordt verwijderd. Plaats deze daarna terug in de sessie
			*/
			if(isset($_GET['Del']))
			{
				


			}
			

			/*
			Opdracht PM15 STAP 2: Besteloverzicht (deel 1)
			Omschrijving: Geef door middel van een For-Loop alle gegevens van de door de gebruiker gereserveerde films weer in een nette tabel. Gebruik hiervoor de gegevens uit de sessie bestelling en vul deze aan door gebruik te maken van een query op de tabel vertoningen. Maak daarnaast voor iedere film een select Field waarin de gebruiker het aantal kaartjes kan wijzigen (zie deel 2). Ook dient iedere regel van een verwijder knop te worden voorzien zodat de gebruiker eventueel een film kan verwijderen uit de reserveer lijst.tot slot geef je een totaal overzicht van de prijs weer.
			*/
			




			/*
			Opdracht PM15 STAP 3: Besteloverzicht (deel 1)
			Omschrijving: Geef de gegevens van de gebruiker weer door een query uit te voeren op de tabel klanten. Het KlantId wat je hiervoor nodig hebt kun je terugvinden in de sessie waarmee de gebruiker is ingelogd.
			*/
			




			/*
			Opdracht PM15 STAP 4: Besteloverzicht (deel 1)
			Omschrijving: maak de bestelknop zoals in de opdracht beschreven
			*/
			



		}
		else
		{
			echo 'U heeft nog niets gereserveerd. Om te reserveren verwijzen wij u nu door naar de reserveringspagina.';
			RedirectNaarPagina(5,1);
		}
		/* ===============CODE================== */
	}
	else
	{
		//user heeft niet het correcte level
		echo 'U heeft niet de juiste bevoegdheid voor deze pagina.';
		RedirectNaarPagina();//redirect naar home
	}
}
else
{
	//user is niet ingelogd
	RedirectNaarPagina(NULL,98);//instant redirect naar inlogpagina
}
?>
