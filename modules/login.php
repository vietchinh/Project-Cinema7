<?php
// Section 1 - Login with validations
// Date Creation: 18-03-2017 | Date Modification: 24-03-2017
// If the user pushed the login button, then it will initialize variables named username and password with equally post name username and password.
// After that it is going to give an error response if either the username or the password is empty.
// Now that everything is A ok, username and password are going to be passed through validations.
// If either is wrong an error message is going to show on screen. If not then it's going to welcome the user and redirect to the homepage.
$login = null;
if(isset($_POST["login"])) {
	
	$username = $_POST["username"];
	$password = $_POST["password"];

	if (!$username){
		echo "<h1> Voer uw gebruikersnaam in. </h1>";		
	}
	elseif (!$password){
		echo "<h1> Voer uw paswoord in. </h1>";		
	}
	else {
		$login = login($pdo, $username, $password);
		if ($login == "ePassword") {
			echo "<h1> U heeft uw password verkeerd ingevoerd, controleer uw password. </h1>";
		}
		elseif ($login == "eUsername"){
			echo "<h1> U heeft uw gebruikersnaam verkeerd ingevoerd of uw gebruikersnaam bestaat niet, controleer uw gebruikersnaam. </h1>";
		}
		else {
			echo "<h1> Welkom op cinema7, $username.";
			echo redirectTopage(5);
		}
	}
}
($login != "success") ? require_once("./forms/loginForm.php") : null;
?>