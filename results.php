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
    <th>Cost per Bottle</th>
    <th>Total Stock Sold</th>
    <th>Total Sales Revenue</th>
  </tr>
<?php
// get the results from the session object from answers.php and form a table with the output

die();
?>
<tr>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>

</body>
</html>