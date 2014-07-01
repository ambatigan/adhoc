<?php
	
	//$filename = "intro.flv";
	$filename= $_GET['filename'];

//	$filename = realpath($filename); //server specific

	$file_extension = strtolower(substr(strrchr($filename,"."),1));
	if (! file_exists( $filename ) )
	{	
		die("NO FILE HERE");
	}
	switch( $file_extension )
	{
		case "pdf": $ctype="application/pdf"; break;
		case "exe": $ctype="application/octet-stream"; break;
		case "zip": $ctype="application/zip"; break;
		case "doc": $ctype="application/msword"; break;
		case "xls": $ctype="application/vnd.ms-excel"; break;
		case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
		case "gif": $ctype="image/gif"; break;
		case "png": $ctype="image/png"; break;
		case "jpe": case "jpeg":
		case "jpg": $ctype="image/jpg"; break;
		default: $ctype="application/force-download";
	}
     $download_file = basename($filename);
	 
	header("Pragma: public"); // required
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); // required for certain browsers
	header("Content-Type: $ctype");
	header("Content-Disposition: attachment; filename=\"$download_file\";" );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".@filesize($filename));
	@readfile("$filename") or die("File not found.");
	exit();

?> 