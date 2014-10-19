<?php 
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/utilities/session.php";
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/classes/utilities/mustache.php";
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/classes/noteTaking/NoteClass.php";
	
	$action = $_POST['action'];
	
	if($action == "loadClasses")
	{
		$noteClass = new NoteClass(floor($_SESSION['userID']));
		$classes = $noteClass->getClasses();
		$template = file_get_contents(
				$_SERVER['DOCUMENT_ROOT']."/resources/components/classSelector.html", false);
		echo $mustache->render($template, $classes);
	}
?>