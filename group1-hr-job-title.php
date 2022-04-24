<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>Event Form DD </title>
  <link href="group1-hr-style.css" type="text/css" rel="stylesheet" />
</head>
<body>
<div class="container">
<?php
  $server = "localhost";
  $user = "cti110";
  $pw = "wtcc";
  $db = "hr";
  
  $title = $_POST['title'];
  $connect = mysqli_connect ($server, $user, $pw, $db);
  if(!$connect) {
	die("ERROR: Cannot connect to database $db on server $server using username $user (".mysqli_connect_errno().", ".mysqli_connect_error().")");
  }
  
  $jobQuery = "SELECT job_title FROM jobs";
  $jobResult = mysqli_query($connect, $jobQuery);
  if(!$jobResult) {
	die("Count not successfully run query ($userQuery) from $db: " . mysqli_error($connect) );
  }
  
  if(mysqli_num_rows($jobResult) == 0){
	print("No records found with query $userQuery");
  } else {
	print("<h1>List of $title</h1>");
	print("<table border = \"1\">");
	print("<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Job Code</th><th>Job Title</th><th>Salary</th></tr>");
	while($row = mysqli_fetch_assoc($jobResult)){
		if(strpos(strtolower($row['job_title']), strtolower($title)) !== false){
			$idQuery = "SELECT job_id FROM jobs WHERE job_title='$row[job_title]'";
			$idResult = mysqli_query($connect, $idQuery);
			if(!$idResult) {
				die("Count not successfully run query ($userQuery) from $db: " . mysqli_error($connect) );
			}
			if(mysqli_num_rows($idResult) == 0){
				print("No records found with query $userQuery");
			} else {
				while($row = mysqli_fetch_assoc($idResult)){
					$empQuery = "SELECT employee_id, first_name, last_name, job_id, salary FROM employees WHERE job_id=$row[job_id]";
					$empResult = mysqli_query($connect, $empQuery);
					if(!$empResult) {	
						die("Count not successfully run query ($userQuery) from $db: " . mysqli_error($connect) );
					} else {
						while($row = mysqli_fetch_assoc($empResult)){
							print ("<tr><td>".$row['employee_id']."</td><td>".$row['first_name']."</td><td>".$row['last_name']."</td><td>".$row['job_id']."</td>");
							$jobQuery2 = "SELECT job_title FROM jobs WHERE job_id=$row[job_id]";
							$jobResult2 = mysqli_query($connect, $jobQuery2);
							if(!$jobResult2) {
								die("Count not successfully run query ($userQuery) from $db: " . mysqli_error($connect) );
							}
							while($row2 = mysqli_fetch_assoc($jobResult2)){
								print ("<td>".$row2['job_title']."</td>");
							}
							print ("<td>$".$row['salary']."</td></tr>");
						}
					}
				}	
			}
		}
	}
	print("</table>");
  }
  mysqli_close($connect);
?>
</div>
</body>
</html>  