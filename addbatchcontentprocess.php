<?php
/**
* Clean path
* @param string $path
* @return string
*/
function fm_clean_path($path)
{
$path = trim($path);
$path = trim($path, '\\/');
$path = str_replace(array('../', '..\\'), '', $path);
if ($path == '..') {
  $path = '';
}
return str_replace('\\', '/', $path);
}
$root_path =  ('C:/xampp/htdocs/test2storage');
$root_url = '';
$http_host = $_SERVER['HTTP_HOST'];
// get path
$p = isset($_GET['p']) ? $_GET['p'] : (isset($_POST['p']) ? $_POST['p'] : '');

// clean path
$p = fm_clean_path($p);
$storage = 'test2storage';



$is_https = isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1)
|| isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https';

// clean and check $root_path
$root_path = rtrim($root_path, '\\/');
$root_path = str_replace('\\', '/', $root_path);
if (!@is_dir($root_path)) {
echo "<h1>Root path \"{$root_path}\" not found!</h1>";
exit;
}

// clean $root_url
$root_url = fm_clean_path($root_url);
defined('FM_ROOT_PATH') || define('FM_ROOT_PATH', $root_path);
defined('FM_ROOT_URL') || define('FM_ROOT_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . (!empty($root_url) ? '/' . $root_url : ''));
defined('FM_SELF_URL') || define('FM_SELF_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . $_SERVER['PHP_SELF']);
define('FM_PATH', $p);




if (isset($_FILES['file']['name'])) {
$asset_dir = "C:/xampp/htdocs/test2storage/Assets";
if (0 < $_FILES['file']['error']) {
  echo 'Error during file upload' . $_FILES['file']['error'];
} else {
  if (file_exists('uploads/' . $_FILES['file']['name'])) {
    echo 'File already exists : uploads/';
  } else {
    move_uploaded_file($_FILES['file']['tmp_name'], "$asset_dir/". $_FILES['file']['name']);
    echo 'Generated';
  }
}
} else {
echo 'Please choose a file';
}


if (isset($_GET['generate_batch'])) {
$url = $_GET['directory'];
$p = $_GET['p'];
$preloader_duration = $_GET['duration'];
$preloader_i = $_GET['image'];
$asset_dir = "C:/xampp/htdocs/test2storage/Assets";


$preloader_i = str_replace( "\\", '/', $preloader_i );
$image = "http://"."$http_host/test2storage/Assets/".basename($preloader_i).$radio;



$filenames = array();
foreach (new DirectoryIterator($url) as $fileInfo) {
  $filenames[] = $fileInfo->getFilename();

  if($fileInfo->isDot()) continue;
  echo $fileInfo->getFilename() . "<br>       \n";



}
$array = $filenames;

foreach($array as $dir){
  $path = FM_ROOT_PATH;
  if (FM_PATH != '') {
   $path .= '/' . FM_PATH;
 }

 if( is_dir($path) === false ){
  mkdir($path);


} 


if (!file_exists("{$path}/{$dir}")) { 
  mkdir("{$path}/{$dir}");
  echo "error";                                                                               
}
$file_to_write = "/index.html";

$file_path=str_replace($_SERVER['DOCUMENT_ROOT'],'',$url);
$file_path='http://'.$_SERVER['HTTP_HOST'].$file_path.'/'.$dir;
 

$content_to_write = '<!DOCTYPE html>
<html>
<head>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title></title>
</head>
<style>
img {
  width: 50%;

}


</style>
<body style="background-color:#5d5d5d;">
<div style="margin-top:260px;"><center><img id="preloader" src="'.$image.'""></center><div>

<script
src="https://code.jquery.com/jquery-2.2.4.min.js"
integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
crossorigin="anonymous"></script>

<script>

$(document).ready(function() {
  $("#preloader").hide();
  $(window).load(function() {
    $("#preloader").fadeIn('.$preloader_duration.'*1000, function (){
      window.location= "'.$file_path.'"
    });
  });
});
</script>

</body>
</html>
</body>
</html>';

$file = fopen("{$path}/{$dir}". $file_to_write,"w");

// a different way to write content into
// fwrite($file,"Hello World.");

fwrite($file, $content_to_write);

// closes the file
fclose($file);



}
}




?>



