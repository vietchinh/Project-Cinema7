<?php

// Section 1 - Check whether register post is set.

if (isset($_POST["register"]) || isset($_POST["Registreren"])){
	$isRegister = true;
}
else {
	$isRegister = false;
}
// Section 2 - Login check
$isLogin = LoginCheck($pdo);

$level = (!$isLogin) ? 0 : $_SESSION['level']; // default level 0

$MenuInUitloggen = (!$isLogin) ? "Login" : "Uitloggen" ; // default menuknop inloggen

// Section 3 - Using data from the database table  menu to create the menu.
echo "<ul id='menu'>";

foreach(fetchDatabase($pdo, "menu", $level) as $key) {
	echo "<li><a href='index.php?pageNr={$key->PaginaNr}'>{$key->Tekst}</a></li>";
}
echo "<li><a href='index.php?pageNr=5'>" . $MenuInUitloggen . "</a></li></ul>";

?>