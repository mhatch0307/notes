<?php
require_once $_SERVER['DOCUMENT_ROOT']."/resources/classes/utilities/DBAccess.php";

/**
 * 
 * @author matthew
 * Notes class for pulling, creating and editing notes
 */ 
class NoteClass
{
	private $userID;
	private $classID;
	
	/**
	 * 
	 * @param number $userID
	 * @param number $classID
	 */
	function __construct($userID = 0, $classID = 0)
	{
		$this->userID = $userID;
		$this->classID = $classID;
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
	 * @param unknown $filters
	 * @param unknown $params
	 */
	function getClasses($filters = array(), $params = array())
	{
		$filters[] = "user_id=:userID";
		$params[':userID'] = $this->userID;
		
		$sql = "SELECT * 
				FROM 
					classes
				WHERE
				".implode(" AND ", $filters);
		
		$result = $this->db->getAllRows($sql, $params);
		$classes = new StdClass();
		$classes->classes = $result;
		return $classes;
	}
}

?>