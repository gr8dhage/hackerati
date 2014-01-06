<?php
$mysql_host = "mysql8.000webhost.com";
$mysql_database = "a9632462_hacker";
$mysql_user = "a9632462_user";
$mysql_password = "simple1";

$con=mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_database);
// Check connection
if (mysqli_connect_errno($con))
{
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}
else
{
	$r=("SELECT * FROM `a9632462_hacker`.`temperature` ORDER BY timestamp");
	$result=mysqli_query($con,$r);

	while($row = mysqli_fetch_array($result)){

	$date= strtotime($row['timestamp'])*1000;   //time in format 2013-03-21 16:23:11 
	$values=(int)$row['value'];
	$array[]=array($date, $values);
	}
	echo json_encode($array);
}
?>