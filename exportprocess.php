 <?php


      

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
      
      $callStartTime = microtime(true);

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
      $objWriter->save(str_replace('.php', '.xlsx', __FILE__));
      $callEndTime = microtime(true);
      $callTime = $callEndTime - $callStartTime;



      // Save Excel 95 file
      
      $callStartTime = microtime(true);

      $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
      $objWriter->save(str_replace('.php', '.xls', __FILE__));
      $callEndTime = microtime(true);
      $callTime = $callEndTime - $callStartTime;



       ?>
