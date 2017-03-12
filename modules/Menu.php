<?php
/*  
	Opdracht PM09 STAP 1: menu op basis van gebruikers levels 
	Omschrijving: Zet het default level op 0 en vul de variabale MenuInUitloggen met de default html code voor de knop inloggen
*/

if (isset($_POST["Register"]) || isset($_POST["Registreren"])){
	$isRegister = true;
}
else {
	$isRegister = false;
}

$isLogin = LoginCheck($pdo);

$Level = (!$isLogin) ? 0 : $_SESSION['level']; // default level 0

$MenuInUitloggen = (!$isLogin) ? "Login" : "Uitloggen" ; // default menuknop inloggen

/*  
	Opdracht PM09 STAP 2: menu op basis van gebruikers levels 
	Omschrijving: Controleer mbv de functie LoginCheck of iemand is ingelogd. Zo ja, dan overschrijf je de default waardes van Level en MenuInUitloggen met het level uit de database en de html code voor de knop uitloggen
*/	

// Already incooperated in the line above. Using ternary operator.

/*  
	Opdracht PM09 STAP 3 : menu op basis van gebruikers levels 
	Omschrijving: Maak een prepared statement waarbij je de menu items opvraagd die de gebruiker op basis van zij/haar level mag zien. Zorg er vervolgens voor dat deze netjes op het scerm worden getoond.
*/


echo "<ul id='menu'>";

foreach(fetchMenudata($pdo, $Level) as $key) {
	echo "<li><a href='index.php?pageNr={$key["PaginaNr"]}'>{$key["Tekst"]}</a></li>";
}
echo "<li><a href='index.php?pageNr=5'>" . $MenuInUitloggen . "</a></li></ul>";


/*  
	Opdracht PM09 STAP 4 : menu op basis van gebruikers levels 
	Omschrijving: Verwijder tot slot de basiscode die we gemaakt hebben in opdracht 2.03 hieronder
*/



?>

<!--
	Opdracht PM03 STAP 1: Bioscoop Modulair
	Omschrijving: Voeg een link toe naar index.php met daarbij een variabele pagina en als waarde het pagina nr

<ul id="menu">
	<li><a href="index.php?pageNr=1">Home</a></li>
	<li><a href="index.php?pageNr=2">Reserveren</a></li>
	<li><a href="index.php?pageNr=3">Verwacht</a></li>
	<li><a href="index.php?pageNr=4">Over ons</a></li>
	<li><a href="index.php?pageNr=5">Inloggen</a></li>
</ul> -->