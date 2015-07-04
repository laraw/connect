<link href="./css/styles.css" rel="stylesheet">
<html>
<head>
</head>
<body>

<h1> Winestore Results </h1>
<?php
session_start();

if (isset($_SESSION['results'])) {
    echo '<p>' . $_SESSION['results'] . '</p>';
}


?>


<table>
<tr>
    <th>Wine Name</th>
    <th>Grape Varieties</th>
    <th>Year</th>
    <th>Winery</th>
	<th>Region</th>
    <th>Cost per Bottle</th>
    <th>Total Stock Sold</th>
    <th>Total Sales Revenue</th>
	<th> Stock Available </th>
  </tr>
<?php
// get the results from the session object from answers.php and form a table with the output
$res = $_SESSION['queryRes'];


foreach ($res as $results) {
    echo '<tr>';
    echo '<td>' . htmlentities($results["WineName"]) . '</td>';
    echo '<td>' . htmlentities($results["WineVarieties"]) . '</td>';
    echo '<td>' . htmlentities($results["Year"]) . '</td>';
    echo '<td>' . htmlentities($results["Winery"]) . '</td>';
    echo '<td>' . htmlentities($results["Region"]) . '</td>';
    echo '<td>$' . htmlentities($results["Cost"]) . '</td>';
    echo '<td>' . htmlentities($results["TotalSold"]) . '</td>';
    echo '<td>$' . htmlentities($results["TotalSalesRevenue"]) . '</td>';
    echo '<td>' . htmlentities($results["Stock"]) . '</td>';
    echo '</tr>';
}


?>

</table>

</body>
</html>