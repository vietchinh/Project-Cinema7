<?php
$username = $_SESSION["username"];
if (isset($_POST["pageName"]["Bestelling Lijst"])){
	$availableReservations	= $_SESSION["availableReservations"];
	$showId					= $_POST["showId"];
	$avrIdkey 				= array_flip(array_keys(array_column($availableReservations, "VertoningsID"), $showId )); // avrid = available reservation key. An index key for the mention array.

	$availableReservations[key($avrIdkey)]["Kaartjes"] = $_POST["tickets"];

	$_SESSION["order"][$username] = $availableReservations[key($avrIdkey)];

	unset($_SESSION["availableReservations"]);
}



if(isset($_SESSION["order"][$username]))
{
	/*
	Opdracht PM15 STAP 1: Besteloverzicht (deel 1)
	Omschrijving: lees de SESSION bestelling uit
	*/
	
	
	$order = $_SESSION["order"][$username];
	
	$customerData = fetchDatabase($pdo, "customerData", $username);
	
	if(isset($_POST["tickets"])) {
		$order["Kaartjes"] = $_POST["tickets"];
	}
	
	$title 	 = $order["Titel"];
	$hallId  = $order["ZaalNR"];
	$date 	 = $order["Datum"];
	$time 	 = $order["Tijd"];
	$tickets = $order["Kaartjes"];
	$price 	 = $order["Prijs"];
	$picture = $order["Plaatje"];
	$totalPrice = $tickets * $price;
	
	$formSelect = null;
	$formSelect .= "
		<form method='POST' >
			<select name='tickets' id='tickets' onchange='this.form.submit();'>";
			for ($a = 1; $a <= 10; $a++){
				$ifSelect = ($tickets == $a) ? "selected" : null;
			$formSelect .= "<option value='$a' {$ifSelect} >{$a}x</option>";
			}
	$formSelect .="
			</select>
			<label for='tickets'>Kaartjes</label>
		</form>";
	
	$table = "
		<div class='displayIflex inheritHw justifyContentc'>
			<div class='displayIflex'>
				<div>
					<img src='./../Project-Cinema7-img/{$picture}'>
				</div>
				<div class='displayIflex flexFlowr' id='orderContent'>
					<div>
						<h3> {$title} </h3>
							<h4>Bioscoop</h4>
							<p>Cinema7 (Zaal: {$hallId})</p>
							<h4>Wanneer</h4>
							<p>{$date} om {$time}</p>
					</div>
					<hr />
					<div class='displayIflex justifyContentsb'>
						{$formSelect}
						<p>&euro;{$price}</p>
					</div>
					<div class='displayIflex justifyContentsb'>
						<p>Totaal</p>
						<p>&euro;{$totalPrice}</p>
					</div>
					<div class='displayIflex justifyContentc'>
						<form method='POST' action='./index.php?pageNr=10'>
							<input type='submit' name='acceptOrder' value='bestellen'>
						</form>
						<form method='POST' action='./index.php?pageNr=2'>
							<input type='submit' name='deleteOrder' value='verwijderen'>
						</form>
					</div>
				</div>
			</div>
			<div class='displayIflex'>
				<div class='displayIflex flexFlowr' id='orderContent'>
					<div>
						<h3> Uw Gegevens </h3>
							<h4>Voornaam en Achternaam</h4>
							<p>{$customerData["Voornaam"]} {$customerData["Achternaam"]}</p>
							<h4>Adres en Postcode</h4>
							<p>{$customerData["Adres"]}, {$customerData["Postcode"]}</p>
							<h4>Plaats</h4>
							<p>{$customerData["Plaats"]}</p>
							<h4>Telefoonnummer</h4>
							<p>{$customerData["TelefoonNr"]}</p>
							<h4>E-mail</h4>
							<p>{$customerData["Email"]}</p>
					</div>
				</div>
			</div>
		</div>";
	echo $table;
}
?>
