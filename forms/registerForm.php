<!-- Section 1 - Register Form -->
<!-- Date Creation: 18-03-2017 | Date Modifcation: 24-03-2017 -->
<!-- Create a dynamic form. Value and the error messag are dynamicly set with php. -->
	<h1>Registreren</h1>
	<form name="RegistratieFormulier" action="" method="post">
		<label for="firstName">Voornaam:</label>
		<input type="text" id="firstName" name="firstName" value="<?php echo $firstName; ?>" required/><?php echo $fNameErr; ?> <!-- Het laat zien de voornaam van een klant, maar als de voornaam niet voldoet aan de voorwaardes, dan geeft die naast de naam een error. -->
		<br />
		<label for="lastName">Achternaam:</label>
		<input type="text" id="lastName" name="lastName" value="<?php echo $lastName; ?>" required /><?php echo $LnameErr; ?> <!-- Het laat zien de achternaam van een klant, maar als de achternaam niet voldoet aan de voorwaardes, dan geeft die naast de achternaam een error. -->
		<br />		
		<label for="adres">adres:</label>
		<input type="text" id="adres" name="adres" value="<?php echo $adres; ?>" /> <!-- Het laat zien de adres van een klant -->
		<br />
		<label for="zipcode">Postcode:</label>
		<input type="text" id="zipcode" name="zipcode" value="<?php echo $zipcode; ?>" /><?php echo $ZipErr; ?> <!-- Het laat zien de postcode van een klant, maar als de postcode niet voldoet aan de voorwaardes, dan geeft die naast de postcode een error. -->
		<br />		
		<label for="city">Plaats:</label>
		<input type="text" id="city" name="city" value="<?php echo $city; ?>" /><?php echo $cityErr;?> <!-- Het laat zien de stad van een klant, maar als de stad niet voldoet aan de voorwaardes, dan geeft die naast de stad een error. -->
		<br />
		<label for="telNr">Telefoon nr.:</label>
		<input type="text" id="telNr" name="telNr" value="<?php echo $telNr; ?>" required /><?php echo $TelErr; ?> <!-- Het laat zien de telefoon nummer van een klant, maar als de telefoon nummer niet voldoet aan de voorwaardes, dan geeft die naast de telefoon nummer een error. -->
		<br />
		<label for="email">E-mail:</label>
		<input type="text" id="email" name="email" value="<?php echo $email; ?>" required /><?php echo $MailErr; ?> <!-- Het laat zien het email van een klant, maar als het email niet voldoet aan de voorwaardes, dan geeft die naast het email een error. -->
		<br />
		<br />
		<label for="username">Gewenste Gebruikersnaam:</label>
		<input type="text" id="username" name="username" value="<?php echo $username; ?>" required /><?php echo $UserErr; ?> <!-- Het laat zien de gebruikersnaam van een klant, maar als de gebruikersnaam niet voldoet aan de voorwaardes, dan geeft die naast de gebruikersnaam een error. -->
		<br />		
		<label for="password">Wachtwoord:</label>
		<input type="password" id="password" name="password" required /><?php echo $PassErr; ?> <!-- Als de paswoord niet voldoet aan de voorwaardes, dan geeft die naast de paswoord een error. -->
		<br />		
		<label for="reTypepassword">Herhaal Wachtwoord:</label>
		<input type="password" id="reTypepassword" name="reTypepassword" required /><?php echo $RePassErr; ?> <!-- Als de opnieuw getypde paswoord niet voldoet aan de voorwaardes, dan geeft die naast de opnieuw getypde paswoord een error. -->
		<br />		
		<input type="submit" name="register" value="Registreer!" />
	</form>
