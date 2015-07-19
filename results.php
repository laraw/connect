<?php
session_start();
require_once ("./mini/MiniTemplator.class.php");
$t = new MiniTemplator;
$t->readTemplateFromFile ("results_template.htm");

if (isset($_SESSION['results'])) {
	$t->setVariable ("results", $_SESSION['results']);
}

// get the results from the session object from answers.php and form a table with the output
$res = $_SESSION['queryRes'];

foreach ($res as $results) {
    $t->setVariable ("WineName", htmlspecialchars($results["WineName"]));
    $t->setVariable ("WineVarieties", htmlspecialchars($results["WineVarieties"]));
	$t->setVariable ("Year", htmlspecialchars($results["Year"]));
	$t->setVariable ("Winery", htmlspecialchars($results["Winery"]));
	$t->setVariable ("Region", htmlspecialchars($results["Region"]));
	$t->setVariable ("Cost", htmlspecialchars($results["Cost"]));
	$t->setVariable ("TotalSold", htmlspecialchars($results["TotalSold"]));
	$t->setVariable ("TotalSalesRevenue", htmlspecialchars($results["TotalSalesRevenue"]));
	$t->setVariable ("Stock", htmlspecialchars($results["Stock"]));
	$t->addBlock ("block1");
}
$t->generateOutput();
?>
