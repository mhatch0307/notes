<?php
	class GlobalVariables
	{
		function getType($num)
		{	
			$listTypeOrder = array("I", "i", "A", "a", "1", "disc", "circle", "square");
			return $listTypeOrder[($num)%8];
		}
	}
?>