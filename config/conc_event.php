<?php
try
{
	$konpdo = new PDO('mysql:host=127.0.0.1:3309;dbname=db_e_learning;charset=utf8', 'root', '');
}
catch(Exception $e)
{
        die('Erreur : '.$e->getMessage());
}
