<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>HR last name or id number database search</title>
  <link href="group1-hr-style.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="container">
<?php
$server = "localhost";
$user = "cti110";
$pw = "wtcc";
$db = "hr";
  
$lastName = $_POST['lastName'];
$idNum = $_POST['idNum'];
$connect = mysqli_connect ($server, $user, $pw, $db);
if(!$connect) {
	die("ERROR: Cannot connect to database $db on server $server using username $user (".mysqli_connect_errno().", ".mysqli_connect_error().")");
}
  
$nameQuery = "SELECT employee_id, first_name, last_name, job_id, salary FROM employees";
$nameResult = mysqli_query($connect, $nameQuery);
if(!$nameResult) {
	die("Count not successfully run query ($userQuery) from $db: " . mysqli_error($connect) );
}
  
if(mysqli_num_rows($nameResult) == 0){
	print("No records found with query $userQuery");
} else {
	print("<h1>List of $lastName</h1>");
	print("<table border = \"1\">");
	print("<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Job Code</th><th>Job Title</th><th>Salary</th></tr>");
	while($row = mysqli_fetch_assoc($nameResult)){
		if(($lastName != null && strpos(strtolower($row['last_name']), strtolower($lastName)) !== false) || ($idNum != null && strpos(strtolower($row['employee_id']), strtolower($idNum)) !== false)){
			print ("<tr><td>".$row['employee_id']."</td><td>".$row['first_name']."</td><td>".$row['last_name']."</td><td>".$row['job_id']."</td>");
			$jobQuery = "SELECT job_title FROM jobs WHERE job_id=$row[job_id]";
			$jobResult = mysqli_query($connect, $jobQuery);
			if(!$jobResult) {
				die("Count not successfully run query ($userQuery) from $db: " . mysqli_error($connect) );
			}
			while($row2 = mysqli_fetch_assoc($jobResult)){
				print ("<td>".$row2['job_title']."</td>");
			}
			print ("<td>$".$row['salary']."</td></tr>");
		}
	}
	
	print("</table>");
}
mysqli_close($connect);
?>
</div>
</body>
</html>  