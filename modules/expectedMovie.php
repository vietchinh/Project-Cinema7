<?php
// Section 1 - Fetch movies that are expected to be air in the cinema
// Date Creation: 18-03-2017 | Date Modifcation: 20-03-2017

	$fv = readMovies($pdo, true, "Verwacht"); // fw = fetchVerwacht

// Section 2 - Return table
// Date Creation: 18-03-2017 | Date Modifcation: 20-03-2017
	echo returnTablestatus($fv);
?>