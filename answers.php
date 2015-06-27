<?php
require_once('./php/dataaccess.php');

session_start();
$db = createDBConnection();

// get the variables from the Search & trim them 

$searchWine = addslashes(trim($_GET['wineName'])); // wine name
$searchWinery = addslashes(trim($_GET['winery']));
$searchRegion = addslashes(trim($_GET['region']));
$searchVariety = addslashes(trim($_GET['variety']));
$searchYear = addslashes(trim($_GET['year']));
$searchMinStock = addslashes(trim($_GET['minStock']));
$searchMaxOrdered = addslashes(trim($_GET['maxOrdered']));
$searchMinPrice = addslashes(trim($_GET['minPrice']));
$searchMaxPrice = addslashes(trim($_GET['maxPrice']));

// check to see if anything has been searched for
$data = false;

foreach($_GET as $val) {
		if(strlen($val) > 0 && $val <> 'Submit') {
			$data = true;
		}
	}
	



// validation checks 
// addslashes, check that number values are actual numbers (??? anything else)

//make them 'sql query' ready



// the actual query


// set the rowcount 

$rowCount = 1;
$result = '';

// build the query based on the parameters
$query = '';


// store the query in a session object



if($rowCount >= 1) {
		$_SESSION['results'] = 'Here are the results that match your criteria: ';
	}
else {
		$_SESSION['results'] = 'Sorry there were no results matching your criteria!';
	}


//header( 'Location: results.php' ) ;

?>