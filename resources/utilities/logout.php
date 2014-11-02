<?php
	require_once $_SERVER['DOCUMENT_ROOT']."/resources/utilities/session.php";
	unset($_SESSION['userID']);
	unset($_SESSION['fname']);
	unset($_SESSION['lname']);
	session_destroy();
	header('Location: ../../index.php');
?>