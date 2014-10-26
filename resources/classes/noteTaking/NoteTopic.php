<?php
require_once $_SERVER['DOCUMENT_ROOT']."/resources/classes/utilities/DBAccess.php";

/**
 * 
 * @author matthew
 * Notes class for pulling, creating and editing notes
 */ 
class NoteTopic
{
	private $userID;
	private $classID;
	private $parentID;
	private static $tableName = "topics";
	
	/**
	 * 
	 * @param number $userID
	 * @param number $classID
	 * @param number $parentID
	 */
	function __construct($userID = 0, $classID = 0, $parentID = 0)
	{
		$this->db = new DBAccess();
		$this->userID = $userID;
		$this->classID = $classID;
		$this->parentID = $parentID;
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
	function setParentID($topicID = 0)
	{
		$this->parentID = $topicID;
	}
	
	/**
	 * 
	 * @param int $topicID
	 * @return Ambigous <string, boolean, unknown>
	 */
	function getTopic($topicID)
	{
		$sql = "SELECT * 
				FROM 
					topics
				WHERE
				topic_id = ".$topicID;
		$result = $this->db->getRow($sql);
		return $result;
	}
	
	/**
	 * 
	 * @param int $topicID
	 * @param string $name
	 */
	function updateTopic($topicID, $name)
	{
		$result = $this->db->update("topics", array("name"=>$name),
				array("topic_id = :topic_id"),
				array(":topic_id"=>$topicID));
		return $result;
	}
	
	/**
	 * 
	 * @param unknown $filters
	 * @param unknown $params
	 */
	function getTopics($filters = array(), $params = array())
	{
		$filters[] = "user_id = :userID";
		$filters[] = "class_id = :classID";
		$filters[] = "visible = :visible";
		$params[':userID'] = $this->userID;
		$params[':classID'] = $this->classID;
		$params[':visible'] = 1;
		$sql = "SELECT * 
				FROM 
					topics
				WHERE
				".implode(" AND ", $filters).
				" ORDER BY parent_id, topic_id";
		$result = $this->db->getAllRows($sql, $params);
		$result = (sizeOf($result) == 0)? false : $result;
		return $result;
	}
	
	function buildMenuStructure()
	{
		$topics = $this->getTopics();
		if($topics == false){
			return false;
		}
		$result = array();
		$currTopic = null;
		foreach($topics AS $topic)
		{
			if($topic['parent_id'] == $topic['topic_id'])
			{
				if($currTopic != null)
				{
					$result[] = $currTopic;
				}
				$currTopic = new StdClass();
				$currTopic->topicID = $topic['topic_id'];
				$currTopic->name = $topic['name'];
				$currTopic->classID = $topic['class_id'];
				$currTopic->subTopics = array();
			}
			else
			{
				$subTopic = new StdClass();
				$subTopic->topicID = $topic['topic_id'];
				$subTopic->name = $topic['name'];
				$subTopic->classID = $topic['class_id'];
				$subTopic->parentID = $topic['parent_id'];
				$currTopic->subTopics[] = $subTopic;
			}
		}
		$result[] = $currTopic;
		$topics = new StdClass();
		$topics->topics = $result;
		return $topics;
	}
	
	/**
	 *
	 * @param int $topicID
	 */
	function hideTopic($topicID)
	{
		$result = $this->db->update("topics", array("visible"=>0),
				array("topic_id = :topic_id"),
				array(":topic_id"=>$topicID));
		return $result;
	}
	
	function addTopic($name)
	{
		$result = $this->db->insert("topics", array("name"=>$name, 
													"user_id"=>$this->userID, 
													"class_id"=>$this->classID,
													"parent_id"=>$this->parentID));
		if($this->parentID == 0)
		{
			$this->parentID = $result;
			$result = $this->db->update("topics", array("parent_id"=>$result),
				array("topic_id = :topic_id"),
				array(":topic_id"=>$result));
		}
	}
}

?>