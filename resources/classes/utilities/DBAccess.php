<?php
require_once $_SERVER['DOCUMENT_ROOT']."/resources/utilities/session.php";
/**
 * 
 * @author Matthew Hatch
 * Class for accessing the database
 */
class DBAccess
{
	private $errorMessage; 
	private $db;
	
	/**
	 * 
	 * @param string $user
	 * @param string $password
	 */
	function __construct($user = "admin2khhnZ1", $password = "AHXtm7R4scYw")
	{
		$this->user = $user;
		$this->password = $password;
		$this->connect();
	}
	
	function __destruct()
	{	
		$this->disconnect();
	}
	
	/**
	 * connect to database
	 * @return string|PDO
	 */
	function connect()
	{
		try{
			$this->db = new PDO("mysql:host=localhost;dbname=notes", $this->user, $this->password);
		}
		catch(PDOException $ex){
			$this->errorMessage = "Error!: " . $ex->getMessage();
			return $this->errorMessage;
		}
		return $this->db;
	}
	
	/**
	 * disconnect form database
	 */
	function disconnect()
	{
		$this->db = null;
	}
	
	/**
	 * Returns the error message from the last failed query
	 * @return string
	 */
	function getErrorMessage()
	{
		return $this->errorMessage;
	}
	
	/**
	 * Run a query on the database, return query result if successful, returns error message if not successful
	 * @param string $sql - sql statement designate placeholders with ":" preceding the placeholder name
	 * @param unknown $params - parameters for place holders in sql statement in an associative array
	 * @param unknown $mode - possible values: 
	 * 							PDO::FETCH_ASSOC - associative array
	 * 							PDO::FETCH_OBJ - php object
	 * 							POD::BOTH - both associative array and php object
	 * 						
	 * @return string
	 */
	function getAllRows($sql = "", $params = array(), $mode = PDO::FETCH_ASSOC)
	{
		
		$success = true;
		$statement = null;
		try
		{
			$statement = $this->db->prepare($sql);
			$val = $statement->execute($params);
		}
		catch(PDOException $e)
		{
			$this->errorMessage = $e->getMessage();
			$success = false;
		}
		if($success)
		{
			$result =  $statement->fetchAll($mode);
			return $result;
		}
		return false;
	}
	
	/**
	 * 
	 * @param string $sql
	 * @param unknown $params
	 * @param unknown $mode
	 * @return string
	 */
	function getRow($sql = "", $params = array(), $mode = PDO::FETCH_ASSOC)
	{
	
		$success = true;
		$statement = null;
		try
		{
			$statement = $this->db->prepare($sql);
			$val = $statement->execute($params);
		}
		catch(PDOException $e)
		{
			$this->errorMessage = $e->getMessage();
			$success = false;
		}
		if($success)
		{
			$result =  $statement->fetch($mode);
			return $result;
		}
		return false;
	}
	
	function insert($table = "", $values = array())
	{
		$sql = "INSERT INTO ".$table;
		$columns = array();
		$vals = array();
		foreach($values AS $key => $value)
		{
			$vals[] = "'".$value."'";
			$columns[] = $key;
		}
		
		$sql.=" (".implode(",", $columns).") VALUES(".implode(",", $vals).")";
		$success = true;
		$statement = null;
		try 
		{
			$statement = $this->db->prepare($sql);
			$val = $statement->execute();
		}
		catch(PDOException $e)
		{
			$this->errorMessage = $e->getMessage();
			$success = false;
		}
		if($success)
		{
			return $this->db->lastInsertId();
		}
		return false;
	}
	
	function update($table = "", $values = array(), $conditions = array(), $params = array())
	{
		$sql = "UPDATE ".$table." SET ";
		foreach($values AS $key => $value)
		{
			if($value == "INCREMENT")
			{
				$sql .= $key . " = " . $key . " + 1 ";
			}
			else
			{
				$sql .= $key . " = " . "'".$value."'";
			}
		}
		if(sizeOf($params) > 0)
		{
			$sql .= " WHERE " . implode(" AND ", $conditions);
		}
		echo $sql;
		$success = true;
		$statement = null;
		try 
		{
			$statement = $this->db->prepare($sql);
			$val = $statement->execute($params);
		}
		catch(PDOException $e)
		{
			$this->errorMessage = $e->getMessage();
			$success = false;
		}
		if($success)
		{
			return true;
		}
		return false;
	}
	
	function delete($table = "", $params = array())
	{
		//TODO: implement to delete rows in the database
	}
}