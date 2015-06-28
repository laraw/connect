<?php
require_once('./php/dataaccess.php');
//require_once('./php/helper.php');

session_start();
$db = createDBConnection();



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
	$_SESSION['results'] = "You didn't search for anything!";
	
	header( 'Location: results.php' ) ;
}
	

// validation checks -- check that number values are actual numbers 
// if(!ctype_digit($searchMinStock) || !ctype_digit($searchMaxOrdered) || !ctype_digit($searchMinPrice) || !ctype_digit($searchMaxPrice))
// {
	// $data = false;
	// $_SESSION['results'] = "Not a valid number!";
	// header( 'Location: results.php' ) ;
// }

if($searchMinYear > $searchMaxYear) {
	$data = false;
	$_SESSION['results'] = "Your minimum year is greater than your maximum!";
	header( 'Location: results.php' ) ;
}


// the actual query

$query = "select  GROUP_CONCAT(DISTINCT(gr.variety)) as WineVarieties, w.wine_name as WineName,  
		 w.year as Year, i.cost as Cost, i.on_hand as Stock, win.winery_name as Winery, wts.totalSold as TotalSold, 
		 wtsr.TotalSalesRevenue as TotalSalesRevenue
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
$query = $query . " group by  w.wine_name,  w.year, i.cost, i.on_hand, win.winery_name, wts.totalSold, wtsr.TotalSalesRevenue;";



$rowCount = 0;


// store the query in a session object

		 
try {
	$stmt = $db->prepare($query);
	$stmt->execute();
	//res = $stmt->fetch(PDO::FETCH_ASSOC);
	$rowCount = $stmt->rowCount();
	$res = $stmt->fetchAll();
	
	 // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					// $rowCount++;
					
					// printf("%-40s %-20s %-20s %-20s %-20s %-20s %-20s \n", $row["WineName"], $row["WineVarieties"], $row["Year"], $row["Stock"], 
							// $row["Cost"], $row["TotalSold"], $row["TotalSalesRevenue"]);
					
					
	// }
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