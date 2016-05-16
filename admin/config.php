<?php
//$connect = mysql_connect('localhost', 'root', 'root');
//$connect = mysql_connect('localhost', 'root', '');

$db_host =      'liveipad.c30huakf13ge.us-west-2.rds.amazonaws.com:3306';     //RDS Endpoint...
$db_username =  'tomasusa';
$db_pass =      'tomasusa29';
$db_name =      'topcharleston'; 


$connect = mysql_connect("$db_host","$db_username","$db_pass");

//mysql_select_db("$db_name") or die("no database by that name");


mysql_select_db("$db_name", $connect) or die('Error:Database connection failed !');
//mysql_select_db('ronny', $connect) or die('Error:Database connection failed !');
mysql_set_charset('utf8',$connect);
ini_set('error_reporting',~E_NOTICE); 
?>
