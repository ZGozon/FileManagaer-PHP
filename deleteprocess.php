 <?php


 //delete file
     if (isset($_GET['del'])) {
    $del = $_GET['del'];
    $trash = 'C:/xampp/htdocs/test2storage/trash/'.$del;
    $path = FM_ROOT_PATH.'/'.$del;
             if (FM_PATH != '') {
                 $path .= '/' . FM_PATH;
             }

    // remove file if it exists
    if ( file_exists($path)) {
        rename($path, $trash);
         
     }
    }


    ?>