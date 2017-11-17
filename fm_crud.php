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









if (isset($_POST['action'])){
  // print_r($_POST);
  $file_to_write = $_POST['namecon'];
  $url = $_POST['urlcon'];
  $choose =  $_POST['choose'];
  $duration =  $_POST['timecon'];

  // $browse =  $_POST['imgcon'];
  $image  = "";

if (isset($_FILES['imgcon']) && $_FILES['imgcon']['name'] != ''){

  $image = $_FILES['imgcon']['name'];
  $asset_dir = "C:/xampp/htdocs/test2storage/Assets";
  move_uploaded_file($_FILES['imgcon']['tmp_name'], "$asset_dir/".$_FILES['imgcon']['name']);
}else {
  $image = $_POST['group1'];
}
  $image = "http://"."$http_host/test2storage/Assets/".$image;
  $content_to_write_html = '<!DOCTYPE html>
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
        $("#preloader").fadeIn('.$duration.'*1000, function (){
        window.location="'.$url.'"
        });
      });
  });
  </script>

  </body>
  </html>
  </body>
  </html>';


  $content_to_write_mp4 = '<p><video controls  src="image.php?name=' . $url. ' "" controls></video></p>';
  $path = FM_ROOT_PATH;
  if (FM_PATH != '') {
     $path .= '/' . FM_PATH;
  }

  if( is_dir($path) === false ){
      mkdir($path);
  } 


  // create subject subfolder
  if (!file_exists("{$path}/{$file_to_write}")) {
      mkdir("{$path}/{$file_to_write}");
  }


  switch ($choose) {
    case 'html':
      $file_to_write .= "/index.html";
      $file = fopen($path . '/' . $file_to_write,"w");

      fwrite($file, $content_to_write_html);

      // closes the file
      fclose($file);
       
      break;

    case 'swf':
      $file_to_write .= "/index.html";
      $file = fopen($path . '/' . $file_to_write,"w");

      fwrite($file, $content_to_write_mp4);
      break;

    case 'mp4':
      $file_to_write .= "/index.html";
      $file = fopen($path . '/' . $file_to_write,"w");

      fwrite($file, $content_to_write_html);
      break;

    case 'bulk upload':
    foreach ($file_to_write as $k) {
      $k .= "/index.html";
      $file = fopen($path . '/' . $k,"w");

      fwrite($file, $content_to_write_html);
    
    }
      
      break;
    
    default:
      # code...
      break;
  }
echo"<script>location.href='filemanager.php';</script>";



}

?>