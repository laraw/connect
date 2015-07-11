<?php
require_once('./php/dataaccess.php');
//require_once('./php/helper.php');
session_start();
$db = createDBConnection();

// if there are any search results from a previous session, clear them out
if (isset($_SESSION['queryRes'])) {
    session_unset($_SESSION['queryRes']);
}
if (isset($_SESSION['error'])) {
    session_unset($_SESSION['error']);
}
/* VALIDATION CHECKS */
/*

1. Wine name should be a string max 50
2. Winery name should be string max 100
3. Min/Max Year min must be less than max, year is int max 4
4. Min/Max Stock int 5 - min must be less than max
5. Min/Max Price decimal (5,2) - min must be less than max

User should be returned to search page with the error field highlighted for correction. No results should appear until all errors fixed. */
$data = true;

// create an array with all the fields in it
$errors = array();

// get the variables from the Search & trim them 
$searchWine       = addslashes(trim($_GET['wineName'])); 
$searchWinery     = addslashes(trim($_GET['winery']));
$searchRegion     = addslashes(trim($_GET['region']));
$searchVariety    = addslashes(trim($_GET['variety']));
$searchMinYear    = addslashes(trim($_GET['minyear']));
$searchMaxYear    = addslashes(trim($_GET['maxyear']));
$searchMinStock   = addslashes(trim($_GET['minStock']));
$searchMinOrdered = addslashes(trim($_GET['minOrdered']));
$searchMinPrice   = addslashes(trim($_GET['minPrice']));
$searchMaxPrice   = addslashes(trim($_GET['maxPrice']));

// Wine name should be a string max 50  						- $errors['winename']
if(strlen($searchWine) > 50) {
	$errors['winename'] = "Wine name is too long - max 50 characters";
	$data = false;
}
elseif ( preg_match("/[^a-z0-9 ]/i", $searchWine) ) {
	$errors['winename'] = "Wine name contains invalid characters - alphanumeric only";
	$data = false;
}

// Winery name should be string max 100							- $errors['wineryname']
if(strlen($searchWinery) > 100) {
	$errors['wineryname'] = "Winery name is too long - max 50 characters";
	$data = false;
}
elseif ( preg_match("/[^a-z0-9 ]/i", $searchWinery) ) {
	$errors['wineryname'] = "Winery name contains invalid characters - alphanumeric only";
	$data = false;
}

// Min/Max Year min must be less than max, year is int max 4	- $errors['year']
if ($searchMinYear > $searchMaxYear) {
    $errors['year'] = "Your minimum year is greater than your maximum!";
	$data = false;
}

// Min Stock int 5 digits only	0-10000		-  $errors['stock']
if (!is_numeric($searchMinStock) && $searchMinStock <> '') {
	$errors['stock'] = "Not a number";
	$data = false;
}
elseif(fmod($searchMinStock, 1) <> 0.0) {
	$errors['stock'] = "Must be a whole number!";
	$data = false;
}
elseif($searchMinStock > 99999 || $searchMinStock < 0 ) {
	$errors['stock'] = "Must be a number between 0 and 10,000";
	$data = false;
}

// Min Ordered int 0 or larger
if (!is_numeric($searchMinOrdered) && $searchMinOrdered <> '') {
	$errors['ordered'] = "Not a number";
	$data = false;
}
elseif(fmod($searchMinOrdered, 1) != 0.0) {
	$errors['ordered'] = "Must be a whole number!";
	$data = false;
}
elseif($searchMinOrdered < 0 ) {
	$errors['ordered'] = "Must not be a negative number";
	$data = false;
}

// Min/Max Price decimal (5,2) - min must be less than max		
$priceErr = array();
$priceData = true;
if((preg_match("/\A(?!\d{1,5}(?:\.\d{1,2})?\z).*/", $searchMinPrice)) && $searchMinPrice <> '') {
	$priceErr[] = "Min Price invalid format";
	$priceData = false;
}
if((preg_match("/\A(?!\d{1,5}(?:\.\d{1,2})?\z).*/", $searchMaxPrice)) && $searchMaxPrice <> '') {
	$priceErr[] = "Max Price invalid format";
	$priceData = false;
}
if($searchMinPrice <> '' && $searchMaxPrice <> '') {
	if($priceData && $searchMinPrice >= $searchMaxPrice ) {
		$priceErr[] = "Min Price must be less than Max Price";
		$priceData = false;
	}
}
if(!$priceData) {
	$errors['price'] = implode(" - ", $priceErr);
	$data = false;
}
// check for ascii code or malicious code

// check the data type & length matches the length in the database see
/* 
if ((!ereg("^([0-9])$", $searchMinStock) && $searchMinStock <> '') || (!ereg("^([0-9])$", $searchMaxOrdered) && $searchMaxOrdered <> '') 
|| (!ereg("^([0-9])$", $searchMaxPrice) && $searchMaxPrice <> '') || (!ereg("^([0-9])$", $searchMinPrice) && $searchMinPrice <> '')) {
$data = false;
$errorMsg = "Not a valid number!";
}
*/

// If the validation check fails ... return to the previous page with error message 
if (!$data) {
    $_SESSION['error'] = $errors;
    header('Location: search.php');
    exit;
}

/* BUILD SEARCH QUERY */
$query = "select  GROUP_CONCAT(DISTINCT(gr.variety)) as WineVarieties, w.wine_name as WineName,  
		 w.year as Year, min(i.cost) as Cost, i.on_hand as Stock, win.winery_name as Winery, coalesce(TotalSold,0.00) as TotalSold, 
		  coalesce(TotalSalesRevenue,0.00) as TotalSalesRevenue, 
		 reg.region_name as Region
		 from wine w
		 inner join wine_variety wv on w.wine_id = wv.wine_id
		 inner join grape_variety gr on wv.variety_id = gr.variety_id
		 inner join inventory i on w.wine_id = i.wine_id
		 inner join winery win on w.winery_id = win.winery_id
		 inner join region reg on reg.region_id = win.region_id
		 left join (select sum(qty) as totalSold,sum(price) as TotalSalesRevenue, wine_id 
								  from items
								 group by wine_id) as wts on wts.wine_id = w.wine_id
		 where 1 = 1";

// set the rowcount 
$rowCount = 1;
$result   = '';

// build the query based on the parameters
if ($searchWine <> "") {
    $query = $query . " and w.wine_name like " . "'%" . $searchWine . "%'";
}
if ($searchWinery <> "") {
    $query = $query . " and win.winery_name like " . "'%" . $searchWinery . "%'";
}
if ($searchRegion <> "" and $searchRegion <> "All") {
    $query = $query . " and reg.region_name like " . "'%" . $searchRegion . "%'";
}
if ($searchVariety <> "") {
    $query = $query . " and gr.variety like " . "'%" . $searchVariety . "%'";
}
if ($searchMinYear <> "") {
    $query = $query . " and w.year >= " . "'" . $searchMinYear . "'";
}
if ($searchMaxYear <> "") {
    $query = $query . " and w.year <= " . "'" . $searchMaxYear . "'";
}
if ($searchMinOrdered <> "") {
    $query = $query . " and totalSold >= " . $searchMinOrdered;
}
if ($searchMinStock <> "") {
    $query = $query . " and i.on_hand >= " . $searchMinStock;
}
if ($searchMinPrice <> "") {
    $query = $query . " and i.cost >= " . $searchMinPrice;
}
if ($searchMaxPrice <> "") {
    $query = $query . " and i.cost <= " . $searchMaxPrice;
}

// complete the query
$query = $query . " group by  w.wine_id;";
$rowCount = 0;


// store the query in a session object
try {
    $stmt = $db->prepare($query);
    $stmt->execute();
    $rowCount             = $stmt->rowCount();
    $res                  = $stmt->fetchAll();
    $_SESSION['rowCount'] = $rowCount;
    $_SESSION['queryRes'] = $res;
}

catch (PDOException $e) {
    echo $e->getMessage();
}

if ($rowCount >= 1 && $data) {
    $_SESSION['results'] = 'There were ' . $rowCount . ' results that match your criteria: ';
}

if ($rowCount < 1 && $data) {
    $_SESSION['results'] = 'Sorry there were no results matching your criteria!';
}

header('Location: results.php');
?>