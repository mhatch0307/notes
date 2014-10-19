<?php
require_once $_SERVER['DOCUMENT_ROOT']."/resources/classes/utilities/DBAccess.php";
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$db = new DBAccess();
	
	$sql = "SELECT * 
			FROM 
				users
			WHERE
				username=:username AND
				password=:password";
	
	$params = array(
		":username"=>$username,
		":password"=>$password
	);
	
	$result = $db->getRow($sql, $params);
	if($result != false)
	{
		$_SESSION['userID'] = $result['user_id'];
		$_SESSION['fname'] = $result['fname'];
		$_SESSION['lname'] = $result['lname'];
		header('location: ../../home.php');
	}

?>