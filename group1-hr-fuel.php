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
	<h2 class="gradient">Navigation</h2>
	<ul>
		<li><a href="group1-hr-job-title.html">Job Title Search</a></li>
		<li><a href="group1-hr-emp-search.html">Employee Search</a></li>
		<li><a href="group1-hr-departmentlocation.html">Department Search</a></li>
		<li><a href="group1-hr-fuel.html">Fuel Calculator</a></li>
		<li><a href="group1-hr-fuel.html">Back</a></li>
	</ul>  
</nav>
<?php
include ("inc-connect-mariadb.php"); 
$connect=mysqli_connect($server, $user, $pw, $db);   
$mpg = $_POST['mpg'];
$gas = $_POST['gas'];
$trip = $_POST['trip'];
print("The cost of the trip will be $".number_format($trip/$mpg*$gas,2)."!");

?>
<footer>
	<h3>Thank you for using this program! - Programmer: Group1</h3>
</footer>
</div>
</body>
</html>  
	