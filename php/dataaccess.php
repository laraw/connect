<?php 
require_once('db.php');

// function for getting drop down list with regions
$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
$db = new PDO($dsn, DB_USER, DB_PW);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

// function for getting grape variety for drop down list
$regions = array();

try {
    //connect as appropriate as above
    $db->query('select region_name from region;'); 
	
} catch(PDOException $ex) {
    $error = $error + ' ' + ($ex->getMessage());
}



// function for getting the year range

$query = 'select min(year), max(year) from wine;'


// get results for results page

$query = '	select gr.variety, w.wine_name,  w.year, i.cost, i.on_hand, win.winery_name, wts.totalSold, wtsr.TotalSalesRevenue
			from wine w
			inner join wine_variety wv on w.wine_id = wv.wine_id
			inner join grape_variety gr on wv.variety_id = gr.variety_id
			inner join inventory i on w.wine_id = i.wine_id
			inner join winery win on w.winery_id = win.winery_id
			inner join (select sum(qty) as totalSold, wine_id 
						from items
						group by wine_id) as WTS on wts.wine_id = w.wine_id
			inner join (select wine_id, sum(price) as TotalSalesRevenue
						from items
						group by wine_id) as WTSR on wtsr.wine_id = w.wine_id;'


?>