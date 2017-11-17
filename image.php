<?php
$upload_folder = "C:/xampp/htdocs/test2storage";
$dir = $_GET['dir'];
$file_name = str_replace("\\", "/", $dir );

$filename = basename($file_name);
$file_extension = strtolower(substr(strrchr($filename,"."),1));

switch( $file_extension ) {
   case "gif": $ctype="image/gif"; break;
   case "png": $ctype="image/png"; break;
   case "jpeg": $ctype="image/jpeg"; break;
   case "jpg": $ctype="image/jpeg"; break;
   case "xls": $ctype="application/vnd.ms-excel"; break;
   case "mp4": $ctype="video/mp4"; break;
   case "html" : $ctype="text/html"; break;
   case "mpeg" : $ctype=" video/mpeg"; break;
}


header('Content-type: ' . $ctype);
header('Content-Disposition: inline; filename="'.$_GET['name'].'"');
if (!readfile($file_name)) {
header('Content-type: text/html');
die("File Not Found");
}
?>