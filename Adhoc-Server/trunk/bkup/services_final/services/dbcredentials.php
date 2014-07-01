<?php
class dbcredentails {
  var $host 	= 			DB_HOST;//"localhost";
  var $user 	=			DB_USER_NAME;//"TatvaPhP";//"root";
  var $password = 			DB_PASSWORD;//"TatvaPhP";//"root";
  var $dbname	=			DB_NAME;//"huurree";

  function getVar($name){
     return $this->{$name};
  }
}
?>