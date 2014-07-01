<?php
$dbobj = new dbcredentails();
// to connect to database
$con = mysql_connect($dbobj->getVar('host'),$dbobj->getVar('user'),$dbobj->getVar('password'));
if (!$con)
{
  die('Could not connect: ' . mysql_error());
}
mysql_select_db($dbobj->getVar('dbname'));
mysql_query("SET NAMES utf8");
?>