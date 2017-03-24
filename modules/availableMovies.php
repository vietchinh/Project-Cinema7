<?php
// Section 1 - Fetch movies that are currently airing in the cinema
// Date Creation: 18-03-2017 | Date Modifcation: 20-03-2017

	$fi = readMovies($pdo, true, "InBios"); // fi = Fetch InBios


// Section 2 - Show table base on user level.
// Date Creation: 18-03-2017 | Date Modifcation: 20-03-2017
    echo (isset($_SESSION['level']) && $_SESSION['level'] >= 1) ? returnTablestatus($fi, true) : returnTablestatus($fi, false, true);
?>