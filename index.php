<?php
session_start();

// Section 1 - Insert functions and databaseFunctions modules
require("./functions/databaseFunctions.php");
require("./functions/functions.php");

// Section 2 - Connect DB
$pdo = connectDB();

// Section 6 - Insert Homepage
require("homepage.php");
?>
