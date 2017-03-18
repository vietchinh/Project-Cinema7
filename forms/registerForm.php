
	<h1>Registreren</h1>
	<form name="RegistratieFormulier" action="" method="post">
		<label for="FirstName">Voornaam:</label>
		<input type="text" id="FirstName" name="FirstName" value="<?php echo $FirstName; ?>" required/><?php echo $FnameErr; ?> <!-- Het laat zien de voornaam van een klant, maar als de voornaam niet voldoet aan de voorwaardes, dan geeft die naast de naam een error. -->
		<br />
		<label for="LastName">Achternaam:</label>
		<input type="text" id="LastName" name="LastName" value="<?php echo $LastName; ?>" required /><?php echo $LnameErr; ?> <!-- Het laat zien de achternaam van een klant, maar als de achternaam niet voldoet aan de voorwaardes, dan geeft die naast de achternaam een error. -->
		<br />		
		<label for="Adres">Adres:</label>
		<input type="text" id="Adres" name="Adres" value="<?php echo $Adres; ?>" /> <!-- Het laat zien de adres van een klant -->
		<br />
		<label for="ZipCode">Postcode:</label>
		<input type="text" id="ZipCode" name="ZipCode" value="<?php echo $ZipCode; ?>" /><?php echo $ZipErr; ?> <!-- Het laat zien de postcode van een klant, maar als de postcode niet voldoet aan de voorwaardes, dan geeft die naast de postcode een error. -->
		<br />		
		<label for="City">Plaats:</label>
		<input type="text" id="City" name="City" value="<?php echo $City; ?>" /><?php echo $CityErr;?> <!-- Het laat zien de stad van een klant, maar als de stad niet voldoet aan de voorwaardes, dan geeft die naast de stad een error. -->
		<br />
		<label for="TelNr">Telefoon nr.:</label>
		<input type="text" id="TelNr" name="TelNr" value="<?php echo $TelNr; ?>" required /><?php echo $TelErr; ?> <!-- Het laat zien de telefoon nummer van een klant, maar als de telefoon nummer niet voldoet aan de voorwaardes, dan geeft die naast de telefoon nummer een error. -->
		<br />
		<label for="Email">E-mail:</label>
		<input type="text" id="Email" name="Email" value="<?php echo $Email; ?>" required /><?php echo $MailErr; ?> <!-- Het laat zien het email van een klant, maar als het email niet voldoet aan de voorwaardes, dan geeft die naast het email een error. -->
		<br />
		<br />
		<label for="Username">Gewenste Gebruikersnaam:</label>
		<input type="text" id="Username" name="Username" value="<?php echo $Username; ?>" required /><?php echo $UserErr; ?> <!-- Het laat zien de gebruikersnaam van een klant, maar als de gebruikersnaam niet voldoet aan de voorwaardes, dan geeft die naast de gebruikersnaam een error. -->
		<br />		
		<label for="Password">Wachtwoord:</label>
		<input type="password" id="Password" name="Password" required /><?php echo $PassErr; ?> <!-- Als de paswoord niet voldoet aan de voorwaardes, dan geeft die naast de paswoord een error. -->
		<br />		
		<label for="RetypePassword">Herhaal Wachtwoord:</label>
		<input type="password" id="RetypePassword" name="RetypePassword" required /><?php echo $RePassErr; ?> <!-- Als de opnieuw getypde paswoord niet voldoet aan de voorwaardes, dan geeft die naast de opnieuw getypde paswoord een error. -->
		<br />		
		<input type="submit" name="registerRf" value="Registreer!" />
	</form>
