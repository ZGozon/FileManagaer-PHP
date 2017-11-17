
<?php
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
    $image = $_POST['grp1'];
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
  echo'<div class="swal2-container swal2-fade swal2-shown" style="overflow-y: auto;"><div role="dialog" aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-modal swal2-show" tabindex="-1" style="width: 500px; padding: 20px; background: rgb(255, 255, 255); display: block; min-height: 318px;"><ul class="swal2-progresssteps" style="display: none;"></ul><div class="swal2-icon swal2-error" style="display: none;"><span class="swal2-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div><div class="swal2-icon swal2-question" style="display: none;">?</div><div class="swal2-icon swal2-warning" style="display: none;">!</div><div class="swal2-icon swal2-info" style="display: none;">i</div><div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: block;"><div class="swal2-success-circular-line-left" style="background: rgb(255, 255, 255);"></div><span class="swal2-success-line-tip swal2-animate-success-line-tip"></span> <span class="swal2-success-line-long swal2-animate-success-line-long"></span><div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background: rgb(255, 255, 255);"></div><div class="swal2-success-circular-line-right" style="background: rgb(255, 255, 255);"></div></div><img class="swal2-image" style="display: none;"><h2 class="swal2-title" id="swal2-title">Content Created !</h2><div id="swal2-content" class="swal2-content" style="display: block;">Content <b>'.$file_to_write.'</b> successfully created in '.$path.'!</div><input class="swal2-input" style="display: none;"><input type="file" class="swal2-file" style="display: none;"><div class="swal2-range" style="display: none;"><output></output><input type="range"></div><select class="swal2-select" style="display: none;"></select><div class="swal2-radio" style="display: none;"></div><label for="swal2-checkbox" class="swal2-checkbox" style="display: none;"><input type="checkbox"></label><textarea class="swal2-textarea" style="display: none;"></textarea><div class="swal2-validationerror" id="swal2-validationerror" style="display: none;"></div><div class="swal2-buttonswrapper" style="display: block;"><a href="filemanager.php" class="swal2-confirm swal2-styled" aria-label="" style="background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">OK</a><button type="button" class="swal2-cancel swal2-styled" aria-label="" style="display: none; background-color: rgb(170, 170, 170);">Cancel</button></div><button type="button" class="swal2-close" style="display: none;">×</button></div></div>';
} else {
 echo '<div class="swal2-container swal2-fade swal2-shown" style="overflow-y: auto;"><div role="dialog" aria-labelledby="swal2-title" aria-describedby="swal2-content" class="swal2-modal swal2-show" tabindex="-1" style="width: 500px; padding: 20px; background: rgb(255, 255, 255); display: block; min-height: 317px;"><ul class="swal2-progresssteps" style="display: none;"></ul><div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: block;"><span class="swal2-x-mark swal2-animate-x-mark"><span class="swal2-x-mark-line-left"></span><span class="swal2-x-mark-line-right"></span></span></div><div class="swal2-icon swal2-question" style="display: none;">?</div><div class="swal2-icon swal2-warning" style="display: none;">!</div><div class="swal2-icon swal2-info" style="display: none;">i</div><div class="swal2-icon swal2-success" style="display: none;"><div class="swal2-success-circular-line-left" style="background: rgb(255, 255, 255);"></div><span class="swal2-success-line-tip"></span> <span class="swal2-success-line-long"></span><div class="swal2-success-ring"></div> <div class="swal2-success-fix" style="background: rgb(255, 255, 255);"></div><div class="swal2-success-circular-line-right" style="background: rgb(255, 255, 255);"></div></div><img class="swal2-image" style="display: none;"><h2 class="swal2-title" id="swal2-title">Oops...</h2><div id="swal2-content" class="swal2-content" style="display: block;">Folder <b>'.$file_to_write.'</b> already exists!</div><input class="swal2-input" style="display: none;"><input type="file" class="swal2-file" style="display: none;"><div class="swal2-range" style="display: none;"><output></output><input type="range"></div><select class="swal2-select" style="display: none;"></select><div class="swal2-radio" style="display: none;"></div><label for="swal2-checkbox" class="swal2-checkbox" style="display: none;"><input type="checkbox"></label><textarea class="swal2-textarea" style="display: none;"></textarea><div class="swal2-validationerror" id="swal2-validationerror" style="display: none;"></div><div class="swal2-buttonswrapper" style="display: block;"><a href="filemanager.php" class="swal2-confirm swal2-styled" aria-label="" style="background-color: rgb(48, 133, 214); border-left-color: rgb(48, 133, 214); border-right-color: rgb(48, 133, 214);">OK</a><button type="button" class="swal2-cancel swal2-styled" aria-label="" style="display: none; background-color: rgb(170, 170, 170);">Cancel</button></div><button type="button" class="swal2-close" style="display: none;">×</button></div></div>';

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

  fwrite($file, $content_to_write_html);
  break;

  case 'mp4':
  $file_to_write .= "/index.html";
  $file = fopen($path . '/' . $file_to_write,"w");

  fwrite($file, $content_to_write_html);
  break;
  
  default:
          # code...
  break;
}





}
?>