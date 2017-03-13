<?php
session_start();

// Section 1 - Insert functions and databaseFunctions modules
require_once("./functions/functions.php");
require_once("./functions/databaseFunctions.php");

// Section 2 - Connect DB
$pdo = connectDB();

// Section 3 - Insert Homepage
require("homepage.php");
?>
