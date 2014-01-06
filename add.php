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
	$query="INSERT INTO `a9632462_hacker`.`temperature` (`value`, `timestamp`) VALUES ('".$_REQUEST['value']."', NOW())";
	$result = mysqli_query($con,$query);
	if($result == TRUE)
	{
		$arr = array('success' => '1');
		echo json_encode($arr);
	}
	else
	{
		$arr = array('success' => '0');
		echo json_encode($arr);
	}
}
mysqli_close($con);

?>