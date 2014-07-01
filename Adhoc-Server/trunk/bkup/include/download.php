<?php
	$file_name = explode('/', $_GET['path']);
	header("Content-Type: application/octet-stream");
   	header("Content-Disposition: attachment; filename=".$file_name[count($file_name)-1]);
   	readfile($_GET['path']);
?>