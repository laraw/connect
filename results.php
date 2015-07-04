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
    echo '<td>' . htmlspecialchars($results["WineName"]) . '</td>';
    echo '<td>' . htmlspecialchars($results["WineVarieties"]) . '</td>';
    echo '<td>' . htmlspecialchars($results["Year"]) . '</td>';
    echo '<td>' . htmlspecialchars($results["Winery"]) . '</td>';
    echo '<td>' . htmlspecialchars($results["Region"]) . '</td>';
    echo '<td>$' . htmlspecialchars($results["Cost"]) . '</td>';
    echo '<td>' . htmlspecialchars($results["TotalSold"]) . '</td>';
    echo '<td>$' . htmlspecialchars($results["TotalSalesRevenue"]) . '</td>';
    echo '<td>' . htmlspecialchars($results["Stock"]) . '</td>';
    echo '</tr>';
}


?>

</table>

</body>
</html>