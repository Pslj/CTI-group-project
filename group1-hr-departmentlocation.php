<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>HR department database search results</title>
  <link href="group1-hr-style.css" type="text/css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Philosopher&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
<header>
	<h1>List of departments</h1>
</header>
<nav>
	<h2>Navigation</h2>
	<ul>
		<li><a href="group1-hr-job-title.html">Job Title Search</a></li>
		<li><a href="group1-hr-emp-search.html">Employee Search</a></li>
		<li><a href="group1-hr-departmentlocation.html">Department Search</a></li>
	</ul>  
</nav>
<?php
include ("inc-connect-mariadb.php"); 
$connect=mysqli_connect($server, $user, $pw, $db);   
$location = $_POST['location'];
$deptName = $_POST['deptName'];
if(!$connect) {
	die("ERROR: Cannot connect to database $db on server $server using username $user (".mysqli_connect_errno().", ".mysqli_connect_error().")");
}
function Query($connect, $location, $deptName){
	$deptQuery = "SELECT * FROM departments JOIN locations ON departments.location_id = locations.location_id WHERE (('$deptName'!='' AND departments.department_name LIKE '%$deptName%') OR ('$location'!='' AND (locations.street_address LIKE '%$location%' OR locations.postal_code LIKE '%$location%' OR locations.city LIKE '%$location%' OR locations.state_province LIKE '%$location%' OR locations.country_id LIKE '%$location%'))) ORDER BY department_id";
	$deptResult = mysqli_query($connect, $deptQuery);
	if(!$deptResult) {
		die("Count not successfully run query ($deptQuery) from $db: " . mysqli_error($connect) );
	} 
	if(mysqli_num_rows($deptResult) == 0){
		print("No records found with query");
	} else {
		print("<table border = \"1\">");
		print("<caption>");
			if($deptName != null){
				print("dept name contains \"$deptName\"");
				if($location != null){
					print(" or ");
				} else {
					print("</caption>");
				}
			} 
			if($location != null){
				print("location contains \"$location\"</caption>");
			}
			
		print("<thead><tr><th>ID</th><th>Name</th><th>Street address</th><th>City</th><th>State/Province</th><th>Postal code</th><th>Country</th></tr></thead>");	
		while($row = mysqli_fetch_assoc($deptResult)){	
			print("<tr><td>".$row['department_id']."</td><td>".$row['department_name']."</td><td>".$row['street_address']."</td><td>".$row['city']."</td><td>".$row['state_province']."</td><td>".$row['postal_code']."</td><td>".$row['country_id']."</td></tr>");
		}
		print("</table>");
	}
}
Query($connect, $location, $deptName);

?>
<footer>
	<p class="white">Thank you for using this program! - Programmer: Group1</p>
</footer>
</div>
</body>
</html>  
	