<link href="./css/styles.css" rel="stylesheet">
<html>
<head>
</head>
<body>
<h1> Winestore search </h1>
<p> Please select your search parameters: </p>
<form> 
<h4> Wine Name: </h4>
<input type="text" id="wineName">
<br />
<br />
<h4> Winery Name: </h4>
<input type="text" id="winery">
<br />
<br />
<h4> Region </h4>
<?php 
$regionslist = array('Sydney','Melbourne','Brisbane','Tasmania','Adelaide','Perth','Darwin','ACT');
 
echo '<select name="regions">'; 
 
/* For each value of the array assign variable name "city" */
foreach($regionslist as $region){ 
 echo '<option value="' . $region . '">' . $region . '</option>';
} 
echo'</select>';
?>

<br />
<br />
<h4> Year </h4>
<br />
<br />
<h4> Min Stock </h4>
<br />
<br />
<h4> Max Ordered </h4>
<br />
<br />
<h4> Price Range </h4>
<p> Min </p>
<p> Max </p>
<br />
<br />

</form>

</body>
</html>