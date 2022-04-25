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
  
$title = $_POST['title'];
$connect = mysqli_connect ($server, $user, $pw, $db);
if(!$connect) {
	die("ERROR: Cannot connect to database $db on server $server using username $user (".mysqli_connect_errno().", ".mysqli_connect_error().")");
}
  
function returnQuery($c, $q1, $q2, $p1){
	$AverageSalary = 0;
	$Query = "SELECT $q1 FROM $q2";
	$Result = mysqli_query($c, $Query);
	if(!$Result) {
		die("Count not successfully run query ($Query) from $db: " . mysqli_error($c) );
	}
	if(mysqli_num_rows($Result) == 0){
		print("No records found with query $userQuery");
	} else {
		while($row = mysqli_fetch_assoc($Result)){
			if(strpos(strtolower($row[$q1]), strtolower($p1)) !== false){
				returnQuery2($c, 'job_id', $q2, $q1, $row, $row[$q1]);
			}
		}
	}
}
  
function returnQuery2($c, $q1, $q2, $p1, $r, $title){
	$Total = 0;
	$Query = "SELECT $q1 FROM $q2 WHERE $p1='$r[$p1]'";
	$Result = mysqli_query($c, $Query);
	if(!$Result) {
		die("Count not successfully run query ($Query) from $db: " . mysqli_error($c) );
	}
	if(mysqli_num_rows($Result) == 0){
		print("No records found with query $userQuery");
	} else {
		while($row = mysqli_fetch_assoc($Result)){
			returnAverage($c, 'AVG(salary)', 'employees', $q1, $row, $title);
		}
	}
}
  
function returnAverage($c, $q1, $q2, $p1, $r, $title){
	$Query = "SELECT $q1 FROM $q2 WHERE $p1='$r[$p1]'";
	$Result = mysqli_query($c, $Query);
	if(!$Result) {
		die("Count not successfully run query ($Query) from $db: " . mysqli_error($c) );
	}
	if(mysqli_num_rows($Result) == 0){
		print("No records found with query $userQuery");
	} else {
		$row = mysqli_fetch_assoc($Result);
		print("<p>Average salary of ".$title.": $".number_format($row[$q1], 2)."</p>");
	}
}
  
//get a list of all the job titles
$jobQuery = "SELECT job_title FROM jobs";
$jobResult = mysqli_query($connect, $jobQuery);
if(!$jobResult) {
	die("Count not successfully run query ($userQuery) from $db: " . mysqli_error($connect) );
}
  
if(mysqli_num_rows($jobResult) == 0){
	print("No records found with query $userQuery");
} else {
	//create the header and table
	print("<h1>List of $title</h1>");
	returnQuery($connect, 'job_title', 'jobs', $title);
	print("<table border = \"1\">");
	print("<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Job Code</th><th>Job Title</th><th>Salary</th></tr>");
	//cycle through list of job titles
	while($row = mysqli_fetch_assoc($jobResult)){
		//check if job title contains the form data
		if(strpos(strtolower($row['job_title']), strtolower($title)) !== false){
			//get job ids that match the job title (should be just one)
			$idQuery = "SELECT job_id FROM jobs WHERE job_title='$row[job_title]'";
			$idResult = mysqli_query($connect, $idQuery);
			if(!$idResult) {
				die("Count not successfully run query ($userQuery) from $db: " . mysqli_error($connect) );
			}
			if(mysqli_num_rows($idResult) == 0){
				print("No records found with query $userQuery");
			} else {
				//cycle through matching job ids (should be just one)
				while($row = mysqli_fetch_assoc($idResult)){
					//select employees with matching job id
					$empQuery = "SELECT employee_id, first_name, last_name, job_id, salary FROM employees WHERE job_id=$row[job_id]";
					$empResult = mysqli_query($connect, $empQuery);
					if(!$empResult) {	
						die("Count not successfully run query ($userQuery) from $db: " . mysqli_error($connect) );
					} else {
						//cycle through employees with matching job id
						while($row = mysqli_fetch_assoc($empResult)){
							//populate table with employee id first name, last name, and, job id
							print ("<tr><td>".$row['employee_id']."</td><td>".$row['first_name']."</td><td>".$row['last_name']."</td><td>".$row['job_id']."</td>");
							//select job title from jobs which matches job id (should be just one)
							$jobQuery2 = "SELECT job_title FROM jobs WHERE job_id=$row[job_id]";
							$jobResult2 = mysqli_query($connect, $jobQuery2);
							if(!$jobResult2) {
								die("Count not successfully run query ($userQuery) from $db: " . mysqli_error($connect) );
							}
							//cycle through matching job titles (should be just one)
							while($row2 = mysqli_fetch_assoc($jobResult2)){
								//populate table with job title
								print ("<td>".$row2['job_title']."</td>");
							}
							//populate table with salary
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