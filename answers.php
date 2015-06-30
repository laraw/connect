<?php
require_once('./php/dataaccess.php');
//require_once('./php/helper.php');

session_start();
$db = createDBConnection();


// if there are any search results from a previous session, clear them out

if(isset($_SESSION['queryRes'])){
	session_unset($_SESSION['queryRes']);
}

if(isset($_SESSION['error'])) {
	session_unset($_SESSION['error']);
}
	

// get the variables from the Search & trim them 


$searchWine = addslashes(trim($_GET['wineName'])); // wine name
$searchWinery = addslashes(trim($_GET['winery']));
$searchRegion = addslashes(trim($_GET['region']));
$searchVariety = addslashes(trim($_GET['variety']));
$searchMinYear = addslashes(trim($_GET['minyear']));
$searchMaxYear = addslashes(trim($_GET['maxyear']));
$searchMinStock = addslashes(trim($_GET['minStock']));
$searchMinOrdered = addslashes(trim($_GET['minOrdered']));
$searchMinPrice = addslashes(trim($_GET['minPrice']));
$searchMaxPrice = addslashes(trim($_GET['maxPrice']));

// check to see if anything has been searched for
$data = false;

foreach($_GET as $val) {
		if(strlen($val) > 0 && $val <> 'Submit') {
			$data = true;
		}
	}

if(!$data) {
	$errorMsg = "You didn't search for anything!";	
}
	

// validation checks -- check that number values are actual numbers 
 if((!is_numeric($searchMinStock) && $searchMinStock <> '') || (!is_numeric($searchMaxOrdered) && $searchMaxOrdered <> '')
	|| (!is_numeric($searchMinPrice) && $searchMinPrice <> '') || (!is_numeric($searchMaxPrice) && $searchMaxPrice <> ''))
 {
	 $data = false;
	 $errorMsg = "Not a valid number!";
	 
 }

if($searchMinYear > $searchMaxYear) {
	$data = false;
	$errorMsg = "Your minimum year is greater than your maximum!";
	
}

// return to the previous page with error message 

if(!$data) {
	$_SESSION['error'] = $errorMsg;
	header( 'Location: search.php' ) ;
	exit;
}

// the actual query

$query = "select  GROUP_CONCAT(DISTINCT(gr.variety)) as WineVarieties, w.wine_name as WineName,  
		 w.year as Year, i.cost as Cost, i.on_hand as Stock, win.winery_name as Winery, wts.totalSold as TotalSold, 
		 wtsr.TotalSalesRevenue as TotalSalesRevenue, 
		 reg.region_name as Region
		 from wine w
		 inner join wine_variety wv on w.wine_id = wv.wine_id
		 inner join grape_variety gr on wv.variety_id = gr.variety_id
		 inner join inventory i on w.wine_id = i.wine_id
		 inner join winery win on w.winery_id = win.winery_id
		 inner join region reg on reg.region_id = win.region_id
		 inner join (select sum(qty) as totalSold, wine_id 
								  from items
								 group by wine_id) as wts on wts.wine_id = w.wine_id
		 inner join (select wine_id, sum(price) as TotalSalesRevenue
								  from items
								  group by wine_id) as wtsr on wtsr.wine_id = w.wine_id 
		  where 1 = 1";
 

// set the rowcount 

$rowCount = 1;
$result = '';

// build the query based on the parameters


if($searchWine <> "") {
	$query = $query . " and w.wine_name like " . "'%" . $searchWine . "%'";
}


if($searchWinery <> "") {
	$query = $query . " and win.winery_name like " . "'%" . $searchWinery . "%'";
}

if($searchRegion <> "") {
	$query = $query . " and reg.region_name like " . "'%" . $searchRegion . "%'";
}

if($searchVariety <> "") {
	$query = $query . " and gr.variety like " . "'%" . $searchVariety . "%'";
}

if($searchMinYear <> "") {
	$query = $query . " and w.year >= " . "'" . $searchMinYear . "'";
}

if($searchMaxYear <> "") {
	$query = $query . " and w.year <= " . "'" . $searchMaxYear . "'";
}


if($searchMinOrdered <> "") {
	$query = $query . " and totalSold >= " . $searchMinOrdered;
}


if($searchMinStock <> "") {
	$query = $query . " and i.on_hand >= " . $searchMinStock;
}


if($searchMinPrice <> "") {
	$query = $query . " and i.cost >= " . $searchMinPrice;
}

if($searchMaxPrice <> "") {
	$query = $query . " and i.cost <= " . $searchMaxPrice;
}




// complete the query
$query = $query . " group by  w.wine_name,  w.year, i.cost, i.on_hand, win.winery_name, wts.totalSold, wtsr.TotalSalesRevenue, reg.region_name;";



$rowCount = 0;


// store the query in a session object

		 
try {
		$stmt = $db->prepare($query);
		$stmt->execute();
		$rowCount = $stmt->rowCount();
		$res = $stmt->fetchAll();	
		$_SESSION['rowCount'] = $rowCount;
		$_SESSION['queryRes'] = $res;
}
		
catch(PDOException $e) {
	echo $e->getMessage();
}


if($rowCount >= 1 && $data) {
		$_SESSION['results'] = 'Here are the results that match your criteria: ';
	}
	
if($rowCount < 1 && $data) {
		$_SESSION['results'] = 'Sorry there were no results matching your criteria!';
	}


header( 'Location: results.php' ) ;

?>