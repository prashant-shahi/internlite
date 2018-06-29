<?php
session_start();
require_once("database.php");

if(isset($_SESSION['success']) && isset($_SESSION['student'])) {
	$studid = $_SESSION['student']; // Student id
	$internid = $_POST['internid'];

	$query = "SELECT COUNT(*) as count FROM internshipapps WHERE studentid='$studid' and internshipid='$internid'";
	$res = mysqli_query($db, $query);
	$first = mysqli_fetch_array($res);
	if($first['count'] == 0) {
		$insertquery = "INSERT INTO internshipapps(studentid,internshipid) values('$studid','$internid')";
		mysqli_query($db,$insertquery);

		$return_arr = array("success"=>"Successfully applied for Internship");
	}
	else {
		$return_arr = array("error"=>"Already applied for internship.");
	}
}
else {
	$return_arr = array("error"=>"no_session");
}

echo json_encode($return_arr);
?>