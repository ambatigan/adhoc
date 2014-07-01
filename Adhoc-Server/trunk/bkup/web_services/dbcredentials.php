<?php
class dbcredentails {
  var $host=DB_HOST;
  var $user=DB_USER;//"root";
  var $password=DB_PASSWORD;//"root";
  var $dbname=DB_NAME;

  function getVar($name)
  {
     return $this->{$name};
  }
}
?>