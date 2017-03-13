<!-- 
	Opdracht PM11 STAP 1 : Film Toevoegen 
	Maak hier het formulier waarmee je een film kan toevoegen aan de database. Let op: dit formulier komt dus overeen met de velden uit de database tabel Films
-->
<?php
$allFilms = fetchDatabase($pdo, "allMovie");
// Source: https://gist.github.com/alexwright/1853977

$enumAge 	= fetchDatabase($pdo, "enum", "Leeftijd");
$enumGenre  = fetchDatabase($pdo, "enum", "Genre");
$enumType 	= fetchDatabase($pdo, "enum", "Type");
$enumStatus = fetchDatabase($pdo, "enum", "Status");

?>
		<style>
			form {
				border: 1px solid orange;
				border-radius: 5px;
				padding: 5px;
				margin: 5px;
			}
			.nieuwe {
				background: orange;
			}
			.rood {
				color: red;
			}
		</style>
<form action="" method="POST" id="new">
	<h3>Nieuw Film</h3>
	<input type="text" name="title" placeholder="Titel" value="" required/>
		<br />
	<input type="number" min="0" max="999" name="duration" placeholder="Duur" value="" required/> <?php echo $durErr ?>
		<br />
	<input type="text" name="genre" placeholder="Genre" value="" required/>
		<br />
	<input type="number" min="0" max="999" step="any" name="price" placeholder="Prijs" value="" required/> <?php echo $priceErr ?> <!-- Step='Any' is from https://www.isotoma.com/blog/2012/03/02/html5-input-typenumber-and-decimalsfloats-in-chrome/ -->
		<br />
	<select name="age" required>
	<?php
		foreach(explode(',', substr($enumAge->enum, 0, (strlen($enumAge->enum) - 1))) as $value)
		{
			$value = trim($value, "'");
			echo "<option value='$value'> $value </option>";
		}
	?>
	</select>
	<select name="genre" required>
	<?php
		foreach(explode(',', substr($enumGenre->enum, 0, (strlen($enumGenre->enum) - 1))) as $value)
		{
			$value = trim($value, "'");
			echo "<option value='$value'> $value </option>";
		}
	?>	
	</select>
	<select name="type" required>
	<?php
		foreach(explode(',', substr($enumType->enum, 0, (strlen($enumType->enum) - 1))) as $value)
		{
			$value = trim($value, "'");
			echo "<option value='$value'> $value </option>";
		}
	?>
	</select>
	<select name="state" required>
	<?php
		foreach(explode(',', substr($enumStatus->enum, 0, (strlen($enumStatus->enum) - 1))) as $value)
		{
			$value = trim($value, "'");
			echo "<option value='$value'> $value </option>";
		}
	?>
	</select>
		<br />
	<textarea type="text" name="description" placeholder="Beschrijving" value="" cols="110" rows="10" required></textarea>
		<br />
	<input type="submit" name="newMovie" value="Nieuw Film" />
</form>


<?php
$types  = array("Normaal", "3D", "IMAX", "IMAX 3D");
$states = array("InBios", "Verwacht");
	foreach ($allFilms as $key){
		echo "
		<form action='' method='POST' id='change'>
			<h3>$key->Titel</h3>
			<input type='text' name='title' placeholder='$key->Titel' required/>
				<br />
			<input type='number' min='0' max='999'  name='duration' placeholder='$key->Duur' required/>
				<br />
			<input type='number' min='1' max='100' name='age' placeholder='$key->Leeftijd' required/>
				<br />
			<input type='number' min='0' max='999'name='price' placeholder='$key->Prijs' required/>
				<br />
			<select name='age' required>";
				foreach(explode(",", substr($enumAge->enum, 0, (strlen($enumAge->enum) - 1))) as $value)
				{
					$selected = null;
					$value = trim($value, "'");
					if ($key->Leeftijd == $value) { 
						$selected = "selected"; 
					}
					echo "<option value='$value' $selected > $value </option>";
				}
		echo "</select>
			<select name='genre' required>";
				foreach(explode(",", substr($enumGenre->enum, 0, (strlen($enumGenre->enum) - 1))) as $value)
				{
					$selected = null;
					$value = trim($value, "'");
					if ($key->Genre == $value) { 
						$selected = "selected"; 
					}
					echo "<option value='$value' $selected> $value </option>";
				}
		echo "</select>
			<select name='type' required>";
				foreach(explode(",", substr($enumType->enum, 0, (strlen($enumType->enum) - 1))) as $value)
				{
					$selected = null;
					$value = trim($value, "'");
					if ($key->Type == $value) { 
						$selected = "selected"; 
					}
					echo "<option value='$value' $selected> $value </option>";
				}
		echo "</select>
			<select name='state' required>";
				foreach(explode(",", substr($enumStatus->enum, 0, (strlen($enumStatus->enum) - 1))) as $value)
				{
					$selected = null;
					$value = trim($value, "'");
					if ($key->Status == $value) { 
						$selected = "selected"; 
					}
					echo "<option value='$value' $selected> $value </option>";
				}
		echo "</select>
				<br />
			<textarea type='text' name='description' placeholder='$key->Beschrijving' cols='110' rows='10' required>$key->Beschrijving</textarea>
				<br />
			<input type='submit' name='wijzigingen' value='Wijzig Film' />
		</form>";
	}
?>