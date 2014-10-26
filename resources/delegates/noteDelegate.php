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
	elseif($action == "updateKeyTerm")
	{
		$notes = new Notes();
		$notes->updateKeyTerm($_POST['noteID'], $_POST['keyTerm']);
	}
	elseif($action == "updateDescription")
	{
		$notes = new Notes();
		$notes->updateDescription($_POST['noteID'], $_POST['description']);
	}
	elseif($action == "insertNote")
	{
		$notes = new Notes($_SESSION['userID'], $_POST['classID'], $_POST['topicID']);
		$notes->insertNote(	$_POST['keyTerm'],  
							$_POST['noteOrder'], 
							$_POST['parentID']);
	}
?>