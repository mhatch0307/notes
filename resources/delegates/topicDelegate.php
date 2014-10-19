<?php 
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/utilities/session.php";
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/classes/utilities/mustache.php";
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/classes/noteTaking/NoteTopic.php";
	
	$action = $_POST['action'];
	
	if($action == "loadTopics")
	{
		$noteTopic = new NoteTopic(floor($_SESSION['userID']), floor($_POST['classID']));
		$topics = $noteTopic->buildMenuStructure();
		$template = file_get_contents(
				$_SERVER['DOCUMENT_ROOT']."/resources/components/topicMenu.html", false);
		echo $mustache->render($template, $topics);
	}
?>