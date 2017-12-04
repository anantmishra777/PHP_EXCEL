<?php
	try
	{
		$conn = new PDO("mysql:host=localhost; dbname=test_db; charset=utf8", "root", "");  //change values accordingly
    		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e)
	{
		echo "-------------".$e->getMessage()."-------------";
	}
    	define('EOL', (PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
?>
