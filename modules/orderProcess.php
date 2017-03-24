<?php
// Section 1 - Order Process
// Date Creation: 18-03-2017 | Date Modification: 24-03-2017
// Process the user order if session order, customerId and username is set.
// If not it give you a gif, that was given by the project leader.
if(isset($_SESSION["customerId"]) && isset($_SESSION["username"])) {

	// Section 1.1 - Initialize variables
	$orderNumbertext = null;

	$clientId = $_SESSION["customerId"];
	$username = $_SESSION["username"];

	// Section 1.2 - Check if array of $username of session order is not empty.
	// If true then the order is going to be process, if not true then it will show a button to go home.
	// Why a button and not an error? I assume that the order has already been processes.
	// So whenever a user refresh the page it will give this home button instead of and empty white page if the error report is turned off.
	if (!empty($_SESSION["order"][$username])) {

		// Section 1.2.1 - Loop through the order and insert into the database. For table reservering and reservering_vertoningen(a buffer table).
		foreach ($_SESSION["order"][$username] as $key => $value) {
			$reservationId = createReservation($pdo, $clientId);
			$addReservationshowId = createReservationshowId($pdo, $reservationId, $value["VertoningsID"], $value["Kaartjes"]);

			// Section 1.2.1-1 - Check if $addReservationshowId is true.
			if ($addReservationshowId) {
				$orderNumber[$reservationId] = null;
				unset($_SESSION["order"][$username][$key]);
			}
		}

		// Section 1.2.2 - Check if array $orderNumber is larger than 1.
		// This allows for multiple order numbers.
		if (count($orderNumber) > 1){
			foreach($orderNumber as $key => $value) {
				$orderNumbertext .= "{$key}, ";
			}
			$orderNumbertext = substr($orderNumbertext, 0, -2);
		}
		else {
			$orderNumbertext = key($orderNumber);
		}

		// Section 1.2.3 - Check if $addReservationshowId is true. The same methode as section 1.2.1-1.
		// This section shows either a button to return to home or order has failed.
		if($addReservationshowId){
			echo "
				<p> Uw bestelling is succesvol bij ons ontvangen! Uw bestelling is bij ons bekend onder bestelnummer: $orderNumbertext </p>
				<form method='POST'>
					<input type='submit' name='pageName[Home]' value='Ga terug naar home'>
				</form>";
		}
		else {
			echo "Bestelling mislukt";
		}
	}
	else {
		echo "
			<p> Uw bestelling is al bij ons bekend. Druk op de knop om terug te gaan naar de home pagina. </p>
			<form method='POST'>
				<input type='submit' name='pageName[Home]' value='Ga terug naar home pagina'>
			</form>";
	}
}
else {
	echo "<img src='./../Project-Cinema7-img/EasterEgg.jpg' alt='Dennis Nedry' />";
}
?>
