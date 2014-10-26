<?php 
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/utilities/session.php";
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/classes/utilities/mustache.php";
	REQUIRE_ONCE $_SERVER['DOCUMENT_ROOT'] . "/resources/classes/noteTaking/NoteTopic.php";
	
	$action = $_POST['action'];
	
	if($action == "loadTopics")
	{
		$noteTopic = new NoteTopic($_SESSION['userID'], $_POST['classID']);
		$topics = $noteTopic->buildMenuStructure();
		$template = file_get_contents(
				$_SERVER['DOCUMENT_ROOT']."/resources/components/topicMenu.html", false);
		echo $mustache->render($template, $topics);
	}
	elseif($action == "editTopic")
	{
		$noteTopic = new NoteTopic($_SESSION['userID'], $_POST['classID']);
		$topic = $noteTopic->getTopic($_POST['topicID']);
		$template = file_get_contents(
				$_SERVER['DOCUMENT_ROOT']."/resources/components/editTopic.html", false);
		echo $mustache->render($template, $topic);
	}
	elseif($action == "updateTopic")
	{
		$noteTopic = new NoteTopic($_SESSION['userID'], $_POST['classID']);
		$topic = $noteTopic->updateTopic($_POST['topicID'], $_POST['name']);
	}
	elseif($action == "editTopic")
	{
		$noteTopic = new NoteTopic($_SESSION['userID'], $_POST['classID']);
		$topic = $noteTopic->hideTopic($_POST['topicID']);
	}
	elseif($action == "addTopic")
	{
		$noteTopic = new NoteTopic($_SESSION['userID'], $_POST['classID'], $_POST['parentID']);
		$noteTopic->addTopic($_POST['name']);
	}
	elseif($action == "hideTopic")
	{
		$noteTopic = new NoteTopic();
		$noteTopic->hideTopic($_POST['topicID']);
	}
?>