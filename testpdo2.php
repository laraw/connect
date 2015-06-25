<link href="./css/styles.css" rel="stylesheet">

<?php
include_once('./php/db.php');

echo '<h1> Test </h1>';

$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME;
$db = new PDO($dsn, DB_USER, DB_PW);
//$db = new PDO('mysql:host=localhost;dbname=winestore', 'root', 'M3gap33k1');
$query = "select * from region";

///echo var_dump($db);
$stmt = $db->query($query);
$row_count = $stmt->rowCount();
echo $row_count.' rows selected';
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['region_id'].' '.$row['region_name'].'<br/>';
}
//foreach($db­>query('SELECT region_id FROM region') as $row) {
//	print $row['region_id']; //. ' ' . $row['region_name'] . '<br />';
//print "test";
//}
$db = NULL; // close the database connection

?> 
