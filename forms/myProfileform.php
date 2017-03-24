<!-- Section 1 - my Profile Form -->
<!-- Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017 -->
<!-- Create a dynamic form. Placeholder and the error message are dynamicly set with php. -->

	<h1>Gegevens Wijzigen</h1>
	<form name="WijzigenFormulier" action="" method="post">
		<label for="FirstName">Voornaam:</label>
		<input type="text" id="FirstName" name="FirstName" placeholder="<?php echo $FirstName; ?>" /><?php echo $FnameErr; ?> <!-- Het laat zien de voornaam van een klant, maar als de voornaam niet voldoet aan de voorwaardes, dan geeft die naast de naam een error. -->
		<br />
		<label for="LastName">Achternaam:</label>
		<input type="text" id="LastName" name="LastName" placeholder="<?php echo $LastName; ?>" /><?php echo $LnameErr; ?> <!-- Het laat zien de achternaam van een klant, maar als de achternaam niet voldoet aan de voorwaardes, dan geeft die naast de achternaam een error. -->
		<br />		
		<label for="Adres">Adres:</label>
		<input type="text" id="Adres" name="Adres" placeholder="<?php echo $Adres; ?>" /> <!-- Het laat zien de adres van een klant -->
		<br />
		<label for="ZipCode">Postcode:</label>
		<input type="text" id="ZipCode" name="ZipCode" placeholder="<?php echo $ZipCode; ?>" /><?php echo $ZipErr; ?> <!-- Het laat zien de postcode van een klant, maar als de postcode niet voldoet aan de voorwaardes, dan geeft die naast de postcode een error. -->
		<br />		
		<label for="City">Plaats:</label>
		<input type="text" id="City" name="City" placeholder="<?php echo $City; ?>" /><?php echo $CityErr;?> <!-- Het laat zien de stad van een klant, maar als de stad niet voldoet aan de voorwaardes, dan geeft die naast de stad een error. -->
		<br />
		<label for="TelNr">Telefoon nr.:</label>
		<input type="text" id="TelNr" name="TelNr" placeholder="<?php echo $TelNr; ?>" /><?php echo $TelErr; ?> <!-- Het laat zien de telefoon nummer van een klant, maar als de telefoon nummer niet voldoet aan de voorwaardes, dan geeft die naast de telefoon nummer een error. -->
		<br />
		<label for="Email">E-mail:</label>
		<input type="text" id="Email" name="Email" placeholder="<?php echo $Email; ?>" /><?php echo $MailErr; ?> <!-- Het laat zien het email van een klant, maar als het email niet voldoet aan de voorwaardes, dan geeft die naast het email een error. -->
		<br />	
		<input type="submit" name="changeProfile" value="Uw gegevens wijzigen" />
	</form>