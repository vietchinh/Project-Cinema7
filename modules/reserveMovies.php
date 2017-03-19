<?php

	unset($_SESSION["order"]);

	$filmId 			   			   = $_POST["filmId"];
	$_SESSION["availableReservations"] = fetchDatabase($pdo, "reserve", $filmId, PDO::PARAM_INT);

	$availableReservations			   = $_SESSION["availableReservations"];
	$hallId 			   			   = fetchDatabase($pdo, "hallId", $filmId, PDO::PARAM_INT);
?>
<form method="POST" id="reserveRadio">
	<h1><?php echo $availableReservations[0]["Titel"] ?></h1>
	<h4><?php echo $availableReservations[0]["Prijs"] ?></h4>
	<?php
		foreach($hallId as $key) {
			echo "
			<h3> Zaal {$key->ZaalNR}: </h3>";
			foreach (array_keys(array_column($availableReservations, "ZaalNR"), $key->ZaalNR) as $key) {
				echo "
				<input type='radio' name='showId' value='{$availableReservations[$key]["VertoningsID"]}' >{$availableReservations[$key]["Datum"]} {$availableReservations[$key]["Tijd"]}<br />";
			}
		}
	?>
	<h4> Aantal Kaartjes: </h4>
		<select name="tickets">
			<?php
				for ($a = 1; $a <= 10; $a++){
					echo "<option value='$a'>$a</option>";
				}
			?>
		</select>
	<br />
	<br />
	<input type="submit" name="pageName[Bestelling Lijst]" value="Reserveren">
</form>

