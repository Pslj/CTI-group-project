<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>HR last name or id number database search results</title>
  <link href="group1-hr-style.css" type="text/css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Archivo+Black&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Philosopher&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
<header>
	<h1>List of employees</h1>
</header>
<nav>
	<h2 class="gradient">Navigation</h2>
	<ul>
		<li><a href="group1-hr-job-title.html">Job Title Search</a></li>
		<li><a href="group1-hr-emp-search.html">Employee Search</a></li>
		<li><a href="group1-hr-departmentlocation.html">Department Search</a></li>
		<li><a href="group1-hr-fuel.html">Fuel Calculator</a></li>
		<li><a href="group1-hr-emp-search.html">Back</a></li>
	</ul>  
</nav>
<?php
include ("inc-connect-mariadb.php"); 
$connect=mysqli_connect($server, $user, $pw, $db); 
$lastName = $_POST['lastName'];
$idNum = $_POST['idNum'];
if(!$connect) {
	die("ERROR: Cannot connect to database $db on server $server using username $user (".mysqli_connect_errno().", ".mysqli_connect_error().")");
}
function Query($connect, $lastName, $idNum){
	if($lastName != '' && $idNum != ''){
		$nameQuery = "SELECT * FROM employees JOIN jobs ON employees.job_id = jobs.job_id WHERE ('$lastName'!='' AND last_name LIKE '$lastName%') AND ('$idNum'!='' AND employee_id LIKE '%$idNum%') ORDER BY employee_id";
	} else if($lastName != '' || $idNum != ''){
		$nameQuery = "SELECT * FROM employees JOIN jobs ON employees.job_id = jobs.job_id WHERE ('$lastName'!='' AND last_name LIKE '$lastName%') OR ('$idNum'!='' AND employee_id LIKE '%$idNum%') ORDER BY employee_id";
	} else {
		$nameQuery = "SELECT * FROM employees JOIN jobs ON employees.job_id = jobs.job_id WHERE (last_name LIKE '$lastName%') OR (employee_id LIKE '%$idNum%') ORDER BY employee_id";
	}
	$nameResult = mysqli_query($connect, $nameQuery);
	if(!$nameResult) {
		die("Count not successfully run query ($nameQuery) from $db: " . mysqli_error($connect) );
	} 
	if(mysqli_num_rows($nameResult) == 0){
		print("No records found with query");
	} else {
		print("<table border = \"1\"><caption>");
		if($lastName != null){
			print("last name starts with \"$lastName\"");
			if($idNum != null){
				print(" and ");
			}
		}
		if($idNum != null){
			print("id number contains \"$idNum\"");
		}
		if($idNum == null && $lastName == null){
			print("all employees");
		}
		print("</caption>");
		print("<thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Job Code</th><th>Job Title</th><th>Salary</th></tr></thead><tbody>");
		while($row = mysqli_fetch_assoc($nameResult)){
			print ("<tr><td>".$row['employee_id']."</td><td>".$row['first_name']."</td><td>".$row['last_name']."</td><td>".$row['job_id']."</td><td>".$row['job_title']."</td><td>$".number_format($row['salary'])."</td></tr>");
		}
		print("<tfoot><tr><td class=\"tableFooter\" colspan=\"100%\">End of Results</tr></td></tfoot></tbody></table>");
	}
	mysqli_close($connect);
}
Query($connect, $lastName, $idNum);
?>
<footer>
	<h3>Thank you for using this program - Programmer: Group1</h3>
</footer>
</div>
</body>
</html>  