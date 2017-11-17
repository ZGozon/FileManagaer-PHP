
      <?php



      // Default timezone for date() and time() - http://php.net/manual/en/timezones.php
      $default_timezone = 'Asia/Manila'; // UTC+3

      // Root path for file manager
      $root_path =  ('C:/xampp/htdocs/test2storage');

      $storage = 'test2storage';

      // Root url for links in file manager.Relative to $http_host. Variants: '', 'path/to/subfolder'
      // Will not working if $root_path will be outside of server document root
      $root_url = '';

      // Server hostname. Can set manually if wrong
      $http_host = $_SERVER['HTTP_HOST'];

      // input encoding for iconv
      $iconv_input_encoding = 'CP1251';

      // date() format for file modification date
      $datetime_format = 'd.m.y H:i';

      //--- EDIT BELOW CAREFULLY OR DO NOT EDIT AT ALL

      // if fm included
      if (defined('FM_EMBED')) {
          $use_auth = false;
      } else {
          @set_time_limit(600);

          date_default_timezone_set($default_timezone);

          ini_set('default_charset', 'UTF-8');
          if (version_compare(PHP_VERSION, '5.6.0', '<') && function_exists('mb_internal_encoding')) {
              mb_internal_encoding('UTF-8');
          }
          if (function_exists('mb_regex_encoding')) {
              mb_regex_encoding('UTF-8');
          }

          session_cache_limiter('');
          session_name('filemanager');
          session_start();
      }

      if (empty($auth_users)) {
          $use_auth = false;
      }

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

      // abs path for site
      defined('FM_ROOT_PATH') || define('FM_ROOT_PATH', $root_path);
      defined('FM_ROOT_URL') || define('FM_ROOT_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . (!empty($root_url) ? '/' . $root_url : ''));
      defined('FM_SELF_URL') || define('FM_SELF_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . $_SERVER['PHP_SELF']);

      
      // Show image here
      if (isset($_GET['img'])) {
          fm_show_image($_GET['img']);
      }


      define('FM_IS_WIN', DIRECTORY_SEPARATOR == '\\');

      // always use ?p=
      if (!isset($_GET['p'])) {
          fm_redirect(FM_SELF_URL . '?p=');
      }

      // get path
      $p = isset($_GET['p']) ? $_GET['p'] : (isset($_POST['p']) ? $_POST['p'] : '');

      // clean path
      $p = fm_clean_path($p);

      // instead globals vars
      define('FM_PATH', $p);
      define('FM_USE_AUTH', $use_auth);
      defined('FM_ICONV_INPUT_ENC') || define('FM_ICONV_INPUT_ENC', $iconv_input_encoding);
      defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);
      unset($p, $use_auth, $iconv_input_encoding);

      /*************************** ACTIONS ***************************/

      
      
      
      include  'addcontentprocess.php';
      include  'addfolderprocess.php';
      include 'deleteprocess.php';
      include 'copyprocess.php';
      include 'masscopyprocess.php';
      include 'renameprocess.php';
      include 'downloadprocess.php';
      include 'uploadprocess.php';
      include 'packprocess.php';
      include 'unpackprocess.php';




      
      // Change Perms (not for Windows)
      if (isset($_POST['chmod']) && !FM_IS_WIN) {
          $path = FM_ROOT_PATH;
          if (FM_PATH != '') {
              $path .= '/' . FM_PATH;
          }

          $file = $_POST['chmod'];
          $file = fm_clean_path($file);
          $file = str_replace('/', '', $file);
          if ($file == '' || (!is_file($path . '/' . $file) && !is_dir($path . '/' . $file))) {
              fm_set_msg('File not found', 'error');
              fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
          }

          $mode = 0;
          if (!empty($_POST['ur'])) {
              $mode |= 0400;
          }
          if (!empty($_POST['uw'])) {
              $mode |= 0200;
          }
          if (!empty($_POST['ux'])) {
              $mode |= 0100;
          }
          if (!empty($_POST['gr'])) {
              $mode |= 0040;
          }
          if (!empty($_POST['gw'])) {
              $mode |= 0020;
          }
          if (!empty($_POST['gx'])) {
              $mode |= 0010;
          }
          if (!empty($_POST['or'])) {
              $mode |= 0004;
          }
          if (!empty($_POST['ow'])) {
              $mode |= 0002;
          }
          if (!empty($_POST['ox'])) {
              $mode |= 0001;
          }

          if (@chmod($path . '/' . $file, $mode)) {
              fm_set_msg('Permissions changed');
          } else {
              fm_set_msg('Permissions not changed', 'error');
          }

          fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
      }

      /*************************** /ACTIONS ***************************/

      // get current path
      $path = FM_ROOT_PATH;
      if (FM_PATH != '') {
          $path .= '/' . FM_PATH;
      }

      // check path
      if (!is_dir($path)) {
          fm_redirect(FM_SELF_URL . '?p=');
      }

      // get parent folder
      $parent = fm_get_parent_path(FM_PATH);

      $objects = is_readable($path) ? scandir($path) : array();
      $folders = array();
      $files = array();
      if (is_array($objects)) {
          foreach ($objects as $file) {
              if ($file == '.' || $file == '..') {
                  continue;
              }
              $new_path = $path . '/' . $file;
              if (is_file($new_path)) {
                  $files[] = $file;
              } elseif (is_dir($new_path) && $file != '.' && $file != '..') {
                  $folders[] = $file;
              }
          }
      }

      if (!empty($files)) {
          natcasesort($files);
      }
      if (!empty($folders)) {
          natcasesort($folders);
      }

      // upload form
      if (isset($_GET['upload'])) {
          fm_show_header(); // HEADER
          fm_show_nav_path(FM_PATH); // current path
          ?>
         
          <?php
          fm_show_footer();
          exit;
      }

      // copy form POST
      if (isset($_POST['copy'])) {
          $copy_files = $_POST['file'];
          if (!is_array($copy_files) || empty($copy_files)) {
              fm_set_msg('Nothing selected', 'alert');
              fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
          }

          fm_show_header(); // HEADER
          fm_show_nav_path(FM_PATH); // current path
          ?>
          <div class="path">
              <p><b>Copying</b></p>
              <form action="" method="post">
                  <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
                  <input type="hidden" name="finish" value="1">
                  <?php
                  foreach ($copy_files as $cf) {
                      echo '<input type="hidden" name="file[]" value="' . fm_enc($cf) . '">' . PHP_EOL;
                  }
                  ?>
                  <p class="break-word">Files: <b><?php echo implode('</b>, <b>', $copy_files) ?></b></p>
                  <p class="break-word">Source folder: <?php echo fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH) ?><br>
                      <label for="inp_copy_to">Destination folder:</label>
                      <?php echo FM_ROOT_PATH ?>/<input name="copy_to" id="inp_copy_to" value="<?php echo fm_enc(FM_PATH) ?>">
                  </p>
                  <p><input type="checkbox" id="test5" name="move" value="1"><label for="test5"> Move</label></p>
                  <p>
                      <button class="btn"><i class="icon-apply"></i> Copy</button> &nbsp;
                      <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="icon-cancel"></i> Cancel</a></b>
                  </p>
              </form>
          </div>
          <?php
          fm_show_footer();
          exit;
      }

      // copy form
      if (isset($_GET['copy']) && !isset($_GET['finish'])) {
          $copy = $_GET['copy'];
          $copy = fm_clean_path($copy);
          if ($copy == '' || !file_exists(FM_ROOT_PATH . '/' . $copy)) {
              fm_set_msg('File not found', 'error');
              fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
          }

          fm_show_header(); // HEADER
          fm_show_nav_path(FM_PATH); // current path
          ?>
          <div class="path" style="margin-left: 207px; margin-top: 80px;">
              <p><b>Copying</b></p>
              
              </p>
              <p>
                  <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode($copy) ?>&amp;finish=1"><i class="icon-apply"></i> Copy</a></b> &nbsp;
                  <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode($copy) ?>&amp;finish=1&amp;move=1"><i class="icon-apply"></i> Move</a></b> &nbsp;
                  <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="icon-cancel"></i> Cancel</a></b>
              </p>
              <p><i>Select folder:</i></p>
              <ul class="folders break-word" style="">
                  <?php
                  if ($parent !== false) {
                      ?>
                      <li><a href="?p=<?php echo urlencode($parent) ?>&amp;copy=<?php echo urlencode($copy) ?>"><i class="icon-arrow_up"></i> ..</a></li>
                  <?php
                  }
                  foreach ($folders as $f) {
                      ?>
                      <li><a href="?p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>&amp;copy=<?php echo urlencode($copy) ?>"><img src="folder.png"> <?php echo fm_convert_win($f) ?></a></li>
                  <?php
                  }
                  ?>
              </ul>
          </div>
          <?php
          fm_show_footer();
          exit;
      }

      // file viewer
      if (isset($_GET['view'])) {
          $file = $_GET['view'];
          $file = fm_clean_path($file);
          $file = str_replace('/', '', $file);
          if ($file == '' || !is_file($path . '/' . $file)) {
              fm_set_msg('File not found', 'error');
              fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
          }

          fm_show_header(); // HEADER
          fm_show_nav_path(FM_PATH); // current path

          $file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);
          $file_path = $path . '/' . $file;

          $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
          $mime_type = fm_get_mime_type($file_path);
          $filesize = filesize($file_path);

          $is_zip = false;
          $is_image = false;
          $is_audio = false;
          $is_video = false;
          $is_text = false;
          $is_html = false;

          $view_title = 'File';
          $filenames = false; // for zip
          $content = ''; // for text

          if ($ext == 'zip') {
              $is_zip = true;
              $view_title = 'Archive';
              $filenames = fm_get_zif_info($file_path);
          } elseif (in_array($ext, fm_get_image_exts())) {
              $is_image = true;
              $view_title = 'Image';
          } elseif (in_array($ext, fm_get_audio_exts())) {
              $is_audio = true;
              $view_title = 'Audio';
          } elseif (in_array($ext, fm_get_video_exts())) {
              $is_video = true;
              $view_title = 'Video';
          } elseif (in_array($ext, fm_get_text_exts()) || substr($mime_type, 0, 4) == 'text' || in_array($mime_type, fm_get_text_mimes())) {
              $is_text = true;
              $content = file_get_contents($file_path);
              } elseif (in_array($ext, fm_get_video_exts())) {
              $is_html = true;
              $view_title = 'HTML';
          }

          ?>
          <div class="path">
              <p class="break-word" style="margin-left: 210px; margin-top: 180px;"><b><?php echo $view_title ?> "<?php echo fm_convert_win($file) ?>"</b></p>
              <p style="margin-left: 210px;">
                  Full path: <?php echo fm_convert_win($file_path)?></a><br>
                  File size: <?php echo fm_get_filesize($filesize) ?><?php if ($filesize >= 1000): ?> (<?php echo sprintf('%s bytes', $filesize) ?>)<?php endif; ?><br>
                  MIME-type: <?php echo $mime_type ?><br>
                  <?php
                  // ZIP info
                  if ($is_zip && $filenames !== false) {
                      $total_files = 0;
                      $total_comp = 0;
                      $total_uncomp = 0;
                      foreach ($filenames as $fn) {
                          if (!$fn['folder']) {
                              $total_files++;
                          }
                          $total_comp += $fn['compressed_size'];
                          $total_uncomp += $fn['filesize'];
                      }
                      ?>
                      Files in archive: <?php echo $total_files ?><br>
                      Total size: <?php echo fm_get_filesize($total_uncomp) ?><br>
                      Size in archive: <?php echo fm_get_filesize($total_comp) ?><br>
                      Compression: <?php echo round(($total_comp / $total_uncomp) * 100) ?>%<br>
                      <?php
                  }
                  // Image info
                  if ($is_image) {
                      $image_size = getimagesize($file_path);
                      echo 'Image sizes: ' . (isset($image_size[0]) ? $image_size[0] : '0') . ' x ' . (isset($image_size[1]) ? $image_size[1] : '0') . '<br>';
                  }
                  // Text info
                  if ($is_text) {
                      $is_utf8 = fm_is_utf8($content);
                      if (function_exists('iconv')) {
                          if (!$is_utf8) {
                              $content = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $content);
                          }
                      }
                      echo 'Charset: ' . ($is_utf8 ? 'utf-8' : '8 bit') . '<br>';
                  }
                  ?>
              </p>
              <p style="margin-left: 210px;">
                  <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;dl=<?php echo urlencode($file) ?>"><i class="icon-download"></i> Download</a></b> &nbsp;
                  <b><a href="<?php echo FM_ROOT_URL .'/'.$storage. (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file ?> " target="_blank"   ><i class="icon-chain"></i> Open</a></b> &nbsp;
                  <?php
                  // ZIP actions
                  if ($is_zip && $filenames !== false) {
                      $zip_name = pathinfo($file_path, PATHINFO_FILENAME);
                      ?>
                      <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;unzip=<?php echo urlencode($file) ?>"><i class="icon-apply"></i> Unpack</a></b> &nbsp;
                      <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;unzip=<?php echo urlencode($file) ?>&amp;tofolder=1" title="Unpack to <?php echo fm_enc($zip_name) ?>"><i class="icon-apply"></i>
                          Unpack to folder</a></b> &nbsp;
                      <?php
                  }
                  ?>
                  <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="icon-goback"></i> Back</a></b>
              </p>
              <?php

             
              if ($is_zip) {
                  // ZIP content
                  if ($filenames !== false) {
                      echo '<code class="maxheight">';
                      foreach ($filenames as $fn) {
                          if ($fn['folder']) {
                              echo '<b>' . $fn['name'] . '</b><br>';
                          } else {
                              echo $fn['name'] . ' (' . fm_get_filesize($fn['filesize']) . ')<br>';
                          }
                      }
                      echo '</code>';
                  } else {
                      echo '<p>Error while fetching archive info</p>';
                  }
              } elseif ($is_image) {
                  // Image content
                  if (in_array($ext, array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico'))) {
                      echo '<p><img src="image.php?name='.$file.'&dir='.$file_path.'" alt="sorry" class="preview-img"></p>';
                  }
              } elseif ($is_audio) {
                  // Audio content
                  echo '<p><audio src="' . $file_url . '" controls preload="metadata"></audio></p>';
              } elseif ($is_video) {
                  // Video content
                  echo '<div class="preview-video"><video src="image.php?name='.$file.'&dir='.$file_path.'" width="640" height="360" controls preload="metadata" preload></video></div>';
              } elseif ($is_text) {
                  
      


      echo '<iframe style="width:86%; height: -webkit-fill-available; margin-left:210px;" src="image.php?name='.$file.'&dir='.$file_path.'"> ';

              }
          
          
              ?>
          </div>
          <?php
          fm_show_footer();
          exit;
      }

      // chmod (not for Windows)
      if (isset($_GET['chmod']) && !FM_IS_WIN) {
          $file = $_GET['chmod'];
          $file = fm_clean_path($file);
          $file = str_replace('/', '', $file);
          if ($file == '' || (!is_file($path . '/' . $file) && !is_dir($path . '/' . $file))) {
              fm_set_msg('File not found', 'error');
              fm_redirect(FM_SELF_URL . '?p=' . urlencode(FM_PATH));
          }

          fm_show_header(); // HEADER
          fm_show_nav_path(FM_PATH); // current path

          $file_url = FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file;
          $file_path = $path . '/' . $file;

          $mode = fileperms($path . '/' . $file);

          ?>
          <div class="path">
              <p><b>Change Permissions</b></p>
              <p>
                  Full path: <?php echo $file_path ?><br>
              </p>
              <form action="" method="post">
                  <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
                  <input type="hidden" name="chmod" value="<?php echo fm_enc($file) ?>">

                  <table class="compact-table">
                      <tr>
                          <td></td>
                          <td><b>Owner</b></td>
                          <td><b>Group</b></td>
                          <td><b>Other</b></td>
                      </tr>
                      <tr>
                          <td style="text-align: right"><b>Read</b></td>
                          <td><input type="checkbox" name="ur" id="test11" value="1"<?php echo ($mode & 00400) ? ' checked' : '' ?>><label for="test11"></label></td>
                          <td><input type="checkbox" name="gr" id="test12" value="1"<?php echo ($mode & 00040) ? ' checked' : '' ?>><label for="test12"></label></td>
                          <td><input type="checkbox" name="or"  id="test13" value="1"<?php echo ($mode & 00004) ? ' checked' : '' ?>><label for="test13"></label></td>
                      </tr>
                      <tr>
                          <td style="text-align: right"><b>Write</b></td>
                          <td><input type="checkbox" name="uw"  id="test14" value="1"<?php echo ($mode & 00200) ? ' checked' : '' ?>><label for="test14"></label></td>
                          <td><input type="checkbox" name="gw"  id="test15" value="1"<?php echo ($mode & 00020) ? ' checked' : '' ?>><label for="test15"></label></td>
                          <td><input type="checkbox" name="ow"   id="test16" value="1"<?php echo ($mode & 00002) ? ' checked' : '' ?>><label for="test16"></label></td>
                      </tr>
                      <tr>
                          <td style="text-align: right"><b>Execute</b></td>
                          <td><input type="checkbox" name="ux"  id="test17" value="1"<?php echo ($mode & 00100) ? ' checked' : '' ?>><label for="test17"></label></td>
                          <td><input type="checkbox" name="gx"  id="test18" value="1"<?php echo ($mode & 00010) ? ' checked' : '' ?>><label for="test18"></label></td>
                          <td><input type="checkbox" name="ox"  id="test19" value="1"<?php echo ($mode & 00001) ? ' checked' : '' ?>><label for="test19"></label></td>
                      </tr>
                  </table>

                  <p>
                      <button class="btn"><i class="icon-apply"></i> Change</button> &nbsp;
                      <b><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="icon-cancel"></i> Cancel</a></b>
                  </p>

              </form>

          </div>
          <?php
          fm_show_footer();
          exit;
      }

      //--- FILEMANAGER MAIN
      fm_show_header(); // HEADER
      fm_show_nav_path(FM_PATH); // current path

      // messages
      fm_show_message();

      $num_files = count($files);
      $num_folders = count($folders);
      $all_files_size = 0;
      ?>
      <form action="" method="post" style="margin-top: 100px;">
      <input type="hidden" name="p" value="<?php echo fm_enc(FM_PATH) ?>">
      <input type="hidden" name="group" value="1">
    


      <!-- <div id="dvData"> -->
      <table class='table bordered highlight striped' style="width: 86%; margin-left: 213px;" ><tr>

    
      <th>Name</th>
      <th>Size</th>
      <th>Modified</th>
      <?php if (!FM_IS_WIN): ?><th style="width:6%">Perms</th><th style="width:10%">Owner</th><?php endif; ?>
      <th style="width:13%"></th></tr>
      <?php
      // link to parent folder
      if ($parent !== false) {
          ?>
      <tr><td></td><td style="" colspan="<?php echo !FM_IS_WIN ? '6' : '4' ?>"><a href="?p=<?php echo urlencode($parent) ?>" style="    margin-left: -352px;"><i class="icon-arrow_up"></i>Go Back</a></td></tr>
      <?php
      }
      $o_folders = array();
      
      foreach ($folders as $f) {
          $is_link = is_link($path . '/' . $f);
          $img = $is_link ? 'icon-link_folder' : 'icon-folder';
          $modif = date(FM_DATETIME_FORMAT, filemtime($path . '/' . $f));
          $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
          if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
              $owner = posix_getpwuid(fileowner($path . '/' . $f));
              $group = posix_getgrgid(filegroup($path . '/' . $f));
          } else {
              $owner = array('name' => '?');
              $group = array('name' => '?');
          }

          $o_folders[] = array($f, FM_ROOT_URL .'/'.$storage. (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f);

          ?>

      <tr>
      
      <td><div class="filename"><a href="?p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"> <i class="material-icons">folder</i>&nbsp;</span><?php echo fm_convert_win($f) ?></a><?php echo ($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div></td>
      <td>Folder</td><td><?php echo $modif ?></td>
      <?php if (!FM_IS_WIN): ?>
      <td><a title="Change Permissions" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;chmod=<?php echo urlencode($f) ?>"><?php echo $perms ?></a></td>
      <td><?php echo $owner['name'] . ':' . $group['name'] ?></td>
      <?php endif; ?>
      <td>
      <a  class="btn-floating btn-small waves-effect waves-light red" title="Delete" href=?p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>" onclick="return confirm('Delete folder?');"><i class="material-icons">delete</i></a>


      <!--  EDIT         -->
      <a class="btn-floating btn-small waves-effect waves-light yellow" title="Rename" href="#" onclick="rename('<?php echo fm_enc(FM_PATH) ?>', '<?php echo fm_enc($f) ?>');return false;"><i class="material-icons">edit</i></a>


      <!--   COPY       -->
      <a class="btn-floating btn-small waves-effect waves-light blue" title="Copy to..." href="?p=&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i class="material-icons">content_copy</i></a>

      <!--    DIRECT LINK       -->
     <a title="Direct link" class="btn-floating btn-small waves-effect waves-light orange" onclick="prompt('Copy Url','<?php echo FM_ROOT_URL .'/'.$storage. (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f ?>')"><i class="material-icons">link</i></a>
         

      </td></tr>
          <?php
          flush();
      }



      foreach ($files as $f) {
          $is_link = is_link($path . '/' . $f);
          $img = $is_link ? 'icon-link_file' : ($path . '/' . $f);
          $modif = date(FM_DATETIME_FORMAT, filemtime($path . '/' . $f));
          $filesize_raw = filesize($path . '/' . $f);
          $filesize = fm_get_filesize($filesize_raw);
          $filelink = '?p=' . urlencode(FM_PATH) . '&amp;view=' . urlencode($f);
          $all_files_size += $filesize_raw;
          $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
          if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
              $owner = posix_getpwuid(fileowner($path . '/' . $f));
              $group = posix_getgrgid(filegroup($path . '/' . $f));
          } else {
              $owner = array('name' => '?');
              $group = array('name' => '?');
          }
           $o_folders[] = array($f, FM_ROOT_URL .'/'.$storage. (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f);
          
                  




                 
          ?>
      <tr>
      
      <td><div class="filename"><a href="<?php echo $filelink ?>" title="File info"><i class="<?php echo $img ?>"></i> <?php echo fm_convert_win($f) ?></a><?php echo ($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div></td>
      <td><span class="gray" title="<?php printf('%s bytes', $filesize_raw) ?>"><?php echo $filesize ?></span></td>
      <td><?php echo $modif ?></td>
      <?php if (!FM_IS_WIN): ?>
      <td><a title="Change Permissions" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;chmod=<?php echo urlencode($f) ?>"><?php echo $perms ?></a></td>
      <td><?php echo $owner['name'] . ':' . $group['name'] ?></td>
      <?php endif; ?>
      <td>
       <!--   <!--  <button type="button" class="btn-floating btn-small waves-effect waves-light red" href="<?php echo urlencode(FM_PATH) ?>&amp;remove.php?del=<?php echo urlencode($f) ?>" id="deletion"> <i class="material-icons">delete</i></a></button> -->      
      <!--  <button type="button" id="deletion "></button>        -->

      <a   class="btn-floating btn-small waves-effect waves-light red" onclick="return confirm('Are you sure you want to delete?')" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>"> <i class="material-icons">delete</i></a> 

      <a  class="btn-floating btn-small waves-effect waves-light yellow" title="Rename" href="#" onclick="rename('<?php echo fm_enc(FM_PATH) ?>', '<?php echo fm_enc($f) ?>');return false;"><i class="material-icons">edit</i></a>

      <a class="btn-floating btn-small waves-effect waves-light blue" title="Copy to..." href="?p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i class="material-icons">content_copy</i></a>

      <a title="Direct link" class="btn-floating btn-small waves-effect waves-light orange" onclick="prompt('Copy Url','<?php echo FM_ROOT_URL .'/'.$storage. (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f ?>')"><i class="material-icons">link</i></a>

      <a title="Download" class="btn-floating btn-small waves-effect waves-light green" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;dl=<?php echo urlencode($f) ?>"><i class="material-icons">file_download</i></a>

      </td></tr>
          <?php
          flush();
      }

      if (empty($folders) && empty($files)) {
          ?>
      <tr><td></td><td colspan="<?php echo !FM_IS_WIN ? '6' : '4' ?>"><em>Folder is empty</em></td></tr>
      <?php
      } else {
          ?>
      <tr><td class="gray"></td><td class="gray" colspan="<?php echo !FM_IS_WIN ? '6' : '4' ?>">
      Full size: <span title="<?php printf('%s bytes', $all_files_size) ?>"><?php echo fm_get_filesize($all_files_size) ?></span>,
      files: <?php echo $num_files ?>,
      folders: <?php echo $num_folders ?>
      



      </td></tr>
      <?php include 'numberticker.php';  ?>
      <?php
      }
      ?></div>
      </table>
      
      
      <input style="     margin-left: 1256px;
      "  class="waves-effect waves-light btn" type="submit" name="btnSubmit" value="Export" onclick="" />

     <?php include 'exportprocess.php';     ?>

      <?php
      fm_show_footer();

      //--- END

      // Functions

      /**
       * Delete  file or folder (recursively)
       * @param string $path
       * @return bool
       */
      function fm_rdelete($path)
      {
          if (is_link($path)) {
              return unlink($path);
          } elseif (is_dir($path)) {
              $objects = scandir($path);
              $ok = true;
              if (is_array($objects)) {
                  foreach ($objects as $file) {
                      if ($file != '.' && $file != '..') {
                          if (!fm_rdelete($path . '/' . $file)) {
                              $ok = false;
                          }
                      }
                  }
              }
              return ($ok) ? rmdir($path) : false;
          } elseif (is_file($path)) {
              return unlink($path);
          }
          return false;
      }

      /**
       * Recursive chmod
       * @param string $path
       * @param int $filemode
       * @param int $dirmode
       * @return bool
       * @todo Will use in mass chmod
       */
      function fm_rchmod($path, $filemode, $dirmode)
      {
          if (is_dir($path)) {
              if (!chmod($path, $dirmode)) {
                  return false;
              }
              $objects = scandir($path);
              if (is_array($objects)) {
                  foreach ($objects as $file) {
                      if ($file != '.' && $file != '..') {
                          if (!fm_rchmod($path . '/' . $file, $filemode, $dirmode)) {
                              return false;
                          }
                      }
                  }
              }
              return true;
          } elseif (is_link($path)) {
              return true;
          } elseif (is_file($path)) {
              return chmod($path, $filemode);
          }
          return false;
      }

      /**
       * Safely rename
       * @param string $old
       * @param string $new
       * @return bool|null
       */
      function fm_rename($old, $new)
      {
          return (!file_exists($new) && file_exists($old)) ? rename($old, $new) : null;
      }

      /**
       * Copy file or folder (recursively).
       * @param string $path
       * @param string $dest
       * @param bool $upd Update files
       * @param bool $force Create folder with same names instead file
       * @return bool
       */
      function fm_rcopy($path, $dest, $upd = true, $force = true)
      {
          if (is_dir($path)) {
              if (!fm_mkdir($dest, $force)) {
                  return false;
              }
              $objects = scandir($path);
              $ok = true;
              if (is_array($objects)) {
                  foreach ($objects as $file) {
                      if ($file != '.' && $file != '..') {
                          if (!fm_rcopy($path . '/' . $file, $dest . '/' . $file)) {
                              $ok = false;
                          }
                      }
                  }
              }
              return $ok;
          } elseif (is_file($path)) {
              return fm_copy($path, $dest, $upd);
          }
          return false;
      }

      /**
       * Safely create folder
       * @param string $dir
       * @param bool $force
       * @return bool
       */
      function fm_mkdir($dir, $force)
      {
          if (file_exists($dir)) {
              if (is_dir($dir)) {
                  return $dir;
              } elseif (!$force) {
                  return false;
              }
              unlink($dir);
          }
          return mkdir($dir, 0777, true);
      }

      /**
       * Safely copy file
       * @param string $f1
       * @param string $f2
       * @param bool $upd
       * @return bool
       */
      function fm_copy($f1, $f2, $upd)
      {
          $time1 = filemtime($f1);
          if (file_exists($f2)) {
              $time2 = filemtime($f2);
              if ($time2 >= $time1 && $upd) {
                  return false;
              }
          }
          $ok = copy($f1, $f2);
          if ($ok) {
              touch($f2, $time1);
          }
          return $ok;
      }

      /**
       * Get mime type
       * @param string $file_path
       * @return mixed|string
       */
      function fm_get_mime_type($file_path)
      {
          if (function_exists('finfo_open')) {
              $finfo = finfo_open(FILEINFO_MIME_TYPE);
              $mime = finfo_file($finfo, $file_path);
              finfo_close($finfo);
              return $mime;
          } elseif (function_exists('mime_content_type')) {
              return mime_content_type($file_path);
          } elseif (!stristr(ini_get('disable_functions'), 'shell_exec')) {
              $file = escapeshellarg($file_path);
              $mime = shell_exec('file -bi ' . $file);
              return $mime;
          } else {
              return '--';
          }
      }

      /**
       * HTTP Redirect
       * @param string $url
       * @param int $code
       */
      function fm_redirect($url, $code = 302)
      {
          header('Location: ' . $url, true, $code);
          exit;
      }

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

      /**
       * Get parent path
       * @param string $path
       * @return bool|string
       */
      function fm_get_parent_path($path)
      {
          $path = fm_clean_path($path);
          if ($path != '') {
              $array = explode('/', $path);
              if (count($array) > 1) {
                  $array = array_slice($array, 0, -1);
                  return implode('/', $array);
              }
              return '';
          }
          return false;
      }

      /**
       * Get nice filesize
       * @param int $size
       * @return string
       */
      function fm_get_filesize($size)
      {
          if ($size < 1000) {
              return sprintf('%s B', $size);
          } elseif (($size / 1024) < 1000) {
              return sprintf('%s KB', round(($size / 1024), 2));
          } elseif (($size / 1024 / 1024) < 1000) {
              return sprintf('%s MB', round(($size / 1024 / 1024), 2));
          } elseif (($size / 1024 / 1024 / 1024) < 1000) {
              return sprintf('%s GB', round(($size / 1024 / 1024 / 1024), 2));
          } else {
              return sprintf('%s TB', round(($size / 1024 / 1024 / 1024 / 1024), 2));
          }
      }

      /**
       * Get info about zip archive
       * @param string $path
       * @return array|bool
       */
      function fm_get_zif_info($path)
      {
          if (function_exists('zip_open')) {
              $arch = zip_open($path);
              if ($arch) {
                  $filenames = array();
                  while ($zip_entry = zip_read($arch)) {
                      $zip_name = zip_entry_name($zip_entry);
                      $zip_folder = substr($zip_name, -1) == '/';
                      $filenames[] = array(
                          'name' => $zip_name,
                          'filesize' => zip_entry_filesize($zip_entry),
                          'compressed_size' => zip_entry_compressedsize($zip_entry),
                          'folder' => $zip_folder
                          //'compression_method' => zip_entry_compressionmethod($zip_entry),
                      );
                  }
                  zip_close($arch);
                  return $filenames;
              }
          }
          return false;
      }

      /**
       * Encode html entities
       * @param string $text
       * @return string
       */
      function fm_enc($text)
      {
          return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
      }

      /**
       * Save message in session
       * @param string $msg
       * @param string $status
       */
      function fm_set_msg($msg, $status = 'ok')
      {
          $_SESSION['message'] = $msg;
          $_SESSION['status'] = $status;
      }

      /**
       * Check if string is in UTF-8
       * @param string $string
       * @return int
       */
      function fm_is_utf8($string)
      {
          return preg_match('//u', $string);
      }

      /**
       * Convert file name to UTF-8 in Windows
       * @param string $filename
       * @return string
       */
      function fm_convert_win($filename)
      {
          if (FM_IS_WIN && function_exists('iconv')) {
              $filename = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $filename);
          }
          return $filename;
      }

      /**
       * Get CSS classname for file
       * @param string $path
       * @return string
       */
      
      /**
       * Get image files extensions
       * @return array
       */
      function fm_get_image_exts()
      {
          return array('ico', 'gif', 'jpg', 'jpeg', 'jpc', 'jp2', 'jpx', 'xbm', 'wbmp', 'png', 'bmp', 'tif', 'tiff', 'psd');
      }

      /**
       * Get video files extensions
       * @return array
       */
      function fm_get_video_exts()
      {
          return array('webm', 'mp4', 'm4v', 'ogm', 'ogv', 'mov');
      }

      /**
       * Get audio files extensions
       * @return array
       */
      function fm_get_audio_exts()
      {
          return array('wav', 'mp3', 'ogg', 'm4a');
      }

      /**
       * Get text file extensions
       * @return array
       */
      function fm_get_text_exts()
      {
          return array(
              'txt', 'css', 'ini', 'conf', 'log', 'htaccess', 'passwd', 'ftpquota', 'sql', 'js', 'json', 'sh', 'config',
              'php', 'php4', 'php5', 'phps', 'phtml', 'htm', 'html', 'shtml', 'xhtml', 'xml', 'xsl', 'm3u', 'm3u8', 'pls', 'cue',
              'eml', 'msg', 'csv', 'bat', 'twig', 'tpl', 'md', 'gitignore', 'less', 'sass', 'scss', 'c', 'cpp', 'cs', 'py',
              'map', 'lock', 'dtd', 'svg',
          );
      }

      /**
       * Get mime types of text files
       * @return array
       */
      function fm_get_text_mimes()
      {
          return array(
              'application/xml',
              'application/javascript',
              'application/x-javascript',
              'image/svg+xml',
              'message/rfc822',
          );
      }

      /**
       * Get file names of text files w/o extensions
       * @return array
       */
      function fm_get_text_names()
      {
          return array(
              'license',
              'readme',
              'authors',
              'contributors',
              'changelog',
          );
      }

      /**
       * Class to work with zip files (using ZipArchive)
       */
      class FM_Zipper
      {
          private $zip;

          public function __construct()
          {
              $this->zip = new ZipArchive();
          }

          /**
           * Create archive with name $filename and files $files (RELATIVE PATHS!)
           * @param string $filename
           * @param array|string $files
           * @return bool
           */
          public function create($filename, $files)
          {
              $res = $this->zip->open($filename, ZipArchive::CREATE);
              if ($res !== true) {
                  return false;
              }
              if (is_array($files)) {
                  foreach ($files as $f) {
                      if (!$this->addFileOrDir($f)) {
                          $this->zip->close();
                          return false;
                      }
                  }
                  $this->zip->close();
                  return true;
              } else {
                  if ($this->addFileOrDir($files)) {
                      $this->zip->close();
                      return true;
                  }
                  return false;
              }
          }

          /**
           * Extract archive $filename to folder $path (RELATIVE OR ABSOLUTE PATHS)
           * @param string $filename
           * @param string $path
           * @return bool
           */
          public function unzip($filename, $path)
          {
              $res = $this->zip->open($filename);
              if ($res !== true) {
                  return false;
              }
              if ($this->zip->extractTo($path)) {
                  $this->zip->close();
                  return true;
              }
              return false;
          }

          /**
           * Add file/folder to archive
           * @param string $filename
           * @return bool
           */
          private function addFileOrDir($filename)
          {
              if (is_file($filename)) {
                  return $this->zip->addFile($filename);
              } elseif (is_dir($filename)) {
                  return $this->addDir($filename);
              }
              return false;
          }

          /**
           * Add folder recursively
           * @param string $path
           * @return bool
           */
          private function addDir($path)
          {
              if (!$this->zip->addEmptyDir($path)) {
                  return false;
              }
              $objects = scandir($path);
              if (is_array($objects)) {
                  foreach ($objects as $file) {
                      if ($file != '.' && $file != '..') {
                          if (is_dir($path . '/' . $file)) {
                              if (!$this->addDir($path . '/' . $file)) {
                                  return false;
                              }
                          } elseif (is_file($path . '/' . $file)) {
                              if (!$this->zip->addFile($path . '/' . $file)) {
                                  return false;
                              }
                          }
                      }
                  }
                  return true;
              }
              return false;
          }
      }

      //--- templates functions

      /**
       * Show nav block
       * @param string $path
       */


      function fm_show_nav_path($path)
      {
          ?>

      <div class="stitched"></div>   
      <div class="path" style="margin-top: 100px;">
      <div class="float-right" style="margin-top: 0px;">

       
       <a class="waves-effect waves-light btn modal-trigger" href="#modal1" style="font-weight: 500">Upload<i class="material-icons">add</i></a>
       
        <a class="waves-effect waves-light btn modal-trigger" href="#modal3" style="font-weight: 500">New Folder<i class="material-icons">folder</i></a>

       <a class="waves-effect waves-light btn modal-trigger" href="#modal2" style="font-weight: 500">Add Single Content<i class="material-icons">add</i></a>
       <a class="waves-effect waves-light btn modal-trigger" href="#modal4" style="font-weight: 500">Add Batch Content<i class="material-icons">add</i></a>


        <?php
        include 'addbatchcontentform.php';
        include 'newfolderform.php';
        include 'addcontentform.php';
        include 'uploadform.php';

        ?>

         
       

      



             
             
     

              <?php
              $path = fm_clean_path($path);
              $root_url = "<a href='?p=' style=' color: white;
      margin-left: 90px;
      font-size: 24px; font-weight:200;'><i class='material-icons' title='" . FM_ROOT_PATH . "'>home</i>Home</a>";
              $sep = '<img src="sep.ico">';
              if ($path != '') {
                  $exploded = explode('/', $path);
                  $count = count($exploded);
                  $array = array();
                  $parent = '';
                  for ($i = 0; $i < $count; $i++) {
                      $parent = trim($parent . '/' . $exploded[$i], '/');
                      $parent_enc = urlencode($parent);
                      $array[] = "<a href='?p={$parent_enc}' style='color: white;
      
      font-size: 24px; font-weight:200;'>" . fm_convert_win($exploded[$i]) . "</a>";
                  }
                  $root_url .= $sep . implode($sep, $array);
              }
              echo '<div class="break-word" style="margin-left:210px;   padding: 20px 10px;
              margin-top:-118px;

     background: #237971;;
     color: #fff;    ">' . $root_url . '</div>';
              ?>

      </div></div></div></div>

      <?php
      }

      /**
       * Show message from session
       */
      function fm_show_message()
      {
          if (isset($_SESSION['message'])) {
              $class = isset($_SESSION['status']) ? $_SESSION['status'] : 'ok';
              echo '<p class="' . $class . '">' . $_SESSION['message'] . '</p>';
              unset($_SESSION['message']);
              unset($_SESSION['status']);
          }
      }

      /**
       * Show page header
       */
      function fm_show_header()
      {
          $sprites_ver = '20160315';
         
          ?>
      <!DOCTYPE html>
      <html>
      <head>
       <meta name="viewport" content="width=device-width, initial-scale=1">
        <!--Import Google Icon Font-->
            <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
            <!--Import materialize.css-->
            <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

            <!--Let browser know website is optimized for mobile-->
            <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
          </head>

          <body>
            <!--Import jQuery before materialize.js-->
            <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
            <script type="text/javascript" src="js/materialize.min.js"></script>
            <script src="dist/sweetalert2.min.js"></script>
          <link rel="stylesheet" type="text/css" href="dist/sweetalert2.css">
              <link href="style.css" rel="stylesheet">


         </head>
          
      <title>PHP File Manager</title>



        <div class="navbar">
      <nav style="background: #26a69a; height: 122px;">
          <div class="nav-wrapper">
            <a href="filemanager.php" class="brand-logo" style="margin-left: 300px;
      margin-top: 27px;
      font-weight: 100;
  ">File Manager</a>
        </div>
            <ul id="slide-out" class="side-nav fixed" style=" width: 210px;">
           <li><div class="userView">
                <img class="background" style="width: 260px;" src="google_inspired_wallpaper__night__by_brebenel_silviu-d6pg3lr.jpg">
                <a href="#!user"><img style="width: 48px;" src="circle.png"></a>
                <a href="#!name"><span class="white-text name">User</span></a>
               
            </div></li>
    

           <ul class="collapsible collapsible-accordion">
            <li>
              <a class="collapsible-header">Home<i class="material-icons">add</i></a>
              <div class="collapsible-body">
                <ul>
                  <li><a href="http://localhost/test8/filemanager.php?p=Contents">Contents</a></li>
                  <li><a href="http://localhost/test8/filemanager.php?p=Subjects">Subjects</a></li>
                
              </ul>
              <li><a href="http://localhost/test8/filemanager.php?p=Trash">Trash</a></li>
          
          <li><a class="subheader" style="margin-top: 500px;">File Manager</a></li>
         
      </ul>
            
          </div>
        </nav>



      <link rel="icon" href="<?php echo FM_SELF_URL ?>?img=favicon" type="image/png">
      <link rel="shortcut icon" href="<?php echo FM_SELF_URL ?>?img=favicon" type="image/png">
      
      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.2.0/styles/<?php echo FM_HIGHLIGHTJS_STYLE ?>.min.css">
     
      </head>
      <body>
      <div id="wrapper">


      <?php
      }

      /**
       * Show page footer
       */
      function fm_show_footer()
      {
          ?>
      <p class="center"><small>PHP File Manager</a></small></p>
      </div>
    
     
    

      
      </body>
      </html>
      <?php
      }

      ?>
      
     
  <script>


  </script>
        <script src="functions.js"></script>
