<?php
// Unset session var 
$_SESSION = array();
 
// ophalen session parameters 
$params = session_get_cookie_params();
 
// verwijderen van sessie cookie 
setcookie(session_name(),
        '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]);
 
// Destroy session 
session_destroy();

// Header refresh naar homepage
RedirectNaarPagina();
?>





