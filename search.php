<link href="./css/styles.css" rel="stylesheet">
<html>
<head>
</head>
<body>
<h1> Winestore search </h1>
<?php
session_start();
if (isset($_SESSION['error'])) {
	//echo '<p><b>' . var_dump($_SESSION['error']) . '</p></b>'; debug
	echo '<p><span id="error"> Errors Present please re-enter data</span></p>';
}
else {
    echo '<p> Please select your search parameters: </p>';
}

?>
<form action="answers.php" method="GET">
<h4> Wine Name: </h4> 
<?php 
if(isset($_SESSION['error']['winename'])) {
	echo '<span id="error">' . $_SESSION['error']['winename'] . '</span></br>';
}
?>
<input type="text" id="wineName" name="wineName">
<br />
<br />
<h4> Winery Name: </h4>
<?php 
if(isset($_SESSION['error']['wineryname'])) {
	echo '<span id="error">' . $_SESSION['error']['wineryname'] . '</span></br>';
}
?>
<input type="text" id="winery" name="winery">
<br />
<br />
<h4> Region </h4>
<?php
// get the region data from the database

include_once('./php/dataaccess.php');

$db = createDBConnection();
$regions = getRegions($db);

echo '<select id="region" name="region"> <option value=""> </option>';

/* Populate the region list */
foreach ($regions as $region) {
    echo '<option value="' . $region . '">' . $region . '</option>';
}
echo '</select>';
?>
<br />
<br />
<h4> Grape Variety </h4>
<?php
// get the grape variety data from the database
include_once('./php/dataaccess.php');

$varieties = getVariety($db);
echo '<select id="variety" name="variety">';
echo '<option value=""> </option>';

/* Populate the grape variety list */
foreach ($varieties as $variety) {
    echo '<option value="' . $variety . '">' . $variety . '</option>';
}
echo '</select>';
?>
<br />
<br />
<h4> Year </h4>
<?php 
if(isset($_SESSION['error']['year'])) {
	echo '<span id="error">' . $_SESSION['error']['year'] . '</span></br>';
}
?>
Min: 
<?php
// get the year data from the database
$years = array();
include_once('./php/dataaccess.php');

$years = getYearRange($db);
echo '<select id="minyear" name="minyear">';
echo '<option value=""> </option>';

/* Populate the region list */
foreach ($years as $year) {
    echo '<option value="' . $year . '">' . $year . '</option>';
}
echo '</select>';
?>
Max: 
<?php
// get the year data from the database
$years = array();
include_once('./php/dataaccess.php');

$years = getYearRange($db);
echo '<select id="maxyear" name="maxyear">';
echo '<option value=""> </option>';

/* Populate the region list */
foreach ($years as $year) {
    echo '<option value="' . $year . '">' . $year . '</option>';
}
echo '</select>';
?>
<br />
<br />
<h4> Min Stock </h4>
<?php 
if(isset($_SESSION['error']['stock'])) {
	echo '<span id="error">' . $_SESSION['error']['stock'] . '</span></br>';
}
?>
<input type="text" id="minStock" name="minStock" size="10">
<br />
<br />
<h4> Min Ordered </h4>
<?php 
if(isset($_SESSION['error']['ordered'])) {
	echo '<span id="error">' . $_SESSION['error']['ordered'] . '</span></br>';
}
?>
<input type="text" id="maxOrdered" name="minOrdered" size="10">
<br />
<br />
<h4> Price Range </h4>
<?php 
if(isset($_SESSION['error']['price'])) {
	echo '<span id="error">' . $_SESSION['error']['price'] . '</span></br>';
}
if (isset($_SESSION['error'])) {
 unset($_SESSION['error']);
}
?>
Min $:
<input type="text" id="minPrice" name="minPrice" size="10">
Max $:
<input type="text" id="maxPrice" name="maxPrice" size="10">
<br />
<br />
<input type="submit" name="submit">
</form>
</body>
</html>
