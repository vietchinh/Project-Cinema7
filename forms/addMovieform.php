<!-- 
	Opdracht PM11 STAP 1 : Film Toevoegen 
	Maak hier het formulier waarmee je een film kan toevoegen aan de database. Let op: dit formulier komt dus overeen met de velden uit de database tabel Films
-->
<?php
$allFilms = fetchFilmsstatus($pdo);
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
<form action="" method="POST" class="nieuwe">
	<h3>Nieuw Film</h3>
	<input type="text" name="titel" placeholder="Titel" value="" required/>
		<br />
	<input type="text" name="length" placeholder="Duur" value="" required/>
		<br />
	<input type="text" name="genre" placeholder="Genre" value="" required/>
		<br />
	<input type="text" name="age" placeholder="Leeftijd" value="" required/>
		<br />
	<input type="text" name="price" placeholder="Prijs" value="" required/>
		<br />
	<input type="text" name="type" placeholder="Type" value="" required/>
		<br />
	<input type="text" name="state" placeholder="Status" value="" required/>
		<br />
	<textarea type="text" name="discription" placeholder="Beschrijving" value="" cols="110" rows="10" required></textarea>
		<br />
	<input type="submit" name="newMovie" value="Nieuw Film" />
</form>


<?php
	foreach ($allFilms as $key){
		echo "
		<form action='' method='POST'>
			<h3>$key->Titel</h3>
			<input type='text' name='titel' placeholder='$key->Titel' required/>
				<br />
			<input type='text' name='length' placeholder='$key->Duur' required/>
				<br />
			<input type='text' name='genre' placeholder='$key->Genre' required/>
				<br />
			<input type='text' name='age' placeholder='$key->Leeftijd' required/>
				<br />
			<input type='text' name='price' placeholder='$key->Prijs' required/>
				<br />
			<input type='text' name='type' placeholder='$key->Type' required/>
				<br />
			<input type='text' name='status' placeholder='$key->Status' required/>
				<br />
			<textarea type='text' name='discription' placeholder='$key->Beschrijving' cols='110' rows='10' required> $key->Beschrijving </textarea>
				<br />
			<input type='submit' name='wijzigingen' value='Wijzig Film' />
		</form>";
	}
?>