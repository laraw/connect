<?php


// get the variables from the Search & trim them and format them for sql query 



// set the rowcount 

$rowCount = 1;
$result = '';

// build the query based on the parameters
$query = '';


// store the query in a session object
session_start();

if($rowCount >= 1) {
		$_SESSION['results'] = 'Here are the results that match your criteria: ';
	}
else {
		$_SESSION['results'] = 'Sorry there were no results matching your criteria!';
	}


header( 'Location: results.php' ) ;

?>