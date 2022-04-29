<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>HR job title database search results</title>
  <link href="group1-hr-style.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="container">
<header>
	<h1>List of Employees by Job Title</h1>
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
$title = $_POST['title'];
if(!$connect) {
	die("ERROR: Cannot connect to database $db on server $server using username $user (".mysqli_connect_errno().", ".mysqli_connect_error().")");
} 

function printAverage($connect, $title){
	$query = "SELECT AVG(salary) FROM employees JOIN jobs ON employees.job_id = jobs.job_id WHERE ('$title'!='' AND job_title LIKE '%$title%') ORDER BY employee_id";
	$result = mysqli_query($connect, $query);
	if(!$result) {
		die("Count not successfully run query ($nameQuery) from $db: " . mysqli_error($connect) );
	} 
	if(mysqli_num_rows($result) == 0){
		print("No records found with query");
	} else {
		while($row = mysqli_fetch_assoc($result)){
			print("$".number_format($row['AVG(salary)']));
		}
	}
}

function printTable($connect, $title){
	$query = "SELECT * FROM employees JOIN jobs ON employees.job_id = jobs.job_id WHERE ('$title'!='' AND job_title LIKE '%$title%') ORDER BY employee_id";
	$result = mysqli_query($connect, $query);
	if(!$result) {
		die("Count not successfully run query ($nameQuery) from $db: " . mysqli_error($connect) );
	} 
	if(mysqli_num_rows($result) == 0){
		print("No records found with query");
	} else {
		print("<table border = \"1\">");
		print("<caption>");
		print("average salary of jobs with titles containing \"$title\" ");
		printAverage($connect, $title);
		print("</caption><thead><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Job Code</th><th>Job Title</th><th>Salary</th></tr></thead><tbody>");
		while($row = mysqli_fetch_assoc($result)){
			print ("<tr><td>".$row['employee_id']."</td><td>".$row['first_name']."</td><td>".$row['last_name']."</td><td>".$row['job_id']."</td><td>".$row['job_title']."</td><td>$".number_format($row['salary'])."</td></tr>");
		}
		print("</tbody></table>");
	}
}
printTable($connect, $title);

mysqli_close($connect);
?>
<footer>
	<p class="white">Thank you for using this program - Programmer is: Group1</p>
</footer>
</div>
</body>
</html>  