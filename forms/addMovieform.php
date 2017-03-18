<?php

// Section 1 - Add movie (PM11);

$allFilms = fetchDatabase($pdo, "allMovie");
// Source: https://gist.github.com/alexwright/1853977

$enumAge 	= fetchDatabase($pdo, "enum", "Leeftijd");
$enumGenre  = fetchDatabase($pdo, "enum", "Genre");
$enumType 	= fetchDatabase($pdo, "enum", "Type");
$enumStatus = fetchDatabase($pdo, "enum", "Status");

?>
<form method="POST" id="new">
	<h3>Nieuw Film</h3>
	<input type="text" name="title" placeholder="Titel" value="" required/>
		<br />
	<input type="number" min="0" max="999" name="duration" placeholder="Duur" value="" required/> <?php echo $durErr ?>
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
// Section 2 - Change movie (PM12);

	foreach ($allFilms as $key){
		echo "
		<form method='POST' id='change'>
			<h3>$key->Titel</h3>
			<input type='hidden' name='movieId' value='$key->FilmID'>
			<input type='text' name='title' placeholder='$key->Titel' value='$key->Titel' required/>
				<br />
			<input type='number' min='0' max='999' placeholder='$key->Duur' name='duration' value='$key->Duur' required/> $durErr
				<br />
			<input type='number' min='0' max='999' step='0.01' placeholder='$key->Prijs' name='price' value='$key->Prijs' required/> $priceErr
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
			<textarea type='text' name='description' cols='110' rows='10' required>$key->Beschrijving</textarea>
				<br />
			<input type='submit' name='changeMovie' value='Wijzig Film' />
			<input type='submit' name='deleteMovie' value='Verwijder Film' />
		</form>";
	}
?>