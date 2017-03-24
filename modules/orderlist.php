<?php
// Section 1 - Create session order.
// Date Creation: 19-03-2017 | Date Modifcation: 20-03-2017
// First it set $username to the session named equally: username.
// After that if the postName is "Bestelling Lijst" then it will set session order with the amount of user tickets.
$username = $_SESSION["username"];

if (isset($_POST["pageName"]["Bestelling Lijst"]) && isset($_SESSION["availableReservations"])){
	$availableReservations	= $_SESSION["availableReservations"];
	$showId					= $_POST["showId"]; // English translation for "vertonings ID"
	$avrIdkey 				= array_flip(array_keys(array_column($availableReservations, "VertoningsID"), $showId )); // avrid = available reservation key. An index key for the mention array.

	$availableReservations[key($avrIdkey)]["Kaartjes"] = $_POST["tickets"]; // Push post tickets into the $availableReservations array.

	$_SESSION["order"][$username][$showId] = $availableReservations[key($avrIdkey)]; // Put the $availableReservations array into session named order

	ksort($_SESSION["order"][$username]);
	
	unset($_SESSION["availableReservations"]); // Remove session availableReservations as it's not needed.
}
// Section 2 - Accept or Delete order
// Date Creation: 20-03-2017 | Date Modifcation: 20-03-2017
// When user accept the order, then the user is going to be redirected to the page order process.
// When the user delete it's order, then the user is going to be redirected to the page available movies and the session order under the username is going to be deleted.
if (isset($_POST["acceptOrder"])){
	redirectTopage(0, "orderProcess");
}
elseif (isset($_POST["deleteOrder"])){
	redirectTopage(0, "availableMovies");
	unset($_SESSION["order"][$username]);
}
// Section 3 - Iterate session order into a nice looking order list.
// Date Creation: 17-03-2017 | Date Modifcation: 24-03-2017
// Basicly what it does is it get the variables from order and the database in the table named Klanten and show those variables to the user.
// Also it calculate the total price so the user can see what the total price is and when the amount of tickets change, it change with that amount in the session order.
// If session order with the username is empty, then it will tell the user that he hasn't order yet and then redirects the the available movies page.
elseif(!empty($_SESSION["order"][$username])) {

	if(isset($_POST["tickets"]) && isset($_POST["orderKey"])) {
		$orderKey = $_POST["orderKey"];
		$_SESSION["order"][$username][$orderKey]["Kaartjes"] = $_POST["tickets"];
	}

	$order = $_SESSION["order"][$username];
	
	$customerData = readCustomerdata($pdo, "full", $username);
	
	$table = null;
	
	foreach ($order as $key => $value) {

	$title 	 = $value["Titel"];
	$hallId  = $value["ZaalNR"];
	$date 	 = $value["Datum"];
	$time 	 = $value["Tijd"];
	$tickets = $value["Kaartjes"];
	$price 	 = $value["Prijs"];
	$picture = $value["Plaatje"];
		
	$totalPrice = $tickets * $price;

	$table .= "
		<div class='displayIflex justifyContentc'>
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
						<form method='POST' >
							<input type='hidden' name='orderKey' value='{$key}'>
							<select name='tickets' id='tickets' onchange='this.form.submit();'>";
								for ($a = 1; $a <= 10; $a++){
									$ifSelect = ($tickets == $a) ? "selected" : null;
									$table .= "<option value='$a' {$ifSelect} >{$a}x</option>";
								}
	$table .= "				</select>
							<label for='tickets'>Kaartjes</label>
						</form>
						<p>&euro;{$price}</p>
					</div>
					<div class='displayIflex justifyContentsb'>
						<p>Totaal</p>
						<p>&euro;{$totalPrice}</p>
					</div>
				</div>
			</div>
		</div>";		
	}
?>
<div class="displayIflex justifyContentc inheritHw">
	<div class="displayIflex flexFlowc justifyContentsa">
		<?php echo $table; ?>
	</div>
	<div class="displayIflex">
		<div class="displayIflex flexFlowr" id="orderContent">
			<div>
				<h3> Uw Gegevens </h3>
					<h4>Voornaam en Achternaam</h4>
					<p><?php echo "{$customerData->Voornaam} {$customerData->Achternaam}"?></p>
					<h4>Adres en Postcode</h4>
					<p><?php echo "{$customerData->Adres} , {$customerData->Postcode}"?></p>
					<h4>Plaats</h4>
					<p><?php echo $customerData->Plaats ?></p>
					<h4>Telefoonnummer</h4>
					<p><?php echo $customerData->TelefoonNr ?></p>
					<h4>E-mail</h4>
					<p><?php echo $customerData->Email ?></p>
			</div>
		</div>
	</div>
</div>
<div class="displayIflex justifyContentc">
	<form method="POST">
		<input type="submit" name="acceptOrder" value="bestellen">
	</form>
	<form method="POST">
		<input type="submit" name="deleteOrder" value="verwijderen">
	</form>
</div>
<?php
}
else {
	echo "U heeft nog niets gereserveerd. Om te reserveren verwijzen wij u nu door naar de reserverings pagina.";
	redirectTopage(1, "availableMovies");
}
?>
