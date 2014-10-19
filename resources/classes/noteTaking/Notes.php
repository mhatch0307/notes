<?php
require_once $_SERVER['DOCUMENT_ROOT']."/resources/classes/utilities/DBAccess.php";
require_once $_SERVER['DOCUMENT_ROOT']."/resources/classes/utilities/GlobalVariables.php";

/**
 * 
 * @author matthew
 * Notes class for pulling, creating and editing notes
 */ 
class Notes
{
	private $userID;
	private $classID;
	private $topicID;
	
	/**
	 * 
	 * @param number $userID
	 * @param number $classID
	 * @param number $topicID
	 */
	function __construct($userID = 0, $classID = 0, $topicID = 0)
	{
		$this->userID = $userID;
		$this->classID = $classID;
		$this->topicID = $topicID;
		$this->db = new DBAccess();
	}
	
	/**
	 * 
	 * @param number $userID
	 */
	function setUserID($userID = 0)
	{
		$this->userID = $userID;
	}
	
	/**
	 * 
	 * @param number $classID
	 */
	function setClassID($classID = 0)
	{
		$this->classID = $classID;
	}
	
	/**
	 * 
	 * @param number $topicID
	 */
	function setTopicID($topicID = 0)
	{
		$this->topicID = $topicID;
	}
	
	/**
	 * 
	 * @param number $parentID
	 */
	function setParentID($parentID = 0)
	{
		$this->parentID = $parentID;
	}
	
	/**
	 * 
	 * @param unknown $filters
	 * @param unknown $params
	 */
	function getNotes($filters = array(), $params = array())
	{
		$filters[] = "user_id=:userID";
		$filters[] = "class_id=:classID";
		$filters[] = "topic_id=:topicID";
		
		$params[':userID'] = $this->userID;
		$params[':classID'] = $this->classID;
		$params[':topicID'] = $this->topicID;
		
		$sql = "SELECT * 
				FROM 
					notes
				WHERE
				".implode(" AND ", $filters)
				." ORDER BY note_order";
		
		$result = $this->db->getAllRows($sql, $params);
		return $result;
	}
	
	function buildNoteStructure()
	{
		$notes = $this->getNotes();
		
		$result = array();
		$currParent = null;
		$indentCount = 0;
		$prevParent = 0;
		$olCount = 0;
		$closeTags = array();
		foreach($notes AS $note)
		{
			$tempNote = new stdClass();
			if($note['parent_id'] == $note['note_id'])
			{
				$indentCount = 0;
				$tempNote->startList = "";
				for($i = 0; $i < $olCount; $i++)
				{
					$tempNote->startList .= "</ol>";
				}
				$bulletType = GlobalVariables::getType($indentCount);
				$tempNote->startList .= "<li type = '".$bulletType."'>";
				$prevParent = 0;
			}
			elseif($note['parent_id'] > $prevParent)
			{
				$indentCount++;
				$prevParent = $note['parent_id'];
				$bulletType = GlobalVariables::getType($indentCount);
				$tempNote->startList = "<ol><li type ='".$bulletType."'>";
				$olCount++;
			}
			elseif($note['parent_id'] < $prevParent)
			{
				$indentCount--;
				$prevParent = $note['parent_id'];
				$bulletType = GlobalVariables::getType($indentCount);
				$tempNote->startList = "</ol><li type = '".$bulletType."'>";
				$olCount--;
			}
			else
			{
				$bulletType = GlobalVariables::getType($indentCount);
				$tempNote->startList = "<li type = '".$bulletType."'>";
			}
			$tempNote->endList = "</li>";
			$tempNote->keyTerm = $note['key_term'];
			$tempNote->description = $note['description'];
			$tempNote->noteID = $note['note_id'];
			$tempNote->topicID = $note['topic_id'];
			$tempNote->noteOrder = $note['note_order'];
			$tempNote->indentCount = $indentCount;
			$result[] = $tempNote;
		}
		$notes = new StdClass();
		$notes->notes = $result;
		return $notes;
	}
}

?>