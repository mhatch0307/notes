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
	
	/**
	 * 
	 * @param number $userID
	 * @param number $classID
	 * @param number $parentID
	 */
	function __construct($userID = 0, $classID = 0, $parentID = 0)
	{
		$this->userID = $userID;
		$this->classID = $classID;
		$this->parentID = $parentID;
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
	function setParentID($topicID = 0)
	{
		$this->parentID = $topicID;
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
		$params[':userID'] = $this->userID;
		$params[':classID'] = $this->classID;
		
		$sql = "SELECT * 
				FROM 
					topics
				WHERE
				".implode(" AND ", $filters).
				" ORDER BY parent_id, topic_id";
		
		$result = $this->db->getAllRows($sql, $params);
		return $result;
	}
	
	function buildMenuStructure()
	{
		$topics = $this->getTopics();
		
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
				$currTopic->subTopics = array();
			}
			else
			{
				$subTopic = new StdClass();
				$subTopic->topicID = $topic['topic_id'];
				$subTopic->name = $topic['name'];
				$currTopic->subTopics[] = $subTopic;
			}
		}
		$result[] = $currTopic;
		$topics = new StdClass();
		$topics->topics = $result;
		return $topics;
	}
}

?>