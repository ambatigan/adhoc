<?php

$dbobj = new dbcredentails();

// to connect to database

$con = mysql_connect($dbobj->getVar('host'),$dbobj->getVar('user'),$dbobj->getVar('password'));

/*mysql_set_charset('utf8',$con);*/
//mysql_set_charset('utf8',$con);

/*mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'", $con);

$re = mysql_query('SHOW VARIABLES LIKE "%character_set%";')or die(mysql_error());
while ($r = mysql_fetch_assoc($re)) {var_dump ($r); echo "<br />";} exit;*/



if (!$con)
{
	die('Could not connect: ' . mysql_error());
}
mysql_select_db($dbobj->getVar('dbname'));

?>