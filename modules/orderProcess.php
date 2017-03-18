<?php
// beveiliging pagina voor niet geautoriseerde bezoekers
if(LoginCheck($pdo))
{
	// user is ingelogd
	if($_SESSION['level'] >= 1) //pagina alleen zichtbaar voor level 1 of hoger
	{
		/* ===============CODE================== */

		
		if(isset($_POST["acceptOrder"]))
		{
			/*
			Opdracht PM16 STAP 3A: Besteloverzicht (deel 2)
			Omschrijving: Lees de sessie bestelling uit
			*/
			
			$clientId = $_SESSION["userId"];
			$username = $_SESSION["username"];
			$showId   = $_SESSION["order"][$username]["VertoningsID"];
			$tickets  = $_SESSION["order"][$username]["Kaartjes"];
			
			$reservationId = addReservation($pdo, $clientId);
			
			if(addReservationshowId($pdo, $reservationId[count($reservationId) - 1]->ReserveringsID, $showId, $tickets )){
				echo "
					<p> Bestelling gelukt </p>
					<form method='POST' action='./index.php?pageNr=1'>
						<input type='submit' value='Ga terug naar home'>
					</form>";
			}
			else {
				echo "Bestelling mislukt";
			}
			unset($_SESSION["order"][$username]);
		}
		else
			echo "<img src='./../Project-Cinema7-img/EasterEgg.jpg' alt="Dennis Nedry" />";
		
		/* ===============CODE================== */
	}
	else
	{
		//user heeft niet het correcte level
		echo "U heeft niet de juiste bevoegdheid voor deze pagina.";
		RedirectNaarPagina(5);//redirect naar home
	}
}
else
{
	//user is niet ingelogd
	RedirectNaarPagina(NULL,98);//instant redirect naar inlogpagina
}
?>
