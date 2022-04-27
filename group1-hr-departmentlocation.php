<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>HR job title database search</title>
  <link href="group1-hr-style.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="container">
<?php
$server = "localhost";
$user = "cti110";
$pw = "wtcc";
$db = "hr";
  
$location = $_POST['location'];
$connect = mysqli_connect ($server, $user, $pw, $db);
if(!$connect) {
	die("ERROR: Cannot connect to database $db on server $server using username $user (".mysqli_connect_errno().", ".mysqli_connect_error().")");
}

//t1 table, c1 column1, c2 column2, p1 search string
function returnQuery($connect, $t1, $c1, $c2, $p1){
	$Query = "SELECT $c1, $c2 FROM $t1";
	$Result = mysqli_query($connect, $Query);
	$Results = array();
	if(!$Result) {
		die("Count not successfully run query ($Query) from $db: " . mysqli_error($connect) );
	}
	if(mysqli_num_rows($Result) != 0){
		while($row = mysqli_fetch_assoc($Result)){
			if($p1 != null && strpos(strtolower($row[$c1]), strtolower($p1)) !== false){
				//printQueryTest($connect, $t1, $c1, $c2, $row[$c1], $row[$c2], $row);
				array_push($Results, $row[$c2]);
			}
		}
	}
	return $Results;
}

function printQuery($connect, $dr){
	for ($i = 0; $i < sizeOf($dr); $i++) {
		$Query = "SELECT department_id, department_name, location_id FROM departments";
		$Result = mysqli_query($connect, $Query);
		if(!$Result) {
			die("Count not successfully run query ($Query) from $db: " . mysqli_error($connect) );
		}
		if(mysqli_num_rows($Result) != 0){
			
			while($row = mysqli_fetch_assoc($Result)){
				if($dr[$i] == $row['department_id']){
					print ("<td>".$row['department_id']."</td><td>".$row['department_name']."</td>");
					printDetail($connect, 'street_address', 'locations', 'location_id', $row);
					printDetail($connect, 'city', 'locations', 'location_id', $row);
					printDetail($connect, 'state_province', 'locations', 'location_id', $row);
					printDetail($connect, 'postal_code', 'locations', 'location_id', $row);
					printDetail($connect, 'country_id', 'locations', 'location_id', $row);
					print ("</tr>");
					
				}
			}
		}
	}
}

function printDetail($connect, $c, $t, $p1, $r){
	$Query = "SELECT $c FROM $t WHERE $p1='$r[$p1]'";
	$Result = mysqli_query($connect, $Query);
	if(!$Result) {
		die("Count not successfully run query ($Query) from $db: " . mysqli_error($connect) );
	}
	if(mysqli_num_rows($Result) != 0){
		while($row = mysqli_fetch_assoc($Result)){
			print ("<td>".$row[$c]."</td>");
		}
	}
}

function printQueryTest($connect, $t1, $c1, $c2, $p1, $p2, $r){
	print($t1." ".$c1." ".$c2." ".$p1." ".$p2.":"."<br>");
}
	
function printTable(){
	print("<table border = \"1\">");
	print("<tr><th>ID</th><th>Name</th><th>Street address</th><th>City</th><th>State/Province</th><th>Postal code</th><th>Country</th></tr>");	
}

print("<h1>List of departments in \"$location\"");
	
if(returnQuery($connect, 'locations', 'city', 'location_id', $location)){
	print(" by city</h1>");
	printTable();
	$LocationResults = returnQuery($connect, 'locations', 'city', 'location_id', $location);
	for ($k = 0; $k < sizeOf($LocationResults); $k++) {
		$DeptResults = returnQuery($connect, 'departments', 'location_id', 'department_id', $LocationResults[$k]);
		printQuery($connect, $DeptResults);
	}
} else if(returnQuery($connect, 'locations', 'state_province', 'location_id', $location)){
	print(" by state/province</h1>");
	printTable();
	$LocationResults = returnQuery($connect, 'locations', 'state_province', 'location_id', $location);
	for ($k = 0; $k < sizeOf($LocationResults); $k++) {
		$DeptResults = returnQuery($connect, 'departments', 'location_id', 'department_id', $LocationResults[$k]);
		printQuery($connect, $DeptResults);
	}
} else if(returnQuery($connect, 'countries', 'country_id', 'country_id', $location)){
	print(" by country id</h1>");
	printTable();
	$CountryResults = returnQuery($connect, 'countries', 'country_id', 'country_id', $location);
	for ($j = 0; $j < sizeOf($CountryResults); $j++) {
		$LocationResults = returnQuery($connect, 'locations', 'country_id', 'location_id', $CountryResults[$j]);
		for ($k = 0; $k < sizeOf($LocationResults); $k++) {
			$DeptResults = returnQuery($connect, 'departments', 'location_id', 'department_id', $LocationResults[$k]);
			printQuery($connect, $DeptResults);
		}
	}
} else if(returnQuery($connect, 'countries', 'country_name', 'country_id', $location)){
	print(" by country</h1>");
	printTable();
	$CountryResults = returnQuery($connect, 'countries', 'country_name', 'country_id', $location);
	for ($j = 0; $j < sizeOf($CountryResults); $j++) {
		$LocationResults = returnQuery($connect, 'locations', 'country_id', 'location_id', $CountryResults[$j]);
		for ($k = 0; $k < sizeOf($LocationResults); $k++) {
			$DeptResults = returnQuery($connect, 'departments', 'location_id', 'department_id', $LocationResults[$k]);
			printQuery($connect, $DeptResults);
		}
	}
} else if(returnQuery($connect, 'regions', 'region_name', 'region_id', $location)){
	print(" region</h1>");
	printTable();
	$RegionResults = returnQuery($connect, 'regions', 'region_name', 'region_id', $location);
	for ($i = 0; $i < sizeOf($RegionResults); $i++) {
		$CountryResults = returnQuery($connect, 'countries', 'region_id', 'country_id', $RegionResults[$i]);
		for ($j = 0; $j < sizeOf($CountryResults); $j++) {
			$LocationResults = returnQuery($connect, 'locations', 'country_id', 'location_id', $CountryResults[$j]);
			for ($k = 0; $k < sizeOf($LocationResults); $k++) {
				$DeptResults = returnQuery($connect, 'departments', 'location_id', 'department_id', $LocationResults[$k]);
				printQuery($connect, $DeptResults);
			}
		}
	}
} else {
	print("</h1>No records found with query $location");
}

	