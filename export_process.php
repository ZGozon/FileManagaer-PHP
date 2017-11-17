<?php
    $storage = 'test2storage';
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
	    
	    $o_folders = array();
	    
	    foreach ($folders as $f) {
	        $is_link = is_link($path . '/' . $f);
	       
	        
	        $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
	        if (function_exists('posix_getpwuid') && function_exists('posix_getgrgid')) {
	            $owner = posix_getpwuid(fileowner($path . '/' . $f));
	            $group = posix_getgrgid(filegroup($path . '/' . $f));
	        } else {
	            $owner = array('name' => '?');
	            $group = array('name' => '?');
	        }

	        $o_folders[] = array($f, FM_ROOT_URL .'/'.$storage. (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f);

	      
	    }



	    foreach ($files as $f) {
	        $is_link = is_link($path . '/' . $f);
	       
	      
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
	        
	                




	    /** Error reporting */
	    error_reporting(E_ALL);
	    ini_set('display_errors', TRUE);
	    ini_set('display_startup_errors', TRUE);
	    date_default_timezone_set('Europe/London');

	    define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	    /** Include PHPExcel */
	    require_once dirname(__FILE__) . '/Classes/PHPExcel.php';

	    // Create new PHPExcel object
	   
	    $objPHPExcel = new PHPExcel();

	    // Set document properties
	   
	    $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
	                                 ->setLastModifiedBy("Maarten Balliauw")
	                                 ->setTitle("PHPExcel Test Document")
	                                 ->setSubject("PHPExcel Test Document")
	                                 ->setDescription("Test document for PHPExcel, generated using PHP classes.")
	                                 ->setKeywords("office PHPExcel php")
	                                 ->setCategory("Test result file");


	    // Add some data
	   
	    $objPHPExcel->setActiveSheetIndex(0)
	                ->setCellValue('A1', 'Name')
	                ->setCellValue('B1', 'Contenturl');

	               

	    


	    
	    $ex_row = 2;
	    foreach ($o_folders as $key => $value) {
	        $objPHPExcel->getActiveSheet()->setCellValue('A'.$ex_row,$value[0]);
	        $objPHPExcel->getActiveSheet()->setCellValue('B'.$ex_row, $value[1]);    
	        $ex_row++;
	    }
	    
	    



	    // Rename worksheet
	   
	    $objPHPExcel->getActiveSheet()->setTitle('Simple');


	    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
	    $objPHPExcel->setActiveSheetIndex(0);


	    // Save Excel 2007 file
	    
	ob_clean();   
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="01simple.xlsx"');
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0
ob_clean();  

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
	}

?>