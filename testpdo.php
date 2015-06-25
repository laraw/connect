<?php
require_once('./php/db.php');
try {
	$dsn = DB_ENGINE .':host='. DB_HOST .';dbname='. DB_NAME;
	$db = new PDO($dsn, DB_USER, DB_PW);
	// how can you change the sql query below to a prepared statement?
	$sql = "select region_id, region_name from region";
	foreach ($db­>query($sql) as $row) {
		print $row['region_id'] .' ­ '. $row['region_name'] . '<br />';
	}
	
	$db = null; // close the database connection
	} 
	catch(PDOException $e) {	
		echo $e­>getMessage();
	}
?>
