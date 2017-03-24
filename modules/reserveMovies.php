<?php
// Section 1 - Intialize data from database and post
// Date Creation: 18-03-2017 | Date Modification: 23-03-2017
$movieId 			   			   = $_POST["filmId"];
$_SESSION["availableReservations"] = readShowdata($pdo, $movieId);

$availableReservations			   = $_SESSION["availableReservations"];
$hallId 			   			   = readShowhallId($pdo, $movieId);
?>
<!-- Section 2 - Reservation Form -->
<!-- Date Creation: 18-03-2017 | Date Modification: 23-03-2017 -->
<!-- Create form with dynamic radio with the data from database and amount of tickets using for loop -->
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

