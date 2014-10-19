<?php 
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/utilities/session.php";
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/classes/utilities/mustache.php";
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/classes/noteTaking/Notes.php";
	
	$action = $_POST['action'];
	
	if($action == "loadNotes")
	{
		$notes = new Notes(floor($_SESSION['userID']), floor($_POST['classID']), floor($_POST['topicID']));
		$notes = $notes->buildNoteStructure();
		$template = file_get_contents(
				$_SERVER['DOCUMENT_ROOT']."/resources/components/noteView.html", false);
		echo $mustache->render($template, $notes);
	}
?>